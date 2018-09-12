<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ReplyTest extends TestCase
{
    use DatabaseMigrations;
    /** @test */
    public function it_has_an_owner()
    {
        $reply = factory('Forum\Reply')->create();

        $this->assertInstanceOf('Forum\User', $reply->owner);
    }
}
