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

        collect($event->reply->mentionedUsers())
          ->map(function ($name) {
              return User::where('name', $name)->first();
          })
          ->filter()
          ->each(function ($user) use ($event) {
              $user->notify(new YouWhereMentioned($event->reply));
          });

        //$event->reply->thread->notifySubscribers($event->reply);
        //$mentionedUsers = $event->reply->mentionedUsers();
        // foreach ($mentionedUsers as $name) {
        //     $user = User::whereName($name)->first();
        //     if ($user) {
        //         $user->notify(new YouWhereMentioned($event->reply));
        //     }
        // }
    }
}
