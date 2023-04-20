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
    }
}
