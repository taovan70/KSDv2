<?php

namespace App\Http\Resources\api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ArticlesResource extends JsonResource
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
            'published' => $this->published,
            'publish_date' => $this->publish_date,
            'category' => $this->category,
            'author' => $this->author,
            'tags' => $this->tags
        ];
    }
}