<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as AuthUser;
use Laravel\Sanctum\HasApiTokens;

class User extends AuthUser
{
    use HasFactory, HasApiTokens, SoftDeletes;

    protected $fillable = [
        'id',
        'user_id',
        'name',
        'email',
        'password',
        'phone',
        'role',
        'wallet',
        'status',
        'referer_uid',
        'updated_at',
        'game_settings'
    ];

    protected $hidden = ['password'];

    public function getTransaction(): HasMany
    {
        return $this->hasMany(Transaction::class, 'user_id', 'id')->orderBy('id', 'DESC')->withTrashed();
    }

    public function bids(): HasMany
    {
        return $this->hasMany(BidsHistory::class, 'user_id', 'id');
    }

    public function payout_settings(): HasOne
    {
        return $this->hasOne(PayoutSetting::class, 'user_id', 'id');
    }
}
