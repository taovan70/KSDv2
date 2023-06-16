<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\AuthorService;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class AuthorController extends Controller
{
    /**
     * @param Request $request
     * @param AuthorService $service
     * @return Collection
     */
    public function fetchAuthors(Request $request, AuthorService $service): Collection
    {
        return $service->getAuthors($request->q);
    }

}
