<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Stories\StoryItemRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Backpack\CRUD\app\Library\Widget;

/**
 * Class StoriesCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class StoryItemCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\InlineCreateOperation;
    use \App\Http\Controllers\Admin\Operations\Traits\InlineUpdateOperation;

    public function setup()
    {
        CRUD::setModel(\App\Models\StoryItem::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/story-item');
        CRUD::setEntityNameStrings(__('models.story_item'), __('models.story_item'));
    }


    protected function setupListOperation()
    {
        CRUD::column('name')->label(__('table.name'))->limit(70);
        CRUD::addColumn([
            'label' => __('models.stories'),
            'name' => 'story_id',
            'attribute' => 'name',
            'entity' => 'stories',
            'wrapper' => [
                'href' => fn($crud, $column, $article, $story_id) => backpack_url("stories/{$story_id}/show")
            ]
        ]);

        Widget::add([
            'type' => 'view',
            'view'    => 'partials.inlineOperationsModal',
            'content'=> [
                'page' => 'story-item'
            ]
        ]);

        Widget::add([
            'type' => 'script',
            'content'  => 'https://unpkg.com/select2@4.0.13/dist/js/select2.full.min.js',
        ]);
    }


    protected function setupCreateOperation()
    {
        CRUD::setValidation(StoryItemRequest::class);
        CRUD::field('name')->label(__('table.name'));
        CRUD::field('text')->label(__('models.text'));
        CRUD::addField([
            'name' => 'story_id',
            'label' => __('models.stories'),
            'type' => 'select2_from_ajax',
            'method' => 'POST',
            'entity' => 'stories',
            'attribute' => 'name',
            'data_source' => url('api/stories'),
        ]);
        CRUD::addField([
            'name' => 'photo_path',
            'label' => __('table.author_fields.photo'),
            'type' => 'upload',
            'upload' => true,
            'disk' => 'public'
        ]);
        CRUD::addField([
            'name' => 'article_id',
            'label' => __('table.article'),
            'type' => 'select2_from_ajax',
            'method' => 'POST',
            'data_source' => url('api/articles'),
        ]);
    }


    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }

    protected function setupShowOperation()
    {
        $this->setupListOperation();
        CRUD::addColumn([
            'name' => 'photo_path',
            'label' => __('table.author_fields.photo'),
            'type' => 'image',
            'prefix' => 'storage/',
            'width' => '100px',
            'height' => 'auto'
        ]);
    }

}
