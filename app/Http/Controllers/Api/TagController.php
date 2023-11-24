<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\api\Tag\TagResource;
use App\Models\Tag;
use App\Services\TagService;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class TagController extends Controller
{
    public function fetchTags(Request $request, TagService $service): Collection
    {
       return $service->getTags($request->q);
    }

    public function index(): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        $tags = Tag::filter()->get();
        return TagResource::collection($tags);
    }
}
