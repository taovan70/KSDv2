<?php

namespace App\Http\Controllers\Admin\Inertia;

use App\Models\Article;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Inertia\Inertia;
use Inertia\Response;

class ArticleController extends Controller
{
    public function create(Request $request): Response
    {
        return Inertia::render('MakeArticle', ['tokenForArticlePreview' => [
            'cookieName' => 'article-preview',
            'cookieValue' => env('PREVIEW_ARTICLE_TOKEN'),
        ]]);
    }

    public function edit(Article $article): Response
    {
        $article->append('tags_ids');
        $article->load('category');
        $mainPic = $article->getMedia('mainPic');
        $article['mainPic'] = $mainPic;
        $article['tokenForArticlePreview'] = [
            'cookieName' => 'article-preview',
            'cookieValue' => env('PREVIEW_ARTICLE_TOKEN'),
        ];
        $article['category_parents_tree'] = Category::find($article->category_id)->parents;
        return Inertia::render('MakeArticle', ['article' => $article]);
    }
}
