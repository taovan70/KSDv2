<?php

namespace App\Http\Controllers\Admin\Blocks\NotFound;

use App\Http\Requests\Blocks\NotFound\PopularNotFoundArticlesRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Backpack\CRUD\app\Library\Widget;

/**
 * Class PopularExpertArticlesCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class PopularNotFoundArticlesCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\InlineCreateOperation;
    use \App\Http\Controllers\Admin\Operations\Traits\InlineUpdateOperation;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     *
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\Blocks\NotFound\PopularNotFoundArticles::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/popular-not-found-articles');
        CRUD::setEntityNameStrings(__('models.popular_articles'), __('models.popular_articles'));
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
            'label' => __('table.article'),
            'name' => 'article_id',
            'attribute' => 'name',
            'entity' => 'article',
            'wrapper' => [
                'href' => 'javascript:void(0);',
                'onclick' => 'setPreviewCookie();window.open(this.dataset.url,`_blank`);return false;',
                'data-url' => fn($crud, $column, $article, $category_id) => (env('FRONT_URL')."/article-preview-".$article['id']),
            ],
            'limit'=> 100,
        ]);

        Widget::add([
            'type' => 'view',
            'view'    => 'partials.inlineOperationsModal',
            'content'=> [
                'page' => 'popular-not-found-articles'
            ]
        ]);

        Widget::add([
            'type' => 'script',
            'content'  => 'https://unpkg.com/select2@4.0.13/dist/js/select2.full.min.js',
        ]);

        Widget::add([
            'type' => 'script',
            'content'  => '/packages/jquery-ui-dist/jquery-ui.min.js',
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
        CRUD::setValidation(PopularNotFoundArticlesRequest::class);
        CRUD::addField([
            'name' => 'article_id',
            'label' => __('table.article'),
            'type' => 'select2_from_ajax',
            'method' => 'POST',
            'data_source' => url('api/articles'),
        ]);
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
        CRUD::addColumn([
            'label' => __('table.article'),
            'name' => 'article_id',
            'attribute' => 'name',
            'entity' => 'article',
            'wrapper' => [
                'href' => 'javascript:void(0);',
                'onclick' => 'setPreviewCookie();window.open(this.dataset.url,`_blank`);return false;',
                'data-url' => fn($crud, $column, $article, $category_id) => (env('FRONT_URL')."/article-preview-".$article['id']),
            ],
        ]);
    }
}
