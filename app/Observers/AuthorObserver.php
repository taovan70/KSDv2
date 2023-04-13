<?php

namespace App\Observers;

use App\Events\LogUserActionOnModel;
use App\Models\Author;

class AuthorObserver
{
    /**
     * Handle the Author "created" event.
     */
    public function created(Author $author): void
    {
        LogUserActionOnModel::dispatch($author, 'автор создан');
    }

    /**
     * Handle the Author "updated" event.
     */
    public function updated(Author $author): void
    {
        LogUserActionOnModel::dispatch($author, 'автор редактирован');
    }

    /**
     * Handle the Author "deleted" event.
     */
    public function deleted(Author $author): void
    {
        LogUserActionOnModel::dispatch($author, 'автор удалён');
    }

    /**
     * Handle the Author "restored" event.
     */
    public function restored(Author $author): void
    {
        LogUserActionOnModel::dispatch($author, 'автор restored');
    }

    /**
     * Handle the Author "force deleted" event.
     */
    public function forceDeleted(Author $author): void
    {
        LogUserActionOnModel::dispatch($author, 'автор force deleted');
    }
}
