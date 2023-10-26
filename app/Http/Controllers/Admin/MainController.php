<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BidsHistory;
use App\Models\Deposit;
use App\Models\Payout;
use App\Models\SiteSetting;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class MainController extends Controller
{
    public function common()
    {
        $settings = SiteSetting::first();
        return $settings;
    }

    public function login(Request $request)
    {
        $data['site_data'] = $this->common();
        $data['title'] = 'Admin Login';

        if (Request()->isMethod('POST')) {
            $request->validate([
                'email'     => 'required|email',
                'password'  => 'required|min:6'
            ]);

            $user = User::where('email', $request['email'])->first();

            if (!empty($user)) {

                if (md5(md5(md5($request['password']))) == $user->password) {

                    $session = new Session();
                    $session::put('admin', $user);
                    return redirect('admin');
                } else {
                    return redirect()->back()->with('message', 'Credentials Not Match.');
                }
            } else {
                return redirect()->back()->with('message', 'Credentials Not Match.');
            }
        }
        return view('admin.login', $data);
    }

    public function logout()
    {
        Session::forget('admin');
        return redirect('login');
    }

    public function dashboard()
    {
        $data['site_data'] = $this->common();
        $data['title'] = 'Dashboard';

        $data['diposit'] = Deposit::where('status', '2')->count();
        $data['payout'] = Payout::where('status', '2')->count();

        $data['total_customer'] = User::where('role', '!=', '0')->count();

        $data['total_customer_wallet_balance'] = User::where('role', '!=', '0')->sum('wallet');

        $data['total_monthly_bid_amount'] = BidsHistory::whereMonth('created_at', Carbon::now()->month)->get()->sum('bid_amount');

        $data['total_monthly_win_amount'] = BidsHistory::whereMonth('created_at', Carbon::now()->month)->get()->sum('won_amount');

        $data['total_today_bid_amount'] = BidsHistory::whereDate('created_at', Carbon::today())->get()->sum('bid_amount');

        $data['total_today_win_amount'] = BidsHistory::whereDate('created_at', Carbon::today())->get()->sum('won_amount');

        // dd($data);

        return view('admin.dashboard', $data);
    }

    public function settings(Request $request)
    {
        $data['site_data'] = $this->common();
        $data['title'] = 'My Settings';
        $data['page'] = 'Dashboard';
        $data['settings_data'] = SiteSetting::first();

        if (Request()->isMethod('POST')) {
            $validator = Validator::make($request->all(), [
                "app_name"              => "required",
                "url"                   => "required|url",
                "phone"                 => "required",
                "email"                 => "required|email",
                "currency_symbol"       => "required",
                "currency_word"         => "required",
                "baner.*"               => "nullable|max:2050",
                "game_rule"             => "required",
                "add_money_details"     => "required",
                "withdrawal_details"    => "required",
                "notice"                => "required",
                "message"               => "nullable",
                "withdrawal"            => "required",
                "min_withdraw"          => "required|numeric",
                "min_add_money"         => "required|numeric",
                "max_single_bet"        => "required|numeric|max:10",
                "max_bet_amount"        => "required|numeric",
            ], [], [
                "app_name"              => "Application Name",
                "url"                   => "Application Website",
                "phone"                 => "Phone No.",
                "email"                 => "Email ID",
                "currency_symbol"       => "Currency Symbol",
                "currency_word"         => "Currency Word",
                "baner"                 => "Welcome Banners",
                "game_rule"             => "Game Rule",
                "add_money_details"     => "Deposit Details",
                "withdrawal_details"    => "Withdrawal Details",
                "notice"                => "Notice",
                "message"               => "Message",
                "withdrawal"            => "Withdrawal",
                "min_withdraw"          => "Minimum Withdrawal Amount",
                "min_add_money"         => "Minimum Deposit",
                "max_single_bet"        => "Maximum Single Bidding Number",
                "max_bet_amount"        => "Maximum Bidding Amount",
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            if ($request->baner && count($request->baner) > 3) {
                return redirect()->back()->with('message', 'You Can Upload Maximum 3 Banner Images.');
            }

            $images_path = [];

            if ($request->baner) {
                $banner_data = json_decode($data['settings_data']['baner'], true);

                foreach ($request->baner as $key => $image) {
                    $image_name = md5(rand(100, 1000)) . '.' . $image->getClientOriginalExtension();
                    $image->storeAs('public/images', $image_name);
                    $images_path[] = $image_name;


                    if ($banner_data && isset($banner_data[$key]) && File::exists('storage/images/' . $banner_data[$key])) {
                        unlink('storage/images/' . $banner_data[$key]);
                    }
                }
            }

            $update_data = [
                "app_name"              => $request['app_name'],
                "url"                   => $request['url'],
                "phone"                 => $request['phone'],
                "email"                 => $request['email'],
                "currency_symbol"       => $request['currency_symbol'],
                "currency_word"         => $request['currency_word'],
                "game_rule"             => $request['game_rule'],
                "add_money_details"     => $request['add_money_details'],
                "withdrawal_details"    => $request['withdrawal_details'],
                "notice"                => $request['notice'],
                "message"               => $request['message'],
                "withdrawal"            => $request['withdrawal'],
                "min_withdraw"          => $request['min_withdraw'],
                "min_add_money"         => $request['min_add_money'],
                "max_single_bet"        => $request['max_single_bet'],
                "max_bet_amount"        => $request['max_bet_amount'],
            ];

            if ($images_path) {
                $update_data['baner'] = $images_path;
            }

            if ($data['settings_data']->update($update_data)) {
                return redirect()->back()->with('message', 'Settings Have Been Updated Successully');
            } else {
                return redirect()->back()->with('message', 'Something Went Wrong.');
            }
        }

        return view('admin.settings', $data);
    }

    public function update_profile(Request $request)
    {
        $data['site_data'] = $this->common();
        $data['title'] = 'My Profile';
        $data['page'] = 'Dashboard';
        $admin_data = User::where('id', Session::get('admin')->id)->first();

        if (Request()->isMethod('POST')) {
            $validator = Validator::make($request->all(), [
                'name'      => "required",
                'email'     => "required|email|unique:users,email,{$admin_data->id}",
                'phone'     => "required|unique:users,phone,{$admin_data->id}",
            ], [], [
                'name'      => "Name",
                'email'     => "Email ID",
                'phone'     => "Phone No.",
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $update_data = [
                "name"      => $request->name,
                "email"     => $request->email,
                "phone"     => $request->phone,
            ];

            if ($admin_data->update($update_data)) {
                return redirect()->back()->with('message', 'Profile Has Been Updated Successully');
            } else {
                return redirect()->back()->with('message', 'Something Went Wrong.');
            }
        }

        $data['admin_data'] = $admin_data;
        return view('admin.update-profile', $data);
    }

    public function change_password(Request $request)
    {
        $data['site_data'] = $this->common();
        $data['title'] = 'Change Password';
        $data['page'] = 'Dashboard';
        $admin_data = User::where('id', Session::get('admin')->id)->first();

        if (Request()->isMethod('POST')) {
            $validator = Validator::make($request->all(), [
                'cur_pass'      => "required",
                'new_pass'      => "required|min:6",
                'c_pass'        => "required|same:new_pass",
            ], [], [
                'cur_pass'      => "Current Password",
                'new_pass'      => "New Password",
                'c_pass'        => "Confirm Password",
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            if (md5(md5(md5($request['cur_pass']))) == $admin_data->password) {
                $admin_data->update(['password' => md5(md5(md5($request['new_pass'])))]);
                return redirect()->back()->with('message', 'Password Change Successfull.');
            } else {
                return redirect()->back()->with('message', 'Current Password Not Match');
            }
        }

        return view('admin.change-password', $data);
    }
}
