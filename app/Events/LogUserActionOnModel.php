<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class LogUserActionOnModel
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Model $observedModel;
    public string $action;

    /**
     * Create a new event instance.
     */
    public function __construct(Model $observedModel, string $action)
    {
        $this->observedModel = $observedModel;
        $this->action = $action;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('channel-name'),
        ];
    }
}
