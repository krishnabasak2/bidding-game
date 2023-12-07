<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\BidsHistory;
use App\Models\GamesList;
use App\Models\GamesResult;
use App\Models\SiteSetting;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;


class Bid extends Controller
{
    public function add(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'result_id'               => 'required|numeric',
                'type'                    => 'required|in:1,2,3',
                'bids.*.number'           => 'integer|min:0',
                'bids.*.amount'           => 'integer|min:1',
            ], [
                'bids.*.number'           => "Please enter valid number.",
                'bids.*.amount'           => "Please enter valid amount.",
            ]);
            if ($validator->fails()) {
                return response()->json(['status' => false, 'message' => $validator->errors()->first(), 'data' => null], 400);
            }

            if ($request['type'] == '3') {
                $validator = Validator::make($request->all(), [
                    'bids.*.number'           => 'integer|min:0|digits:3',
                ], [
                    'bids.*.number'           => "Please enter valid number.",
                ]);
            } elseif ($request['type'] == '2') {
                $validator = Validator::make($request->all(), [
                    'bids.*.number'           => 'integer|min:0|digits:2',
                ], [
                    'bids.*.number'           => "Please enter valid number.",
                ]);
            } elseif ($request['type'] == '1') {
                $validator = Validator::make($request->all(), [
                    'bids.*.number'           => 'integer|min:0|digits:1',
                ], [
                    'bids.*.number'           => "Please enter valid number.",
                ]);
            }

            if ($validator->fails()) {
                return response()->json(['status' => false, 'message' => $validator->errors()->first(), 'data' => null], 400);
            }

            $game_result = GamesResult::where('id', $request['result_id'])->with('getTime')->first();

            // dd($game_result['getTime']['stop_time']);

            if (empty($game_result)) {
                return response()->json(['status' => false, 'message' => 'Game not found.', 'data' => null], 400);
            }

            $current_time = date('H:s');
            if ($game_result['getTime']['stop_time'] < $current_time) {
                return response()->json(['status' => false, 'message' => 'Bidding time is over..', 'data' => null], 400);
            }

            $settings = SiteSetting::first();
            $user_bids = BidsHistory::where(['user_id' => Auth::id()])->whereDate('created_at', Carbon::today())->get();
            $user_data = User::where('id', Auth::id())->first();

            $user_settings = json_decode($user_data->game_settings, true);

            if ($user_settings['setting'] == 'on') {
                $max_amount = $user_data['max_bid_amo'];
                $max_single_num = $user_data['max_single_bid_num'];
            } else {
                $max_amount = $settings->max_bet_amount;
                $max_single_num = $settings->max_single_bet;
            }

            // maximum bidding amount check
            $total_req_bid_amo = 0;
            $user_total_bids_amo = $user_bids->sum('bid_amount');

            foreach ($request['bids'] as $value) {
                $total_req_bid_amo = $total_req_bid_amo + $value['amount'];
            }

            if ($total_req_bid_amo > $user_data['wallet']) {
                return response()->json(['status' => false, 'message' => 'Insufficient wallet balance.', 'data' => null], 400);
            }

            if ($max_amount < ($user_total_bids_amo + $total_req_bid_amo)) {
                return response()->json(['status' => false, 'message' => "Maximum bidding amount is $settings->max_bet_amount", 'data' => null], 400);
            }

            // how many single number can bids checks
            if ($request['type'] == '1') {
                $total_single_bids = $user_bids->where('game_type', '1');
                $total_single_num = $user_bids->where('game_type', '1')->count();

                foreach ($total_single_bids as $value) {

                    foreach ($request['bids'] as $bid) {
                        if ($value['bid_number'] == $bid['number']) {
                            $common = true;
                            break;
                        } else {
                            $common = false;
                        }
                    }

                    if (!$common) {
                        $total_single_num = $total_single_num + 1;
                    }
                }

                if ($max_single_num < $total_single_num) {
                    return response()->json(['status' => false, 'message' => "Maximum bidding number is $max_single_num", 'data' => null], 400);
                }
            }

            // submitting bids
            foreach ($request['bids'] as $bid) {

                $bid_data = BidsHistory::where(['user_id' => Auth::id(), 'game_type' => $request['type'], 'result_id' => $request['result_id'], 'bid_number' => $bid['number']])->first();

                if (!empty($bid_data)) {
                    BidsHistory::find($bid_data->id)->update(['bid_amount' => round($bid_data->bid_amount + $bid['amount'], 2)]);
                } else {
                    BidsHistory::insert(['user_id' => Auth::id(), 'game_id' => $game_result->game_id, 'time_id' => $game_result->time_id, 'game_type' => $request['type'], 'bid_number' => $bid['number'], 'bid_amount' => $bid['amount'], 'result_id' => $request['result_id'], 'status' => '2']);
                }

                Helper::wallet(Auth::id(), '0', $bid['amount'], '0', 'For bidding');
            }

            return response()->json(['status' => true, 'message' => 'Bids are submitted successfully.', 'data' => null], 200);
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'message' => 'Internal server errors.', 'data' => null], 500);
        }
    }


    public function history($game_id, $result_id = null)
    {
        try {
            $game_title = GamesList::select('title')->where('id', $game_id)->first();
            $data = BidsHistory::where(['user_id' => Auth::id(), 'game_id' => $game_id])->orderBy('id', 'DESC')->with('time')->paginate(200);
            return response()->json(['status' => true, 'message' => "Transaction list", 'data' => $data, 'game_title' => $game_title], 200);
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'message' => 'Internal server errors.', 'data' => null], 500);
        }
    }

    public function current_bid_history($result_id, $type)
    {
        try {
            $data = BidsHistory::where(['user_id' => Auth::id(), 'result_id' => $result_id, 'game_type' => $type])->orderBy('id', 'DESC')->get();
            return response()->json(['status' => true, 'message' => "Transaction list", 'data' => $data], 200);
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'message' => 'Internal server errors.', 'data' => null], 500);
        }
    }
}
