<?php

namespace App\Http\Resources\api\AdvPage;

use App\Http\Resources\api\AdvBlock\AdvBlockResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AdvPageResource extends JsonResource
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
            'adv_blocks' => AdvBlockResource::collection($this->advBlocks),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
