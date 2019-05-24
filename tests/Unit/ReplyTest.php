<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Carbon\Carbon;

class ReplyTest extends TestCase
{
    use DatabaseMigrations;
    /** @test */
    public function it_has_an_owner()
    {
        $reply = factory('Forum\Reply')->create();

        $this->assertInstanceOf('Forum\User', $reply->owner);
    }

    /** @test */
    public function reply_knows_if_it_was_just_published()
    {
        $reply = factory('Forum\Reply')->create();

        $this->assertTrue($reply->wasJustPublished());

        $reply->created_at = Carbon::now()->subMonth();

        $this->assertFalse($reply->wasJustPublished());
    }

    /** @test */
    public function reply_can_detect_all_mentioned_users_in_the_body()
    {
        $reply = create('Forum\Reply', [
            'body'=>'@JaneDoe wants to talk to @JohnDoe'
          ]);

        $this->assertEquals(['JaneDoe','JohnDoe'], $reply->mentionedUsers());
    }

    /** @test */
    public function reply_wraps_mentioned_username_in_the_body_within_anchor_tags()
    {
        // $reply = create('Forum\Reply', [
        //   'body'=>'Hello @JaneDoe'
        // ]);
        $reply = new \Forum\Reply([
          'body'=>'Hello @Jane-Doe'
        ]);

        $this->assertEquals(
            'Hello <a href="/profiles/Jane-Doe">@Jane-Doe</a>',
            $reply->body
          );
    }

    /** @test */
    public function it_knows_if_it_is_the_best_reply()
    {
        $reply = create('Forum\Reply');

        $this->assertFalse($reply->isBest());

        $reply->thread->update(['best_reply_id'=>$reply->id]);

        $this->assertTrue($reply->fresh()->isBest());
    }
}
