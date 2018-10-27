<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ParticipateInForumTest extends TestCase
{
    use DatabaseMigrations;

    public function setUp()
    {
        parent::setUp();
        $this->reply = factory('Forum\Reply')->create();
        $this->user = factory('Forum\User')->create();
        $this->thread = factory('Forum\Thread')->create();
    }


    /** @test */
    public function an_authenticated_user_may_participate_in_forum_thread()
    {
        //Given we have an authenticated user

        //$this->user = factory('Forum\User')->create();
        $this->be($this->user);
        // And an existing thread
        // $thread = factory('Forum\Thread')->create();
        // when the user adds a reply to the thread
        //$reply = factory('Forum\Reply')->create();
        $this->post('/threads/'.$this->thread->id.'/replies', $this->reply->toArray());

        // Then their reply should be visible on the page.
        $this->get($this->thread->path())
            ->assertSee($this->reply->body);

        $this->assertEquals(1, $this->thread->fresh()->replies_count);
    }
    /** @test **/
    public function a_reply_requires_a_body()
    {
        $this->signIn();
        $reply = make('Forum\Reply', ['body'=>null]);
        $this->post('/threads/'.$this->thread->id.'/replies', $reply->toArray())
          ->assertSessionHasErrors('body');
    }
}
