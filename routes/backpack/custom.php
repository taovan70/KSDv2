<?php

use App\Http\Controllers\Admin\FileManagerController;
use App\Http\Controllers\Api\ArticleController;
use App\Http\Controllers\Api\SettingController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\LangController;
use Illuminate\Support\Facades\Route;

// --------------------------
// Custom Backpack Routes
// --------------------------
// This route file is loaded automatically by Backpack\Base.
// Routes you generate using Backpack\Generators will be placed here.

Route::group([
    'prefix' => config('backpack.base.route_prefix', 'admin'),
    'middleware' => array_merge(
        (array)config('backpack.base.web_middleware', 'web'),
        (array)config('backpack.base.middleware_key', 'admin')
    ),
    'namespace' => 'App\Http\Controllers\Admin',
], function () { // custom admin routes
    Route::crud('tag', 'TagCrudController');
    Route::crud('category', 'CategoryCrudController');
    Route::crud('author', 'AuthorCrudController');
    Route::crud('user', 'UserCrudController');

    Route::get('make-article', [App\Http\Controllers\Admin\Inertia\ArticleController::class, 'create'])->name('article.make-article');
    Route::get('article/{article}/edit', [App\Http\Controllers\Admin\Inertia\ArticleController::class, 'edit']);
    Route::post('article/store', [ArticleController::class, 'store']);
    Route::post('article/preview', [ArticleController::class, 'preview']);
    Route::post('article/{article}/update', [ArticleController::class, 'update']);
    Route::post('article/{article}/update-preview', [ArticleController::class, 'updatePreview']);
    Route::get('settings-info', [SettingController::class, 'index']);

    // Roles available: admin, manager, guest
    Route::group(['middleware' => ['role:admin']], function () {
        Route::crud('article', 'ArticleCrudController');
        Route::crud('log-user-event', 'LogUserEventCrudController');
    });

    Route::get('file_manager', [FileManagerController::class, 'index'])->name('page.file_manager.index');
    Route::post('image/store', [ImageController::class, 'tempStore']);
    Route::crud('adv-block', 'AdvBlockCrudController');
    Route::crud('adv-page', 'AdvPageCrudController');

    Route::post('language',  [LangController::class, 'setLanguage'])->name('user.set_language');;

    Route::crud('did-you-know-in-articles', 'DidYouKnowInArticlesCrudController');
    Route::crud('popular-categories', 'PopularCategoriesCrudController');
    Route::crud('most-talked-article', 'MostTalkedArticleCrudController');
}); // this should be the absolute last line of this file