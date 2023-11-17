<?php

namespace App\Http\Controllers\Admin\Blocks\SubCategory;

use App\Http\Requests\Blocks\SubCategory\SubCatKnowMoreAboutEachBlockRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class SubCatKnowMoreAboutEachBlockCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class SubCatKnowMoreAboutEachBlockCrudController extends CrudController
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
        CRUD::setModel(\App\Models\Blocks\SubCategory\SubCatKnowMoreAboutEachBlock::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/sub-cat-know-more-about-each-block');
        CRUD::setEntityNameStrings(__('models.sub_cat_know_more_about_each_block'), __('models.sub_cat_know_more_about_each_block'));
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
    }

    /**
     * Define what happens when the Create operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(SubCatKnowMoreAboutEachBlockRequest::class);
        CRUD::field('name')->label(__('table.author_fields.name'));
        CRUD::addField([
            'name'  => 'block_data',
            'label' => __('table.author_fields.items'),
            'type'  => 'repeatable',
            'subfields' => [
                [
                    'name' => 'name',
                    'label' => __('table.author_fields.name'),
                    'type' => 'text',
                ],
                [
                    'name' => 'photo_path',
                    'label' => __('table.author_fields.photo'),
                    'type' => 'upload',
                    'upload' => true,
                    'disk' => 'public'
                ],
                [
                    'label' => __('models.article_one'),
                    'type' => 'select2_from_ajax',
                    'name' => 'article_one_id',
                    'attribute' => 'name',
                    'entity' => 'article',
                    'method' => 'POST',
                    'data_source' => url('api/articles'),
                ],
                [
                    'label' => __('models.article_two'),
                    'type' => 'select2_from_ajax',
                    'name' => 'article_two_id',
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
