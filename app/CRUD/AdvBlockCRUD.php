<?php

namespace App\CRUD;

use App\Models\AdvPage;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanel;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Illuminate\Database\Eloquent\Builder;

class AdvBlockCRUD extends CrudPanelFacade
{
    public static function advFilter(CrudPanel $crud): void
    {
        CRUD::addFilter([
            'type' => 'adv_custom_select_filter',
            'label' => __('table.tags'),
            'name' => 'adv_block_filter'
        ], function () {
            return AdvPage::all();
        }, function () use ($crud) {
                $page = $crud->getRequest()->query('page');
                $colorType = $crud->getRequest()->query('color_type');
                $deviceType = $crud->getRequest()->query('device_type');
                $pageId = AdvPage::where('slug', $page)?->first()?->id;
                if (!empty($pageId)) {
                    $crud->addClause(function (Builder $query) use ($pageId, $colorType, $deviceType) {
                        return $query->whereHas(
                            'advPage',
                            function (Builder $query) use ($pageId, $colorType, $deviceType) {
                                $res = $query->where('adv_page_id', $pageId);
                                if (!empty($colorType)) {
                                    $res = $res->where('color_type', $colorType);
                                }
                                if (!empty($deviceType)) {
                                    $res = $res->where('device_type', $deviceType);
                                }
                                return $res;
                            }
                        );
                    });
                }
            }
        );
    }
}
