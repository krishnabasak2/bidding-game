<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\Deposit;
use App\Models\Payout;
use App\Models\PayoutSetting;
use App\Models\SiteSetting;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use PhpParser\Node\Stmt\TryCatch;

class UserMain extends Controller
{

    public function register(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name'              => 'required',
                'phone'             => 'required|unique:users,phone',
                'password'          => 'required_with:confirm_password|same:confirm_password|min:6',
                'confirm_password'  => 'required|min:6'
            ]);

            if ($validator->fails()) {
                return response()->json(['status' => false, 'message' => $validator->errors()->first(), 'data' => null], 400);
            }

            $count = User::count();
            $request['password'] = md5(md5(md5($request['password'])));
            $request['user_id'] = Helper::user_id($count, 4);
            $user = User::create($request->except('_token'));
            if ($user) {
                $token = $user->createToken('myApp')->plainTextToken;
                return response()->json(['status' => true, 'token' => $token, 'message' => 'Registration successful.', 'data' => null], 200);
            } else {
                return response()->json(['status' => false, 'message' => 'Registration failed.', 'data' => null], 400);
            }
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'message' => 'Internal server errors.', 'data' => $th], 500);
        }
    }


    public function login(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'phone'             => 'required',
                'password'          => 'required|min:6',
            ]);

            if ($validator->fails()) {
                return response()->json(['status' => false, 'message' => $validator->errors()->first(), 'data' => null]);
            }

            $user = User::where('phone', $request['phone'])->first();
            if (!empty($user) && $user->status == '1') {

                if (md5(md5(md5($request['password']))) == $user->password) {
                    $token = $user->createToken('myApp')->plainTextToken;
                    return response()->json(['status' => true, 'token' => $token, 'message' => 'Login successful.', 'data' => null], 200);
                } else {
                    return response()->json(['status' => false, 'message' => 'Password not match.', 'data' => null], 400);
                }
            } elseif (!empty($user) && $user->status == '0') {
                return response()->json(['status' => false, 'message' => 'Your account has been suspended.', 'data' => null], 400);
            } else {
                return response()->json(['status' => false, 'message' => 'Phone number not exist.', 'data' => null], 400);
            }
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'message' => 'Internal server errors.', 'data' => null], 500);
        }
    }


    public function logout()
    {
        try {
            return response()->json(['user' => Auth::user()]);
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'message' => 'Internal server errors.', 'data' => null], 500);
        }
    }


    public function get_user()
    {
        try {
            $user = User::where(['id' => Auth::id(), 'status' => '1'])->first();
            if (!empty($user)) {
                return response()->json(['status' => true, 'user' => $user, 200]);
            } else {
                return response()->json(['status' => false, 'user' => null, 400]);
            }
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'message' => 'Internal server errors.', 'data' => null], 500);
        }
    }


    public function update(Request $request)
    {
        try {
            $user = User::where(['id' => Auth::id(), 'role' => '1'])->first();

            $validator = Validator::make($request->all(), [
                'name'              => 'required',
                'phone'             => "required|numeric|unique:users,phone,$user->id",
                'email'             => "sometimes|email|unique:users,email,$user->id"
            ]);

            if ($validator->fails()) {
                return response()->json(['status' => false, 'message' => $validator->errors()->first(), 'data' => null], 400);
            }

            if ($user->update(['name' => $request['name'], 'phone' => $request['phone'], 'email' => $request['email']])) {

                $user = User::where(['id' => Auth::id(), 'role' => '1'])->first();

                return response()->json(['status' => true, 'message' => 'Account Has Been Updated Successully.', 'data' => $user], 200);
            } else {
                return response()->json(['status' => false, 'message' => "Something went wrong! Please try agin.", 'data' => null], 400);
            }
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'message' => 'Internal server errors.', 'data' => null], 500);
        }
    }

    public function change_password(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'current_password'          => 'required|min:6',
                'new_password'              => 'required_with:confirm_password|same:confirm_new_password',
                'confirm_new_password'      => 'required|min:6',
            ]);

            if ($validator->fails()) {
                return response()->json(['status' => false, 'message' => $validator->errors()->first(), 'data' => null]);
            }

            $user = User::where('id', Auth::id())->first();

            if (!empty($user)) {
                if (md5(md5(md5($request['current_password']))) == $user->password) {
                    $user->update(['password' => md5(md5(md5($request['new_password'])))]);

                    return response()->json(['status' => true, 'message' => 'Password Has Been Updated Successully.', 'data' => $user], 200);
                } else {
                    return response()->json(['status' => false, 'message' => "Current password not match.", 'data' => null], 400);
                }
            } else {
                return response()->json(['status' => false, 'message' => "Something went wrong! Please try agin.", 'data' => null], 400);
            }
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'message' => 'Internal server errors.', 'data' => $th], 500);
        }
    }

    public function get_payout_setting()
    {
        try {
            $settings = PayoutSetting::where('id', Auth::id())->first();

            return response()->json(['status' => true, 'settings' => $settings, 200]);
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'message' => 'Internal server errors.', 'data' => null], 500);
        }
    }


    public function payout_settings(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'txn_mode'             => 'required|in:1,2,3,4',
                'payout_number'        => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json(['status' => false, 'message' => $validator->errors()->first(), 'data' => null], 400);
            }

            $payout_data = PayoutSetting::where('user_id', Auth::id())->first();

            if (!empty($payout_data)) {
                $payout_data->update(['txn_mode' => $request['txn_mode'], 'payout_number' => $request['payout_number']]);

                $payout_data = PayoutSetting::where('user_id', Auth::id())->first();

                return response()->json(['status' => true, 'message' => 'Payout settings update successful.', 'data' => $payout_data], 200);
            } else {
                PayoutSetting::create(['user_id' => Auth::id(), 'txn_mode' => $request['txn_mode'], 'payout_number' => $request['payout_number']]);

                return response()->json(['status' => true, 'message' => 'Payout settings update successful.', 'data' => null], 201);
            }
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'message' => 'Internal server errors.', 'data' => null], 500);
        }
    }


    public function deposit_request(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'amount'             => 'required|numeric',
                'txn_number'         => 'required',
                'txn_method'         => 'required|in:1,2,3,4',
            ]);

            if ($validator->fails()) {
                return response()->json(['status' => false, 'message' => $validator->errors()->first(), 'data' => null], 400);
            }

            $settings = SiteSetting::first();

            if ($settings->min_add_money > $request['amount']) {
                return response()->json(['status' => false, 'message' => "Minimum deposit amount is {$settings->min_add_money}", 'data' => null], 400);
            }

            $pending_check = Deposit::where(['user_id' => Auth::id(), 'status' => '2'])->count();
            if ($pending_check >= 1) {
                return response()->json(['status' => false, 'message' => "Already you have a pending request.", 'data' => null], 400);
            }

            $txn_count = Transaction::count();
            $txn_id = Helper::txn_id($txn_count, 8, 'D');

            $deposit = Deposit::create(['user_id' => Auth::id(), 'amount' => $request['amount'], 'txn_id' => $txn_id, 'txn_number' => $request['txn_number'], 'txn_method' => $request['txn_method']]);

            if ($deposit) {
                return response()->json(['status' => true, 'message' => "Deposit request successful.", 'data' => null], 200);
            } else {
                return response()->json(['status' => false, 'message' => "Something went wrong! Please try agin.", 'data' => null], 400);
            }
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'message' => 'Internal server errors.', 'data' => null], 500);
        }
    }


    public function payout_request(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'amount'             => 'required|numeric',
                // 'method'             => 'required|in:1,2,3,4',
            ]);

            if ($validator->fails()) {
                return response()->json(['status' => false, 'message' => $validator->errors()->first(), 'data' => null], 400);
            }

            $user = User::where('id', Auth::id())->with('payout_settings')->first();
            // dd($user);

            // return $user;

            if ($user->wallet < $request['amount']) {
                return response()->json(['status' => false, 'message' => "Insufficient wallet balance.", 'data' => null], 400);
            }

            if (empty($user->payout_settings) || empty($user->payout_settings->payout_number) || empty($user->payout_settings->txn_mode)) {
                return response()->json(['status' => false, 'message' => "Payout settings are not updated.", 'data' => null], 400);
            }

            $settings = SiteSetting::first();
            if ($settings->withdrawal == '0') {
                return response()->json(['status' => false, 'message' => "Payout time is over.", 'data' => null], 400);
            }
            if ($settings->min_withdraw > $request['amount']) {
                return response()->json(['status' => false, 'message' => "Minimum payout amount is {$settings->min_withdraw}", 'data' => null], 400);
            }

            $pending_check = Payout::where(['user_id' => Auth::id(), 'status' => '2'])->count();
            if ($pending_check >= 1) {
                return response()->json(['status' => false, 'message' => "Already you have a pending request.", 'data' => null], 400);
            }

            $txn_count = Transaction::count();
            $txn_id = Helper::txn_id($txn_count, 6, 'P');

            $payout = Payout::create(['user_id' => Auth::id(), 'amount' => $request['amount'], 'txn_id' => $txn_id, 'txn_number' => $user->payout_settings->payout_number, 'txn_method' => $user->payout_settings->txn_mode]);

            if ($payout) {

                Helper::wallet(Auth::id(), '0', $request['amount'], '3', 'For payout');

                return response()->json(['status' => true, 'message' => "Payout request successful.", 'data' => null], 200);
            } else {
                return response()->json(['status' => false, 'message' => "Something went wrong! Please try agin.", 'data' => null], 400);
            }
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'message' => 'Internal server errors.', 'data' => null], 500);
        }
    }


    public function deposit_list()
    {
        try {
            $data = Deposit::where('user_id', Auth::id())->orderBy('id', 'DESC')->paginate(20);

            // dd($data->toArray());
            return response()->json(['status' => true, 'message' => "Deposit list", 'data' => $data], 200);
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'message' => 'Internal server errors.', 'data' => null], 500);
        }
    }


    public function payout_list()
    {
        try {
            $data = Payout::where('user_id', Auth::id())->orderBy('id', 'DESC')->paginate(20);

            // dd($data->toArray());
            return response()->json(['status' => true, 'message' => "Payout list", 'data' => $data], 200);
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'message' => 'Internal server errors.', 'data' => null], 500);
        }
    }


    public function transaction()
    {
        try {
            $data = Transaction::where('user_id', Auth::id())->orderBy('id', 'DESC')->paginate(20);

            // dd($data->toArray());
            return response()->json(['status' => true, 'message' => "Transaction list", 'data' => $data], 200);
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'message' => 'Internal server errors.', 'data' => null], 500);
        }
    }
}
