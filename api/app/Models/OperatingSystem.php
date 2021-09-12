<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OperatingSystem extends Model
{
    public static function getOsByName(string $name): ?OperatingSystem
    {
        return OperatingSystem::where('name', $name)->first();
    }

    /**
     * @return HasMany
     */
    public function devices(): HasMany
    {
        return $this->hasMany(Device::class);
    }
}
