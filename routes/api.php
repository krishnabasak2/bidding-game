<?php

use App\Http\Controllers\Api\AppMain;
use App\Http\Controllers\Api\Bid;
use App\Http\Controllers\Api\UserMain;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return auth()->user();
});

Route::post('register', [UserMain::class, 'register']);
Route::post('login', [UserMain::class, 'login']);

Route::get('info', [AppMain::class, 'info']);

Route::prefix('game')->group(function () {
    Route::get('list', [AppMain::class, 'game_list']);
    Route::get('result/{id}', [AppMain::class, 'game_result']);
    Route::get('time/{game_id}', [AppMain::class, 'game_time']);
});

Route::middleware('auth:sanctum')->group(function () {

    Route::get('logout', [UserMain::class, 'logout']);

    Route::prefix('user')->group(function () {
        Route::get('get', [UserMain::class, 'get_user']);
        Route::post('update', [UserMain::class, 'update']);
        Route::post('change-password', [UserMain::class, 'change_password']);
    });

    Route::prefix('wallet')->group(function () {
        Route::get('get-payout-settings', [UserMain::class, 'get_payout_setting']);
        Route::post('payout-settings', [UserMain::class, 'payout_settings']);
        Route::post('deposit-request', [UserMain::class, 'deposit_request']);
        Route::post('payout-request', [UserMain::class, 'payout_request']);
        Route::get('deposit-list', [UserMain::class, 'deposit_list']);
        Route::get('payout-list', [UserMain::class, 'payout_list']);
        Route::get('transaction', [UserMain::class, 'transaction']);
    });

    Route::prefix('bidding')->group(function () {
        Route::post('add', [Bid::class, 'add']);
        Route::get('history/{game_id}', [Bid::class, 'history']);
        Route::get('current-bid-history/{result_id}/{type}', [Bid::class, 'current_bid_history']);
    });
});
