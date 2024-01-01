<?php

namespace App\Observers;

use App\Models\User;
use Illuminate\Contracts\Events\ShouldHandleEventsAfterCommit;

class UserObserver
{
    public function __construct()
    {
        dd('class observe');

    }
    /**
     * Handle the User "created" event.
     */
    public function created(User $user): void
    {
        logger("user model updated");
        //
    }

    public function updating()
    {
        dd('cls');

}
    /**
     * Handle the User "updated" event.
     */
    public function updated(User $user): void
    {
        logger("user model updated");
    }

    /**
     * Handle the User "deleted" event.
     */
    public function deleted(User $user): void
    {
        logger("user model updated");
        //
    }

    /**
     * Handle the User "restored" event.
     */
    public function restored(User $user): void
    {
        logger("user model updated");
        //
    }

    /**
     * Handle the User "force deleted" event.
     */
    public function forceDeleted(User $user): void
    {
        logger("user model updated");
        //
    }
}
