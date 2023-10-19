<?php

namespace App\Http\Controllers\Admin;


use App\Http\Requests\Blocks\Category\EveryoneTalkingAboutRequest;
use App\Models\Blocks\Category\EveryoneTalkingAbout;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

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
        CRUD::column('name')->label(__('table.name'));
        CRUD::addColumn([
            'label' => __('table.article'),
            'type' => 'select',
            'name' => 'article_id',
            'attribute' => 'name',
            'entity' => 'article',
            'wrapper' => [
                'href' => fn($crud, $column, $article, $article_id) => backpack_url("article/{$article_id}/show")
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
        CRUD::setValidation(EveryoneTalkingAboutRequest::class);
        CRUD::field('name')->label(__('table.name'));
        CRUD::addField([
            'name' => 'article_id',
            'label' => __('table.article'),
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
        CRUD::column('name')->label(__('table.name'));
        CRUD::addColumn([
            'label' => __('table.article'),
            'type' => 'select',
            'name' => 'article_id',
            'attribute' => 'name',
            'entity' => 'article',
            'wrapper' => [
                'href' => fn($crud, $column, $article, $article_id) => backpack_url("article/{$article_id}/show")
            ]
        ]);
    }
}
