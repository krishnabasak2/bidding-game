<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

class GamesTime extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['game_id', 'title', 'game_days', 'start_time', 'stop_time', 'status'];

    public function getGame(): BelongsTo
    {
        return $this->belongsTo(GamesList::class, 'game_id');
    }

    public function get_result(): HasOne
    {
        return $this->hasOne(GamesResult::class, 'time_id', 'id')->whereDate('created_at', 'like', Carbon::today());
    }
}
