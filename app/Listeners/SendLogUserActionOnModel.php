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

    private function getUserInfo(): array
    {
        $user = backpack_user();
        $ip = request()->ip();
        $agent = request()->server('HTTP_USER_AGENT');
        $url = request()->url();
        return [
            'user_id' => $user->id,
            'url' => $url,
            'ip' => $ip,
            'agent' => $agent,
        ];
    }

    /**
     * Handle the event.
     */
    public function handle(LogUserActionOnModel $event): void
    {
        // check if all logs is on
        if (DB::table('settings')->where('key', 'user_logging_all')->first()->value == '0') {
            return;
        }
        // check if model logs is on
        if (DB::table('settings')->where('key', 'user_logging_on_model')->first()->value == '0') {
            return;
        }

        DB::table('log_user_events')->insert([
            'user_id' => $this->getUserInfo()['user_id'],
            'url' => $this->getUserInfo()['url'],
            'ip' => $this->getUserInfo()['ip'],
            'agent' => $this->getUserInfo()['agent'],
            'data' => json_encode($event->observedModel->toArray()),
            'tags' => $event->observedModel->getTable(),
            'event' => $event->action,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
