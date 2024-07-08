<?php

namespace App\Traits;

use App\Models\User;

trait SessionManage
{
    public function manage($user_id)
    {
        $user = User::where('id', $user_id)->first();
        $login_session = date("YmdHis") . mt_rand(10, 99);

        if (!$user['session']) {
            $data = [$login_session];
            $user->update(['session' => json_encode($data)]);
        } else {
            $data = json_decode($user['session'], true);
            if (count($data) >= 5) {
                $user->update(['session' => null]);
                $data = [];
            }
            array_push($data, $login_session);
            $user->update(['session' => json_encode($data)]);
        }

        return $login_session;
    }
}
