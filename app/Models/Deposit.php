<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Deposit extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'txn_id', 'amount', 'txn_number', 'txn_method', 'status', 'remarks'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
