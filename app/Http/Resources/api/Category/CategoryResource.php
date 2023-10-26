<?php

namespace App\Http\Resources\api\Category;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
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
            "depth" => $this->depth,
            "rgt" => $this->rgt,
            "lft" => $this->lft,
            "icon_path" => $this->icon_path,
            "photo_path" => $this->photo_path,
            'description' => $this->description,
            "mini_pic_path" => $this->mini_pic_path,
            "children" => CategoryResource::collection($this->children),
            "parent_id" => $this->parent_id,
            "created_at" => $this->created_at,
            "updated_at" => $this->updated_at
        ];
    }
}
