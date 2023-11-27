<?php

namespace Database\Seeders;

use App\Models\AdvBlock;
use App\Models\AdvPage;
use App\Models\Role;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdvPageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (!AdvPage::where('name', 'главная')->first()) {
            AdvPage::create(
                [
                    'name' => 'главная',
                    'slug' => 'main',
                    'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                    'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                ]
            );
        }

        if (!AdvPage::where('name', 'категория')->first()) {
            AdvPage::create(
                [
                    'name' => 'категория',
                    'slug' => 'category',
                    'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                    'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                ]
            );
        }

        if (!AdvPage::where('name', 'статья')->first()) {
            AdvPage::create(
                [
                    'name' => 'статья',
                    'slug' => 'article',
                    'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                    'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                ]
            );
        }

        if (!AdvPage::where('name', 'авторы')->first()) {
            AdvPage::create(
                [
                    'name' => 'авторы',
                    'slug' => 'authors',
                    'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                    'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                ]
            );
        }

        if (!AdvPage::where('name', 'поиск')->first()) {
            AdvPage::create(
                [
                    'name' => 'поиск',
                    'slug' => 'search',
                    'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                    'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                ]
            );
        }

        if (!AdvPage::where('name', '404')->first()) {
            AdvPage::create(
                [
                    'name' => '404',
                    'slug' => '404',
                    'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                    'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                ]
            );
        }

    }
}
