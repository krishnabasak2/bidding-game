<?php

namespace App\Observers;

use App\Models\GamesList;
use App\Models\GamesTime;

class GamesListObserver
{
    /**
     * Handle the GamesList "created" event.
     */
    public function created(GamesList $gamesList): void
    {
        //
    }

    /**
     * Handle the GamesList "updated" event.
     */
    public function updated(GamesList $gamesList): void
    {
        //
    }

    /**
     * Handle the GamesList "deleted" event.
     */
    public function deleted(GamesList $gamesList): void
    {
        GamesTime::where('game_id', $gamesList->id)->delete();
    }

    /**
     * Handle the GamesList "restored" event.
     */
    public function restored(GamesList $gamesList): void
    {
        GamesTime::where('game_id', $gamesList->id)->withTrashed()->restore();
    }

    /**
     * Handle the GamesList "force deleted" event.
     */
    public function forceDeleted(GamesList $gamesList): void
    {
        GamesTime::where('game_id', $gamesList->id)->withTrashed()->forceDelete();
    }
}
