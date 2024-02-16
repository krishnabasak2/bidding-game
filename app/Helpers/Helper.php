<?php

namespace App\Helpers;

use App\Models\SiteSetting;
use App\Models\Transaction;
use App\Models\User;

class Helper
{
    public static function user_id($user_id, $length)
    {
        $prefix_zero = NULL;
        $max_id_length = $length;
        $user_id_length = strlen($user_id);

        for ($max_id_length; $max_id_length > $user_id_length; $max_id_length--) {
            $prefix_zero = $prefix_zero . '0';
        }

        $prefix = env('CUSTOMER_CODE');
        return $prefix . $prefix_zero . $user_id;
    }

    public static function txn_id($txn_id, $length, $pre)
    {
        $prefix_zero = NULL;
        $max_id_length = $length;
        $user_id_length = strlen($txn_id);

        for ($max_id_length; $max_id_length > $user_id_length; $max_id_length--) {
            $prefix_zero = $prefix_zero . '0';
        }

        $prefix = env('CUSTOMER_CODE');
        return $prefix . $pre . $prefix_zero . $txn_id;
    }


    public static function transaction($user_id, $txn_id, $amount, $type, $status, $current, $remarks = null)
    {
        $data = [
            'user_id'           => $user_id,
            'txn_id'            => $txn_id,
            'amount'            => $amount,
            'type'              => $type,
            'status'            => $status,
            'current_balance'   => $current,
            'remarks'           => $remarks
        ];

        if (Transaction::create($data)) {
            return true;
        } else {
            return false;
        }
    }

    public static function wallet($user_id, $type, $amount, $for, $remarks)
    {
        $user = User::where('id', $user_id)->withTrashed()->first();

        if ($type == '0') {
            $balance = round($user->wallet - $amount, 2);
        } elseif ($type == '1') {
            $balance = round($user->wallet + $amount, 2);
        } else {
            $balance = $user->wallet;
        }

        if ($user->update(['wallet' => $balance])) {
            $prefix = env('CUSTOMER_CODE');

            $tranId = $prefix . rand(100000, 999999);
            self::transaction($user_id, $tranId, $amount, $type, $for, $balance, $remarks);
            return true;
        } else {
            return false;
        }
    }

    public static function sendPush($device_ids, $message)
    {
        $info = SiteSetting::where('id', '1')->first();

        $content = array("en" => $message);
        $heading = array("en" => $info->app_name);

        foreach ($device_ids as $key => $device_id) {
            $fields = array(
                'app_id' => "63bccc3f-9b9c-4df0-a7bf-583a9b08ce30",
                'include_external_user_ids' => array($device_id),
                'data' => array("data" => "test data"),
                'contents' => $content,
                'headings' => $heading
            );
            $fields = json_encode($fields);

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8', 'Authorization: Basic MzcwZjU5YzAtODJhOS00ZjM0LTg3YTMtNDNhZGViNzdiM2Nk'));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($ch, CURLOPT_HEADER, FALSE);
            curl_setopt($ch, CURLOPT_POST, TRUE);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

            $response = curl_exec($ch);
            curl_close($ch);
            return $response;
        }
    }

    public static function login_check($token)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => env('MASTER_URL') . '/api/login-check/' . env('CUSTOMER_ID') . '/' . $token,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return $response;
    }

    public static function customer_check()
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => env('MASTER_URL') . '/api/customer/get/' . env('CUSTOMER_ID'),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return $response;
    }

    public static function master_check($master_id)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => env('MASTER_URL') . "/api/customer/master-id-check/" . $master_id,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return $response;
    }

    public static function import_csv($csv_file)
    {
        if ((file_exists($csv_file)) and (is_readable($csv_file))) {
            if (($file = fopen($csv_file, 'r')) != FALSE) {
                $index = FALSE;

                while (($value = fgetcsv($file)) != FALSE) {
                    if (!$index) {
                        $index = str_replace('"', NULL, $value);
                    } else {
                        $data[] = array_combine($index, $value);
                    }
                }

                fclose($file);
                return $data;
            } else {
                return NULL;
            }
        } else {
            return NULL;
        }
    }
}
