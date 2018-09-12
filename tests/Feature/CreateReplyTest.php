<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Forum\Reply;

class CreateReplyTest extends TestCase
{
    use DatabaseMigrations;
    /** @test **/
    public function an_authenticated_user_can_create_new_reply_to_thread()
    {
        // given a logged in user..
        $this->actingAs(factory('Forum\User')->create());
        //$this->signIn();
        // Given an existing thread user can add reply to a thread..
        $thread = factory('Forum\Thread')->create();
        $this->post('/threads', $thread->toArray());
        $thread_id = $thread->id;
        //user can create reply
        $reply = factory('Forum\Reply')->create(['thread_id'=>$thread_id]);
        // we post Reply.

        //  dd($reply->id. ': '.$thread_id." ".$reply->body);
        $this->post("/threads/$thread_id", $reply->toArray());
        //$responds->assertStatus(405);
        // when we visit the thread we should see reply..
        $response = $this->get($thread->path());
        //dd($thread->path());
        $response->assertSee($reply->body);
    }
    /** @test **/
    public function a_reply_to_a_thread_can_be_saved()
    {
        $this->signIn();
        $thread = create('Forum\Thread');
        $reply = make('Forum\Reply');
        //dd($thread->repPostPath());
        $this->post($thread->repPostPath(), $reply->toArray());
        //dd($thread->path());
        $this->get($thread->path())
              ->assertSee($reply->body);
    }
    /** @test **/
    public function an_unauthorized_user_cannot_delete_reply()
    {
        //$this->withoutExceptionHandling();
        $reply = create('Forum\Reply');
        $this->delete("/replies/$reply->id")
          ->assertRedirect('login');

        $this->signIn();

        $this->delete("/replies/$reply->id")
              ->assertStatus(403);
        $this->assertDatabaseHas('replies', ['id'=>$reply->id]);
        //dd(Reply::all()->toArray());
    }
    /** @test **/
    public function authorized_user_can_delete_reply()
    {
        $this->signIn();
        $reply = create('Forum\Reply', ['user_id'=>auth()->id()]);
        $this->delete("/replies/$reply->id")
            ->assertStatus(302);
        $this->assertDatabaseMissing('replies', [
              'id'=>$reply->id
            ]);
    }
    /** @test **/
    public function authorized_user_can_edit_reply()
    {
        $this->signIn();
        $reply = create('Forum\Reply', ['user_id'=>auth()->id()]);
        $updatedReply = 'You been changed, fool.';
        $this->patch("/replies/{$reply->id}", ['body'=>$updatedReply]);

        $this->assertDatabaseHas('replies', ['id'=>$reply->id,'body'=>$updatedReply]);
    }
    /** @test **/
    public function an_unauthorized_user_cannot_edit_reply()
    {
        //$this->withoutExceptionHandling();
        $reply = create('Forum\Reply');
        $this->patch("/replies/$reply->id")
          ->assertRedirect('login');

        $this->signIn();
        $updatedReply = 'You been changed, fool.';
        $this->patch("/replies/{$reply->id}", ['body'=>$updatedReply])
              ->assertStatus(403);
        //  $this->assertDatabaseMissing('replies', ['id'=>$reply->id,'body'=>$updatedReply]);
        //  dd(Reply::all()->toArray());
    }
}
