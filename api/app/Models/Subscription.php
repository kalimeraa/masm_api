<?php

namespace App\Models;

use App\Enums\SubscriptionStatusEnum;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Subscription extends Model
{
    protected $fillable = ['device_id', 'app_id', 'status', 'expire_date'];

    protected $dates = ['created_at', 'updated_at', 'expire_date'];

    /**
     * @param array $data
     * @return Subscription
     */
    public function subscribe(array $data): Subscription
    {
        return Subscription::create($data + ['status' => SubscriptionStatusEnum::STARTED]);
    }

    /**
     * @param array $data
     * @return Subscription
     */
    public function renewSubscription(array $data): Subscription
    {
        $this->update($data + ['status' => SubscriptionStatusEnum::RENEWED]);

        return $this->refresh();
    }

    /**
     * @return Subscription
     */
    public function cancelSubscription(): Subscription
    {
        $this->status = SubscriptionStatusEnum::CANCELED;
        $this->save();

        return $this;
    }

    /**
     * @param int $deviceId
     * @param int $appId
     * @return Subscription|null
     */
    public static function getSubscription(int $deviceId, int $appId): ?Subscription
    {
        return Subscription::where('device_id', $deviceId)->where('app_id', $appId)->first();
    }

    /**
     * @return BelongsTo
     */
    public function device(): BelongsTo
    {
        return $this->belongsTo(Device::class);
    }

    /**
     * @return BelongsTo
     */
    public function app(): BelongsTo
    {
        return $this->belongsTo(App::class);
    }

    /**
     * @return bool
     */
    public function isActiveSubscription(): bool
    {
        return (bool)$this->expire_date->greaterThan(Carbon::now()
            ->setTimezone('America/New_York')
            ->format('Y-m-d H:i:s T'));
    }
}
