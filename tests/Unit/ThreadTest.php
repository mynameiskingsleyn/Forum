<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ThreadTest extends TestCase
{
    use DatabaseMigrations;
    protected $thread;
    public function setUp()
    {
        parent::setUp();
        $this->thread = factory('Forum\Thread')->create();
        $this->user = factory('Forum\User')->create();
        //$this->be($user);
    }
    /** @test */
    public function a_thread_has_user()
    {
        //Get the thread.
        $this->assertInstanceOf('Forum\User',$this->thread->owner);
    }
    /** @test */
    public function a_thread_has_replies()
    {
        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection',$this->thread->replies);
    }
    /** @test */
    public function a_thread_can_add_a_reply()
    {

        $this->thread->addReply([
            'body' => 'testing',
            'user_id' => $this->user->id
        ]);
        $this->assertCount(1,$this->thread->replies);
    }
}
