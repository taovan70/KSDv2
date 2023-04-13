<?php

namespace App\Observers;

use App\Events\LogUserActionOnModel;
use App\Models\CustomSetting;

class CustomSettingObserver
{
    /**
     * Handle the CustomSetting "created" event.
     */
    public function created(CustomSetting $customSetting): void
    {
        LogUserActionOnModel::dispatch($customSetting, 'настройка сайта создана');
    }

    /**
     * Handle the CustomSetting "updated" event.
     */
    public function updated(CustomSetting $customSetting): void
    {
        LogUserActionOnModel::dispatch($customSetting, 'настройка сайта изменена');
    }

    /**
     * Handle the CustomSetting "deleted" event.
     */
    public function deleted(CustomSetting $customSetting): void
    {
        LogUserActionOnModel::dispatch($customSetting, 'настройка сайта удалена');
    }

    /**
     * Handle the CustomSetting "restored" event.
     */
    public function restored(CustomSetting $customSetting): void
    {
        LogUserActionOnModel::dispatch($customSetting, 'настройка сайта restored');
    }

    /**
     * Handle the CustomSetting "force deleted" event.
     */
    public function forceDeleted(CustomSetting $customSetting): void
    {
        LogUserActionOnModel::dispatch($customSetting, 'настройка сайта force deleted');
    }
}
