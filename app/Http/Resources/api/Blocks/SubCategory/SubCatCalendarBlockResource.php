<?php

namespace App\Http\Resources\api\Blocks\SubCategory;


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

        return [
            'name' => $this->name,
            'month_data' => json_decode($this->month_data) ?? [],
        ];
    }
}
