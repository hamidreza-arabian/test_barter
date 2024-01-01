<?php

namespace App\Providers;

use App\Models\MainRegisterEstate;
use App\Models\User;
use App\Observers\MainRegisterEstateObserver;
use App\Observers\UserObserver;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
    ];



    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        //codet ro gite?
        //bale  khob to git addm kon ba system klhodem chekesh knom
        //bashe younes_mokhtari
//        dd('clas');
        User::observe([UserObserver::class]);
        //chetori test mikoni?
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return true;
    }
}
