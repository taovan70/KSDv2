<?php

namespace Database\Seeders;

use App\Models\Section;
use App\Models\SubSection;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class SubSectionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        SubSection::query()->delete();

        Section::all()->each(function (Section $section) {
            for ($i = 0; $i < 5; $i ++) {
                $name = fake()->unique()->address();

                SubSection::create([
                    'name' => $name,
                    'slug' => Str::slug($name),
                    'section_id' => $section->id
                ]);
            }
        });
    }
}
