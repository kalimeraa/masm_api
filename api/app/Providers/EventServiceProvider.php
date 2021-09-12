<?php

namespace App\Providers;

use App\Events\SubscriptionCanceled;
use App\Events\SubscriptionRenewed;
use App\Events\SubscriptionStarted;
use App\Listeners\NotifySubscription;
use App\Models\Subscription;
use App\Observers\SubscriptionObserver;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],

        SubscriptionStarted::class => [
            NotifySubscription::class
        ],

        SubscriptionRenewed::class => [
            NotifySubscription::class
        ],

        SubscriptionCanceled::class => [
            NotifySubscription::class
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        Subscription::observe(SubscriptionObserver::class);
    }
}
