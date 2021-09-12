<?php

namespace App\Events;

use App\Models\Subscription;

abstract class SubscriptionEvent
{
    protected Subscription $subscription;

    public function getSubscription(): Subscription
    {
        return $this->subscription;
    }
}
