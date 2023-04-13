<?php

namespace App\Observers;

use App\Events\LogUserActionOnModel;
use App\Models\Tag;

class TagObserver
{
    /**
     * Handle the Tag "created" event.
     */
    public function created(Tag $tag): void
    {
        LogUserActionOnModel::dispatch($tag, 'тег создан');
    }

    /**
     * Handle the Tag "updated" event.
     */
    public function updated(Tag $tag): void
    {
        LogUserActionOnModel::dispatch($tag, 'тег изменён');
    }

    /**
     * Handle the Tag "deleted" event.
     */
    public function deleted(Tag $tag): void
    {
        LogUserActionOnModel::dispatch($tag, 'тег удалён');
    }

    /**
     * Handle the Tag "restored" event.
     */
    public function restored(Tag $tag): void
    {
        LogUserActionOnModel::dispatch($tag, 'тег restored');
    }

    /**
     * Handle the Tag "force deleted" event.
     */
    public function forceDeleted(Tag $tag): void
    {
        LogUserActionOnModel::dispatch($tag, 'тег force deleted');
    }
}
