<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Backpack\Settings\app\Http\Controllers\SettingCrudController;

class CustomSettingCrudController extends SettingCrudController
{
    public function setup()
    {
        CRUD::setModel(\App\Models\CustomSetting::class);
        CRUD::setEntityNameStrings(
            trans('backpack::settings.setting_singular'),
            trans('backpack::settings.setting_plural')
        );
        CRUD::setRoute(backpack_url(config('backpack.settings.route')));
    }
}
