<?php

namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use App\Http\Resources\api\StandalonePageResource;
use App\Models\StandalonePage;

class StandalonePageController extends Controller
{

    public function show(string $slug)
    {
        $page = StandalonePage::where('slug', $slug)->first();
        return new StandalonePageResource($page);
    }
}
