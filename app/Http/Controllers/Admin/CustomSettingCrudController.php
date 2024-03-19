<?php

namespace App\Http\Controllers\Admin;

use App\Models\Setting;
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

    public function setupListOperation()
    {
        // only show settings which are marked as active
        CRUD::addClause('where', 'active', 1);

        // columns to show in the table view
        CRUD::setColumns([
            [
                'name'  => 'name',
                'label' => trans('backpack::settings.name'),
                'limit' => 255
            ],
            [
                'name'  => 'value',
                'label' => trans('backpack::settings.value'),
            ],
            [
                'name'  => 'description',
                'label' => trans('backpack::settings.description'),
            ],
        ]);
    }


    public function index()
    {
        $commonSettingsKeys = ["user_logging_all", "user_logging_auth", "user_logging_on_model", "subscribe_telegram_channel", "subscribe_vk_channel"];
        $viewSettingsKeys = [
            "text_on_article_image_preview",
            "hide_theme_change_button",
            "buttons_type_on_site",
            "article_content_button_behavior",
            "footer_bottom_copyright_text",
            "site_logo_day",
            "site_logo_night",
        ];
        $this->crud->hasAccessOrFail('list');
        $this->crud->setDefaultPageLength(100);

        $this->data['crud'] = $this->crud;
        $this->data['title'] = $this->crud->getTitle() ?? mb_ucfirst($this->crud->entity_name_plural);
        $this->data['settings'] = Setting::all();
        $this->data['settingCommon'] = Setting::all()->filter()->whereIn('key', $commonSettingsKeys);
        $this->data['settingView'] = Setting::all()->filter()->whereIn('key', $viewSettingsKeys);

        // load the view from /resources/views/vendor/backpack/crud/ if it exists, otherwise load the one in the package
        return view('vendor.backpack.crud.settings-list', $this->data);
    }
}
