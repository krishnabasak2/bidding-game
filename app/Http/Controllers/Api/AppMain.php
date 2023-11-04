<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\GamesList;
use App\Models\GamesResult;
use App\Models\GamesTime;
use App\Models\SiteSetting;
use App\Traits\AutoActive;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class AppMain extends Controller
{
    use AutoActive;
    public function info()
    {
        try {
            $data = SiteSetting::first();

            // dd($data->toArray());
            return response()->json(['status' => true, 'message' => "App info", 'data' => $data], 200);
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'message' => 'Internal server errors.', 'data' => null], 500);
        }
    }


    public function game_list()
    {
        try {
            $data = GamesList::where('status', '1')->with('get_last_time')->get();

            return response()->json(['status' => true, 'message' => "Game list", 'data' => $data], 200);
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'message' => 'Internal server errors.', 'data' => null], 500);
        }
    }


    public function game_time($game_id)
    {
        try {
            $this->game_on($game_id);

            $data = GamesTime::where(['game_id' => $game_id, 'status' => '1'])->with('get_result')->get();

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
            ->get()
            ->groupBy('date');

        $game_name = GamesList::select('title')->where('id', $game_id)->first();

        return response()->json(['status' => true, 'result' => $results, 'game_name' => $game_name]);
        // dd($results);
    }
}
