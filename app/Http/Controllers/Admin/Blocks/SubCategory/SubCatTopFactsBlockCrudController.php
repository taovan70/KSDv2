<?php

namespace App\Http\Controllers\Admin\Blocks\SubCategory;

use App\Http\Requests\Blocks\SubCategory\SubCatTopFactsBlockRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Backpack\CRUD\app\Library\Widget;

/**
 * Class SubCatTopFactsBlockCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class SubCatTopFactsBlockCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\InlineCreateOperation;
    use \App\Http\Controllers\Admin\Operations\Traits\InlineUpdateOperation;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     *
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\Blocks\SubCategory\SubCatTopFactsBlock::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/sub-cat-top-facts-block');
        CRUD::setEntityNameStrings(__('models.sub_cat_top_facts_block'), __('models.sub_cat_top_facts_block'));

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

        Widget::add([
            'type' => 'view',
            'view'    => 'partials.inlineOperationsModal',
            'content'=> [
                'page' => 'sub-cat-top-facts-block'
            ]
        ]);

        Widget::add([
            'type' => 'script',
            'content'  => 'https://unpkg.com/select2@4.0.13/dist/js/select2.full.min.js',
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
        CRUD::setValidation(SubCatTopFactsBlockRequest::class);
        CRUD::field('name')->label(__('table.name'));
        CRUD::addField([
            'name' => 'background_photo_path',
            'label' => __('models.background_photo_path'),
            'type' => 'upload',
            'upload' => true,
            'disk' => 'public'
        ]);
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
        CRUD::field('text')->label(__('models.text'));
        CRUD::field('number_one')->label(__('models.number_one'));
        CRUD::field('text_one')->label(__('models.text_one'));
        CRUD::field('number_two')->label(__('models.number_two'));
        CRUD::field('text_two')->label(__('models.text_two'));
        CRUD::field('number_three')->label(__('models.number_three'));
        CRUD::field('text_three')->label(__('models.text_three'));

        CRUD::addField([
            'name' => 'article_one',
            'label' => __('models.article_one'),
            'type' => 'select2_from_ajax',
            'method' => 'POST',
            'data_source' => url('api/articles'),
        ]);

        CRUD::addField([
            'name' => 'article_two',
            'label' => __('models.article_two'),
            'type' => 'select2_from_ajax',
            'method' => 'POST',
            'data_source' => url('api/articles'),
        ]);
    }

    protected function setupShowOperation()
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
        CRUD::addColumn([
            'name' => 'background_photo_path',
            'label' => __('models.background_photo_path'),
            'type' => 'image',
            'prefix' => 'storage/',
            'width' => '100px',
            'height' => '100px'
        ]);
        CRUD::column('text')->label(__('models.text'));
        CRUD::column('number_one')->label(__('models.number_one'));
        CRUD::column('text_one')->label(__('models.text_one'));
        CRUD::column('number_two')->label(__('models.number_two'));
        CRUD::column('text_two')->label(__('models.text_two'));
        CRUD::column('number_three')->label(__('models.number_three'));
        CRUD::column('text_three')->label(__('models.text_three'));

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
