<?php

namespace App\Http\Resources\api\Blocks\SubCategory;


use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SubCatGameTwoBlockResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        return [
            'question' => $this->question,
            'description' => $this->description,
            'answer_data' => json_decode($this->answer_data) ?? [],
            'article' => [
                'name' => $this->article?->name,
                'slug' => $this->article?->slug,
                'publish_date' => $this->article?->publish_date,
            ],
            'photo_path_one' => $this->photo_path_one ?? null,
            'photo_path_two' => $this->photo_path_two ?? null
        ];
    }
}
