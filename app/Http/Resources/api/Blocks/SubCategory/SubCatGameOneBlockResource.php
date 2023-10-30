<?php

namespace App\Http\Resources\api\Blocks\SubCategory;


use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SubCatGameOneBlockResource extends JsonResource
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
            'answer_data' => json_decode($this->answer_data) ?? [],
            'photo_path' => $this->photo_path ?? null
        ];
    }
}
