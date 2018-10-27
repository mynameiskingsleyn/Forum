<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Exceptions\Handler;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ThreadTest extends TestCase
{
    use DatabaseMigrations;
    protected $thread;
    public function setUp()
    {
        parent::setUp();
        $this->thread = factory('Forum\Thread')->create();
        $this->user = factory('Forum\User')->create();
        //$this->be($user);
    }
    /** @test */
    public function a_thread_has_user()
    {
        //Get the thread.
        $this->assertInstanceOf('Forum\User', $this->thread->owner);
    }
    /** @test */
    public function a_thread_has_replies()
    {
        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $this->thread->replies);
    }
    /** @test */
    public function a_thread_can_add_a_reply()
    {
        $this->thread->addReply([
            'body' => 'testing',
            'user_id' => $this->user->id
        ]);
        $this->assertCount(1, $this->thread->replies);
    }
    /** @test */
    public function a_thread_can_be_subscribed_to()
    {
        //Given we have a thread. and authenticated user
        $thread = create('Forum\Thread');
        $user_id = 2;
        $user = $this->signIn();
        //when the user subscribes to the thread..

        $thread->subscribe();
        // Then we should be able to fetch all threads that the user has subscribed to.

        $count = $thread->subscriptions()->where('user_id', auth()->id())->count();
        //dd($count);
        $this->assertEquals(1, $count);
    }
    /** @test */
    public function a_thread_can_be_unsubscribed_from()
    {
        $thread = create('Forum\Thread');

        $thread->subscribe($userId = 1);

        $thread->unsubscribe($userId = 1);

        $count = $thread->subscriptions()->where('user_id', 1)->count();
        //dd($thread->subscriptions()->get()->toArray());
        $this->assertEquals(0, $count);
    }

    /** @test */
    public function a_thread_knows_if_authenticated_user_is_subscribed_to_it()
    {
        $this->withoutExceptionHandling();
        $thread = create('Forum\Thread');
        $this->signIn();
        $this->assertFalse($thread->isSubscribedTo);
        $thread->subscribe();

        $this->assertTrue($thread->isSubscribedTo);
    }
}
