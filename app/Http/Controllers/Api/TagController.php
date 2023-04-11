<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\TagService;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class TagController extends Controller
{
    public function fetchTags(Request $request, TagService $service): Collection
    {
       return $service->getTags($request->q);
    }
}
