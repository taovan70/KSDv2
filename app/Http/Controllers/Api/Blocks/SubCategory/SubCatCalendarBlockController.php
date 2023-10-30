<?php

namespace App\Http\Controllers\Api\Blocks\SubCategory;


use App\Http\Controllers\Controller;
use App\Http\Resources\api\Blocks\SubCategory\SubCatCalendarBlockResource;
use App\Models\Blocks\SubCategory\SubCatCalendar;

class SubCatCalendarBlockController extends Controller
{

    public function __construct()
    {
    }

    public function index(string $category_slug): SubCatCalendarBlockResource
    {
        $result = SubCatCalendar::whereHas('category', function ($q) use ($category_slug) {
            $q->where('slug', '=', $category_slug);
        })->firstOrFail();
        return new SubCatCalendarBlockResource($result);
    }
}
