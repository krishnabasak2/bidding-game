<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class BidsHistory extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['user_id ', 'game_id', 'time_id', 'game_type', 'bid_number', 'bid_amount', 'status', 'won_amount', 'result_id'];

    public function getUser(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function time(): BelongsTo
    {
        return $this->belongsTo(GamesTime::class);
    }

    public function result(): BelongsTo
    {
        return $this->belongsTo(GamesResult::class);
    }
}
