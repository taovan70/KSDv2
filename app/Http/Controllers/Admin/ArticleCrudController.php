<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Article\ArticleStoreRequest;
use App\Models\Article;
use App\Services\ArticleService;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

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
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     * 
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\Article::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/article');
        CRUD::setEntityNameStrings(__('models.article'), __('models.articles'));
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
            'label' => __('table.author'),
            'type' => 'select',
            'name' => 'author_id',
            'attribute' => 'name',
            'entity' => 'author',
            'wrapper' => [
                'href' => fn($crud, $column, $article, $author_id) => backpack_url("author/{$author_id}/show")
            ]
        ]);
        CRUD::addColumn([
            'label' => __('table.sub_section'),
            'type' => 'select',
            'name' => 'sub_section_id',
            'attribute' => 'name',
            'entity' => 'subSection',
            'wrapper' => [
                'href' => fn($crud, $column, $article, $sub_section_id) => backpack_url("sub-section/{$sub_section_id}/show")
            ]
        ]);
        CRUD::column('created_at')->label(__('table.created'));

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
        CRUD::setValidation(ArticleStoreRequest::class);

        CRUD::field('name')->label(__('table.name'));
        CRUD::addField([
            'name' => 'sub_section_id',
            'label' => __('table.sub_section'),
            'type' => 'select2_grouped',
            'entity' => 'subSection',
            'attribute' => 'name',
            'group_by'  => 'section',
            'group_by_attribute' => 'name',
            'group_by_relationship_back' => 'subSections'
        ]);
        CRUD::addField([
            'name' => 'author_id',
            'label' => __('table.author'),
            'type' => 'select2_from_ajax',
            'entity' => 'author',
            'attribute' => 'fullName',
            'data_source' => url('api/article_authors'),
            'minimum_input_length' => 0,
            'dependencies' => ['sub_section_id'],
            'method' => 'POST',
            'include_all_form_fields' => true
        ]);
        CRUD::addField([
            'name' => 'article_text',
            'label' => __('table.article'),
            'type' => 'ckeditor',
            'options'       => [
                'autoGrow_minHeight'   => 200,
                'autoGrow_bottomSpace' => 50
            ]
        ]);

        Article::created(function (Article $article) {
            /** @var ArticleService $articleService */
            $articleService = app(ArticleService::class);
            $request        = $this->crud->validateRequest();

            $articleService->parseArticle($request->article_text, $article->id);
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
        $this->setupCreateOperation();
    }

    protected function setupShowOperation()
    {
        $this->setupListOperation();

        CRUD::addColumn([
            'name' => 'structure',
            'label' => __('table.articles.structure'),
            'type' => 'markdown'
        ]);

        CRUD::addColumn([
            'name' => 'content',
            'label' => __('table.articles.content'),
            'type' => 'markdown'
        ]);
    }
}
