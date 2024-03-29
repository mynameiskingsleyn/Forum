<?php

namespace Forum\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'Forum\Events\Event' => [
            'Forum\Listeners\EventListener',
        ],
        'Forum\Events\ThreadRecievedNewReply'=>[
            'Forum\Listeners\NotifyMentionedUsers',
            'Forum\Listeners\NotifyThreadSubscribers',
        ],

        //Registered::class
        // 'Illuminate\Auth\Events\Registered' => [
        //   //'Forum\Listeners\SendEmailConfirmationRequest'
        // ]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
