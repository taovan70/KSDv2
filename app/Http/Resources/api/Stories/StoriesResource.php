<?php

namespace App\Http\Resources\api\Stories;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StoriesResource extends JsonResource
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
            'name' => $this->name,
            'photo_path' => $this->photo_path,
        ];
    }
}
