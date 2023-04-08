<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Author\AuthorRequest;
use App\Models\Author;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Illuminate\Support\Facades\Auth;
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

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     * 
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\Author::class);
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
        CRUD::column('full_name')->label(__('table.users.full_name'));
        CRUD::column('age')->label(__('table.users.age'));
        CRUD::column('gender')->label(__('table.users.gender'));
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
        CRUD::setValidation(AuthorRequest::class);

        CRUD::field('name')->label(__('table.users.name'));
        CRUD::field('surname')->label(__('table.users.surname'));
        CRUD::field('middle_name')->label(__('table.users.middle_name'));
        CRUD::addField([
            'name' => 'gender',
            'label' => __('table.users.gender'),
            'type' => 'select_from_array',
            'options' => [
                Author::MALE => __('table.users.male'),
                Author::FEMALE => __('table.users.female'),
            ]
        ]);
        CRUD::field('age')->label(__('table.users.age'))->type('number');
        CRUD::field('biography')->label(__('table.users.biography'))->type('textarea');
        CRUD::field('address')->label(__('table.users.address'));
        CRUD::addField([
            'name' => 'photo_path',
            'label' => __('table.users.photo'),
            'type' => 'upload',
            'upload' => true,
            'disk' => 'public'
        ]);

        CRUD::field('personal_site')->label(__('table.users.personal_site'));
        CRUD::addField([
            'name'  => 'social_networks',
            'label' => __('table.users.social_networks'),
            'type'  => 'repeatable',
            'subfields' => [
                [
                    'name' => 'social_network',
                    'label' => __('table.users.social_network'),
                    'type' => 'select_from_array',
                    'options' => Author::SOCIAL_NETWORKS
                ],
                [
                    'name' => 'account',
                    'label' => __('table.users.account'),
                    'type' => 'text'
                ]
            ]
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
            'label' => __('table.users.photo'),
            'type' => 'image',
            'prefix' => 'storage/',
            'width' => '100px',
            'height' => '100px'
        ]);
        CRUD::column('full_name')->label(__('table.users.full_name'));
        CRUD::column('age')->label(__('table.users.age'));
        CRUD::column('gender')->label(__('table.users.gender'));
        CRUD::addColumn([
            'name' => 'biography',
            'label' => __('table.users.biography'),
            'limit' => 1000
        ]);
        CRUD::column('address')->label(__('table.users.address'));
        CRUD::column('personal_site')->label(__('table.users.personal_site'));
        CRUD::addColumn([
            'name' => 'social_networks_array',
            'label' => __('table.users.social_networks'),
            'type' => 'array'
        ]);
    }

    protected function setupDeleteOperation()
    {
        Author::deleting(function (Author $author) {
            Storage::disk('public')->delete($author->photo_path);
        });
    }
}
