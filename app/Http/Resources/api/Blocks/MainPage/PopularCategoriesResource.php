<?php

namespace App\Http\Resources\api\Blocks\MainPage;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PopularCategoriesResource extends JsonResource
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
            'photo_path' => $this->photo_path,
            'category' => [
                'name' => $this->category?->name,
                'slug' => $this->category?->slug,
                'depth' => $this->category?->depth,
                'photo_path' => $this->category?->photo_path,
                'icon_path' => $this->category?->icon_path,
                'articles_count' => $this->category?->articles_count,
            ],
        ];
    }
}
