<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        "app_name", "url", "phone", "email", "logo", "baner", "game_rule",
        "add_money_details", "withdrawal_details", "notice", "withdrawal", "min_withdraw", "min_add_money", "max_single_bet", "max_bet_amount", 'currency_word', 'currency_symbol', 'currency_icon', 'message', 'wd_start_time', 'wd_end_time', 'wd_days'
    ];
}
