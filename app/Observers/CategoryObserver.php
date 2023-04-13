<?php

namespace App\Observers;

use App\Events\LogUserActionOnModel;
use App\Models\Category;

class CategoryObserver
{
    /**
     * Handle the Category "created" event.
     */
    public function created(Category $category): void
    {
        LogUserActionOnModel::dispatch($category, 'категория создана');
    }

    /**
     * Handle the Category "updated" event.
     */
    public function updated(Category $category): void
    {
        LogUserActionOnModel::dispatch($category, 'категория изменена');
    }

    /**
     * Handle the Category "deleted" event.
     */
    public function deleted(Category $category): void
    {
        LogUserActionOnModel::dispatch($category, 'категория удалена');
    }

    /**
     * Handle the Category "restored" event.
     */
    public function restored(Category $category): void
    {
        LogUserActionOnModel::dispatch($category, 'категория restored');
    }

    /**
     * Handle the Category "force deleted" event.
     */
    public function forceDeleted(Category $category): void
    {
        LogUserActionOnModel::dispatch($category, 'категория force deleted');
    }
}
