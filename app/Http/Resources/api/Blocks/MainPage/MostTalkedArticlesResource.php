<?php

namespace App\Http\Resources\api\Blocks\MainPage;

use App\Http\Resources\api\Category\CategoryResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MostTalkedArticlesResource extends JsonResource
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
            'article' => [
                'name' => $this->article->name,
                'slug' => $this->article->slug,
                'photo_path' => $this->article->photo_path,
                'created_at' => $this->article->created_at,
                'author' => [
                    'id' => $this->article->author->id,
                    'name' => $this->article->author->name,
                    'slug' => $this->article->author->slug,
                ],
                'category' => [
                    'name' => $this->article->category->name,
                    'slug' => $this->article->category->slug,
                ]
            ],
        ];
    }
}
