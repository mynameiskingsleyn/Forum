<?php

namespace Forum\Listeners;

use Forum\Events\ThreadHasNewReply;
use Forum\Events\ThreadRecievedNewReply;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class NotifyThreadSubscribers
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  ThreadHasNewReply  $event
     * @return void
     */
    public function handle(ThreadRecievedNewReply $event)
    {

        //prepare notification..
        $event->reply->thread->notifySubscribers($event->reply);
    }
}
