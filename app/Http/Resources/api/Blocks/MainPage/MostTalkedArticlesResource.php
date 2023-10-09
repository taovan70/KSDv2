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
                'slug' => $this->article->slug
            ],
        ];
    }
}
