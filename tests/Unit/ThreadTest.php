<?php

namespace Tests\Unit;

use Tests\TestCase;
use Forum\Exceptions\Handler;
use Forum\Notifications\ThreadWasUpdated;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Redis;

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
    public function a_thread_has_a_path()
    {
        $thread = create('Forum\Thread');
        $this->assertEquals("/threads/{$thread->channel->slug}/{$thread->slug}", $thread->path());
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
    public function a_thread_notifies_all_registered_subscribers_when_a_reply_is_added()
    {
        Notification::fake();

        $this->signIn()
            ->thread
            ->subscribe()
            ->addReply([
          'body' => 'testing',
          'user_id' => 20
      ]);

        Notification::assertSentTo(auth()->user(), ThreadWasUpdated::class);
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
    /** @test */
    public function a_thread_can_check_if_the_authenticated_user_has_read_all_replies()
    {
        $this->signIn();
        $thread = create('Forum\Thread');
        $this->assertTrue($thread->hasUpdatesFor(auth()->user()));
        //user reads thread replies..

        $key = sprintf("user.%s.visits.%s", auth()->id(), $thread->id);
        cache()->forever($key, \Carbon\Carbon::now());
        $this->assertFalse($thread->hasUpdatesFor(auth()->user()));
    }
    /** @test */
    // public function a_thread_records_each_visit()
    // {
    //     //Redis::del("threads.{$this->thread->id}.visits");
    //     $this->thread->resetVisits();
    //     $this->signIn();
    //     //  $thread = create('Forum\Thread', ['id'=>20]);
    //     //dd($this->thread->visits());
    //     $this->assertSame(0, $this->thread->visits());
    //
    //     $this->thread->recordVisit(); // incr 100 to 101
    //
    //     $this->assertEquals(1, $this->thread->visits());
    //
    //     $this->thread->recordVisit();
    //
    //     $this->assertEquals(2, $this->thread->visits());
    // }
}
