<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\api\Author\AuthorResource;
use App\Models\Author;
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

    public function index(): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        $authors = Author::query()
           ->with('categories:id,name,slug')
           ->withCount('articles')
           ->get();
        return AuthorResource::collection($authors);
    }

    public function show(string $id)
    {
        $author = Author::with('categories:id,name,slug')->findOrFail($id);
        return new AuthorResource($author);

    }

}
