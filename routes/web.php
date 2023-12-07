<?php

use App\Http\Controllers\Admin\GameController;
use App\Http\Controllers\Admin\MainController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\WalletController;
use App\Http\Controllers\Utility;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [Utility::class, 'web']);


Route::match(['get', 'post'], 'login/{token?}/{login_id?}', [MainController::class, 'login'])->middleware('LoginCheck');

Route::prefix('admin')->middleware('AdminCheck')->group(function () {
    Route::get('/', [MainController::class, 'dashboard']);
    Route::match(['get', 'post'], 'settings', [MainController::class, 'settings']);
    Route::match(['get', 'post'], 'update-profile', [MainController::class, 'update_profile']);
    Route::match(['get', 'post'], 'change-password', [MainController::class, 'change_password']);

    Route::prefix('user')->group(function () {
        Route::match(['get', 'post'], 'add', [UserController::class, 'add']);
        Route::match(['get', 'post'], 'edit/{id}', [UserController::class, 'edit']);
        Route::match(['get', 'post'], 'settings/{id}', [UserController::class, 'settings']);
        Route::get('list/{type}', [UserController::class, 'list']);
        Route::get('status/{id}/{type}', [UserController::class, 'status']);
        // Route::get('get-user/{phone}', [UserController::class, 'getUser']);
        Route::get('search/{key}', [UserController::class, 'search_user']);
    });

    Route::prefix('game')->group(function () {
        Route::match(['get', 'post'], 'create', [GameController::class, 'create']);
        Route::match(['get', 'post'], 'edit/{id}', [GameController::class, 'edit']);
        Route::get('list/{type}', [GameController::class, 'list']);
        Route::get('status/{id}/{status}', [GameController::class, 'status']);

        Route::get('history/{game_id?}/', [GameController::class, 'history']);
        Route::get('history/bid/{result_id?}/', [GameController::class, 'bid_history']);
        Route::get('history/bid-delete/{bid_id}/', [GameController::class, 'bid_delete']);

        Route::prefix('time')->group(function () {
            Route::match(['get', 'post'], '/{game_id}/{time_id?}', [GameController::class, 'time_list']);
            Route::get('status/{id}/{status}', [GameController::class, 'time_status']);
        });

        Route::get('active/{id?}', [GameController::class, 'active_games']);
        Route::match(['get', 'post'], 'result/{time_id}', [GameController::class, 'result']);
        Route::get('distribute/{result_id}', [GameController::class, 'distribute']);
    });

    Route::prefix('wallet')->group(function () {
        Route::match(['get', 'post'], 'transaction', [WalletController::class, 'transaction']);
        Route::match(['get', 'post'], 'history', [WalletController::class, 'history']);

        Route::get('payout/{type}', [WalletController::class, 'payout']);
        Route::get('deposit/{type}', [WalletController::class, 'deposit']);

        Route::get('payout-status/{id}/{status}', [WalletController::class, 'payout_status']);
        Route::get('deposit-status/{id}/{status}', [WalletController::class, 'deposit_status']);
    });

    Route::match(['get', 'post'], 'import', [MainController::class, 'csv_import']);
});

Route::get('logout', [MainController::class, 'logout']);
Route::get('test-push', [MainController::class, 'test_push']);


Route::get('reboot', function () {
    Artisan::call('view:clear');
    Artisan::call('route:clear');
    Artisan::call('cache:clear');
    Artisan::call('config:clear');
});
Route::get('install', function () {
    // Artisan::call('storage:link');
    // Artisan::call("migrate:fresh --seed");
});
