<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class GamesResult extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['game_id', 'time_id', 'patti_win_value', 'jodi_win_value', 'single_win_value', 'cp_win_value', 'status', 'distribute'];

    public function getTime(): BelongsTo
    {
        return $this->belongsTo(GamesTime::class, 'time_id');
    }

    public function getGame(): HasMany
    {
        return $this->hasMany(GamesList::class, 'id', 'game_id');
    }

    public function bid(): HasMany
    {
        return $this->hasMany(BidsHistory::class, 'result_id', 'id');
    }
}
