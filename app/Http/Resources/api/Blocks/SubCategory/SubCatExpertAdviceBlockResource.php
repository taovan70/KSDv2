<?php

namespace App\Http\Resources\api\Blocks\SubCategory;


use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SubCatExpertAdviceBlockResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        return [
            'author' => $this->category->authors?->map?->only([ 'id', 'name', 'surname', 'middle_name', "articles_count","photo_path", "categories"])[0] ?? null,
            'text' => $this->text,
        ];
    }
}
