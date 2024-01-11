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
        $subscribeTelegramChannel = Setting::where('key', 'subscribe_telegram_channel')->first();
        $subscribeVKChannel = Setting::where('key', 'subscribe_vk_channel')->first();
        $settings = [
            'preview_type' => $textOnArticle->value ? 'type2' : 'type1',
            'subscribe_telegram_channel' => $subscribeTelegramChannel->value,
            'subscribe_vk_channel' => $subscribeVKChannel->value,
        ];

        return $settings;
    }
}
