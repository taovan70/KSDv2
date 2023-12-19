<?php

namespace App\Http\Resources\api\Stories;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StoryItemsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'text' => $this->text,
            'name' => $this->name,
            'photo_path' => $this->photo_path,
            'article' => [
                'name' => $this->article->name,
                'slug' => $this->article->slug,
                'preview_text' => $this->article->preview_text
            ]
        ];
    }
}
