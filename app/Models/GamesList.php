<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class GamesList extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['title', 'single_win_value', 'patti_win_value', 'jodi_win_value', 'cp_win_value', 'status'];

    public function getTime(): HasMany
    {
        return $this->hasMany(GamesTime::class, 'game_id', 'id');
    }
}
