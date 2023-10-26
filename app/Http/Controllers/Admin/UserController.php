<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function add(Request $request)
    {
        $mainController = new MainController();
        $data['site_data'] = $mainController->common();
        $data['title'] = 'Add User';
        $data['page'] = 'Dashboard';
        $data['user'] = '';

        if (Request()->isMethod('POST')) {
            $validator = Validator::make($request->all(), [
                'name'          => 'required',
                'phone'         => 'required|unique:users',
                'email'         => 'required|unique:users',
                'password'      => 'required|min:6',
                'referer_uid'   => 'nullable',
            ], [], [
                'name'          => 'Name',
                'phone'         => 'Phone No.',
                'email'         => 'Email ID',
                'password'      => 'Password'
            ]);
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $count = User::count();
            $request['password'] = md5(md5(md5($request['password'])));
            $request['user_id'] = Helper::user_id($count, 4);

            if (User::create($request->except('_token'))) {
                return redirect()->back()->with('message', "User Has Been Added Successully.");
            } else {
                return redirect()->back()->with('message', 'Something Went Wrong.');
            }
        }
        return view('admin.customer-update', $data);
    }

    public function edit($id, Request $request)
    {
        $mainController = new MainController();
        $data['site_data'] = $mainController->common();
        $data['title'] = 'Edit User';
        $data['page'] = 'Dashboard';
        $user_data = User::where(['id' => $id, 'role' => '1'])->first();

        if (empty($user_data)) {
            return redirect()->back()->with('message', 'User not found.');
        }

        if (Request()->isMethod('POST')) {
            $validator = Validator::make($request->all(), [
                'name'          => 'required',
                'phone'         => "required|unique:users,phone,{$user_data->id}",
                'email'         => "required|unique:users,email,{$user_data->id}",
                'referer_uid'   => 'nullable',
            ]);
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            if ($user_data->referer_uid) {
                $request['referer_uid'] = $user_data->referer_uid;
            }

            if ($user_data->update($request->except('_token'))) {
                return redirect()->back()->with('message', "User Has Been Updated Successully.");
            } else {
                return redirect()->back()->with('message', 'Something Went Wrong.');
            }
        }

        $data['user'] = $user_data;
        return view('admin.customer-update', $data);
    }

    public function settings($id, Request $request)
    {
        $mainController = new MainController();
        $data['site_data'] = $mainController->common();
        $data['title'] = 'User Settings';
        $data['page'] = 'Dashboard';
        $user_data = User::where(['id' => $id, 'role' => '1'])->first();

        if (empty($user_data)) {
            return redirect()->back()->with('message', 'User not found.');
        }

        if (Request()->isMethod('POST')) {
            $validator = Validator::make($request->all(), [
                'setting'               => 'required|in:on,off',
                'max_single_bid_num'    => "required_if:setting,==,on",
                'max_bid_amo'           => "required_if:setting,==,on"
            ], [], [
                'max_single_bid_num'    => "Maximum Single Bidding Number",
                'max_bid_amo'           => "Maximum Bidding Amount"
            ]);
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $game_setting = [
                'setting' => $request['setting'],
                'max_single_bid_num' => $request['max_single_bid_num'],
                'max_bid_amo' => $request['max_bid_amo']
            ];

            if ($user_data->update(['game_settings' => json_encode($game_setting)])) {
                return redirect()->back()->with('message', "Settings Has Been Updated Successully.");
            } else {
                return redirect()->back()->with('message', 'Something Went Wrong.');
            }
        }

        $data['settings'] = json_decode($user_data->game_settings);
        return view('admin.user_settings', $data);
    }

    public function list($type)
    {
        $mainController = new MainController();
        $data['site_data'] = $mainController->common();
        $data['page'] = 'Dashboard';

        if ($type == 'all') {
            $data['title'] = 'All Users';
            $data['data_list'] = User::where('role', '1')->get();
        } elseif ($type == 'active') {
            $data['title'] = 'Active Users';
            $data['data_list'] = User::where('role', '1')->where('status', '1')->get();
        } elseif ($type == 'suspend') {
            $data['title'] = 'Suspened Users';
            $data['data_list'] = User::where('role', '1')->where('status', '0')->get();
        } elseif ($type == 'trash') {
            $data['title'] = 'Removed Users';
            $data['data_list'] = User::where('role', '1')->onlyTrashed()->get();
        }

        return view('admin.customers-list', $data);
    }

    public function status($id, $type)
    {
        $user = User::where('id', $id)->withTrashed()->first();
        if (empty($user)) {
            return response()->json(['status' => false, 'message' => 'User Not Found.']);
        }

        if ($type == '0') {
            $user->update(['status' => '0']);
            $message = "User Has Been Suspened Successfully.";
            $reload = false;
        } elseif ($type == '1') {
            $user->update(['status' => '1']);
            $message = "User Has Been Activated Successfully.";
            $reload = false;
        } elseif ($type == '2') {
            $user->delete();
            $message = "User Has Been Moved To Trash Successfully.";
            $reload = true;
        } elseif ($type == '3') {
            $user->restore();
            $message = "User Has Been Restored Successfully.";
            $reload = true;
        } elseif ($type == '4') {
            $user->forceDelete();
            $message = "User Has Been Deleted Successfully.";
            $reload = true;
        } elseif ($type == '5') {
            $password = rand(100000, 999999);
            $user->update(['password' => md5(md5(md5($password)))]);
            $message = "New Password Has Been Generated Successfully. New Password is: {$password}";
            $reload = false;
        } else {
            $message = "Something Went Wrong.";
        }

        return response()->json(['status' => true, 'message' => $message, 'reload' => $reload]);
    }

    // public function getUser($phone)
    // {
    //     $user = User::where('phone', 'LIKE', "%$phone%")->withTrashed()->first();
    //     return response()->json(['status' => true, 'data' => $user]);
    // }
}
