<?php

namespace App\Observers;

use App\Models\MainRegisterEstate;

class MainRegisterEstateObserver
{
    /**
     * Handle the MainRegisterEstate "created" event.
     */
    public function created(MainRegisterEstate $mainRegisterEstate): void
    {
        //
    }

    /**
     * Handle the MainRegisterEstate "updated" event.
     */
    public function updated(MainRegisterEstate $mainRegisterEstate): void
    {
        logger("model updated");
    }

    /**
     * Handle the MainRegisterEstate "deleted" event.
     */
    public function deleted(MainRegisterEstate $mainRegisterEstate): void
    {
        //
    }

    /**
     * Handle the MainRegisterEstate "restored" event.
     */
    public function restored(MainRegisterEstate $mainRegisterEstate): void
    {
        //
    }

    /**
     * Handle the MainRegisterEstate "force deleted" event.
     */
    public function forceDeleted(MainRegisterEstate $mainRegisterEstate): void
    {
        //
    }
}
