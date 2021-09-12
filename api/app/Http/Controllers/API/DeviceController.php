<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDeviceRequest;
use App\Models\App;
use App\Models\Device;
use App\Models\DeviceApp;
use App\Models\Language;
use App\Models\OperatingSystem;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;

class DeviceController extends Controller
{
    /**
     * @param StoreDeviceRequest $request
     * @return JsonResponse
     */
    public function store(StoreDeviceRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $app = App::getAppByName($validated['app_id']);

        if (is_null($app)) {
            return response()->json([
                'status' => false,
                'message' => trans('app.does_not_exist')
            ], 404);
        }

        $device = Device::getDevice($validated['uid']);
        if (!is_null($device)) {
            $deviceApp = DeviceApp::getRegister($device->id, $app->id);
            if (!is_null($deviceApp)) {
                return response()->json([
                    'status' => true,
                    'message' => trans('device.registered'),
                    'token' => $deviceApp->token
                ]);
            }
        }

        $language = Language::firstOrCreate(['locale' => $validated['language']]);
        $os = OperatingSystem::firstOrCreate(['name' => $validated['os']]);
        $token = hash('sha256', Str::random(40));

        if (is_null($device)) {
            $device = Device::create([
                'os_id' => $os->id,
                'language_id' => $language->id,
                'uid' => $validated['uid']
            ]);
        }

        DeviceApp::create([
            'device_id' => $device->id,
            'app_id' => $app->id,
            'token' => $token
        ]);

        return response()->json([
            'status' => true,
            'message' => trans('device.registered'),
            'token' => $token
        ]);
    }
}
