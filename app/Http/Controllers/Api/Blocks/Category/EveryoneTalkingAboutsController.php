<?php

namespace App\Http\Controllers\Api\Blocks\Category;


use App\Http\Controllers\Controller;
use App\Http\Resources\api\Blocks\Category\EveryoneTalkingAboutResource;
use App\Models\Blocks\Category\EveryoneTalkingAbout;


class EveryoneTalkingAboutsController extends Controller
{

    public function __construct()
    {
    }

    public function index(): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        $result = EveryoneTalkingAbout::with('article')
            ->orderBy('lft', 'ASC')
            ->take(3)
            ->get();

        return EveryoneTalkingAboutResource::collection($result);
    }
}