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

        if (!Setting::where('key', 'hide_theme_change_button')->first()) {
            Setting::firstOrCreate(
                [
                    'key' => "hide_theme_change_button",
                    'name' => json_encode(
                        ["en" => "Hide theme change button", "ru" => "Скрыть кнопку смены темы на сайте"]
                    ),
                    'Description' => json_encode(
                        ["en" => "", "ru" => ""]
                    ),
                    'value' => "",
                    'field' => json_encode(["name" => "value", "label" => "Value", "type" => "checkbox"]),
                    'active' => 1,
                    'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                    'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                ]
            );
        }

        if (!Setting::where('key', 'buttons_type_on_site')->first()) {
            Setting::firstOrCreate(
                [
                    'key' => "buttons_type_on_site",
                    'name' => json_encode(
                        ["en" => "Buttons type on site", "ru" => "Стили кнопок на сайте"]
                    ),
                    'Description' => json_encode(
                        ["en" => "", "ru" => ""]
                    ),
                    'value' => "",
                    'field' => '{"name":"value","label":"","type":"select_from_array","options":{"initial":"По умолчанию (initial)","3DButtonGlare":"3D Button Glare"},"allows_null":false,"default":"initial"}',
                    'active' => 1,
                    'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                    'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                ]
            );
        }

        if (!Setting::where('key', 'article_content_button_behavior')->first()) {
            Setting::firstOrCreate(
                [
                    'key' => "article_content_button_behavior",
                    'name' => json_encode(
                        ["en" => "Article content button behavior", "ru" => "Поведение кнопки содержания в статье"]
                    ),
                    'Description' => json_encode(
                        ["en" => "", "ru" => ""]
                    ),
                    'value' => "",
                    'field' => '{"name":"value","label":"","type":"select_from_array","options":{"initial":"По умолчанию (initial)","newBehaviour":"Новое поведение"},"allows_null":false,"default":"initial"}',
                    'active' => 1,
                    'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                    'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                ]
            );
        }
    }
}
