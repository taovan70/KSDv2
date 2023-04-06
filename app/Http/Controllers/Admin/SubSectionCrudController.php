<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\SubSection\SubSectionStoreRequest;
use App\Http\Requests\SubSection\SubSectionUpdateRequest;
use App\Models\SubSection;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Illuminate\Support\Str;

/**
 * Class SubSectionCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class SubSectionCrudController extends CrudController
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
        CRUD::setModel(\App\Models\SubSection::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/sub-section');
        CRUD::setEntityNameStrings(__('models.sub_section'), __('models.sub_sections'));
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
        CRUD::column('created_at')->label(__('table.created'));
        CRUD::addColumn([
            'label' => __('table.section'),
            'type' => 'select',
            'name' => 'section_id',
            'attribute' => 'name'
        ]);

        /**
         * Columns can be defined using the fluent syntax or array syntax:
         * - CRUD::column('price')->type('number');
         * - CRUD::addColumn(['name' => 'price', 'type' => 'number']); 
         */
    }

    /**
     * Define what happens when the Create operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(SubSectionStoreRequest::class);

        CRUD::field('name')->label(__('table.name'));
        CRUD::addField([
            'label' => __('table.section'),
            'name' => 'section_id',
            'type' => 'select_grouped',
            'attribute' => 'name',
            'entity' => 'section',
            'group_by' => 'subject',
            'group_by_attribute' => 'name',
            'group_by_relationship_back' => 'sections'
        ]);

        SubSection::creating(function (SubSection $subSection) {
            $subSection->slug = Str::slug($subSection->name);
        });

        /**
         * Fields can be defined using the fluent syntax or array syntax:
         * - CRUD::field('price')->type('number');
         * - CRUD::addField(['name' => 'price', 'type' => 'number'])); 
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
        CRUD::setValidation(SubSectionUpdateRequest::class);

        CRUD::field('name')->label(__('table.name'));
        CRUD::addField([
            'label' => __('table.section'),
            'name' => 'section_id',
            'type' => 'select_grouped',
            'attribute' => 'name',
            'entity' => 'section',
            'group_by' => 'subject',
            'group_by_attribute' => 'name',
            'group_by_relationship_back' => 'sections'
        ]);

        SubSection::creating(function (SubSection $subSection) {
            $subSection->slug = Str::slug($subSection->name);
        });
    }

    protected function setupShowOperation()
    {
        $this->setupListOperation();
    }
}
