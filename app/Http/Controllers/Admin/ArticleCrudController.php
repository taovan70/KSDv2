<?php

namespace App\Http\Controllers\Admin;

use App\CRUD\ArticleCRUD;
use App\Models\Article;
use App\Services\ArticleService;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Illuminate\View\View;
use Spatie\MediaLibrary\MediaCollections\Exceptions\MediaCannotBeDeleted;

/**
 * Class ArticleCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class ArticleCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation { destroy as traitDestroy; }
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     *
     * @return void
     */
    public function setup(): void
    {
        CRUD::setModel(\App\Models\Article::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/article');
        CRUD::setEntityNameStrings(__('models.article'), __('models.articles'));
    }

    /**
     * Define what happens when the List operation is loaded.
     *
     * @return void
     */
    protected function setupListOperation(): void
    {
        //Columns
        ArticleCRUD::listColumns();
        // Filters
        ArticleCRUD::tagFilter($this->crud);
        ArticleCRUD::categoryFilter($this->crud);
    }

    /**
     * Display all rows in the database for this entity.
     *
     * @return View
     */
    public function index()
    {
        $this->crud->hasAccessOrFail('list');

        $this->data['crud'] = $this->crud;
        $this->data['title'] = $this->crud->getTitle() ?? mb_ucfirst($this->crud->entity_name_plural);

        // load the view from /resources/views/vendor/backpack/crud/ if it exists, otherwise load the one in the package
        return view('vendor.backpack.crud.articles-list', $this->data);
    }

    protected function setupShowOperation(): void
    {
        $this->setupListOperation();
        ArticleCRUD::showColumns();
    }

    /**
     * @throws MediaCannotBeDeleted
     */
    public function destroy($id, ArticleService $articleService): bool|string
    {

        $this->crud->hasAccessOrFail('delete');

        $article = Article::find($id);
        $articleService->deleteAllAttachedMedia($article);

        return $this->crud->delete($id);
    }
}
