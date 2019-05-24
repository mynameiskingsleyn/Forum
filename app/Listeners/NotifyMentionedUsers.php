<?php

namespace Forum\Listeners;

use Forum\Events\ThreadRecievedNewReply;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Forum\User;
use Forum\Notifications\YouWhereMentioned;

class NotifyMentionedUsers
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
     * @param  ThreadRecievedNewReply  $event
     * @return void
     */
    public function handle(ThreadRecievedNewReply $event)
    {
        //
        User::WhereIn('name', $event->reply->mentionedUsers())->get()
        ->each(function ($user) use ($event) {
            $user->notify(new YouWhereMentioned($event->reply));
        });
    }
}
