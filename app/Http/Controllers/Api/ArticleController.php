<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\ArticleService;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    public function fetchAuthors(Request $request, ArticleService $service)
    {
        return $service->getAuthorsBySubSection($request);
    }
}
