<?php

namespace App\Http\Resources\api\Author;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AuthorResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'surname' => $this->surname,
            'middle_name' => $this->middle_name,
            'age' => $this->age,
            'gender' => $this->gender,
            'biography' => $this->biography,
            'address' => $this->address,
            'photo_path' => $this->photo_path,
            'personal_site' => $this->personal_site,
            'social_networks' => $this->social_networks,
            'articles_count' => $this->articles_count,
            'categories' => $this->categories->map?->only(['name', 'slug'])
        ];
    }
}
