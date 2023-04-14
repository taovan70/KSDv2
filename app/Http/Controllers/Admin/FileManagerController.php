<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Routing\Controller;

/**
 * Class FileManagerController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class FileManagerController extends Controller
{
    public function index()
    {
        return view('admin.file_manager', [
            'title' => __('models.file_manager'),
            'breadcrumbs' => [
                trans('backpack::crud.admin') => backpack_url('dashboard'),
                'FileManager' => false,
            ],
            'page' => 'resources/views/admin/file_manager.blade.php',
            'controller' => 'app/Http/Controllers/Admin/FileManagerController.php',
        ]);
    }
}
