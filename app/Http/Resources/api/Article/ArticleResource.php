<?php

namespace App\Http\Resources\api\Article;

use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ArticleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'slug' => $this->slug,
            'title' => $this->name,
            'published' => $this->published,
            'publish_date' => $this->publish_date,
            'preview_text' => $this->preview_text,
            'category' => $this->category,
            'contentMarkdown' => $this->content_html,
            'author' => $this->author,
            'tags' => $this->tags,
            'meta' => [
                'title' => $this->title,
                'description' => $this->description,
                'keywords' => $this->keywords
            ],
            'media' => [
                'mainPic' => Article::where('slug', '=',  $this->slug)->with('media')->first()->getMedia('mainPic'),
                'allPics' => Article::where('slug', '=',  $this->slug)->with('media')->first()->getMedia('default'),
            ]
        ];
    }
}
