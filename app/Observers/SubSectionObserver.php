<?php

namespace App\Observers;

use App\Events\LogUserActionOnModel;
use App\Models\SubSection;

class SubSectionObserver
{
    /**
     * Handle the SubSection "created" event.
     */
    public function created(SubSection $subSection): void
    {
        LogUserActionOnModel::dispatch($subSection, 'подраздел создан');
    }

    /**
     * Handle the SubSection "updated" event.
     */
    public function updated(SubSection $subSection): void
    {
        LogUserActionOnModel::dispatch($subSection, 'подраздел изменён');
    }

    /**
     * Handle the SubSection "deleted" event.
     */
    public function deleted(SubSection $subSection): void
    {
        LogUserActionOnModel::dispatch($subSection, 'подраздел удалён');
    }

    /**
     * Handle the SubSection "restored" event.
     */
    public function restored(SubSection $subSection): void
    {
        LogUserActionOnModel::dispatch($subSection, 'подраздел restored');
    }

    /**
     * Handle the SubSection "force deleted" event.
     */
    public function forceDeleted(SubSection $subSection): void
    {
        LogUserActionOnModel::dispatch($subSection, 'подраздел force deleted');
    }
}
