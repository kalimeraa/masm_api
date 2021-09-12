<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class App extends Model
{
    protected $fillable = ['name', 'endpoint'];

    protected $dates = ['created_at', 'updated_at'];

    public static function getAppByName(string $name): ?App
    {
        return App::where('name', $name)->first();
    }
}
