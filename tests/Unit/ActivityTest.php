<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Forum\Activity;

//use Illuminate\Support\Carbon\Carbon;

class ActivityTest extends TestCase
{
    use DatabaseMigrations;
    /** @test */
    public function it_records_activity_when_a_thread_is_created()
    {
        $this->signIn();
        $thread = create('Forum\Thread');
        $this->assertDatabaseHas('activities', [
          'type' => 'created_thread',
          'user_id' => auth()->id(),
          'subject_id' => $thread->id,
          'subject_type' => 'Forum\Thread'
        ]);
        $activity = Activity::first();

        $this->assertEquals($activity->subject->id, $thread->id);
    }

    /** @test */
    public function it_records_activity_when_a_reply_is_created()
    {
        $this->signIn();
        $thread = create('Forum\Thread');
        $reply = create('Forum\Reply', [
          'thread_id'=>$thread->id,
          'user_id'=>auth()->id()
        ]);
        //$reply = create('Forum\Reply');
        $this->assertDatabaseHas('activities', [
        'type' => 'created_reply',
        'user_id' => auth()->id(),
        'subject_id' => $reply->id,
        'subject_type' => 'Forum\Reply'
      ]);
        ///$activity = Activity::find(2);
        //
        // $this->assertEquals($activity->subject->id, $thread->id);
    }
    /** @test **/
    public function it_fetches_activity_for_any_user()
    {
        //Given we have a thread..
        $this->signIn();

        create('Forum\Thread', ['user_id'=> auth()->id()], 2);

        // And another thread from a week ago.
        // create('Forum\Thread', [
        //   'user_id'=> auth()->id(),'created_at'=>\Carbon::now()->subWeek()
        // ],2);
        auth()->user()->activity()->first()->update(['created_at'=> \Carbon::now()->subWeek()]);
        // when we fetch their feed
        $feed = Activity::feed(auth()->user());
        //  dd($feed->toArray());

        //it should return in proper format..

        $this->assertTrue($feed->keys()->contains(
            \Carbon::now()->format('Y-m-d')
          ));

        $this->assertTrue($feed->keys()->contains(
          \Carbon::now()->subWeek()->format('Y-m-d')
        ));
    }
}
