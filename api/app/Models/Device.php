<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Device extends Model
{
    protected $fillable = [
        'uid',
        'language_id',
        'os_id',
    ];

    protected $dates = ['created_at', 'updated_at'];

    /**
     * @param string $uid
     * @return Device|null
     */
    public static function getDevice(string $uid): ?Device
    {
        return Device::where('uid', $uid)->first();
    }

    /**
     * @return HasMany
     */
    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }

    /**
     * @return HasMany
     */
    public function apps(): HasMany
    {
        return $this->hasMany(App::class);
    }

    /**
     * @return BelongsTo
     */
    public function os(): BelongsTo
    {
        return $this->belongsTo(OperatingSystem::class);
    }
}
