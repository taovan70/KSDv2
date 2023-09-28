<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\api\AdvPage\AdvPageResource;
use App\Models\AdvPage;
use App\Services\TagService;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class AdvPageController extends Controller
{

    public function fetchAdvPages(Request $request, TagService $service): Collection
    {
        return $service->getTags($request->q);
    }

    public function show(string $slug)
    {
        $advPage = AdvPage::where('slug', $slug)->with('advBlocks')->firstOrFail();
        // dd($advPage);
        return new AdvPageResource($advPage);
    }

}
