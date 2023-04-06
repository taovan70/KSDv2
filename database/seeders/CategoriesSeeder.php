<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Category::query()->delete();

        for ($i = 0; $i < 10; $i++) {
            $name = fake()->unique()->jobTitle();

            Category::create([
                'name' => $name,
                'slug' => Str::slug($name)
            ]);
        }
    }
}
