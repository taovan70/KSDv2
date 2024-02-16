<?php

namespace App\Http\Controllers\Admin\Blocks\SubCategory;

use App\Http\Requests\Blocks\SubCategory\SubCatEncyclopediaBlockRequest;
use App\Models\Article;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Backpack\CRUD\app\Library\Widget;

/**
 * Class SubCatEncyclopediaBlockCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class SubCatEncyclopediaBlockCrudController extends CrudController
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
        CRUD::setModel(\App\Models\Blocks\SubCategory\SubCatEncyclopediaBlock::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/sub-cat-encyclopedia-block');
        CRUD::setEntityNameStrings(__('models.sub_cat_encyclopedia_block'), __('models.sub_cat_encyclopedia_block'));
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
                'href' => fn($crud, $column, $article, $category_id) => backpack_url("category/{$category_id}/show")
            ],
            'limit'=> 70,
        ]);

        Widget::add([
            'type' => 'view',
            'view'    => 'partials.inlineOperationsModal',
            'content'=> [
                'page' => 'sub-cat-encyclopedia-block'
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
        CRUD::setValidation(SubCatEncyclopediaBlockRequest::class);
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
        CRUD::field('text')->label(__('models.text'));
        CRUD::addField([
            'name' => 'photo_path',
            'label' => __('table.author_fields.photo'),
            'type' => 'upload',
            'upload' => true,
            'disk' => 'public'
        ]);
        CRUD::addField([
            'label' => __('models.article_one'),
            'type' => 'select2_from_ajax',
            'name' => 'article_one_id',
            'attribute' => 'name',
            'entity' => 'article_one',
            'method' => 'POST',
            'data_source' => url('api/articles'),
        ]);
        CRUD::addField([
            'label' => __('models.article_two'),
            'type' => 'select2_from_ajax',
            'name' => 'article_two_id',
            'attribute' => 'name',
            'entity' => 'article_two',
            'method' => 'POST',
            'data_source' => url('api/articles'),
        ]);
        CRUD::addField([
            'label' => __('models.article_three'),
            'type' => 'select2_from_ajax',
            'name' => 'article_three_id',
            'attribute' => 'name',
            'entity' => 'article_three',
            'method' => 'POST',
            'data_source' => url('api/articles'),
        ]);
        CRUD::addField([
            'label' => __('models.article_four'),
            'type' => 'select2_from_ajax',
            'name' => 'article_four_id',
            'attribute' => 'name',
            'entity' => 'article_four',
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
        CRUD::column('text')->label(__('models.text'));
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
        ]);

        CRUD::addColumn([
            'name' => 'photo_path',
            'label' => __('table.author_fields.photo'),
            'type' => 'image',
            'prefix' => 'storage/',
            'width' => '100px',
            'height' => '100px'
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
