<?php

namespace App\Http\Resources\api\Article;

use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ArticleForBlocksResource extends JsonResource
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
            'preview_text' => $this->preview_text,
            'slug' => $this->slug,
            'publish_date' => $this->publish_date,
            'author' => [
                'id' => $this->author->id,
                'name' => $this->author->name,
                'surname' => $this->author->surname,
                'slug' => $this->author->slug,
            ],
            'category' => [
                'name' => $this->category->name,
                'slug' => $this->category->slug,
                'depth' => $this->category->depth,
                'icon_path' => $this->category->icon_path
            ],
            'media' => [
                'mainPic' => $this->getMedia('mainPic'),
            ],
            'tags' => $this->tags?->map?->only([ 'name', 'slug']),
        ];
    }
}
