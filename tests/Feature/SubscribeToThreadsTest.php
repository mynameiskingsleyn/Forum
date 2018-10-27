<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Exceptions\Handler;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class SubscribeToThreadsTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */

    public function a_user_can_subscribe_to_threads()
    {
        //  $this->disableExceptionHandling();
        $this->withoutExceptionHandling();
        $this->signIn();
        $thread = create('Forum\Thread');
        //dd($thread->path());
        $this->post($thread->path().'/subscribe');

        $this->assertCount(1, $thread->subscriptions);
        $this->assertCount(0, auth()->user()->notifications);
    }
    /** @test */
    public function a_user_can_unsubscribe_to_thread()
    {
        $this->withoutExceptionHandling();
        $this->signIn();
        $thread = create('Forum\Thread');
        $this->post($thread->path().'/subscribe');
        $this->assertCount(1, $thread->subscriptions);
        //dd($thread->subscriptions);
        $this->delete($thread->path().'/subscribe');
        //dd($thread->subscriptions);
        $this->assertCount(0, $thread->fresh()->subscriptions);
    }

    /** @test */
    public function thread_subscribed_users_should_recieve_notification_on_reply()
    {
        $this->withoutExceptionHandling();
        $this->signIn();
        $thread = create('Forum\Thread');
        //dd($thread->path());
        $this->post($thread->path().'/subscribe');
        $this->assertCount(0, auth()->user()->notifications);
        //Each time a reply is made,
        $thread->addReply([
        'user_id' => auth()->id(),
        'body' => 'Some body here'
      ]);
        //A notification should be prepared for the user..
        //A notification should be prepared for the user..

        $this->assertCount(1, auth()->user()->fresh()->notifications);
    }
}
