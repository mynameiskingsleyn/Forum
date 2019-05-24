<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use \Exception;

class MentionUserTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function mentioned_users_in_a_reply_are_notified()
    {
        $this->withoutExceptionHandling();
        // Given i have a user JohnDoe that is signed in..
        $john = create('Forum\User', ['name'=>'JohnDoe']);
        $this->signIn($john);
        // Another user named KingDoe
        $jane = create('Forum\User', ['name'=>'JaneDoe']);
        // If we have a thread..
        $thread = create('Forum\Thread');
        // And John replies and mentions @King,
        $reply = make(
            'Forum\Reply',
        ['body'=>'good job @JaneDoe from @JohnDoe']
        );
        $response = $this->postJson($thread->repPostPath(), $reply->toArray());
        //Then, JaneDoe should be notified.
        $this->assertCount(1, $jane->notifications);
    }

    /** @test */
    public function it_can_fetch_all_mentioned_users_starting_with_the_given_characters()
    {
        $user1 = create('Forum\User', ['name'=>'johndoe']);
        $user3 = create('Forum\User', ['name'=>'johndoe2']);
        $user2 = create('Forum\User', ['name'=>'janedoe']);
        $results = $this->json('GET', '/api/users', ['name'=>'john']); // json request-> (Method,uri,data).

        $this->assertCount(2, $results->json());
    }
}
