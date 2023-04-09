<?php

namespace Database\Seeders;

use App\Models\Section;
use App\Models\Subject;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class SectionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Section::query()->delete();

        Subject::all()->each(function (Subject $subject) {
            for ($i = 0; $i < 5; $i ++) {
                $name = fake()->unique()->firstName();

                Section::create([
                    'name' => $name,
                    'slug' => Str::slug($name),
                    'subject_id' => $subject->id
                ]);
            }
        });
    }
}
