<?php

namespace App\Observers;

use App\Events\LogUserActionOnModel;
use App\Models\ArticleElement;

class ArticleElementObserver
{
    /**
     * Handle the ArticleElement "created" event.
     */
    public function created(ArticleElement $articleElement): void
    {
        LogUserActionOnModel::dispatch($articleElement, 'элемент статьи создан');
    }

    /**
     * Handle the ArticleElement "updated" event.
     */
    public function updated(ArticleElement $articleElement): void
    {
        LogUserActionOnModel::dispatch($articleElement, 'элемент статьи изменен');
    }

    /**
     * Handle the ArticleElement "deleted" event.
     */
    public function deleted(ArticleElement $articleElement): void
    {
        LogUserActionOnModel::dispatch($articleElement, 'элемент статьи удален');
    }

    /**
     * Handle the ArticleElement "restored" event.
     */
    public function restored(ArticleElement $articleElement): void
    {
        LogUserActionOnModel::dispatch($articleElement, 'элемент статьи restored');
    }

    /**
     * Handle the ArticleElement "force deleted" event.
     */
    public function forceDeleted(ArticleElement $articleElement): void
    {
        LogUserActionOnModel::dispatch($articleElement, 'элемент статьи force deleted');
    }
}
