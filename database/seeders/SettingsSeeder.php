<?php

namespace Database\Seeders;

use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (!Setting::where('key', 'user_logging_all')->first()) {
            Setting::firstOrCreate(
                [
                    'key' => "user_logging_all",
                    'name' => json_encode(["en" => "user logging", "ru" => "Логирование пользователей"]),
                    'description' => json_encode(["en" => "on-off user logging", "ru" => "логирование полностью"]),
                    'value' => 1,
                    'field' => json_encode(["name" => "value", "label" => "Value", "type" => "checkbox"]),
                    'active' => 1,
                    'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                    'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                ]
            );
        }

        if (!Setting::where('key', 'user_logging_auth')->first()) {
            Setting::firstOrCreate(
                [
                    'key' => "user_logging_auth",
                    'name' => json_encode(
                        ["en" => "user logging when login", "ru" => "Логирование пользователей при входе"]
                    ),
                    'Description' => json_encode(
                        ["en" => "on-off user logging", "ru" => "логирование при входе к панель"]
                    ),
                    'value' => 1,
                    'field' => json_encode(["name" => "value", "label" => "Value", "type" => "checkbox"]),
                    'active' => 1,
                    'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                    'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                ]
            );
        }

        if (!Setting::where('key', 'user_logging_on_model')->first()) {
            Setting::firstOrCreate(
                [
                    'key' => "user_logging_on_model",
                    'name' => json_encode(
                        ["en" => "user logging on models", "ru" => "Логирование пользователей на моделях"]
                    ),
                    'Description' => json_encode(
                        ["en" => "on-off user logging on models", "ru" => "логирование при изменении моделей"]
                    ),
                    'value' => 1,
                    'field' => json_encode(["name" => "value", "label" => "Value", "type" => "checkbox"]),
                    'active' => 1,
                    'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                    'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                ]
            );
        }

        if (!Setting::where('key', 'site_url')->first()) {
            Setting::firstOrCreate(
                [
                    'key' => "site_url",
                    'name' => json_encode(
                        ["en" => "site url", "ru" => "url сайта"]
                    ),
                    'Description' => json_encode(
                        ["en" => "", "ru" => ""]
                    ),
                    'value' => 'mysite.com',
                    'field' => json_encode(["name" => "value", "label" => "Value", "type" => "text"]),
                    'active' => 1,
                    'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                    'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                ]
            );
        }

        if (!Setting::where('key', 'text_on_article_image_preview')->first()) {
            Setting::firstOrCreate(
                [
                    'key' => "text_on_article_image_preview",
                    'name' => json_encode(
                        ["en" => "show text on article image preview", "ru" => "Текст на картинках превью статьи"]
                    ),
                    'Description' => json_encode(
                        ["en" => "show text on article image preview", "ru" => "Текст на картинках превью статьи"]
                    ),
                    'value' => 0,
                    'field' => json_encode(["name" => "value", "label" => "Value", "type" => "checkbox"]),
                    'active' => 1,
                    'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                    'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                ]
            );
        }

        if (!Setting::where('key', 'subscribe_telegram_channel')->first()) {
            Setting::firstOrCreate(
                [
                    'key' => "subscribe_telegram_channel",
                    'name' => json_encode(
                        ["en" => "telegram channel for subscription", "ru" => "Telegram канал для подписки"]
                    ),
                    'Description' => json_encode(
                        ["en" => "", "ru" => ""]
                    ),
                    'value' => "",
                    'field' => json_encode(["name"=>"value","label"=>"","type"=>"text"]),
                    'active' => 1,
                    'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                    'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                ]
            );
        }

        if (!Setting::where('key', 'subscribe_vk_channel')->first()) {
            Setting::firstOrCreate(
                [
                    'key' => "subscribe_vk_channel",
                    'name' => json_encode(
                        ["en" => "VK channel for subscription", "ru" => "ВК канал для подписки"]
                    ),
                    'Description' => json_encode(
                        ["en" => "", "ru" => ""]
                    ),
                    'value' => "",
                    'field' => json_encode(["name"=>"value","label"=>"","type"=>"text"]),
                    'active' => 1,
                    'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                    'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                ]
            );
        }
    }
}
