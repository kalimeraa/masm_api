<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\ShowSubscriptionRequest;
use App\Http\Requests\StoreSubscriptionRequest;
use App\Models\DeviceApp;
use App\Models\OperatingSystem;
use App\Models\Subscription;
use App\Services\SubscriptionService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;

class SubscriptionController extends Controller
{
    /**
     * @var SubscriptionService
     */
    private SubscriptionService $subscriptionService;
    private array $validated;
    private ?DeviceApp $deviceApp;
    private ?Subscription $subscription;

    public function __construct(ShowSubscriptionRequest $request, SubscriptionService $subscriptionService)
    {
        $this->subscriptionService = $subscriptionService;
        $this->validated = $request->validated();
        $this->deviceApp = DeviceApp::getDeviceAppByToken($this->validated['token']);
    }

    /**
     * @return JsonResponse
     */
    public function show(): JsonResponse
    {
        if (is_null($this->deviceApp)) {
            return response()->json([
                'status' => false,
                'message' => trans('device.does_not_exist')
            ], 404);
        }

        $this->subscriptionService->setDeviceApp($this->deviceApp);
        if (!$this->subscriptionService->isDeviceSubscribed()) {
            return response()->json([
                'status' => false,
                'message' => trans('subscription.no_subscription'),
            ]);
        }

        $this->subscription = $this->subscriptionService->getSubscription();
        if ($this->subscriptionService->isActiveSubscription()) {
            return response()->json([
                'status' => true,
                'message' => trans('subscription.subscribed'),
                'expire_date' => Carbon::parse($this->subscription->expire_date)
                    ->setTimezone('America/New_York')
                    ->format('Y-m-d H:i:s T'),
            ]);
        }

        return response()->json([
            'status' => false,
            'message' => trans('subscription.expired_subscription'),
            'expire_date' => Carbon::parse($this->subscription->expire_date)
                ->setTimezone('America/New_York')
                ->format('Y-m-d H:i:s T'),
        ]);
    }

    public function store(StoreSubscriptionRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $checkSubscriptionResponse = $this->show();
        $checkSubscription = json_decode($checkSubscriptionResponse->content(), true);
        /**
         * if subscription active or device does not exist
         */
        if ($checkSubscription['message'] === trans('device.does_not_exist') || $checkSubscription['status']) {
            return $checkSubscriptionResponse;
        }

        $os = OperatingSystem::findOrFail($this->deviceApp->device->os_id);

        $providerResponse = $this->subscriptionService->verifyByProvider($os->name, $validated['receipt']);
        $providerData = json_decode($providerResponse->getContent(), true);

        if (!$providerData['status']) {
            return $providerResponse;
        }

        if ($checkSubscription['message'] === trans('subscription.no_subscription')) {
            $subscription = new Subscription();
            $this->subscription = $subscription->subscribe([
                'device_id' => $this->deviceApp->device_id,
                'app_id' => $this->deviceApp->app_id,
                'expire_date' => $providerData['expire_date'],
            ]);
            $msg = trans('subscription.subscribed');
        } else {
            $this->subscription = $this->subscription->renewSubscription(['expire_date' => $providerData['expire_date']]);
            $msg = trans('subscription.renewed');
        }

        return response()->json([
            'status' => true,
            'message' => $msg,
            'expire_date' => Carbon::parse($this->subscription->expire_date)
                ->setTimezone('America/New_York')
                ->format('Y-m-d H:i:s T'),
        ]);
    }
}
