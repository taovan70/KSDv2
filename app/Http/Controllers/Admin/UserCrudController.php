<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\User\UserStoreRequest;
use App\Http\Requests\User\UserUpdateRequest;
use App\Models\Role;
use App\Models\User;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Backpack\CRUD\app\Library\Widget;
use Illuminate\Support\Facades\Hash;

/**
 * Class UserCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class UserCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \App\Http\Controllers\Admin\Operations\Traits\InlineUpdateOperation;
    use \App\Http\Controllers\Admin\Operations\Traits\InlineCreateOperation;


    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     *
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\User::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/user');
        CRUD::setEntityNameStrings(__('models.user_possessive_case'), __('models.users'));
    }

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        CRUD::column('name')->label(__('table.user_fields.name'));
        CRUD::column('email')->label(__('table.user_fields.email'));

        Widget::add([
            'type' => 'view',
            'view'    => 'partials.inlineOperationsModal',
            'content'=> [
                'page' => 'user',
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
        CRUD::setValidation(UserStoreRequest::class);

        $this->renderForm();

        User::creating(function($entry) {
            $entry->password = Hash::make($entry->password);
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
        CRUD::setValidation(UserUpdateRequest::class);

        $this->renderForm();

        User::updating(function($entry) {
            if (empty($entry->password)) {
                $entry->password = $entry->getOriginal('password');
            } else $entry->password = Hash::make($entry->password);
        });

    }

    protected function renderForm(){

        $userRole = Role::firstWhere('name', config('roles_seeding.default_role'));
        $userRoleId = $userRole ? $userRole->id : null;

        CRUD::field('name')->label(__('table.user_fields.name'));
        CRUD::field('email')->label(__('table.user_fields.email'));
        CRUD::field('password')
            ->type('password')
            ->label(__('table.user_fields.password'));
        CRUD::field('password_confirmation')
            ->type('password')
            ->label(__('table.user_fields.password_confirmation'));

        CRUD::addField([
            'label' => __('table.user_fields.role'),
            'type' => 'select2_from_array',
            'name' => 'role_id',
            'options' => Role::all()->pluck('name', 'id')->toArray(),
            'allows_null' => false,
            'default' => $userRoleId ,
        ]);
    }
}
