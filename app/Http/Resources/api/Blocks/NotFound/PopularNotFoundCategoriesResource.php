<?php

namespace App\Http\Resources\api\Blocks\NotFound;

use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PopularNotFoundCategoriesResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'name' => $this->category?->name,
            'slug' => $this->category?->slug,
        ];
    }
}
