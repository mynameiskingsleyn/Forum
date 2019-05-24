<?php

namespace Tests\Feature;

use Tests\TestCase;
use Auth;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransaction;
//use Forum\Reply;
use Forum\Activity;
use Forum\Reply;
use Forum\Thread;
use DB;

class BestReplyTest extends TestCase
{
    use DatabaseMigrations;
    public $thread;
    public $user;

    /** @test */
    public function a_thread_creator_may_mark_any_reply_as_the_best_reply()
    {
        $this->signIn();

        $thread = create('Forum\Thread', ['user_id'=>auth()->id()]);

        $replies = create('Forum\Reply', ['thread_id'=>$thread->id], 2);

        $this->assertFalse($replies[1]->isBest());

        $this->postJson(route('best-replies.store', [$replies[1]->id]));

        $this->assertTrue($replies[1]->fresh()->isBest());
    }

    /** @test */
    public function only_the_thread_creator_may_mark_reply_as_best()
    {
        $this->signIn();

        $thread = create('Forum\Thread', ['user_id'=>auth()->id()]);

        $replies = create('Forum\Reply', ['thread_id'=>$thread->id], 2);

        $thread_creator = auth()->user();
        $this->postJson(route('best-replies.store', [$replies[1]->id]));

        $this->assertTrue($replies[1]->fresh()->isBest());

        $this->signIn(create('Forum\User'));

        $this->postJson(route('best-replies.store', [$replies[0]->id]))->assertStatus(403);

        $this->assertFalse($replies[0]->fresh()->isBest());
    }

    /** @test */
    public function if_a_best_reply_is_deleted_then_the_thread_is_properly_updated_to_reflect_that()
    {
        $this->signIn();
        $thread = create('Forum\Thread');
        $reply = create('Forum\Reply', ['thread_id'=> $thread->id,'user_id'=>auth()->id()]);
        $reply->thread->markBestReply($reply);
        $best_reply = $thread->fresh()->best_reply_id;
        $this->assertNotNull($best_reply);
        // when we delete a reply.
        $this->deleteJson(route('reply.delete', $reply));
        // Then the thread should be updated.
        $best_reply = $thread->fresh()->best_reply_id;
        $this->assertNull($best_reply);
    }
}
