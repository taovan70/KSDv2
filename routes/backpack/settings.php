<?php

use Illuminate\Support\Facades\Route;

Route::group([
    'namespace' => 'App\Http\Controllers\Admin',
    'prefix' => config('backpack.base.route_prefix', 'admin'),
    'middleware' => ['web', backpack_middleware()],
], function () {
    Route::crud(config('backpack.settings.route'), 'CustomSettingCrudController');
});
