<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Blocks\Category\QACategoryRequest;
use App\Models\Blocks\Category\QACategory;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Backpack\CRUD\app\Library\Widget;

/**
 * Class QACategoryCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class QACategoryCrudController extends CrudController
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
        CRUD::setModel(QACategory::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/q-a-category');
        CRUD::setEntityNameStrings(__('models.q-a-category'), __('models.q-a-category'));
    }

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        CRUD::addColumn([
            'label' => __('table.category'),
            'type' => 'select',
            'name' => 'category_id',
            'attribute' => 'name',
            'entity' => 'category',
            'wrapper' => [
                'href' => fn ($crud, $column, $article, $category_id) => backpack_url("category/{$category_id}/show")
            ]
        ]);

        Widget::add([
            'type' => 'view',
            'view'    => 'partials.inlineOperationsModal',
            'content'=> [
                'page' => 'q-a-category'
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
        CRUD::setValidation(QACategoryRequest::class);
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
            'name' => 'article_one_id',
            'label' => __('models.article_one'),
            'type' => 'select2_from_ajax',
            'method' => 'POST',
            'data_source' => url('api/articles'),
        ]);
        CRUD::addField([
            'name' => 'article_two_id',
            'label' => __('models.article_two'),
            'type' => 'select2_from_ajax',
            'method' => 'POST',
            'data_source' => url('api/articles'),
        ]);
        CRUD::addField([
            'name' => 'article_three_id',
            'label' => __('models.article_three'),
            'type' => 'select2_from_ajax',
            'method' => 'POST',
            'data_source' => url('api/articles'),
        ]);
        CRUD::addField([
            'name' => 'article_four_id',
            'label' => __('models.article_four'),
            'type' => 'select2_from_ajax',
            'method' => 'POST',
            'data_source' => url('api/articles'),
        ]);
        CRUD::addField([
            'name' => 'article_five_id',
            'label' => __('models.article_five'),
            'type' => 'select2_from_ajax',
            'method' => 'POST',
            'data_source' => url('api/articles'),
        ]);
        CRUD::addField([
            'name' => 'article_six_id',
            'label' => __('models.article_six'),
            'type' => 'select2_from_ajax',
            'method' => 'POST',
            'data_source' => url('api/articles'),
        ]);
    }

    protected function setupShowOperation()
    {
        $this->setupListOperation();
        CRUD::addColumn([
            'label' => __('models.article_one'),
            'name' => 'article_one_id',
            'attribute' => 'name',
            'entity' => 'article_one',
            'wrapper' => [
                'href' => 'javascript:void(0);',
                'onclick' => 'setPreviewCookie();window.open(this.dataset.url,`_blank`);return false;',
                'data-url' => fn($crud, $column, $article, $category_id) => (env('FRONT_URL')."/".$article['article_one']['slug']),
            ],
            'limit'=> 100,
        ]);
        CRUD::addColumn([
            'label' => __('models.article_two'),
            'name' => 'article_two_id',
            'attribute' => 'name',
            'entity' => 'article_two',
            'wrapper' => [
                'href' => 'javascript:void(0);',
                'onclick' => 'setPreviewCookie();window.open(this.dataset.url,`_blank`);return false;',
                'data-url' => fn($crud, $column, $article, $category_id) => (env('FRONT_URL')."/".$article['article_two']['slug']),
            ],
            'limit'=> 100,
        ]);
        CRUD::addColumn([
            'label' => __('models.article_three'),
            'name' => 'article_three_id',
            'attribute' => 'name',
            'entity' => 'article_three',
            'wrapper' => [
                'href' => 'javascript:void(0);',
                'onclick' => 'setPreviewCookie();window.open(this.dataset.url,`_blank`);return false;',
                'data-url' => fn($crud, $column, $article, $category_id) => (env('FRONT_URL')."/".$article['article_three']['slug']),
            ],
            'limit'=> 100,
        ]);
        CRUD::addColumn([
            'label' => __('models.article_four'),
            'name' => 'article_four_id',
            'attribute' => 'name',
            'entity' => 'article_four',
            'wrapper' => [
                'href' => 'javascript:void(0);',
                'onclick' => 'setPreviewCookie();window.open(this.dataset.url,`_blank`);return false;',
                'data-url' => fn($crud, $column, $article, $category_id) => (env('FRONT_URL')."/".$article['article_four']['slug']),
            ],
            'limit'=> 100,
        ]);
        CRUD::addColumn([
            'label' => __('models.article_five'),
            'name' => 'article_five_id',
            'attribute' => 'name',
            'entity' => 'article_five',
            'wrapper' => [
                'href' => 'javascript:void(0);',
                'onclick' => 'setPreviewCookie();window.open(this.dataset.url,`_blank`);return false;',
                'data-url' => fn($crud, $column, $article, $category_id) => (env('FRONT_URL')."/".$article['article_five']['slug']),
            ],
            'limit'=> 100,
        ]);
        CRUD::addColumn([
            'label' => __('models.article_six'),
            'name' => 'article_six_id',
            'attribute' => 'name',
            'entity' => 'article_six',
            'wrapper' => [
                'href' => 'javascript:void(0);',
                'onclick' => 'setPreviewCookie();window.open(this.dataset.url,`_blank`);return false;',
                'data-url' => fn($crud, $column, $article, $category_id) => (env('FRONT_URL')."/".$article['article_six']['slug']),
            ],
            'limit'=> 100,
        ]);
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
