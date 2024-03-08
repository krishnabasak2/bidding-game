<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Models\SiteSetting;
use App\Models\User;
use Illuminate\Http\Request;

class Utility extends Controller
{
    public static function text()
    {
        return true;
    }

    public function web()
    {
        $setting = SiteSetting::select('phone')->first();
        // $check = Helper::customer_check();
        // $check = json_decode($check, true);

        $status_check = User::where(['id' => '1', 'role' => '0'])->select('game_settings')->first();

        $check = json_decode($status_check['game_settings'], true);

        if ($check && $check['status'] == true) {
            $data['app_link'] = $check['data']['app_link'];
            $data['phone'] = $setting['phone'];

            return view('web.index', $data);
        } else {
            return view('web.maintenance');
        }
    }
}
