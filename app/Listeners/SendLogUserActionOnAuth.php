<?php

namespace App\Listeners;


use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\DB;

class SendLogUserActionOnAuth
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
    public function handle(Login $event): void
    {

        // check if all logs is on
        if (DB::table('settings')->where('key', 'user_logging_all')->first()?->value == '0') {
            return;
        }
        // check if auth logs is on
        if (DB::table('settings')->where('key', 'user_logging_auth')->first()?->value == '0') {
            return;
        }

        $userInfo = $this->getUserInfo();

        if (isset($userInfo)) {
            DB::table('log_user_events')->insert([
                'user_id' => $userInfo['user_id'],
                'url' => $userInfo['url'],
                'ip' => $userInfo['ip'],
                'agent' => $userInfo['agent'],
                'data' => '',
                'tags' => $event->user->getTable(),
                'event' => 'user login',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
