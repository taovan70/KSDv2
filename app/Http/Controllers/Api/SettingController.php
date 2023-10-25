<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Setting;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::all()->pluck('value', 'key');
        $settings['lang'] = app()->getLocale();
        return $settings;
    }

    public function getAppSettings()
    {
        $settings = [
            'preview_type' => 'type2',
        ];
       
        return $settings;
    }
}
