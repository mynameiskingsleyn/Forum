<?php

namespace Forum\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class ThreadHasNewReply
{
    use SerializesModels;
    public $thread;
    public $reply;

    /**
     * Create a new event instance.
     * @param \Forum\Thread $thread
     * @param \Forum\Reply $reply
     * @return void
     */
    public function __construct($thread, $reply)
    {
        //
        $this->reply = $reply;
        $this->thread = $thread;
    }
}
