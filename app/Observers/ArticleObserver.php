<?php

namespace App\Observers;

use App\Events\LogUserActionOnModel;
use App\Models\Article;

class ArticleObserver
{
    /**
     * Handle the Article "created" event.
     */
    public function created(Article $article): void
    {
        LogUserActionOnModel::dispatch($article, 'статья создана');
    }

    /**
     * Handle the Article "updated" event.
     */
    public function updated(Article $article): void
    {
        LogUserActionOnModel::dispatch($article, 'статья обновлена');
    }

    /**
     * Handle the Article "deleted" event.
     */
    public function deleted(Article $article): void
    {
        LogUserActionOnModel::dispatch($article, 'статья удалена');
    }

    /**
     * Handle the Article "restored" event.
     */
    public function restored(Article $article): void
    {
        LogUserActionOnModel::dispatch($article, 'статья restored');
    }

    /**
     * Handle the Article "force deleted" event.
     */
    public function forceDeleted(Article $article): void
    {
        LogUserActionOnModel::dispatch($article, 'статья force deleted');
    }
}
