<?php

namespace App\Http\Resources\api;


use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StandalonePageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'main_text' => $this->main_text,
            'add_text' => $this->add_text,
            'photo_path' => $this->photo_path
        ];
    }
}
