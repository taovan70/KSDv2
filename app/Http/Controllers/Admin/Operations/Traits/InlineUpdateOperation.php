<?php

namespace App\Http\Controllers\Admin\Operations\Traits;

use Illuminate\Support\Facades\Route;
use Prologue\Alerts\Facades\Alert;

trait InlineUpdateOperation
{
    /**
     * Define which routes are needed for this operation.
     *
     * @param  string  $segment  Name of the current entity (singular). Used as first URL segment.
     * @param  string  $routeName  Prefix of the route name.
     * @param  string  $controller  Name of the current CrudController.
     */
    protected function setupInlineUpdateRoutes($segment, $routeName, $controller)
    {
        Route::post($segment.'/inline/update/modal/{id}', [
            'as'        => $segment.'-inline-update',
            'uses'      => $controller.'@getInlineUpdateModal',
            'operation' => 'InlineUpdate',
        ]);
        Route::post($segment.'/inline/update/{id}', [
            'as'        => $segment.'-inline-update-save',
            'uses'      => $controller.'@storeInlineUpdate',
            'operation' => 'InlineUpdate',
        ]);
    }

    /**
     * Setup the operation default settings. In this operation we want to make sure that the defaults are only applied when
     * the Operation is needed because it relies on calling other operation methods.
     */
    protected function setupInlineUpdateDefaults() {

        if ($this->crud->getCurrentOperation() !== 'InlineUpdate') {
            return;
        }

        if (method_exists($this, 'setup')) {
            $this->setup();
        }

        $this->crud->applyConfigurationFromSettings('update');

        if (method_exists($this, 'setupUpdateOperation')) {
            $this->setupUpdateOperation();
        }
    }

    /**
     * Returns the HTML of the create form. It's used by the CreateInline operation, to show that form
     * inside a popup (aka modal).
     */
    public function getInlineUpdateModal($id)
    {
        if (! request()->has('entity')) {
            abort(400, 'No "entity" inside the request.');
        }

        return view(
            'vendor/backpack/crud/operations/inline_create_modal',
            [
                'fields' => $this->crud->getUpdateFields($id),
                'action' => 'update',
                'crud' => $this->crud,
                'entity' => request()->get('entity'),
                'modalClass' => request()->get('modal_class'),
                'parentLoadedAssets' => request()->get('parent_loaded_assets'),
            ]
        );
    }

    /**
     * Update the specified resource in the database.
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function updateInline()
    {
        $this->crud->hasAccessOrFail('update');

        // execute the FormRequest authorization and validation, if one is required
        $request = $this->crud->validateRequest();

        // register any Model Events defined on fields
        $this->crud->registerFieldEvents();

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

    /**
     * Runs the update() function in controller like a regular crud create form.
     * Developer might overwrite this if they want some custom save behaviour when added on the fly.
     *
     * @return void
     */
    public function storeInlineUpdate()
    {

        $result = $this->updateInline();


        //Alert::flush();

        return $result;
    }
}
