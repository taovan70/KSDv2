<?php

namespace App\Http\Controllers\Admin\Blocks\SubCategory;

use App\Http\Requests\Blocks\SubCategory\SubCatBehindTheScenesBlockRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class SubCatBehindTheScenesBlockCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class SubCatBehindTheScenesBlockCrudController extends CrudController
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
        CRUD::setModel(\App\Models\Blocks\SubCategory\SubCatBehindTheScenesBlock::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/sub-cat-behind-the-scenes-block');
        CRUD::setEntityNameStrings(__('models.sub_cat_behind_the_scenes_block'), __('models.sub_cat_behind_the_scenes_block'));
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
        CRUD::setValidation(SubCatBehindTheScenesBlockRequest::class);
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
        CRUD::field('video_path')->label(__('models.video_path_id'));
        CRUD::addField([
            'name' => 'article_id',
            'label' => __('table.article'),
            'type' => 'select2_from_ajax',
            'method' => 'POST',
            'data_source' => url('api/articles'),
        ]);

    }

    protected function setupShowOperation()
    {
        $this->setupListOperation();
        CRUD::column('text')->label(__('models.text'));
        CRUD::column('video_path')->label(__('models.video_path_id'));
        CRUD::addColumn([
            'label' => __('table.article'),
            'type' => 'select',
            'name' => 'article_id',
            'attribute' => 'name',
            'entity' => 'article',
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
