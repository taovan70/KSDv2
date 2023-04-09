<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Section\SectionStoreRequest;
use App\Http\Requests\Section\SectionUpdateRequest;
use App\Models\Section;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Illuminate\Support\Str;

/**
 * Class SubjectCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class SectionCrudController extends CrudController
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
        CRUD::setModel(\App\Models\Section::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/section');
        CRUD::setEntityNameStrings(__('models.section'), __('models.sections'));
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
            'label' => __('table.subject'),
            'type' => 'select',
            'name' => 'subject_id',
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
        CRUD::setValidation(SectionStoreRequest::class);

        CRUD::field('name')->label(__('table.name'));
        CRUD::addField([
            'label' => __('table.subject'),
            'name' => 'subject_id',
            'type' => 'select_grouped',
            'attribute' => 'name',
            'entity' => 'subject',
            'group_by' => 'category',
            'group_by_attribute' => 'name',
            'group_by_relationship_back' => 'subjects'
        ]);

        Section::creating(function (Section $section) {
            $section->slug = Str::slug($section->name);
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
        CRUD::setValidation(SectionUpdateRequest::class);

        CRUD::field('name')->label(__('table.name'));
        CRUD::addField([
            'label' => __('table.subject'),
            'name' => 'subject_id',
            'type' => 'select_grouped',
            'attribute' => 'name',
            'entity' => 'subject',
            'group_by' => 'category',
            'group_by_attribute' => 'name',
            'group_by_relationship_back' => 'subjects'
        ]);

        Section::updating(function (Section $section) {
            $section->slug = Str::slug($section->name);
        });
    }

    protected function setupShowOperation()
    {
        $this->setupListOperation();

        CRUD::addColumn([
            'label' => __('table.sub_sections'),
            'type' => 'select_multiple',
            'name' => 'subSections',
            'attribute' => 'name'
        ]);
    }
}
