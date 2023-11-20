<?php

namespace App\Http\Resources\api\Blocks\SubCategory;


use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SubCatKnowMoreAboutEachBlockResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $blockDataFromDB = json_decode($this->block_data) ?? [];

        $data = [];

        foreach ($blockDataFromDB as $value) {
            $data[] = [
                'name' => $value->name ?? '',
                'photo_path' => $value->photo_path ?? '',
                'article_one' => Article::select('slug', 'name')->where('id', $value->article_one_id)?->get()?->map?->only(['name', 'slug']),
                'article_two' => Article::select('slug', 'name')->where('id', $value->article_two_id)?->get()?->map?->only(['name', 'slug']),
            ];
        }

        return [
            'name' => $this->name,
            'category_id' => $this->category_id,
            'block_data' => $data,
        ];
    }
}
