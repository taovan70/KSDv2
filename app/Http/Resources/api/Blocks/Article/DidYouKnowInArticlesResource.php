<?php

namespace App\Http\Resources\api\Blocks\Article;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DidYouKnowInArticlesResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'text' => $this->text,
            'category' => $this->category,
        ];
    }
}
