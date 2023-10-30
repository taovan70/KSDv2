<?php

namespace App\Http\Controllers\Admin\Blocks\SubCategory;



use App\Http\Requests\Blocks\SubCategory\SubCatExpertAdviceRequest;
use App\Models\Blocks\SubCategory\SubCatExpertAdvice;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class SubCatExpertAdviceCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class SubCatExpertAdviceCrudController extends CrudController
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
        CRUD::setModel(SubCatExpertAdvice::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/sub-cat-expert-advice');
        CRUD::setEntityNameStrings(__('models.sub_cat_expert_advice'), __('models.sub_cat_expert_advice'));
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
        CRUD::column('text')->label(__('models.text'));
    }

    /**
     * Define what happens when the Create operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(SubCatExpertAdviceRequest::class);
        CRUD::field('text')->label(__('models.text'));
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