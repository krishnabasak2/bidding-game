<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class GamesList extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['title', 'icon', 'single_win_value', 'patti_win_value', 'jodi_win_value', 'cp_win_value', 'status'];

    public function getTime(): HasMany
    {
        return $this->hasMany(GamesTime::class, 'game_id', 'id');
    }

    public function get_last_time(): HasOne
    {
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

        return $this->hasOne(GamesTime::class, 'game_id', 'id')->whereJsonContains('game_days', $day)->where('status', '1')->latest('start_time');
    }
}
