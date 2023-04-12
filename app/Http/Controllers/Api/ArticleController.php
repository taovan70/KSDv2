<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\ArticleService;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class ArticleController extends Controller
{
    /**
     * @param Request $request
     * @param ArticleService $service
     * @return Collection
     */
    public function fetchAuthors(Request $request, ArticleService $service): Collection
    {
        return $service->getAuthorsBySubSection($request);
    }
}
