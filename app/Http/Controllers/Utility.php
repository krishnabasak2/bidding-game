<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use Illuminate\Http\Request;

class Utility extends Controller
{
    public static function text()
    {
        return true;
    }

    public function web()
    {
        $check = Helper::customer_check();
        $check = json_decode($check, true);

        if ($check && $check['status'] == true) {
            $data['app_link'] = $check['data']['app_link'];
            $data['phone'] = $check['data']['phone'];
            return view('web.index', $data);
        } else {
            return view('web.maintenance');
        }
    }
}
