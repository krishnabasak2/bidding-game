<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\Deposit;
use App\Models\Payout;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class WalletController extends Controller
{
    public function transaction(Request $request)
    {
        $mainController = new MainController();
        $data['site_data'] = $mainController->common();
        $data['page'] = 'Dashboard';
        $data['title'] = 'Transaction';

        if (Request()->isMethod('POST')) {
            $validator = Validator::make($request->all(), [
                'phone'         => 'required',
                'type'          => 'required',
                'amount'        => 'required',
            ], [], [
                'phone'         => 'Phone No.',
                'type'          => 'TXN Type',
                'amount'        => 'Amount',
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $user = User::where('phone', $request['phone'])->first();
            if (empty($user)) {
                return redirect()->back()->with('message', 'User Not Found.');
            }

            $update = Helper::wallet($user->id, $request['type'], $request['amount'], '4', null);

            if ($update) {
                return redirect()->back()->with('message', 'Transaction Successful.');
            } else {
                return redirect()->back()->with('message', 'Transaction Failed.');
            }
        }
        return view('admin.wallet.transaction', $data);
    }

    public function history(Request $request)
    {
        $mainController = new MainController();
        $data['site_data'] = $mainController->common();
        $data['page'] = 'Dashboard';
        $data['title'] = 'Wallet History';
        $data['data_list'] = [];

        if (Request()->isMethod('POST')) {
            $validator = Validator::make($request->all(), [
                'phone'         => 'required',
            ], [], [
                'phone'         => 'Phone No.'
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $user = User::where('phone', $request['phone'])->with('getTransaction')->first();
            if (empty($user)) {
                return redirect()->back()->with('message', 'User Not Found.');
            }

            $data['data_list'] = $user->getTransaction;
            $data['phone'] = $request['phone'];
        }

        return view('admin.wallet.history', $data);
    }

    public function deposit($type)
    {
        $mainController = new MainController();
        $data['site_data'] = $mainController->common();
        $data['page'] = 'Dashboard';

        if ($type == 'request') {
            $data['title'] = 'New Deposit Requests';
            $data_list = Deposit::where(['status' => '2'])->with('user')->get();
        } elseif ($type == 'approved') {
            $data['title'] = 'Approved Deposit';
            $data_list = Deposit::where(['status' => '1'])->with('user')->get();
        } elseif ($type == 'rejected') {
            $data['title'] = 'Rejected Deposit';
            $data_list = Deposit::where(['status' => '0'])->with('user')->get();
        } else {
            return redirect()->back();
        }

        $data['data_list'] = $data_list;
        return view('admin.wallet.deposit', $data);
    }

    public function payout($type)
    {
        $mainController = new MainController();
        $data['site_data'] = $mainController->common();
        $data['page'] = 'Dashboard';

        if ($type == 'request') {
            $data['title'] = 'New Payouts Requests';
            $data_list = Payout::where(['status' => '2'])->with('user')->get();
        } elseif ($type == 'approved') {
            $data['title'] = 'Approved Payouts';
            $data_list = Payout::where(['status' => '1'])->with('user')->get();
        } elseif ($type == 'rejected') {
            $data['title'] = 'Rejected Payouts';
            $data_list = Payout::where(['status' => '0'])->with('user')->get();
        } else {
            return redirect()->back();
        }

        $data['data_list'] = $data_list;
        return view('admin.wallet.payout', $data);
    }

    public function payout_status($id, $status)
    {
        $payout = Payout::where('id', $id)->first();

        if (empty($payout)) {
            return response()->json(['status' => false, 'message' => 'Payout details not found.']);
        }

        if ($status == '1') {
            $payout->update(['status' => '1']);
            return response()->json(['status' => true, 'message' => 'Payout has been approved successfully.']);
        } elseif ($status == '0') {
            Helper::wallet($payout->user_id, '1', $payout->amount, '3', 'For payout reject');

            $payout->update(['status' => '0']);
            return response()->json(['status' => true, 'message' => 'Payout has been rejected successfully.']);
        }
    }

    public function deposit_status($id, $status)
    {
        $deposit = Deposit::where('id', $id)->first();

        if (empty($deposit)) {
            return response()->json(['status' => false, 'message' => 'Deposit details not found.']);
        }

        if ($status == '1') {
            $deposit->update(['status' => '1']);
            Helper::wallet($deposit->user_id, '1', $deposit->amount, '2', 'Add money');
            return response()->json(['status' => true, 'message' => 'Deposit has been approved successfully.']);
        } elseif ($status == '0') {
            $deposit->update(['status' => '0']);
            return response()->json(['status' => true, 'message' => 'Deposit has been rejected successfully.']);
        }
    }
}
