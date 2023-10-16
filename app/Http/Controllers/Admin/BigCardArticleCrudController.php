<?php

namespace App\Http\Controllers\Admin;


use App\Models\Blocks\MainPage\BigCardArticle;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use App\Http\Requests\Blocks\MainPage\BigCardArticleRequest;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class BigCardArticleCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class BigCardArticleCrudController extends CrudController
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
        CRUD::setModel(BigCardArticle::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/big-card-article');
        CRUD::setEntityNameStrings(__('models.big_card_article'), __('models.big_card_article'));
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
                'href' => fn ($crud, $column, $article, $article_id) => backpack_url("article/{$article_id}/show")
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
        CRUD::setValidation(BigCardArticleRequest::class);
        CRUD::field('name')->label(__('table.name'));
        CRUD::addField([
            'name' => 'content',
            'label' => __('table.article'),
            'type' => 'ckeditor',
            'options'       => [
                'autoGrow_minHeight'   => 200,
                'autoGrow_bottomSpace' => 50
            ]
        ]);
        CRUD::addField([
            'name' => 'article_id',
            'label' => __('table.article'),
            'type' => 'select2_from_ajax',
            'method' => 'POST',
            'data_source' => url('api/articles'),
        ]);
        CRUD::addField([
            'name' => 'photo_path',
            'label' => __('table.author_fields.photo'),
            'type' => 'upload',
            'upload' => true,
            'disk' => 'public'
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
        CRUD::column('name')->label(__('table.name'));
        CRUD::addColumn([
            'label' => __('table.article'),
            'type' => 'select',
            'name' => 'article_id',
            'attribute' => 'name',
            'entity' => 'article',
            'wrapper' => [
                'href' => fn ($crud, $column, $article, $article_id) => backpack_url("article/{$article_id}/show")
            ]
        ]);
        CRUD::addColumn([
            'name' => 'photo_path',
            'label' => __('table.author_fields.photo'),
            'type' => 'image',
            'prefix' => 'storage/',
            'width' => '100px',
            'height' => '100px'
        ]);
    }

    protected function setupDeleteOperation()
    {
        BigCardArticle::deleting(function (BigCardArticle $bigCardArticle) {
            BigCardArticle::disk('public')->delete($bigCardArticle->photo_path);
        });
    }
}
