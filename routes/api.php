<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
Route::group([], function () {
    Route::post('article_authors', [\App\Http\Controllers\Api\ArticleController::class, 'fetchAuthors']);

    Route::group(['prefix' => 'tags'], function () {
        Route::post('/', [\App\Http\Controllers\Api\TagController::class, 'fetchTags']);
    });

    Route::group(['prefix' => 'adv-pages'], function () {
        Route::get('/', [\App\Http\Controllers\Api\AdvPageController::class, 'fetchTags'])->name('advPage.index');
        Route::get('/{slug}',  [\App\Http\Controllers\Api\AdvPageController::class, 'show'])->name('advPage.show');
    });

    Route::group(['prefix' => 'categories'], function () {
        Route::post('/', [\App\Http\Controllers\Api\CategoryController::class, 'fetchCategories'])->name('categories.index');
        Route::get('/', [\App\Http\Controllers\Api\CategoryController::class, 'fetchCategories'])->name('categoriesGet.index');
        Route::get('/{slug}',  [\App\Http\Controllers\Api\CategoryController::class, 'show'])->name('categories.show');
    });

    Route::group(['prefix' => 'articles'], function () {
        Route::get('/',  [\App\Http\Controllers\Api\ArticleController::class, 'index'])->name('article.index');
        Route::get('/random/{count}',  [\App\Http\Controllers\Api\ArticleController::class, 'random'])->name('article.random');
        Route::get('/{slug}',  [\App\Http\Controllers\Api\ArticleController::class, 'show'])->name('article.show');
   });

    Route::group(['prefix' => 'authors'], function () {
        Route::post('/', [\App\Http\Controllers\Api\AuthorController::class, 'fetchAuthors'])->name('authors.index');
        Route::get('/{id}',  [\App\Http\Controllers\Api\AuthorController::class, 'show'])->name('authors.show');
    });

    Route::group(['prefix' => 'did_you_know_in_articles'], function () {
        Route::get('/',  [\App\Http\Controllers\Api\DidYouKnowInArticlesController::class, 'index'])->name('didTouKnowInArticle.index');
        Route::get('/random/{count}/{category_id?}',  [\App\Http\Controllers\Api\DidYouKnowInArticlesController::class, 'random'])->name('didTouKnowInArticle.random');
    });

});
