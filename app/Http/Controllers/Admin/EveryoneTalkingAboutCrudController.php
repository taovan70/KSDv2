<?php

namespace App\Http\Controllers\Admin;


use App\Http\Requests\Blocks\Category\EveryoneTalkingAboutRequest;
use App\Models\Blocks\Category\EveryoneTalkingAbout;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Backpack\CRUD\app\Library\Widget;

/**
 * Class EveryoneTalkingAboutCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class EveryoneTalkingAboutCrudController extends CrudController
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
        CRUD::setModel(EveryoneTalkingAbout::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/everyone-talking-about');
        CRUD::setEntityNameStrings(__('models.everyone_talking_about'), __('models.everyone_talking_about'));
    }

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        CRUD::column('name')->label(__('table.name'))->limit(70);
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
                'page' => 'everyone-talking-about'
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
        CRUD::setValidation(EveryoneTalkingAboutRequest::class);
        CRUD::field('name')->label(__('table.name'));
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

        /**
         * Fields can be defined using the fluent syntax:
         * - CRUD::field('price')->type('number');
         */
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
                'data-url' => fn($crud, $column, $article, $category_id) => (env('FRONT_URL')."/article-preview-".$article['article_one']['id']),
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
                'data-url' => fn($crud, $column, $article, $category_id) => (env('FRONT_URL')."/article-preview-".$article['article_two']['id']),
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
                'data-url' => fn($crud, $column, $article, $category_id) => (env('FRONT_URL')."/article-preview-".$article['article_three']['id']),
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
                'data-url' => fn($crud, $column, $article, $category_id) => (env('FRONT_URL')."/article-preview-".$article['article_four']['id']),
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
                'data-url' => fn($crud, $column, $article, $category_id) => (env('FRONT_URL')."/article-preview-".$article['article_five']['id']),
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
                'data-url' => fn($crud, $column, $article, $category_id) => (env('FRONT_URL')."/article-preview-".$article['article_six']['id']),
            ],
            'limit'=> 100,
        ]);
    }
}
