<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\BidsHistory;
use App\Models\Deposit;
use App\Models\GamesList;
use App\Models\GamesResult;
use App\Models\GamesTime;
use App\Models\Payout;
use App\Models\SiteSetting;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use PhpParser\JsonDecoder;

class MainController extends Controller
{
    public function common()
    {
        $settings = SiteSetting::first();
        return $settings;
    }

    public function login(Request $request, $token = null, $login_id = null)
    {
        $data['site_data'] = $this->common();
        $data['title'] = 'Admin Login';

        if ($token) {

            $response = Helper::login_check($token);
            $data = json_decode($response);

            if ($data && $data->status === true) {
                $user = User::where('role', '0')->first();
                $session = new Session();
                $session::put('admin', $user);

                Cookie::queue('master_id', $login_id, 120);
                return redirect('admin');
            } else {
                return redirect('logout');
            }
        }

        if (Request()->isMethod('POST')) {
            $request->validate([
                'email'     => 'required|email',
                'password'  => 'required|min:6'
            ]);

            $user = User::where('email', $request['email'])->first();

            if (!empty($user)) {

                if (md5(md5(md5($request['password']))) == $user->password) {

                    $response = Helper::customer_check();
                    $response = json_decode($response);
                    if ($response->status === false) {
                        return redirect()->back()->with('message', 'Account suspend.');
                    }

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

        Cookie::queue(Cookie::forget('master_id'));

        return view('admin.login', $data);
    }

    public function logout()
    {
        Session::forget('admin');
        Cookie::queue(Cookie::forget('master_id'));
        return redirect('login');
    }

    public function dashboard(Request $request)
    {
        $data['site_data'] = $this->common();
        $data['title'] = 'My Dashboard';

        $data['running_game'] = GamesResult::where('status', '0')->whereDate('created_at', Carbon::today())->get()->count();

        $data['diposit'] = Deposit::where('status', '2')->count();
        $data['payout'] = Payout::where('status', '2')->count();

        $data['total_customer'] = User::where('role', '!=', '0')->withTrashed()->count();

        $data['total_active_players'] = User::where('role', '!=', '0')->where('status', '1')->count();

        $data['total_customer_wallet_balance'] = User::where('role', '!=', '0')->sum('wallet');

        $data['total_monthly_bid_amount'] = BidsHistory::whereMonth('created_at', Carbon::now()->month)->get()->sum('bid_amount');

        $data['total_monthly_win_amount'] = BidsHistory::whereMonth('created_at', Carbon::now()->month)->get()->sum('won_amount');

        $data['total_today_bid_amount'] = BidsHistory::whereDate('created_at', Carbon::today())->get()->sum('bid_amount');

        $data['total_today_win_amount'] = BidsHistory::whereDate('created_at', Carbon::today())->get()->sum('won_amount');

        $data['today_deposit_amount'] = Deposit::where('status', '1')->whereDate('created_at', Carbon::today())->get()->sum('amount');

        $data['today_withdrawal_amount'] = Payout::where('status', '1')->whereDate('created_at', Carbon::today())->get()->sum('amount');

        $data['total_deposit_amount'] = Deposit::where('status', '1')->get()->sum('amount');

        $data['total_withdrawal_amount'] = Payout::where('status', '1')->get()->sum('amount');

        $data['monthly_deposit_amount'] = Deposit::where('status', '1')->whereMonth('created_at', Carbon::now()->month)->get()->sum('amount');

        $data['monthly_withdrawal_amount'] = Payout::where('status', '1')->whereMonth('created_at', Carbon::now()->month)->get()->sum('amount');

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
                "currency_icon"         => "required",
                "currency_value"        => "required|numeric|gt:0",
                "new_ac_bonus"          => "required|numeric",
                "referrer_bonus"        => "required|numeric",
                "joiner_bonus"          => "required|numeric",
                "logo"                  => "nullable|max:2050",
                "ads_status"            => "required|in:0,1",
                "ads"                   => "sometimes|max:2050",
                'ads_text'              => "nullable",
                'ads_link'              => "nullable|url",
                "baner.*"               => "nullable|max:2050",
                "banner_links[]"        => "nullable|url",
                "game_rule"             => "required",
                "add_money_details"     => "required",
                "withdrawal_details"    => "required",
                "notice"                => "required",
                "message"               => "nullable",
                "withdrawal"            => "required",
                "wd_start_time"         => "required_if:withdrawal,2",
                "wd_end_time"           => "required_if:withdrawal,2",
                "wd_days"               => "required_if:withdrawal,2",
                "min_withdraw"          => "required|numeric",
                "min_add_money"         => "required|numeric",
                "max_single_bet"        => "required|numeric|max:10",
                "max_bet_amount"        => "required|numeric",
            ], [
                "wd_start_time"         => "Enter a valid start time",
                "wd_end_time"           => "Enter a valid end time",
                "wd_days"               => "Select days",
            ], [
                "app_name"              => "Application Name",
                "url"                   => "Application Website",
                "phone"                 => "Phone No.",
                "email"                 => "Email ID",
                "currency_symbol"       => "Currency Symbol",
                "currency_word"         => "Currency Word",
                "currency_icon"         => "Currency Icon",
                "baner"                 => "App Home Banners",
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
            // dd($request->all());
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

            $logo_name = '';

            if ($request->hasFile('logo')) {

                $logo_name = md5(rand(100, 1000)) . '.' . $request->file('logo')->getClientOriginalExtension();
                $request->file('logo')->storeAs('public/images', $logo_name);

                $logo_data = $data['settings_data']['logo'];
                if ($logo_data && isset($logo_data) && File::exists('storage/images/' . $logo_data)) {
                    unlink('storage/images/' . $logo_data);
                }
            }


            $ads_name = '';
            if ($request->hasFile('ads')) {

                $ads_name = md5(rand(100, 1000)) . '.' . $request->file('ads')->getClientOriginalExtension();
                $request->file('ads')->storeAs('public/images', $ads_name);

                $logo_data = $data['settings_data']['ads'];
                if ($logo_data && isset($logo_data) && File::exists('storage/images/' . $logo_data)) {
                    unlink('storage/images/' . $logo_data);
                }
            }

            $update_data = [
                "app_name"              => $request['app_name'],
                "url"                   => $request['url'],
                "phone"                 => $request['phone'],
                "email"                 => $request['email'],
                "currency_symbol"       => $request['currency_symbol'],
                "currency_word"         => $request['currency_word'],
                "currency_icon"         => $request['currency_icon'],
                "currency_value"        => $request['currency_value'],
                "new_ac_bonus"          => $request['new_ac_bonus'],
                "referrer_bonus"        => $request['referrer_bonus'],
                "joiner_bonus"          => $request['joiner_bonus'],
                "ads_status"            => $request['ads_status'],
                "ads_text"              => $request['ads_text'],
                "ads_link"              => $request['ads_link'],
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
                "wd_start_time"         => $request['wd_start_time'] ?? $data['settings_data']['wd_start_time'],
                "wd_end_time"           => $request['wd_end_time'] ?? $data['settings_data']['wd_end_time'],
                "wd_days"               => $request['wd_days'] ? json_encode($request['wd_days']) : $data['settings_data']['wd_days'],
                "banner_links"          => json_encode($request['banner_links']),
            ];

            if ($images_path) {
                $update_data['baner'] = $images_path;
            }

            if ($logo_name) {
                $update_data['logo'] = $logo_name;
            }

            if ($ads_name) {
                $update_data['ads'] = $ads_name;
            }

            if ($request['message'] != $data['settings_data']['message']) {
                Helper::sendPush([env('CUSTOMER_ID')], $request['message']);
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

    public function test_push()
    {
        $response = Helper::sendPush([env('CUSTOMER_ID')], "testing push notification");
        return $response;
    }

    public function csv_import(Request $request)
    {
        $data['site_data'] = $this->common();
        $data['title'] = 'Import CSV';
        $data['page'] = 'Dashboard';

        if (Request()->isMethod('POST')) {
            $validator = Validator::make($request->all(), [
                'type'          => "required|in:1,2,3",
                'csv_file'      => "required",
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $all_data = Helper::import_csv($request['csv_file']);

            if ($request['type'] == '1') {
                $count = User::count();
                $prefix = Helper::customer_check();
                $prefix = json_decode($prefix, true);
                $prefix = $prefix['data']['prefix'];

                if ($all_data) {
                    foreach ($all_data as $key => $value) {
                        if ($key != 0) {
                            $data = [
                                'user_id'       => $prefix . '0000' . $count,
                                'name'          => $value['name'],
                                'phone'         => $value['email'],
                                'password'      => $value['password'],
                                'wallet'        => $value['wallet'],
                            ];

                            User::create($data);
                            $count++;
                        }
                    }
                }
            } elseif ($request['type'] == '2') {
                if ($all_data) {
                    foreach ($all_data as $key => $value) {
                        $data = [
                            'id'        => $value['id'],
                            'title'     => $value['title']
                        ];

                        GamesList::create($data);
                    }
                }
            } elseif ($request['type'] == '3') {
                if ($all_data) {
                    foreach ($all_data as $key => $value) {

                        GamesList::where('id', $value['game_id'])->update(['patti_win_value' => $value['patti_win_amount'], 'jodi_win_value' => $value['jora_win_amount'], 'single_win_value' => $value['single_win_amount']]);

                        $data = [
                            'id'        => $value['id'],
                            'game_id'   => $value['game_id'],
                            'title'     => $value['title'],
                            'game_days' => $value['days'],
                            'start_time' => $value['start_time'],
                            'stop_time' => $value['end_time']
                        ];

                        GamesTime::create($data);
                    }
                }
            } else {
            }

            return redirect()->back()->with('message', 'Import Successfull.');
        }

        return view('admin.csv_import', $data);
    }
}
