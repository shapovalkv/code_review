<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CopyrightUnassignedEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $userProject;
    public $agent;
    /**
     * Create a new event instance.
     */
    public function __construct($userProject, $agent)
    {
        $this->userProject = $userProject;
        $this->agent = $agent;
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
