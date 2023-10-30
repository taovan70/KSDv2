<?php

namespace App\Http\Controllers\Admin\Blocks\SubCategory;




use App\Http\Requests\Blocks\SubCategory\SubCatCalendarRequest;
use App\Models\Blocks\SubCategory\SubCatCalendar;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class SubCatCalendarCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class SubCatCalendarCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     * 
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(SubCatCalendar::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/sub-cat-calendar');
        CRUD::setEntityNameStrings(__('models.sub_cat_calendar'), __('models.sub_cat_calendar'));
    }

    /**
     * Define what happens when the List operation is loaded.
     * 
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        CRUD::column('name')->label(__('table.name'));
        CRUD::addColumn([
            'label' => __('table.category'),
            'type' => 'select',
            'name' => 'category_id',
            'attribute' => 'name',
            'entity' => 'category',
            'wrapper' => [
                'href' => fn($crud, $column, $article, $category_id) => backpack_url("category/{$category_id}/show")
            ]
        ]);
    }

    /**
     * Define what happens when the Create operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(SubCatCalendarRequest::class);
        CRUD::field('name')->label(__('table.author_fields.name'));
        CRUD::addField([
            'name' => 'category_id',
            'label' => __('table.category'),
            'type' => 'select2_from_ajax',
            'entity' => 'category',
            'attribute' => 'name',
            'data_source' => url('api/categories'),
            'minimum_input_length' => 0,
            'method' => 'POST',
            'include_all_form_fields' => true
        ]);
        CRUD::addField([
            'name'  => 'month_data',
            'label' => __('table.author_fields.social_networks'),
            'type'  => 'repeatable',
            'subfields' => [
                [
                    'name' => 'name',
                    'label' => __('table.author_fields.name'),
                    'type' => 'text',
                ],
                [
                    'name' => 'text',
                    'label' => __('models.text'),
                    'type' => 'ckeditor',
                ],
                [
                    'label' => __('table.article'),
                    'type' => 'select2_from_ajax',
                    'name' => 'article_id',
                    'attribute' => 'name',
                    'entity' => 'article',
                    'method' => 'POST',
                    'data_source' => url('api/articles'),
                ],
            ]
        ]);
    }

    protected function setupShowOperation()
    {
        $this->setupListOperation();
    }

    /**
     * Define what happens when the Update operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-update
     * @return void
     */
    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }
}
