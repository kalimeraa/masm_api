<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class DeviceApp extends Model
{
    protected $fillable = ['device_id', 'app_id', 'token'];

    protected $dates = ['created_at', 'updated_at'];

    /**
     * @param int $deviceId
     * @param int $appId
     * @return DeviceApp|null
     */
    public static function getRegister(int $deviceId, int $appId): ?DeviceApp
    {
        return DeviceApp::where('device_id', $deviceId)->where('app_id', $appId)->first();
    }

    /**
     * @param string $token
     * @return DeviceApp|null
     */
    public static function getDeviceAppByToken(string $token): ?DeviceApp
    {
        return DeviceApp::where('token', $token)->first();
    }

    /**
     * @return HasOne
     */
    public function device(): HasOne
    {
        return $this->hasOne(Device::class, 'id', 'device_id');
    }
}
