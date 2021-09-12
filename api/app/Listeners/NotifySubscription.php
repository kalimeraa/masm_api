<?php

namespace App\Listeners;

use App\Events\SubscriptionEvent;
use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Http;

class NotifySubscription implements ShouldQueue
{
    /**
     * Opsiyonel olarak eğer ilgili event için set edilmiş 3rd-party endpoint HTTP 200 veya 201
     * harici bir status verdiğinde bu event bildirimi tekrar gönderilebilmesi için bir mekanizma
     * oluşturulabilir.
     */
    public function __get($name)
    {
        if ($name === 'queue') {
            return 'notify_subscription';
        }
    }

    public function handle(SubscriptionEvent $event)
    {
        $subscription = $event->getSubscription();
        $app = $subscription->app;
        $device = $subscription->device;

        $response = [
            'status' => false,
            'message' => 'Error',
        ];

        $rand = rand(200, 202);

        if ($rand === 200 || $rand === 201) {
            $response = [
                'status' => true,
                'message' => 'OK',
            ];
        }

        Http::fake([$app->endpoint => Http::response($response, $rand)]);

        $appEndpoint = Http::post($app->endpoint, [
            'appID,' => $app->id,
            'deviceID' => $device->id,
            'type' => $event->type
        ]);

        $status = $appEndpoint->status();
        if ($status !== 200 && $status !== 201) {
            throw new Exception(
                'App Endpoint status is not OK param app: ' .
                $app->name . ' status: ' . $status . 'subscription_id: ' . $subscription->id
            );
        }
    }
}
