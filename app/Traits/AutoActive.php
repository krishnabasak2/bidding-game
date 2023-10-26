<?php

namespace App\Traits;

use App\Models\GamesResult;
use App\Models\GamesTime;

trait AutoActive
{
    public function game_on($id)
    {
        $current_time = date("H:i");
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

        $game_data = GamesTime::where(['status' => '1', 'game_id' => $id])->where('start_time', '<', $current_time)->whereJsonContains('game_days', $day)->with('getGame')->get();

        // dd($game_data->toArray());

        foreach ($game_data as $value) {
            
            $checkResult = GamesResult::where('created_at', 'like', "%" . date('Y-m-d') . "%")->where(['game_id' => $value->game_id, 'time_id' => $value->id])->first();

            if (empty($checkResult)) {
                GamesResult::create(['game_id' => $value->game_id, 'time_id' => $value->id, 'status' => '0']);
            }
        }
    }
}
