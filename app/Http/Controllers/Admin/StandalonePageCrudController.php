<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\StandalonePageRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class StandalonePageCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class StandalonePageCrudController extends CrudController
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
        CRUD::setModel(\App\Models\StandalonePage::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/standalone-page');
        CRUD::setEntityNameStrings(__('models.standalone_pages'), __('models.standalone_pages'));
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
        CRUD::column('main_text')->label(__('models.main_text'));
        CRUD::column('add_text')->label(__('models.add_text'));
    }

    /**
     * Define what happens when the Create operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(StandalonePageRequest::class);
        CRUD::field('name')->label(__('table.name'));
        CRUD::field('main_text')->label(__('models.main_text'));
        CRUD::field('add_text')->label(__('models.add_text'));
        CRUD::addField([
            'name' => 'photo_path',
            'label' => __('table.author_fields.photo'),
            'type' => 'upload',
            'upload' => true,
            'disk' => 'public'
        ]);
    }

    protected function setupShowOperation()
    {
        CRUD::addColumn([
            'name' => 'photo_path',
            'label' => __('table.author_fields.photo'),
            'type' => 'image',
            'prefix' => 'storage/',
            'width' => '100px',
            'height' => '100px'
        ]);
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
