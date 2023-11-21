<?php

namespace App\Http\Resources\api\Blocks\SubCategory;


use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SubCatBehindTheScenesBlockResource extends JsonResource
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
            'video_path' => $this->video_path,
            'article' => [
                'name' => $this->article?->name,
                'slug' => $this->article?->slug,
                'publish_date' => $this->article?->publish_date,
            ],
        ];
    }
}
