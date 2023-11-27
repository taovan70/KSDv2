<?php

namespace App\Http\Controllers\Admin\Blocks\SubCategory;



use App\Http\Requests\Blocks\SubCategory\SubCatGameTwoBlockRequest;
use App\Models\Blocks\SubCategory\SubCatGameTwoBlock;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Backpack\CRUD\app\Library\Widget;

/**
 * Class SubCatGameOneBlockCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class SubCatGameTwoBlockCrudController extends CrudController
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
        CRUD::setModel(SubCatGameTwoBlock::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/sub-cat-game-two-block');
        CRUD::setEntityNameStrings("Pregunta", "Preguntas");
    }

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        CRUD::column('question')->label(__('models.question'))->limit(70);
        CRUD::addColumn([
            'label' => __('table.category'),
            'type' => 'select',
            'name' => 'category_id',
            'attribute' => 'name',
            'entity' => 'category',
            'wrapper' => [
                'href' => fn ($crud, $column, $article, $category_id) => backpack_url("category/{$category_id}/show")
            ],
            'limit'=> 70,
        ]);

        Widget::add([
            'type' => 'view',
            'view'    => 'partials.inlineOperationsModal',
            'content'=> [
                'page' => 'sub-cat-game-two-block'
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
        CRUD::setValidation(SubCatGameTwoBlockRequest::class);
        CRUD::field('title')->label(__('table.title'));
        CRUD::field('description')->label(__('table.description'));
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
            'name' => 'article_id',
            'label' => __('table.article'),
            'type' => 'select2_from_ajax',
            'method' => 'POST',
            'data_source' => url('api/articles'),
        ]);
        CRUD::field('question')->label(__('models.question'));
        CRUD::addField([
            'name' => 'photo_path_one',
            'label' => __('table.author_fields.photo'),
            'type' => 'upload',
            'upload' => true,
            'disk' => 'public'
        ]);
        CRUD::addField([
            'name' => 'photo_path_two',
            'label' => __('table.author_fields.photo'),
            'type' => 'upload',
            'upload' => true,
            'disk' => 'public'
        ]);
        CRUD::addField([
            'name'  => 'answer_data',
            'label' => __('models.answer'),
            'type'  => 'repeatable',
            'subfields' => [
                [
                    'name' => 'answer',
                    'label' => __('models.answer'),
                    'type' => 'text',
                ],
                [
                    'name' => 'is_correct',
                    'label' => __('models.is_correct'),
                    'type' => 'select_from_array',
                    'allows_null' => false,
                    'options' => [
                        true => __('models.correct'),
                        false => __('models.incorrect'),
                    ],
                    'default' => 0
                ],
            ]
        ]);
    }

    protected function setupShowOperation()
    {
        $this->setupListOperation();
        CRUD::column('title')->label(__('table.title'));
        CRUD::column('description')->label(__('table.description'));
        CRUD::addColumn([
            'label' => __('table.article'),
            'name' => 'article_id',
            'attribute' => 'name',
            'entity' => 'article',
            'wrapper' => [
                'href' => 'javascript:void(0);',
                'onclick' => 'setPreviewCookie();window.open(this.dataset.url,`_blank`);return false;',
                'data-url' => fn($crud, $column, $article, $category_id) => (env('FRONT_URL')."/article-preview-".$article['id']),
            ],
            'limit'=> 100,
        ]);
        CRUD::addColumn([
            'name' => 'photo_path_one',
            'label' => __('table.author_fields.photo'),
            'type' => 'image',
            'prefix' => 'storage/',
            'width' => '100px',
            'height' => '100px'
        ]);
        CRUD::addColumn([
            'name' => 'photo_path_two',
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
