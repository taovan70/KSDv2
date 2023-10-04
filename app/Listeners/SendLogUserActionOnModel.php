<?php

namespace App\Listeners;

use App\Events\LogUserActionOnModel;
use Illuminate\Support\Facades\DB;

class SendLogUserActionOnModel
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get information from event.
     */
    private function parseEvent($event)
    {
       $table = $event->table;
    }

    private function getUserInfo(): ?array
    {
        $user = backpack_user();
        $ip = request()->ip();
        $agent = request()->server('HTTP_USER_AGENT');
        $url = request()->url();

        if (isset($user)) {
            return [
                'user_id' => $user->id,
                'url' => $url,
                'ip' => $ip,
                'agent' => $agent,
            ];
        }

        return null;
    }

    /**
     * Handle the event.
     */
    public function handle(LogUserActionOnModel $event): void
    {
        // check if all logs is on
        if (DB::table('settings')->where('key', 'user_logging_all')->first()?->value == '0') {
            return;
        }
        // check if model logs is on
        if (DB::table('settings')->where('key', 'user_logging_on_model')->first()?->value == '0') {
            return;
        }

        $userInfo = $this->getUserInfo();

        if (isset($userInfo)) {
            DB::table('log_user_events')->insert([
                'user_id' => $userInfo['user_id'],
                'url' => $userInfo['url'],
                'ip' => $userInfo['ip'],
                'agent' => $userInfo['agent'],
                'data' => '', //json_encode($event->observedModel->toArray()), TODO пока выключим
                'tags' => $event->observedModel->getTable(),
                'event' => $event->action,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
