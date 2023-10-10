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
            'category' => [
                'name' => $this->category->name,
                'slug' => $this->category->slug,
                'articles_count' => $this->category->articles_count,
            ],
        ];
    }
}
