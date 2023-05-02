<?php

use Illuminate\Support\Facades\Route;

// --------------------------
// Custom Backpack Routes
// --------------------------
// This route file is loaded automatically by Backpack\Base.
// Routes you generate using Backpack\Generators will be placed here.

Route::group([
    'prefix'     => config('backpack.base.route_prefix', 'admin'),
    'middleware' => array_merge(
        (array) config('backpack.base.web_middleware', 'web'),
        (array) config('backpack.base.middleware_key', 'admin')
    ),
    'namespace'  => 'App\Http\Controllers\Admin',
], function () { // custom admin routes
    Route::crud('tag', 'TagCrudController');
    Route::crud('category', 'CategoryCrudController');
    Route::crud('author', 'AuthorCrudController');
    Route::crud('user', 'UserCrudController');

    // Roles available: admin, manager, guest
    Route::group(['middleware' => ['role:admin']], function () {
        Route::crud('article', 'ArticleCrudController');
        Route::crud('log-user-event', 'LogUserEventCrudController');
    });

    Route::get('file_manager', 'FileManagerController@index')->name('page.file_manager.index');
    Route::crud('adv-block', 'AdvBlockCrudController');
    Route::crud('adv-page', 'AdvPageCrudController');
}); // this should be the absolute last line of this file
