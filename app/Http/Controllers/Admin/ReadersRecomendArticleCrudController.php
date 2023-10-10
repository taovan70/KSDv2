<?php

namespace App\Http\Controllers\Admin;


use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use App\Http\Requests\Blocks\MainPage\ReadersRecomendArticleRequest;
use App\Models\Blocks\MainPage\ReadersRecomendArticle;

/**
 * Class ReadersRecomendArticleCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class ReadersRecomendArticleCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ReorderOperation;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     * 
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(ReadersRecomendArticle::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/readers-recomend-article');
        CRUD::setEntityNameStrings(__('models.readers_recomend_articles'), __('models.readers_recomend_articles'));
    }

    protected function setupReorderOperation()
    {
        // define which model attribute will be shown on draggable elements
        $this->crud->set('reorder.label', 'name');
        // define how deep the admin is allowed to nest the items
        // for infinite levels, set it to 0
        // $this->crud->set('reorder.max_level', 0);
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

        /**
         * Columns can be defined using the fluent syntax:
         * - CRUD::column('price')->type('number');
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
        CRUD::setValidation(ReadersRecomendArticleRequest::class);
        CRUD::field('name')->label(__('table.name'));
        CRUD::field('article_id')
            ->label(__('table.article'))
            ->type('select2')
            ->options((function ($query) {
                return $query->where('published', 1)->get();
            }));

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
