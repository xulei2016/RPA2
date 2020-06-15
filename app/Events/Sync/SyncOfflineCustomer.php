<?php

namespace App\Events\Sync;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class SyncOfflineCustomer
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @var $data
     */
    protected $data;

    /**
     * @var $type
     */
    protected $type;

    /**
     * Create a new event instance.
     *
     * @param array $data
     * @param int $type
     */
    public function __construct(array $data, int $type)
    {
        $this->data = $data;
        $this->type = $type;
    }

    /**
     * @return array
     */
    public function getData(){
        return $this->data;
    }

    /**
     * @return int
     */
    public function getType(){
        return $this->type;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
