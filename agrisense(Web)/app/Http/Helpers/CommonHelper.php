<?php

use App\Models\Settings;
use Illuminate\Support\Facades\Cache;

function getSetting()
{
    $setting = null;

    if (Cache::has(env('APP_KEY'))) {
        $setting = Cache::get(env('APP_KEY'));
    } else {
        $setting = Settings::latest()->first();
        Cache::put(env('APP_KEY'), $setting);
    }
    return $setting;
}

function clearSettingCache()
{
    Cache::forget(env('APP_KEY'));
}
