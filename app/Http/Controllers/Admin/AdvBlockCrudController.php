<?php

namespace App\Http\Controllers\Admin;

use App\CRUD\AdvBlockCRUD;
use App\Http\Requests\AdvBlock\AdvBlockStoreRequest;
use App\Models\AdvBlock;
use App\Models\AdvPage;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanel;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;


/**
 * Class AdvBlockCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class AdvBlockCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\BulkDeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     *
     * @return void
     */
    public function setup()
    {
        $pageName = request()->query('page');
        $advPage = AdvPage::where('slug', $pageName)->first();
        $pageName = !empty($advPage) ?  ' - '.$advPage->name : '';
        CRUD::setModel(AdvBlock::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/adv-block');
        CRUD::setEntityNameStrings(__('models.adv-block'), __('models.adv-blocks'));
    }

    /**
     * Display all rows in the database for this entity.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $this->crud->hasAccessOrFail('list');

        $this->data['crud'] = $this->crud;
        $this->data['title'] = $this->crud->getTitle() ?? mb_ucfirst($this->crud->entity_name_plural);
        $pages = AdvPage::all();

        // load the view from /resources/views/vendor/backpack/crud/ if it exists, otherwise load the one in the package
        return view('vendor.backpack.crud.adv-block-list', array_merge($this->data, compact('pages')));
    }

    /**
     * Add the default settings, buttons, etc that this operation needs.
     */
    protected function setupCreateDefaults()
    {
        $this->crud->allowAccess('create');

        $this->crud->operation('create', function () {
            $this->crud->loadDefaultOperationSettingsFromConfig();
            $this->crud->setupDefaultSaveActions();
        });

        $this->crud->operation('list', function () {
            $this->crud->addButton('top', 'create', 'view', 'crud::buttons.create-adv-block');
        });
    }

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        AdvBlockCRUD::advFilter($this->crud);

        CRUD::column('name')->label(__('table.name'));
        CRUD::column('active')
            ->value(function ($entry) {
                return $entry->active ? __('table.yes') : __('table.no');
            })
            ->label(__('table.adv_block_fields.active'));
        CRUD::column('device_type')->label(__('table.adv_block_fields.device_type'));
        CRUD::column('color_type')->label(__('table.adv_block_fields.color_type'));
        CRUD::column('adv_page_id')
            ->value(function ($entry) {
                return $entry->advPage->name;
            })
            ->label(__('table.adv_block_fields.page'));
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
        CRUD::setValidation(AdvBlockStoreRequest::class);
        $pageName = request()->query('page');
        if (!empty($pageName)) {
            $advPage = AdvPage::where('slug', $pageName)->first();
        } else {
            $advBlockId = request()->route("id");
            if (!empty($advBlockId)) {
                $advBlock = AdvBlock::where('id', $advBlockId)->first();
                $advPage = AdvPage::where('id', $advBlock->adv_page_id)->first();
            }
        }

        CRUD::field('name')->label(__('table.name'));
        CRUD::field('description')->label(__('table.adv_block_fields.description'));
        CRUD::field('content')->type('textarea')->label(__('table.adv_block_fields.content'));
        CRUD::field('active')->label(__('table.adv_block_fields.active'));
        CRUD::addField([
            'name' => 'device_type',
            'label' => __('table.adv_block_fields.device_type'),
            'type' => 'select_from_array',
            'allows_null' => false,
            'options' => [
                AdvBlock::PC => "pc",
                AdvBlock::MOBILE => "mobile",
            ],
            'default' => AdvBlock::PC
        ]);
        CRUD::addField([
            'name' => 'color_type',
            'label' => __('table.adv_block_fields.color_type'),
            'type' => 'select_from_array',
            'allows_null' => false,
            'options' => [
                AdvBlock::DAY => "day",
                AdvBlock::NIGHT => "night",
            ],
            'default' => AdvBlock::DAY
        ]);

        CRUD::addField([
            'name' => 'adv_page_id',
            'entity' => 'advPage',
            'label' => __('table.adv_block_fields.page'),
            'type' => 'select2',
            'attribute' => 'name',
            'default' => !empty($advPage) ? $advPage->id : null,
        ]);
        /**
         * Fields can be defined using the fluent syntax or array syntax:
         * - CRUD::field('price')->type('number');
         * - CRUD::addField(['name' => 'price', 'type' => 'number']));
         */
    }

    protected function setupShowOperation()
    {
        $this->setupListOperation();
        CRUD::column('name')->type("textarea")->label(__('table.name'));
        CRUD::column('content')->type("textarea")->label(__('table.adv_block_fields.content'));
        CRUD::column('description')->type("textarea")->label(__('table.adv_block_fields.description'));
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
