<?php

namespace App\Http\Resources\api\Blocks\Category;


use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class QACategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $articles = [
            [
            'name' => $this->article_one?->name,
            'slug' => $this->article_one?->slug,
            'publish_date' => $this->article_one?->publish_date,
            'preview_text' => $this->article_one?->preview_text,
            'author' => [
                'id' => $this->article_one?->author->id,
                'name' => $this->article_one?->author->name,
                'surname' => $this->article_one?->author->surname,
                'slug' => $this->article_one?->author->slug,
            ],
            'category' => [
                'name' => $this->article_one?->category->name,
                'slug' => $this->article_one?->category->slug,
                'icon_path' => $this->article_one?->category->icon_path
            ],
            'media' => [
                'mainPic' => $this->article_one?->getMedia('mainPic')
            ]
        ],
            [
                'name' => $this->article_two?->name,
                'slug' => $this->article_two?->slug,
                'publish_date' => $this->article_two?->publish_date,
                'preview_text' => $this->article_two?->preview_text,
                'author' => [
                    'id' => $this->article_two?->author->id,
                    'name' => $this->article_two?->author->name,
                    'surname' => $this->article_two?->author->surname,
                    'slug' => $this->article_two?->author->slug,
                ],
                'category' => [
                    'name' => $this->article_two?->category->name,
                    'slug' => $this->article_two?->category->slug,
                    'icon_path' => $this->article_two?->category->icon_path
                ],
                'media' => [
                    'mainPic' => $this->article_two?->getMedia('mainPic')
                ]
            ]
        ];

        if(!empty($this->article_three?->slug)) {
            $articles[] =  [
                'name' => $this->article_three?->name,
                'slug' => $this->article_three?->slug,
                'publish_date' => $this->article_three?->publish_date,
                'preview_text' => $this->article_three?->preview_text,
                'author' => [
                    'id' => $this->article_three?->author->id,
                    'name' => $this->article_three?->author->name,
                    'surname' => $this->article_three?->author->surname,
                    'slug' => $this->article_three?->author->slug,
                ],
                'category' => [
                    'name' => $this->article_three?->category->name,
                    'slug' => $this->article_three?->category->slug,
                    'icon_path' => $this->article_three?->category->icon_path
                ],
                'media' => [
                    'mainPic' => $this->article_three?->getMedia('mainPic')
                ]
            ];
        }

        if(!empty($this->article_four?->slug)) {
            $articles[] = [
                'name' => $this->article_four?->name,
                'slug' => $this->article_four?->slug,
                'publish_date' => $this->article_four?->publish_date,
                'preview_text' => $this->article_four?->preview_text,
                'author' => [
                    'id' => $this->article_four?->author->id,
                    'name' => $this->article_four?->author->name,
                    'surname' => $this->article_four?->author->surname,
                    'slug' => $this->article_four?->author->slug,
                ],
                'category' => [
                    'name' => $this->article_four?->category->name,
                    'slug' => $this->article_four?->category->slug,
                    'icon_path' => $this->article_four?->category->icon_path
                ],
                'media' => [
                    'mainPic' => $this->article_four?->getMedia('mainPic')
                ]
            ];
        }

        if(!empty($this->article_five?->slug)) {
            $articles[] = [
                'name' => $this->article_five?->name,
                'slug' => $this->article_five?->slug,
                'publish_date' => $this->article_five?->publish_date,
                'preview_text' => $this->article_five?->preview_text,
                'author' => [
                    'id' => $this->article_five?->author->id,
                    'name' => $this->article_five?->author->name,
                    'surname' => $this->article_five?->author->surname,
                    'slug' => $this->article_five?->author->slug,
                ],
                'category' => [
                    'name' => $this->article_five?->category->name,
                    'slug' => $this->article_five?->category->slug,
                    'icon_path' => $this->article_five?->category->icon_path
                ],
                'media' => [
                    'mainPic' => $this->article_five?->getMedia('mainPic')
                ]
            ];
        }

        if(!empty($this->article_six?->slug)) {
            $articles[] = [
                'name' => $this->article_six?->name,
                'slug' => $this->article_six?->slug,
                'publish_date' => $this->article_six?->publish_date,
                'preview_text' => $this->article_six?->preview_text,
                'author' => [
                    'id' => $this->article_six?->author->id,
                    'name' => $this->article_six?->author->name,
                    'surname' => $this->article_six?->author->surname,
                    'slug' => $this->article_six?->author->slug,
                ],
                'category' => [
                    'name' => $this->article_six?->category->name,
                    'slug' => $this->article_six?->category->slug,
                    'icon_path' => $this->article_six?->category->icon_path
                ],
                'media' => [
                    'mainPic' => $this->article_six?->getMedia('mainPic')
                ]
            ];
        }

        return [
            'articles' => $articles
        ];
    }
}
