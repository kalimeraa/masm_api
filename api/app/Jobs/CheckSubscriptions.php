<?php

namespace App\Jobs;

use App\Models\DeviceApp;
use App\Models\Subscription;
use App\Services\SubscriptionService;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;

class CheckSubscriptions implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var Subscription
     */
    private Subscription $subscription;
    private SubscriptionService $subscriptionService;

    /**
     * @param Subscription $subscription
     */
    public function __construct(Subscription $subscription)
    {
        $this->subscription = $subscription;
        $this->subscriptionService = new SubscriptionService();
    }

    /**
     * @throws Exception
     */
    public function handle()
    {
        $rand = (string)rand(10, 555541);

        $deviceApp = DeviceApp::getRegister($this->subscription->device_id, $this->subscription->app_id);
        $response = $this->subscriptionService->verifyByProvider($deviceApp->device->os->name, $rand);
        $responseData = json_decode($response->content(), true);

        if (!$responseData['status']) {
            throw new Exception($rand . ' ' . $responseData['message']);
        } else {
            $this->subscription->cancelSubscription();
        }
    }

    /**
     * @return Carbon
     * Ayrıca iOS ve Google API’ları mobile application bazlı olarak rate-limitleri
     * bulunmaktadır. Bekleyen kayıtları eritirken bazı istekler (reciept değerindeki son 2
     * basamak 6’ya bölünebiliyorsa) iOS ve Google API’larından rate-limit hatası alabilir, bu
     * durumda ilgili kayıt daha sonra tekrar denenmelidir.
     */
    public function retryUntil()
    {
        return now()->addSeconds(5);
    }
}
