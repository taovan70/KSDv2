<?php

namespace App\Observers;

use App\Events\LogUserActionOnModel;
use App\Models\User;

class UserObserver
{
    /**
     * Handle the User "created" event.
     */
    public function created(User $user): void
    {
        LogUserActionOnModel::dispatch($user, 'пользователь создан');
    }

    /**
     * Handle the User "updated" event.
     */
    public function updated(User $user): void
    {
        LogUserActionOnModel::dispatch($user, 'пользователь изменён');
    }

    /**
     * Handle the User "deleted" event.
     */
    public function deleted(User $user): void
    {
        LogUserActionOnModel::dispatch($user, 'пользователь удалён');
    }

    /**
     * Handle the User "restored" event.
     */
    public function restored(User $user): void
    {
        LogUserActionOnModel::dispatch($user, 'пользователь restored');
    }

    /**
     * Handle the User "force deleted" event.
     */
    public function forceDeleted(User $user): void
    {
        LogUserActionOnModel::dispatch($user, 'пользователь force deleted');
    }
}
