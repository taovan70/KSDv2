<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Category\CategoryStoreRequest;
use App\Http\Requests\Category\CategoryUpdateRequest;
use App\Models\Article;
use App\Models\Category;
use App\Services\CategoryService;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Backpack\CRUD\app\Library\Widget;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Prologue\Alerts\Facades\Alert;

/**
 * Class CategoryCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class CategoryCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ReorderOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\InlineCreateOperation;
    use \App\Http\Controllers\Admin\Operations\Traits\InlineUpdateOperation;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     *
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\Category::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/category');
        CRUD::setEntityNameStrings(__('models.category'), __('models.categories'));
    }

    protected function setupReorderOperation()
    {
        // define which model attribute will be shown on draggable elements
        $this->crud->set('reorder.label', 'name');
        // define how deep the admin is allowed to nest the items
        // for infinite levels, set it to 0
        $this->crud->set('reorder.max_level', 100);
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
        CRUD::column('created_at')->label(__('table.created'));
        CRUD::column('slug')->label(__('table.category_fields.slug'));
        CRUD::addColumn([
            'name' => 'parent',
            'label' => __('table.parent_category'),
            'wrapper' => [
                'href' => function ($crud, $column, $category) {
                    return backpack_url('category/' . $category->parent_id . '/show');
                }
            ]
        ]);
        Widget::add([
            'type' => 'view',
            'view'    => 'partials.inlineOperationsModal',
            'content'=> [
                'page' => 'category'
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
        CRUD::setValidation(CategoryStoreRequest::class);

        CRUD::field('name')->label(__('table.name'));
        CRUD::field('menu_order')->type('number')->label(__('table.menu_order'));
        CRUD::addField([
            'name' => 'description',
            'label' => __('table.description'),
            'type' => 'textarea',
            'options'       => [
                'autoGrow_minHeight'   => 200,
                'autoGrow_bottomSpace' => 50
            ]
        ]);
        CRUD::addField([
            'name' => 'photo_path',
            'label' => __('table.author_fields.photo'),
            'type' => 'upload',
            'upload' => true,
            'disk' => 'public'
        ]);
        CRUD::addField([
            'name' => 'mini_pic_path',
            'label' => __('table.mini-pic'),
            'type' => 'upload',
            'upload' => true,
            'disk' => 'public'
        ]);
        CRUD::addField([
            'name' => 'icon_path',
            'label' => __('table.icon'),
            'type' => 'upload',
            'upload' => true,
            'disk' => 'public'
        ]);

        Category::creating(function (Category $category) {
            $category->slug = Str::slug($category->name, '_');
        });
        /**
         * Fields can be defined using the fluent syntax or array syntax:
         * - CRUD::field('price')->type('number');
         * - CRUD::addField(['name' => 'price', 'type' => 'number']));
         */
    }


    /**
     *  Reorder the items in the database using the Nested Set pattern.
     *
     *  Database columns needed: id, parent_id, lft, rgt, depth, name/title
     *
     */
    public function reorder(CategoryService $service)
    {
        $this->crud->hasAccessOrFail('reorder');

        if (!$this->crud->isReorderEnabled()) {
            abort(403, 'Reorder is disabled.');
        }

        $this->data = $service->getSubCategoryInfo($this->data, $this->crud);

        return view('vendor/backpack/crud/category-reorder', $this->data);
    }

    /**
     * Define what happens when the Update operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-update
     * @return void
     */
    protected function setupUpdateOperation()
    {
        CRUD::setValidation(CategoryUpdateRequest::class);

        CRUD::field('name')->label(__('table.name'));
        CRUD::field('menu_order')->type('number')->label(__('table.menu_order'));
        CRUD::addField([
            'name' => 'description',
            'label' => __('table.description'),
            'type' => 'textarea',
            'options'       => [
                'autoGrow_minHeight'   => 200,
                'autoGrow_bottomSpace' => 50
            ]
        ]);
        CRUD::addField([
            'name' => 'photo_path',
            'label' => __('table.author_fields.photo'),
            'type' => 'upload',
            'upload' => true,
            'disk' => 'public'
        ]);
        CRUD::addField([
            'name' => 'mini_pic_path',
            'label' => __('table.mini-pic'),
            'type' => 'upload',
            'upload' => true,
            'disk' => 'public'
        ]);
        CRUD::addField([
            'name' => 'icon_path',
            'label' => __('table.icon'),
            'type' => 'upload',
            'upload' => true,
            'disk' => 'public'
        ]);

        Category::updating(function (Category $category) {
            $category->slug = Str::slug($category->name, '_');
        });
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
        CRUD::addColumn([
            'name' => 'mini_pic_path',
            'label' => __('table.mini-pic'),
            'type' => 'image',
            'prefix' => 'storage/',
            'width' => '100px',
            'height' => 'auto'
        ]);
        CRUD::addColumn([
            'name' => 'icon_path',
            'label' => __('table.icon'),
            'type' => 'image',
            'prefix' => 'storage/',
            'width' => '100px',
            'height' => 'auto'
        ]);
        $this->setupListOperation();
    }

    public function destroy($id, CategoryService $categoryService)
    {
        $categories = $categoryService->getCategoriesIds(Category::with('subcategories')->where('id', $id)->first());
        $articles = Article::whereIn('category_id', $categories)->get()->count();
        if ($articles > 0) {
            return Alert::add('error', __('validation.category.not_empty'));
        } else {
            return $this->crud->delete($id);
        }
    }


    protected function setupDeleteOperation()
    {
        Category::deleting(function (Category $category) {
            if (!empty($category->photo_path)) {
                Storage::disk('public')->delete($category->photo_path);
            }
            if (!empty($category->mini_pic_path)) {
                Storage::disk('public')->delete($category->mini_pic_path);
            }
            if (!empty($category->icon_path)) {
                Storage::disk('public')->delete($category->icon_path);
            }
        });
    }
}
