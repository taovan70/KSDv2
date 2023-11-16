<?php

namespace App\Http\Resources\api\Blocks\SubCategory;


use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SubCatTopFactsBlockResource extends JsonResource
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
            'text' => $this->text,
            'number_one' => $this->number_one,
            'text_one' => $this->text_one,
            'number_two' => $this->number_two,
            'text_two' => $this->text_two,
            'number_three' => $this->number_three,
            'text_three' => $this->text_three,
            'article_one' => [
                'name' => $this->article_one?->name,
                'slug' => $this->article_one?->slug,
                'image' => $this->article_one?->getMedia('mainPic')
            ],
            'article_two' => [
                'name' => $this->article_two?->name,
                'slug' => $this->article_two?->slug,
                'image' => $this->article_one?->getMedia('mainPic')
            ],
            'background_photo_path' => $this->background_photo_path,
        ];
    }
}
