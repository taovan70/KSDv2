<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Author\AuthorRequest;
use App\Models\Author;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Backpack\CRUD\app\Library\Widget;
use Illuminate\Support\Facades\Storage;

/**
 * Class AuthorCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class AuthorCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;
    use \App\Http\Controllers\Admin\Operations\Traits\InlineUpdateOperation;
    use \App\Http\Controllers\Admin\Operations\Traits\InlineCreateOperation;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     *
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(Author::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/author');
        CRUD::setEntityNameStrings(__('models.author'), __('models.authors'));
    }

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        CRUD::column('full_name')->label(__('table.author_fields.full_name'));
        CRUD::column('age')->label(__('table.author_fields.age'));
        CRUD::column('fullGender')->label(__('table.author_fields.gender'));
        CRUD::column('created_at')->label(__('table.created'));

        Widget::add([
            'type' => 'view',
            'view'    => 'partials.inlineOperationsModal',
            'content'=> [
                'page' => 'author',
            ]
        ]);

        Widget::add([
            'type' => 'script',
            'content'  => 'https://unpkg.com/select2@4.0.13/dist/js/select2.full.min.js',
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
        CRUD::setValidation(AuthorRequest::class);

        CRUD::field('name')->label(__('table.author_fields.name'));
        CRUD::field('surname')->label(__('table.author_fields.surname'));
        CRUD::field('middle_name')->label(__('table.author_fields.middle_name'));
        CRUD::addField([
            'name' => 'gender',
            'label' => __('table.author_fields.gender'),
            'type' => 'select_from_array',
            'options' => [
                Author::MALE => __('table.author_fields.' . Author::MALE),
                Author::FEMALE => __('table.author_fields.' . Author::FEMALE),
            ]
        ]);
        CRUD::field('age')->label(__('table.author_fields.age'))->type('number');
        CRUD::field('biography')->label(__('table.author_fields.biography'))->type('textarea');
        CRUD::field('description')->label(__('table.author_fields.description'))->type('textarea');
        CRUD::field('address')->label(__('table.author_fields.address'));
        CRUD::addField([
            'name' => 'photo_path',
            'label' => __('table.author_fields.photo'),
            'type' => 'upload',
            'upload' => true,
            'disk' => 'public'
        ]);

        CRUD::field('personal_site')->label(__('table.author_fields.personal_site'));
        CRUD::addField([
            'name'  => 'social_networks',
            'label' => __('table.author_fields.social_networks'),
            'type'  => 'repeatable',
            'subfields' => [
                [
                    'name' => 'social_network',
                    'label' => __('table.author_fields.social_network'),
                    'type' => 'select_from_array',
                    'options' => Author::SOCIAL_NETWORKS
                ],
                [
                    'name' => 'account',
                    'label' => __('table.author_fields.account'),
                    'type' => 'text'
                ]
            ]
        ]);

        CRUD::addField([
            'name' => 'categories',
            'label' => __('table.category'),
            'type' => 'select2_multiple',
            'attribute' => 'name'
        ]);

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
        CRUD::addColumn([
            'name' => 'photo_path',
            'label' => __('table.author_fields.photo'),
            'type' => 'image',
            'prefix' => 'storage/',
            'width' => '100px',
            'height' => 'auto'
        ]);
        CRUD::column('full_name')->label(__('table.author_fields.full_name'));
        CRUD::column('age')->label(__('table.author_fields.age'));
        CRUD::column('fullGender')->label(__('table.author_fields.gender'));
        CRUD::addColumn([
            'name' => 'biography',
            'label' => __('table.author_fields.biography'),
            'limit' => 1000
        ]);
        CRUD::column('address')->label(__('table.author_fields.address'));
        CRUD::column('personal_site')->label(__('table.author_fields.personal_site'));
        CRUD::addColumn([
            'name' => 'social_networks_array',
            'label' => __('table.author_fields.social_networks'),
            'type' => 'array'
        ]);
        CRUD::addColumn([
            'label' => __('table.category'),
            'name' => 'categories',
            'type' => 'select_multiple',
            'wrapper' => [
                'href' => function ($crud, $column, $author, $related_key) {
                    return backpack_url('category/' . $related_key . '/show');
                }
            ]
        ]);
    }

    protected function setupDeleteOperation()
    {
        Author::deleting(function (Author $author) {
            if (!empty($author->photo_path)) {
                Storage::disk('public')->delete($author->photo_path);
            }
        });
    }
}
