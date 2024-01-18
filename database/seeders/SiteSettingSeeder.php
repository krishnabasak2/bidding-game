<?php

namespace Database\Seeders;

use App\Models\SiteSetting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SiteSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        SiteSetting::insert([
            'admin_id'              => 1,
            'app_name'              => env('APP_NAME'),
            'url'                   => null,
            'phone'                 => '1234567890',
            'email'                 => 'subho7113@gmail.com',
            'url'                   => env('APP_URL'),
            'currency_symbol'       => '₹',
            'currency_word'         => 'INR',
            'logo'                  => null,
            'ads'                   => null,
            'baner'                 => null,
            'ads_text'              => 'N/A',
            'ads_status'            => '0',
            'game_rule'             => 'N/A',
            'add_money_details'     => 'N/A',
            'withdrawal_details'    => 'N/A',
            'notice'                => 'N/A',
            'message'               => 'N/A',
            'withdrawal'            => '1',
            'min_withdraw'          => 500,
            'min_add_money'         => 100,
            'max_single_bet'        => 10,
            'max_bet_amount'        => 10000,
        ]);
    }
}
