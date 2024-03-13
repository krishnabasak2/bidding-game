<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\GamesList;
use App\Models\GamesResult;
use App\Models\GamesTime;
use App\Models\SiteSetting;
use App\Models\User;
use App\Traits\AutoActive;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Stmt\TryCatch;

class AppMain extends Controller
{
    use AutoActive;
    public function info()
    {
        try {
            $data = SiteSetting::first();
            $status_check = User::where(['id' => '1', 'role' => '0'])->select('game_settings')->first();

            $app = json_decode($status_check['game_settings']);

            // $app = [
            //     "status" => true,
            //     "data" => [
            //         "app_version" => "1.4",
            //         "app_link" => "http://127.0.0.1:8001/bidding-game-1.1.apk",
            //     ]
            // ];

            // User::where(['id' => '1', 'role' => '0'])->update(['game_settings' => json_encode($app)]);

            // dd($app);
            return response()->json(['status' => true, 'message' => "App info", 'data' => $data, 'app' => $app], 200);
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'message' => 'Internal server errors.', 'data' => null], 500);
        }
    }


    public function game_list()
    {
        try {
            $data = GamesList::with('get_last_time')->get();

            return response()->json(['status' => true, 'message' => "Game list", 'data' => $data], 200);
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'message' => 'Internal server errors.', 'data' => null], 500);
        }
    }


    public function game_time($game_id)
    {
        try {
            $this->game_on($game_id);

            $current_day = date("l");
            $day = '';
            if ($current_day == 'Monday') {
                $day = '1';
            } elseif ($current_day == 'Tuesday') {
                $day = '2';
            } elseif ($current_day == 'Wednesday') {
                $day = '3';
            } elseif ($current_day == 'Thursday') {
                $day = '4';
            } elseif ($current_day == 'Friday') {
                $day = '5';
            } elseif ($current_day == 'Saturday') {
                $day = '6';
            } elseif ($current_day == 'Sunday') {
                $day = '0';
            }

            $data = GamesTime::where(['game_id' => $game_id, 'status' => '1'])->whereJsonContains('game_days', $day)->with('get_result')->get();

            $game_name = GamesList::select('title')->where('id', $game_id)->first();

            return response()->json(['status' => true, 'message' => "App info", 'data' => $data, 'game_name' => $game_name->title], 200);
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'message' => 'Internal server errors.', 'data' => $th], 500);
        }
    }

    public function result_data($result_id)
    {
        try {
            $result = GamesResult::where('id', $result_id)->with('getTime', 'getGame')->first();

            $first_game = GamesResult::where('game_id', $result->game_id)->whereDate('created_at', 'like', Carbon::today())->first();

            return response()->json(['status' => true, 'message' => "App info", 'data' => $result, 'first_game' => $first_game], 200);
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'message' => 'Internal server errors.', 'data' => $th], 500);
        }
    }

    public function game_result($game_id)
    {
        $results = DB::table('games_results')
            ->select('patti_win_value', 'single_win_value', 'jodi_win_value', 'created_at', DB::raw('DATE(created_at) as date'))
            ->where('game_id', $game_id)
            ->orderBy('date', 'DESC')
            ->orderBy('created_at', 'ASC')
            ->get()
            ->groupBy('date')->take(90);

        $game_name = GamesList::select('title')->where('id', $game_id)->first();

        return response()->json(['status' => true, 'result' => $results, 'game_name' => $game_name]);
    }

    public function game_result_web($game_id)
    {
        $results = DB::table('games_results')
            ->select('patti_win_value', 'single_win_value', 'jodi_win_value', 'created_at', DB::raw('DATE(created_at) as date'))
            ->where('game_id', $game_id)
            ->orderBy('date', 'DESC')
            ->orderBy('created_at', 'ASC')
            ->get()
            ->groupBy('date');

        $abc = [];

        foreach ($results as $key => $result) {
            $month = date('M', strtotime($key));

            $abc[$month][$key] = $result;
        }

        $game_name = GamesList::select('title')->where('id', $game_id)->first();

        return response()->json(['status' => true, 'result' => $abc, 'game_name' => $game_name]);
    }

    public function web_game(Request $request)
    {
        $token = [
            'customer_id' => 456456,
            'user_id' => 45,
            'unique_id' => date('YmdHi')
        ];

        $encript = Crypt::encryptString(json_encode($token));
        $decript = Crypt::decryptString($encript);
        dd($encript, json_decode($decript, true));

        // try {
        //     $decript = Crypt::decryptString($request->token);
        //     dd(json_decode($decript, true));
        // } catch (\Throwable $th) {
        //     dd("sdsa");
        // }


        // $encript = base64_encode(json_encode($token));
        // $decript = base64_decode($encript);
        // dd($encript, json_decode($decript, true));

        // try {
        //     $decript = base64_decode($request->token);
        //     dd(json_decode($decript, true));
        // } catch (\Throwable $th) {
        //     dd("Invalid params");
        // }
    }
}
