<?php

namespace App\Http\Resources\api\Blocks\SubCategory;


use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SubCatCalendarBlockResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $monthDataFromDB = json_decode($this->month_data) ?? [];

        $data = [];

        foreach ($monthDataFromDB as $value) {
            $data[] = [
                'name' => $value->name ?? '',
                'text' => $value->text ?? '',
                'article' => Article::select('slug', 'name')->where('id', $value->article_id)?->get()?->map?->only(['name', 'slug']),
            ];
        }

        return [
            'name' => $this->name,
            'month_data' => $data,
        ];
    }
}
