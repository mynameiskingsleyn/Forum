<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ReadThreadsTest extends TestCase
{
    use DatabaseMigrations;
    //public $thread;
    public function setUp()
    {
        parent::setUp();
        $this->thread = factory('Forum\Thread')->create();
    }
    /** @test **/
    public function a_thread_can_make_a_string_path()
    {
        //$thread = make('Forum\Thread');
        $this->assertEquals('/threads/'.$this->thread->channel->slug.'/'.$this->thread->slug, $this->thread->path());
    }

    /** @test */
    public function a_user_can_browse_threads()
    {
        //$thread = factory('App\Thread')->create();
        $response = $this->get('/threads');

        //$response->assertStatus(200);
        $response->assertSee($this->thread->title);
    }
    /** @test */
    public function a_user_can_view_single_thread()
    {
        //$thread = factory('App\Thread')->create();
        $response = $this->get($this->thread->path());
        $response->assertSee($this->thread->title);
    }
    /** @test */
    public function a_user_can_read_replies_associated_with_a_thread()
    {
        //Get the replies for the thread.
        $reply = factory('Forum\Reply')->create(['thread_id'=>$this->thread->id]);
        //when visited
        $this->get($this->thread->path())
            ->assertSee($reply->body);
    }
    /** @test **/
    public function a_thread_has_user()
    {
        //Get the thread.
        $this->assertInstanceOf('Forum\User', $this->thread->owner);
    }
    /** @test **/
    public function a_thread_belongs_to_a_channel()
    {
        $this->assertInstanceOf('Forum\Channel', $this->thread->channel);
    }
    /** @test **/
    public function a_reader_can_filter_threads_according_to_a_tag()
    {
        // given we have a channel...
        $channel = create('Forum\Channel');
        //$channel2 = create('Forum\Channel');
        $threadInChannel = create('Forum\Thread', ['channel_id'=>$channel->id]);
        $threadNotInChannel = create('Forum\Thread');

        //given we have a thread associated to one channel a user will see only threads in channel
        $this->get('/threads/'.$channel->slug)
          ->assertSee($threadInChannel->title)
          ->assertDontSee($threadNotInChannel->title);
    }
    /** @test **/
    public function a_user_can_filter_trade_by_user_name()
    {
        $john = create('Forum\User', ['name'=>'JohnDoe','id'=>3]);
        $threadByJohn = create('Forum\Thread', ['user_id'=>$john->id]);
        $threadNotByJohn = create('Forum\Thread');
        $response = $this->get('/threads?by=JohnDoe');
        $response->assertSee($threadByJohn->title);
        //  ->assertDontSee($threadNotByJohn->title);
    }
    /** @test **/
    public function a_user_can_filter_threads_by_popularity()
    {
        //Given we have three threadsCount

        // with 2 replies, 3 replies and 0 replies respectively.
        $threadWithTwoReplies = create('Forum\Thread');
        create('Forum\Reply', ['thread_id'=>$threadWithTwoReplies->id], 2);

        $threadWithThreeReplies = create('Forum\Thread');
        create('Forum\Reply', ['thread_id'=>$threadWithThreeReplies->id], 3);

        $threadWithNoReplies = $this->thread;
        // when i filter threads by popularities they should return by popularity.
        $response = $this->getJson('threads?popular=1')->json();
        $replies_count = array_column($response['data'], 'replies_count');
        //dd($replies);

        // then they should be returned from the most replies to least.
        $this->assertEquals([3,2,0], $replies_count);
    }
    /**
    *@test
    */
    public function a_user_can_filter_trade_by_those_that_are_unanswered()
    {
        $thread = create('Forum\Thread');
        $reply = create('Forum\Reply', ['thread_id'=>$thread->id]);

        $response = $this->getJson('threads?unanswered=1')->json();
        //dd($response);
        $this->assertCount(1, $response['data']);
    }
    /**
    * @test
    */
    public function user_can_request_all_reply_for_a_given_thread()
    {
        $thread = create('Forum\Thread');
        create('Forum\Reply', ['thread_id'=>$thread->id], 2);
        $response = $this->getJson($thread->path().'/replies')->json();
        //dd($response);
        $this->assertCount(2, $response['data']);
        $this->assertEquals(2, $response['total']);
    }
    /** @test **/
    public function we_record_a_new_visit_each_time_the_thread_is_read()
    {
        $thread = create('Forum\Thread');
        //$thread->resetVisits();
        // dd($thread->fresh()->toArray());
        //dd($thread->toArray());
        $this->assertEquals(0, $thread->fresh()->visits);

        $this->call('GET', $thread->path());

        $this->assertEquals(1, $thread->fresh()->visits);
    }
}
