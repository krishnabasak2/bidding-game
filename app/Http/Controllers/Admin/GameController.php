<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\BidsHistory;
use App\Models\GamesList;
use App\Models\GamesResult;
use App\Models\GamesTime;
use App\Traits\AutoActive;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;


class GameController extends Controller
{
    use AutoActive;

    public function create(Request $request)
    {
        $mainController = new MainController();
        $data['site_data'] = $mainController->common();
        $data['title'] = 'Create Game';
        $data['page'] = 'Dashboard';
        $data['game_data'] = '';

        if (Request()->isMethod('POST')) {

            $validator = Validator::make($request->all(), [
                'title'             => 'required',
                'single_win_value'  => 'required|numeric',
                'patti_win_value'   => 'required|numeric',
                'jodi_win_value'    => 'required|numeric',
                'cp_win_value'      => 'required|numeric',
            ], [], [
                'title'             => 'Game Title',
                'single_win_value'  => 'Single Win value',
                'patti_win_value'   => 'Patti Win value',
                'jodi_win_value'    => 'Jodi Win value',
                'cp_win_value'      => 'CP Win value',
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            if (GamesList::create($request->except('_token'))) {
                return redirect()->back()->with('message', "Game Has Been Created Successully.");
            } else {
                return redirect()->back()->with('message', 'Something Went Wrong.');
            }
        }
        return view('admin.game.create-game', $data);
    }

    public function edit($id, Request $request)
    {
        $mainController = new MainController();
        $data['site_data'] = $mainController->common();
        $data['title'] = 'Edit Game';
        $data['page'] = 'Dashboard';
        $game_data = GamesList::where('id', $id)->withTrashed()->first();

        $data['game_data'] = $game_data;

        if (Request()->isMethod('POST')) {

            $validator = Validator::make($request->all(), [
                'title'             => 'required',
                'single_win_value'  => 'required|numeric',
                'patti_win_value'   => 'required|numeric',
                'jodi_win_value'    => 'required|numeric',
                'cp_win_value'      => 'required|numeric',
            ], [], [
                'title'             => 'Game Title',
                'single_win_value'  => 'Single Win value',
                'patti_win_value'   => 'Patti Win value',
                'jodi_win_value'    => 'Jodi Win value',
                'cp_win_value'      => 'CP Win value',
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            if ($game_data->update($request->except('_token'))) {
                return redirect()->back()->with('message', "Game Has Been Updated Successully.");
            } else {
                return redirect()->back()->with('message', 'Something Went Wrong.');
            }
        }
        return view('admin.game.create-game', $data);
    }

    public function list($type)
    {
        $mainController = new MainController();
        $data['site_data'] = $mainController->common();
        $data['page'] = 'Dashboard';

        if ($type == 'all') {
            $data['title'] = 'All Games';
            $game_list = GamesList::get();
        } elseif ($type == 'trash') {
            $data['title'] = 'Removed Games';
            $game_list = GamesList::onlyTrashed()->get();
        } elseif ($type == 'active') {
            $data['title'] = 'Active Games';
            $game_list = GamesList::where('status', '1')->get();
        } elseif ($type == 'inactive') {
            $data['title'] = 'Inactived Games';
            $game_list = GamesList::where('status', '0')->get();
        } else {
            return redirect()->back()->with('message', 'Something Went Wrong.');
        }
        $data['data_list'] = $game_list;
        return view('admin.game.game-list', $data);
    }

    public function status($id, $status)
    {
        $game = GamesList::where('id', $id)->withTrashed()->first();
        if (empty($game)) {
            return response()->json(['status' => false, 'message' => 'Something Went Wrong.']);
        }

        if ($status == '1') {
            $game->update(['status' => '1']);
            return response()->json(['status' => true, 'message' => 'Game Has Been Activated Successfully.', 'reload' => false]);
        } elseif ($status == '0') {
            $game->update(['status' => '0']);
            return response()->json(['status' => true, 'message' => 'Game Has Been Inactivated Successfully.', 'reload' => false]);
        } elseif ($status == '2') {
            $game->delete();
            return response()->json(['status' => true, 'message' => 'Game Has Been Moved To Trash Successfully.', 'reload' => true]);
        } elseif ($status == '3') {
            $game->restore();
            return response()->json(['status' => true, 'message' => 'Game Has Been Restored Successfully.', 'reload' => true]);
        } elseif ($status == '4') {
            $game->forceDelete();
            return response()->json(['status' => true, 'message' => 'Game Has Been Deleted Successfully.', 'reload' => true]);
        } else {
            return response()->json(['status' => false, 'message' => 'Something Went Wrong.']);
        }
    }

    public function time_list($game_id, Request $request, $time_id = null)
    {
        $game_data = GamesList::where('id', $game_id)->withTrashed()->first();
        if (empty($game_data)) {
            return redirect()->back()->with('message', 'Game Not Found.');
        }

        if ($time_id) {
            $time_data = GamesTime::where(['game_id' => $game_data->id, 'id' => $time_id])->withTrashed()->first();
            if (empty($time_data)) {
                return redirect()->back()->with('message', 'Sub-Game Not Found.');
            } else {
                $data['time_data'] = $time_data;
                $data['title'] = "{$game_data->title}: Update Sub-Games ($time_data->title)";
            }
        } else {
            $data['time_data'] = '';
            $data['title'] = "{$game_data->title}: Create New Sub-Games";
        }

        $mainController = new MainController();
        $data['site_data'] = $mainController->common();
        $data['page'] = 'Dashboard';
        $game_time = GamesTime::where('game_id', $game_data->id)->withTrashed()->get();
        $data['data_list'] = $game_time;

        if (Request()->isMethod('POST')) {

            $validator = Validator::make($request->all(), [
                'title'         => 'required',
                'game_days'     => 'required|array|min:1',
                'game_days.*'   => 'required|in:0,1,2,3,4,5,6',
                'start_time'    => 'required',
                'stop_time'     => 'required',
            ], [], [
                'title'         => 'Title',
                'game_days'     => 'Game Days',
                'game_days.*'   => 'Game Days',
                'start_time'    => 'Start Time',
                'stop_time'     => 'Stop Time',
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            if ($time_id && !empty($time_data)) {
                $gameTime = [
                    'title'         => $request['title'],
                    'game_days'     => json_encode($request['game_days']),
                    'start_time'    => $request['start_time'],
                    'stop_time'     => $request['stop_time'],
                ];

                if ($time_data->update($gameTime)) {
                    return redirect("admin/game/time/$game_data->id")->with('message', "Sub-Game Has Been Updated Successully.");
                } else {
                    return redirect()->back()->with('message', 'Something Went Wrong.');
                }
            } else {
                $gameTime = [
                    'game_id'       => $game_data->id,
                    'title'         => $request['title'],
                    'game_days'     => json_encode($request['game_days']),
                    'start_time'    => $request['start_time'],
                    'stop_time'     => $request['stop_time'],
                ];

                if (GamesTime::create($gameTime)) {
                    return redirect()->back()->with('message', "Sub-Game Has Been Created Successully.");
                } else {
                    return redirect()->back()->with('message', 'Something Went Wrong.');
                }
            }
        }
        return view('admin.game.game-time', $data);
    }

    public function time_status($id, $status)
    {
        $time_data = GamesTime::where(['id' => $id])->withTrashed()->first();
        if (empty($time_data)) {
            return redirect()->back()->with('message', 'Sub-Game Not Found.');
        }

        if ($status == '1') {
            $time_data->update(['status' => '1']);
            return response()->json(['status' => true, 'message' => 'Sub-Game Has Been Activated Successfully.', 'reload' => false]);
        } elseif ($status == '0') {
            $time_data->update(['status' => '0']);
            return response()->json(['status' => true, 'message' => 'Sub-Game Has Been Inactivated Successfully.', 'reload' => false]);
        } elseif ($status == '2') {
            $time_data->delete();
            return response()->json(['status' => true, 'message' => 'Sub-Game Has Been Moved To Trash Successfully.', 'reload' => true]);
        } elseif ($status == '3') {
            $time_data->restore();
            return response()->json(['status' => true, 'message' => 'Sub-Game Has Been Restored Successfully.', 'reload' => true]);
        } elseif ($status == '4') {
            $time_data->forceDelete();
            return response()->json(['status' => true, 'message' => 'Sub-Game Has Been Deleted Successfully.', 'reload' => true]);
        } else {
            return response()->json(['status' => false, 'message' => 'Something Went Wrong.']);
        }
    }

    public function history($game_id = null)
    {
        $mainController = new MainController();
        $data['site_data'] = $mainController->common();
        $data['page'] = 'Dashboard';
        $data['title'] = 'Games Histories';

        $game_data = GamesList::where(['status' => '1'])->get();
        $data['data_list'] = $game_data;

        if ($game_id) {
            $game = GamesList::where(['status' => '1', 'id' => $game_id])->first();
            if (!empty($game)) {
                $game_data = GamesResult::where(['game_id' => $game->id])->with('getTime', 'bid')->orderBy('id', 'DESC')->get();
                // dd($game_data);
                $data['title'] = "{$game->title}: Sub-Game Histories";
                $data['data_list'] = $game_data;
                return view('admin.game.game-histories-list', $data);
            }
        }

        return view('admin.game.game-histories', $data);
    }

    public function bid_history($result_id)
    {
        $mainController = new MainController();
        $data['site_data'] = $mainController->common();
        $data['page'] = 'Dashboard';
        $data['title'] = 'Bid List';

        $data_list = BidsHistory::where(['result_id' => $result_id])->with('getUser', 'time', 'result')->orderBy('user_id', 'asc')->get();

        // dd($data_list->toArray());

        $data['data_list'] = $data_list;
        return view('admin.game.bid-list', $data);
    }


    public function bid_delete($id)
    {
        $bid = BidsHistory::where('id', $id)->with('getUser')->first();
        if (empty($bid)) {
            return response()->json(['status' => false, 'message' => 'Bid not found.']);
        }

        if (Helper::wallet($bid->getUser->id, '1', $bid->bid_amount, '4', 'For bid cancel.') && $bid->forceDelete()) {
            return response()->json(['status' => true, 'message' => 'has been deleted successfull.']);
        } else {
            return response()->json(['status' => false, 'message' => 'Something went wrong.']);
        }
    }


    public function active_games($id = null)
    {
        $mainController = new MainController();
        $data['site_data'] = $mainController->common();
        $data['page'] = 'Dashboard';
        $data['title'] = 'Running Games';

        if ($id) {
            $game = GamesList::where('id', $id)->first();

            if (!empty($game)) {
                $this->game_on($id);

                $current_time = date('H:s');

                $game_time = GamesTime::where(['status' => '1', 'game_id' => $game->id])->min('start_time');

                if ($game_time <= $current_time) {
                    $game_data = GamesResult::where(['game_id' => $id])->whereDate('created_at', 'like', Carbon::today())->with('getTime', 'getGame')->get();
                } else {
                    $date = new DateTime(date('Y-m-d'));
                    $date = $date->modify('-1 day');

                    $game_data = GamesResult::where(['game_id' => $id])->where('created_at', '>', $date->format('Y-m-d'))->with('getTime', 'getGame')->get();
                }

                $data['title'] = "{$game->title}: Running Sub-Games";
                $data['data_list'] = $game_data;
                return view('admin.game.active-game-list', $data);
            }
        }

        // BidsHistory::insert(['user_id' => '2', 'game_id' => '1', 'time_id' => '1', 'game_type' => '1', 'bid_number' => '1', 'bid_amount' => '10', 'result_id' => '135', 'status' => '2']);

        $game_data = GamesList::where(['status' => '1'])->get();
        $data['data_list'] = $game_data;
        return view('admin.game.active-game', $data);
    }


    public function result($id, Request $request)
    {
        $mainController = new MainController();
        $data['site_data'] = $mainController->common();
        $data['page'] = 'Dashboard';
        $data['title'] = 'Game Result';

        $game_result = GamesResult::where('id', $id)->with('getGame', 'getTime')->first();
        if (empty($game_result)) {
            return redirect()->back()->with('message', 'Game Details Not Found.');
        }

        $jodi_win_value = null;

        $previous_time = GamesTime::where(['game_id' => $game_result->game_id, 'status' => '1'])->where('start_time', '<', $game_result->getTime->start_time)->orderBy('start_time', 'DESC')->first();

        if ($previous_time) {

            $previous_result = GamesResult::where(['game_id' => $game_result->game_id, 'time_id' => $previous_time->id])->with('getTime')->orderBy('created_at', 'DESC')->first();

            if ($previous_result && isset($previous_result->single_win_value) && isset($previous_result->patti_win_value)) {
                $jodi_win_value = $previous_result->single_win_value;
            } else {
                return redirect()->back()->with('message', "Please Update The Previous Game Result First. ({$previous_result->getTime->title})");
            }
        }

        if (Request()->isMethod('POST')) {
            $validator = Validator::make($request->all(), [
                'single_win_value'    => 'required|numeric|digits:1',
                'patti_win_value'     => 'required|numeric|digits:3',
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            if (isset($jodi_win_value)) {
                $jodi_win_value = $jodi_win_value . $request['single_win_value'];
            }

            if ($game_result->update(['single_win_value' => $request['single_win_value'], 'patti_win_value' => $request['patti_win_value'], 'jodi_win_value' => $jodi_win_value, 'status' => '0'])) {



                $single_num = (isset($request['single_win_value'])) ? $request['single_win_value'] : 'N/A';
                $patti_num = (isset($request['patti_win_value'])) ? $request['patti_win_value'] : 'N/A';
                $jora_num = (isset($jodi_win_value)) ? $jodi_win_value : 'N/A';

                $game_name = $game_result->getGame[0]['title'];
                $game_time = $game_result->getTime['title'];

                $notification = "Result: $game_name - $game_time - (SINGLE = $single_num) - (PATTI = $patti_num) - (JODI = $jora_num)";

                Helper::sendPush([env('CUSTOMER_ID')], $notification);

                return redirect("/admin/game/active/{$game_result->game_id}")->with('message', 'Result Has Been Updated Successfully.');
            } else {
                return redirect()->back()->with('message', 'Something Went Wrong.');
            }
        }

        $data['single_percent'] = $game_result->getGame[0]->single_win_value;
        $data['patti_percent'] = $game_result->getGame[0]->patti_win_value;
        $data['jodi_percent'] = $game_result->getGame[0]->jodi_win_value;

        $data['single_total'] = BidsHistory::where(['result_id' => $id, 'game_type' => '1'])->sum('bid_amount');
        $data['patti_total'] = BidsHistory::where(['result_id' => $id, 'game_type' => '3'])->sum('bid_amount');
        $data['jodi_total'] = BidsHistory::where(['result_id' => $id, 'game_type' => '2'])->sum('bid_amount');

        $data['single_bids'] = BidsHistory::select(DB::raw('bid_number, COUNT(bid_number) as totalBid, SUM(bid_amount) as totalAmount'))
            ->where(['result_id' => $id, 'game_type' => '1'])
            ->groupBy('bid_number')->get();

        $data['patti_bids'] = BidsHistory::select(DB::raw('bid_number, COUNT(bid_number) as totalBid, SUM(bid_amount) as totalAmount'))
            ->where(['result_id' => $id, 'game_type' => '3'])
            ->groupBy('bid_number')->get();

        $data['jodi_bids'] = BidsHistory::select(DB::raw('bid_number, COUNT(bid_number) as totalBid, SUM(bid_amount) as totalAmount'))
            ->where(['result_id' => $id, 'game_type' => '2'])
            ->groupBy('bid_number')->get();

        $data['game_result'] = $game_result;
        return view('admin.game.result', $data);
    }


    public function distribute($id)
    {
        $result_data = GamesResult::where(['id' => $id, 'status' => '0'])->with('getGame', 'getTime')->first();
        if (empty($result_data)) {
            return response()->json(['status' => false, 'message' => 'Game details not found.'], 400);
        }

        $previous_time = GamesTime::where(['game_id' => $result_data->game_id, 'status' => '1'])->where('start_time', '<', $result_data->getTime->start_time)->orderBy('start_time', 'DESC')->first();

        // -------------------- Reverse Process --------------------
        $already_win = BidsHistory::where(['game_id' => $result_data->game_id, 'time_id' => $result_data->time_id, 'result_id' => $result_data->id, 'status' => '1'])->get();

        if ($already_win->isNotEmpty()) {

            foreach ($already_win as $value) {
                if ($value->game_type == '1' && $value->bid_number != $result_data->single_win_value) {

                    Helper::wallet($value->user_id, '0', $value->won_amount, '4', null);
                    BidsHistory::where('id', $value->id)->update(['status' => '2', 'won_amount' => null]);
                } elseif ($value->game_type == '3' && $value->bid_number != $result_data->patti_win_value) {

                    Helper::wallet($value->user_id, '0', $value->won_amount, '4', null);
                    BidsHistory::where('id', $value->id)->update(['status' => '2', 'won_amount' => null]);
                } elseif ($value->game_type == '2' && $value->bid_number != $result_data->jodi_win_value) {

                    Helper::wallet($value->user_id, '0', $value->won_amount, '4', null);
                    BidsHistory::where('id', $value->id)->update(['status' => '2', 'won_amount' => null]);
                }
            }

            BidsHistory::where(['game_id' => $result_data->game_id, 'time_id' => $result_data->time_id, 'result_id' => $result_data->id, 'status' => '0'])->update(['status' => '2', 'won_amount' => null]);
        }

        if ($previous_time) {
            $previous_result = GamesResult::where(['game_id' => $result_data->game_id, 'time_id' => $previous_time->id])->with('getTime')->orderBy('created_at', 'DESC')->first();

            $already_win_jodi = BidsHistory::where(['game_id' => $result_data->game_id, 'time_id' => $previous_result->time_id, 'result_id' => $previous_result->id, 'status' => '1', 'game_type' => '2'])->get();

            if ($already_win_jodi->isNotEmpty()) {
                foreach ($already_win_jodi as $value) {
                    Helper::wallet($value->user_id, '0', $value->won_amount, '4', null);
                    BidsHistory::where('id', $value->id)->update(['status' => '2', 'won_amount' => null]);
                }
            }

            BidsHistory::where(['game_id' => $result_data->game_id, 'time_id' => $previous_result->time_id, 'result_id' => $previous_result->id, 'status' => '0', 'game_type' => '2'])->update(['status' => '2', 'won_amount' => null]);
        }

        // -------------------- DISTRIBUTION PROCESS --------------------
        $single_percent = $result_data->getGame[0]->single_win_value;
        $patti_percent = $result_data->getGame[0]->patti_win_value;
        $jodi_percent = $result_data->getGame[0]->jodi_win_value;

        // -------------------- Single Process --------------------
        if ($result_data->single_win_value) {
            $single = BidsHistory::where(['game_id' => $result_data->game_id, 'time_id' => $result_data->time_id, 'result_id' => $result_data->id, 'status' => '2'])->where(['bid_number' => $result_data->single_win_value, 'game_type' => '1'])->get();

            if ($single->isNotEmpty()) {
                foreach ($single as $value) {
                    $win_amount = round($value->bid_amount * $single_percent, 2);
                    Helper::wallet($value->user_id, '1', $win_amount, '1', 'For wining');

                    BidsHistory::where('id', $value->id)->update(['won_amount' => $win_amount, 'status' => '1']);
                }
            }

            BidsHistory::where(['game_id' => $result_data->game_id, 'time_id' => $result_data->time_id, 'result_id' => $result_data->id, 'game_type' => '1', 'status' => '2'])->update(['status' => '0']);
        }

        // -------------------- Patti Process --------------------
        if ($result_data->patti_win_value) {
            $patti = BidsHistory::where(['game_id' => $result_data->game_id, 'time_id' => $result_data->time_id, 'result_id' => $result_data->id, 'status' => '2'])->where(['bid_number' => $result_data->patti_win_value, 'game_type' => '3'])->get();

            if ($patti->isNotEmpty()) {
                foreach ($patti as $value) {
                    $win_amount = round($value->bid_amount * $patti_percent, 2);
                    Helper::wallet($value->user_id, '1', $win_amount, '1', 'For wining');

                    BidsHistory::where('id', $value->id)->update(['won_amount' => $win_amount, 'status' => '1']);
                }
            }

            BidsHistory::where(['game_id' => $result_data->game_id, 'time_id' => $result_data->time_id, 'result_id' => $result_data->id, 'game_type' => '3', 'status' => '2'])->update(['status' => '0']);
        }

        // -------------------- Jodi Process --------------------
        if ($result_data->jodi_win_value) {

            if (!empty($previous_time)) {
                $previous_result = GamesResult::where(['game_id' => $result_data->game_id, 'time_id' => $previous_time->id])->with('getTime')->orderBy('created_at', 'DESC')->first();

                $jodi = BidsHistory::where(['game_id' => $result_data->game_id, 'time_id' => $previous_result->time_id, 'result_id' => $previous_result->id, 'status' => '2'])->where(['bid_number' => $result_data->jodi_win_value, 'game_type' => '2'])->get();

                if ($jodi->isNotEmpty()) {
                    foreach ($jodi as $value) {
                        $win_amount = round($value->bid_amount * $jodi_percent, 2);
                        Helper::wallet($value->user_id, '1', $win_amount, '1', 'For wining');

                        BidsHistory::where('id', $value->id)->update(['won_amount' => $win_amount, 'status' => '1']);
                    }
                }
            }
            BidsHistory::where(['game_id' => $result_data->game_id, 'time_id' => $previous_result->time_id, 'result_id' => $previous_result->id, 'game_type' => '2', 'status' => '2'])->update(['status' => '0']);
        }

        $result_data->update(['status' => '1']);

        return response()->json(['status' => true, 'message' => 'Distribution Successful.'], 200);
    }
}
