<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UserTest extends TestCase
{
    use DatabaseMigrations;
    /** @test */
    public function a_user_can_fetch_their_most_recent_reply()
    {
        $user = create('Forum\User');
        $reply = create('Forum\Reply', ['user_id'=> $user->id]);
        $this->assertEquals($reply->id, $user->lastReply->id);
    }
}
