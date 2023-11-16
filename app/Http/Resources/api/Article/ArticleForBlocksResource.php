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
                'icon_path' => $this->category->icon_path
            ],
            'media' => [
                'mainPic' => Article::where('slug', '=',  $this->slug)->with('media')->first()->getMedia('mainPic'),
            ]
        ];
    }
}
