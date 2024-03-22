<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        "app_name", "url", "phone", "whatsapp", "email", "logo", "baner", "banner_links", "game_rule", "add_money_details", "withdrawal_details", "notice", "withdrawal", "min_withdraw", "min_add_money", "max_single_bet", "max_bet_amount", 'currency_word', 'currency_symbol', 'currency_icon', 'currency_value', 'message', 'wd_start_time', 'wd_end_time', 'wd_days', 'wd_limit', 'ads', 'ads_status', 'ads_text', 'ads_link', 'joiner_bonus', 'referrer_bonus', 'new_ac_bonus', "max_jodi_bet", "max_jodi_amount", "max_patti_bet", "max_patti_amount",
    ];
}
