<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Subject;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class SubjectsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Subject::query()->delete();

        Category::all()->each(function (Category $category) {
            for ($i = 0; $i < 5; $i ++) {
                $name = fake()->unique()->lastName();

                Subject::create([
                    'name' => $name,
                    'slug' => Str::slug($name),
                    'category_id' => $category->id
                ]);
            }
        });
    }
}
