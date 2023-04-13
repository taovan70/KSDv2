<?php

namespace App\Observers;

use App\Events\LogUserActionOnModel;
use App\Models\Subject;

class SubjectObserver
{
    /**
     * Handle the Subject "created" event.
     */
    public function created(Subject $subject): void
    {
        LogUserActionOnModel::dispatch($subject, 'тематика создана');
    }

    /**
     * Handle the Subject "updated" event.
     */
    public function updated(Subject $subject): void
    {
        LogUserActionOnModel::dispatch($subject, 'тематика изменена');
    }

    /**
     * Handle the Subject "deleted" event.
     */
    public function deleted(Subject $subject): void
    {
        LogUserActionOnModel::dispatch($subject, 'тематика удалена');
    }

    /**
     * Handle the Subject "restored" event.
     */
    public function restored(Subject $subject): void
    {
        LogUserActionOnModel::dispatch($subject, 'тематика restored');
    }

    /**
     * Handle the Subject "force deleted" event.
     */
    public function forceDeleted(Subject $subject): void
    {
        LogUserActionOnModel::dispatch($subject, 'тематика force deleted');
    }
}
