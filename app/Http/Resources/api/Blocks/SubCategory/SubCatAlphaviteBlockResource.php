<?php

namespace App\Http\Resources\api\Blocks\SubCategory;


use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SubCatAlphaviteBlockResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        return [
            'name' => $this->name
        ];
    }
}
