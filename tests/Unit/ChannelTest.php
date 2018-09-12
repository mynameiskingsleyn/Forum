<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ChannelTest extends TestCase
{
    use DatabaseMigrations;

    /** @test **/
    public function a_channel_consists_of_threads()
    {
        $channel = create('Forum\Channel');
        $thread = create('Forum\Thread', ['channel_id'=>$channel->id]);

        $this->assertTrue($channel->threads->contains($thread));
    }
    /** @test **/
    public function a_channel_list_is_always_present()
    {
        $channel = create('Forum\Channel');
        $this->get('/threads/')
          ->assertSee($channel->name);
    }
}
