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
        $textOnArticle = Setting::where('key', 'text_on_article_image_preview')->first();
        $settings = [
            'preview_type' => $textOnArticle->value ? 'type2' : 'type1',
        ];
       
        return $settings;
    }
}
