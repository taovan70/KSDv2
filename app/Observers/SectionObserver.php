<?php

namespace App\Observers;

use App\Events\LogUserActionOnModel;
use App\Models\Section;

class SectionObserver
{
    /**
     * Handle the Section "created" event.
     */
    public function created(Section $section): void
    {
        LogUserActionOnModel::dispatch($section, 'раздел создан');
    }

    /**
     * Handle the Section "updated" event.
     */
    public function updated(Section $section): void
    {
        LogUserActionOnModel::dispatch($section, 'раздел изменён');
    }

    /**
     * Handle the Section "deleted" event.
     */
    public function deleted(Section $section): void
    {
        LogUserActionOnModel::dispatch($section, 'раздел удалён');
    }

    /**
     * Handle the Section "restored" event.
     */
    public function restored(Section $section): void
    {
        LogUserActionOnModel::dispatch($section, 'раздел restored');
    }

    /**
     * Handle the Section "force deleted" event.
     */
    public function forceDeleted(Section $section): void
    {
        LogUserActionOnModel::dispatch($section, 'раздел force deleted');
    }
}
