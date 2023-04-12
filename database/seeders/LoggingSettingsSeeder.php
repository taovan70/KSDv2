<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LoggingSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('settings')->insert([
            'key' => "user logging",
            'name' => json_encode(["en"=>"user logging", "ru"=>"Логирование пользователей"]),
            'Description' => json_encode(["en"=>"on-off user logging", "ru"=> "Вкл-Выкл логирование полностью"]),
            'value' => 1,
            'field' => json_encode(["name"=>"value","label"=>"Value","type"=>"checkbox"]),
            'active' => 1,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);
    }
}
