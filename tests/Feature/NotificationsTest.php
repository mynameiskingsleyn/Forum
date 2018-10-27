<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Exceptions\Handler;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class NotificationsTest extends TestCase
{
    use DatabaseMigrations;

    public function setup()
    {
        parent::setup();
    }

    /** @test */
    public function a_notification_is_prepared_when_a_subscribed_thread_recieves_a_new_reply_not_by_current_user()
    {
        $this->withoutExceptionHandling();
        $this->signIn();
        $thread = create('Forum\Thread')->subscribe();
        //dd($thread->path());
        //$this->post($thread->path().'/subscribe');
        $this->assertCount(0, auth()->user()->notifications);
        //Each time a reply is made,
        $thread->addReply([
        'user_id' => auth()->id(),
        'body' => 'Some body here'
      ]);
        //if user is ourself no notification should be prepared.
        $this->assertCount(0, auth()->user()->fresh()->notifications);
        //A notification should be prepared for the user if not ourself..
        //Each time a reply is made,
        $thread->addReply([
        'user_id' => create('Forum\User')->id,
        'body' => 'Some body here'
      ]);
        $this->assertCount(1, auth()->user()->fresh()->notifications);
    }
    /** @test */
    public function a_user_can_fetch_their_unread_notification()
    {
        $this->withoutExceptionHandling();
        $this->signIn();
        $user = auth()->user();
        $thread = create('Forum\Thread')->subscribe();
        $thread->addReply([
          'user_id' => create('Forum\User')->id,
          'body' => 'Some body here'
        ]);
        $response = $this->getJson("/profiles/$user->name/notifications")->json();

        $this->assertCount(1, $response);
    }

    /** @test */
    public function a_user_can_mark_notification_as_read()
    {
        $this->withoutExceptionHandling();
        $this->signIn();
        $thread = create('Forum\Thread')->subscribe();
        $thread->addReply([
          'user_id' => create('Forum\User')->id,
          'body' => 'Some body here'
        ]);

        $this->assertCount(1, auth()->user()->unreadNotifications);
        $user = auth()->user();
        $notificationId = $user->unreadNotifications->first()->id;

        //clearnotification...
        $this->delete("/profiles/$user->name/notifications/{$notificationId}");

        $this->assertCount(0, $user->fresh()->unreadNotifications);
    }
}
