<?php

namespace App\Http\Controllers\Admin;

use App\CRUD\ArticleCRUD;
use App\Http\Requests\Article\ArticleStoreRequest;
use App\Http\Requests\Article\ArticleUpdateRequest;
use App\Models\Article;
use App\Services\ArticleElementService;
use App\Services\ArticleService;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Illuminate\View\View;

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

    /**
     * Define what happens when the Create operation is loaded.
     *
     * @return void
     */
    protected function setupCreateOperation(): void
    {
        CRUD::setValidation(ArticleStoreRequest::class);

        ArticleCRUD::formFields();

        Article::created(function (Article $article) {
            /** @var ArticleService $articleService */
            $articleService = app(ArticleService::class);

            $request = $this->crud->validateRequest();
            $articleService->parseArticle($request, $article);
        });
    }

    /**
     * Define what happens when the Update operation is loaded.
     *
     * @return void
     */
    protected function setupUpdateOperation(): void
    {
        CRUD::setValidation(ArticleUpdateRequest::class);

        ArticleCRUD::formFields(false);
    }

    protected function setupShowOperation(): void
    {
        $this->setupListOperation();
        ArticleCRUD::showColumns();
    }

    public function update()
    {
        /** @var ArticleService $articleService */
        $articleService = app(ArticleService::class);

        $this->crud->hasAccessOrFail('update');

        // execute the FormRequest authorization and validation, if one is required
        $request = $this->crud->validateRequest();

        // register any Model Events defined on fields
        $this->crud->registerFieldEvents();

        // update all article elements
        $articleService->updateArticleElements($request->all());

        // update the row in the db
        $item = $this->crud->update(
            $request->get($this->crud->model->getKeyName()),
            $this->crud->getStrippedSaveRequest($request)
        );
        $this->data['entry'] = $this->crud->entry = $item;

        // show a success message
        \Alert::success(trans('backpack::crud.update_success'))->flash();

        // save the redirect choice for next time
        $this->crud->setSaveAction();

        return $this->crud->performSaveAction($item->getKey());
    }

    public function destroy($id)
    {
        /** @var ArticleElementService $articleElementService */
        $articleElementService = app(ArticleElementService::class);

        $this->crud->hasAccessOrFail('delete');

        $article = Article::find($id);
        $articleElementService->deleteImages($article);

        return $this->crud->delete($id);
    }
}
