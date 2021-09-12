<?php

namespace App\Services;

use App\Enums\DeviceEnum;
use App\Http\Mocks\AppleVerification;
use App\Http\Mocks\GoogleVerification;
use App\Http\Mocks\ProviderVerification;
use App\Models\Device;
use App\Models\DeviceApp;
use App\Models\Subscription;
use Illuminate\Http\JsonResponse;

class SubscriptionService
{
    /**
     * @var Subscription|null
     */
    private ?Subscription $subscription;

    /**
     * @var Device|null
     */
    private ?Device $device;
    /**
     * @var DeviceApp
     */
    private DeviceApp $deviceApp;

    /**
     * @param string $os
     * @param string $receipt
     * @return JsonResponse
     */
    public function verifyByProvider(string $os, string $receipt): JsonResponse
    {
        switch ($os) {
            case DeviceEnum::ANDROID:
                $provider = (new ProviderVerification($receipt))->verify(new GoogleVerification());
                return $provider;
            case DeviceEnum::IOS:
                $provider = (new ProviderVerification($receipt))->verify(new AppleVerification());
                return $provider;
        }

        return response()->json(['status' => false, 'message' => trans('subscription.failed')]);
    }

    public function setDeviceApp(DeviceApp $deviceApp): void
    {
        $this->deviceApp = $deviceApp;
        $this->device = $deviceApp->device;
        $this->subscription = $this->device->subscriptions->where('app_id', $this->deviceApp->app_id)->first();
    }

    public function isDeviceSubscribed(): bool
    {
        /**
         * @var Subscription|null $subscription
         */

        if (is_null($this->subscription)) {
            return false;
        }

        return true;
    }

    /**
     * @return bool
     */
    public function isActiveSubscription(): bool
    {
        if (is_null($this->subscription)) {
            return false;
        }

        $activeSubscription = $this->subscription->isActiveSubscription();
        if (!$activeSubscription) {
            return false;
        }

        return true;
    }

    public function getSubscription(): ?Subscription
    {
        return $this->subscription;
    }
}
