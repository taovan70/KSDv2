<?php

namespace App\Http\Resources\api\AdvBlock;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AdvBlockResource extends JsonResource
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
            'description' => $this->description,
            'content' => $this->content,
            'active' => $this->active,
            'device_type' => $this->device_type,
            'number_of_elements_from_beginning' => $this->number_of_elements_from_beginning,
            'color_type' => $this->color_type,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
