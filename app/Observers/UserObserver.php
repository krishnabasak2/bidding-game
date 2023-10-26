<?php

namespace App\Observers;

use App\Models\BidsHistory;
use App\Models\PayoutSetting;
use App\Models\Transaction;
use App\Models\User;

class UserObserver
{
    /**
     * Handle the User "created" event.
     */
    public function created(User $user): void
    {
        PayoutSetting::create(['user_id' => $user->id]);

        $game_setting = [
            'setting' => 'off',
            'max_single_bid_num' => 10,
            'max_bid_amo' => 10000
        ];
        
        $user->update(['game_settings' => json_encode($game_setting)]);
    }

    /**
     * Handle the User "updated" event.
     */
    public function updated(User $user): void
    {
        //
    }

    /**
     * Handle the User "deleted" event.
     */
    public function deleted(User $user): void
    {
        Transaction::where('user_id', $user->id)->delete();
        BidsHistory::where('user_id', $user->id)->delete();
        PayoutSetting::where('user_id', $user->id)->delete();
    }

    /**
     * Handle the User "restored" event.
     */
    public function restored(User $user): void
    {
        Transaction::where('user_id', $user->id)->onlyTrashed()->restore();
        BidsHistory::where('user_id', $user->id)->onlyTrashed()->restore();
        PayoutSetting::where('user_id', $user->id)->onlyTrashed()->restore();
    }

    /**
     * Handle the User "force deleted" event.
     */
    public function forceDeleted(User $user): void
    {
        Transaction::where('user_id', $user->id)->withTrashed()->forceDelete();
        BidsHistory::where('user_id', $user->id)->withTrashed()->forceDelete();
        PayoutSetting::where('user_id', $user->id)->withTrashed()->forceDelete();
    }
}
