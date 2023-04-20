<?php

namespace Database\Seeders;

use App\Models\Author;
use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;

class AuthorsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Author::query()->delete();
        Storage::disk('public')->deleteDirectory('authors_photos');
        $categories = Category::all();

        for ($i = 0; $i < 20; $i++) {
            $author = $this->createAuthor();

            $author->categories()->sync($categories->random(rand(1, 5)));
        }
    }

    private function createAuthor(): Author
    {
        $gender = Author::GENDERS[rand(0, 1)];
        $avatar = new File(database_path("avatars/{$gender}/" . rand(1, 3) . ".png"));
        $path   = Storage::disk('public')->putFile('authors_photos', $avatar);

        $author = new Author();
        $author->name = fake()->firstName();
        $author->surname = fake()->lastName();
        $author->age = rand(18, 80);
        $author->gender = $gender;
        $author->biography = fake()->text(400);
        $author->address = fake()->city();
        $author->photo_path = $path;
        $author->save();

        return $author;
    }
}
