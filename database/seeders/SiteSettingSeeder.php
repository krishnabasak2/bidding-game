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
            'app_name'              => 'Bidding App',
            'url'                   => null,
            'phone'                 => '1234567890',
            'email'                 => 'admin@gmail.com',
            'currency_symbol'       => null,
            'currency_word'         => null,
            'logo'                  => null,
            'baner'                 => null,
            'game_rule'             => 'N/A',
            'add_money_details'     => 'N/A',
            'withdrawal_details'    => 'N/A',
            'notice'                => 'N/A',
            'message'               => 'N/A',
            'withdrawal'            => '1',
            'min_withdraw'          => 0,
            'min_add_money'         => 0,
            'max_single_bet'        => 10,
            'max_bet_amount'        => 1000,
        ]);
    }
}
