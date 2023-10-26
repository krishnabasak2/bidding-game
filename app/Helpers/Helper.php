<?php

namespace App\Helpers;

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

        return "BG" . $prefix_zero . $user_id;
    }

    public static function txn_id($txn_id, $length, $pre)
    {
        $prefix_zero = NULL;
        $max_id_length = $length;
        $user_id_length = strlen($txn_id);

        for ($max_id_length; $max_id_length > $user_id_length; $max_id_length--) {
            $prefix_zero = $prefix_zero . '0';
        }

        return "BG" . $pre . $prefix_zero . $txn_id;
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
            self::transaction($user_id, rand(100000, 999999), $amount, $type, $for, $balance, $remarks);
            return true;
        } else {
            return false;
        }
    }
}
