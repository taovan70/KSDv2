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
        $settings['site_url'] = env('FRONT_URL') ?? 'https://example.com';
        return $settings;
    }

    public function getAppSettings()
    {
        $textOnArticle = Setting::where('key', 'text_on_article_image_preview')->first();
        $subscribeTelegramChannel = Setting::where('key', 'subscribe_telegram_channel')->first();
        $subscribeVKChannel = Setting::where('key', 'subscribe_vk_channel')->first();
        $hideThemeChangeButton = Setting::where('key', 'hide_theme_change_button')->first();
        $buttonTypeOnSite = Setting::where('key', 'buttons_type_on_site')->first();
        $settings = [
            'preview_type' => $textOnArticle->value ? 'type2' : 'type1',
            'subscribe_telegram_channel' => $subscribeTelegramChannel->value,
            'subscribe_vk_channel' => $subscribeVKChannel->value,
            'hide_theme_change_button' => (bool) $hideThemeChangeButton->value,
            'buttons_type_on_site' => $buttonTypeOnSite->value
        ];

        return $settings;
    }
}
