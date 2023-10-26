<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PayoutSetting extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['user_id', 'txn_mode', 'payout_number', 'ac_name', 'ac_number', 'ac_ifsc', 'remarks', 'created_at', 'updated_at'];
}
