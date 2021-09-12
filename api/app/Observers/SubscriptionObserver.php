<?php

namespace App\Observers;

use App\Enums\SubscriptionStatusEnum;
use App\Events\SubscriptionCanceled;
use App\Events\SubscriptionRenewed;
use App\Events\SubscriptionStarted;
use App\Models\Subscription;

class SubscriptionObserver
{
    /**
     * Handle the Subscription "created" event.
     *
     * @param Subscription $subscription
     * @return void
     */
    public function created(Subscription $subscription)
    {
        SubscriptionStarted::dispatch($subscription);
    }

    /**
     * Handle the Subscription "updated" event.
     *
     * @param Subscription $subscription
     * @return void
     */
    public function updated(Subscription $subscription)
    {
        $changes = $subscription->getChanges();
        if (isset($changes['expire_date'])) {
            SubscriptionRenewed::dispatch($subscription);
        } elseif (isset($changes['status']) && $changes['status'] === SubscriptionStatusEnum::CANCELED) {
            SubscriptionCanceled::dispatch($subscription);
        }
    }

    /**
     * Handle the Subscription "deleted" event.
     *
     * @param Subscription $subscription
     * @return void
     */
    public function deleted(Subscription $subscription)
    {
        //
    }

    /**
     * Handle the Subscription "restored" event.
     *
     * @param Subscription $subscription
     * @return void
     */
    public function restored(Subscription $subscription)
    {
        //
    }

    /**
     * Handle the Subscription "force deleted" event.
     *
     * @param Subscription $subscription
     * @return void
     */
    public function forceDeleted(Subscription $subscription)
    {
        //
    }
}
