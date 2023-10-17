<?php

namespace App\Http\Resources\api\Blocks\MainPage;

use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReadersRecomendArticleResource extends JsonResource
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
                'publish_date' => $this->article->publish_date,
                'author' => [
                    'id' => $this->article->author->id,
                    'name' => $this->article->author->name,
                    'surname' => $this->article->author->surname,
                    'slug' => $this->article->author->slug,
                ],
                'category' => [
                    'name' => $this->article->category->name,
                    'slug' => $this->article->category->slug,
                    'icon_path' => $this->article->category->icon_path
                ],
                'media' => [
                    'mainPic' => Article::where('slug', '=',  $this->article->slug)->first()->getMedia('mainPic'),
                ]
            ],
        ];
    }
}
