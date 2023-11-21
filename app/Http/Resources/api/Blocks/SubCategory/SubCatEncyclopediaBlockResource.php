<?php

namespace App\Http\Resources\api\Blocks\SubCategory;


use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SubCatEncyclopediaBlockResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        return [
            'name' => $this->name,
            'text' => $this->text,
            'photo_path' => $this->photo_path,
            'article_one' => [
                'name' => $this->article_one?->name,
                'slug' => $this->article_one?->slug,
            ],
            'article_two' => [
                'name' => $this->article_two?->name,
                'slug' => $this->article_two?->slug,
            ],
            'article_three' => [
                'name' => $this->article_three?->name,
                'slug' => $this->article_three?->slug,
            ],
            'article_four' => [
                'name' => $this->article_four?->name,
                'slug' => $this->article_four?->slug,
            ],
        ];
    }
}
