<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\GamesList;
use App\Models\GamesTime;
use App\Models\SiteSetting;
use App\Traits\AutoActive;
use Illuminate\Http\Request;
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
            $data = GamesList::where('status', '1')->get();

            // dd($data->toArray());
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

            // dd($data->toArray());
            return response()->json(['status' => true, 'message' => "App info", 'data' => $data], 200);
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'message' => 'Internal server errors.', 'data' => null], 500);
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

        return response()->json(['status' => true, 'result' => $results]);
        // dd($results);
    }
}
