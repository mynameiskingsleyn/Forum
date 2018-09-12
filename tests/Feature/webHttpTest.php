<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Forum\Thread;

class webHttpTest extends TestCase
{
    use RefreshDatabase;
    /** @test **/
    public function home_page_works()
    {
        $response = $this->get('/');
        $response->assertStatus(200);
    }
    /** @test **/
    public function threads_page_works()
    {
        $response = $this->call('get', '/threads');
        $response->assertStatus(200);
    }
    /** @test **/
    public function single_threads_page_works()
    {
        $thread = create('Forum\Thread');
        $response = $this->call('get', $thread->path());
        $response->assertStatus(200);
    }
    public function channels_menu_works_when_visit_thread_page()
    {
        $channel = create('Forum\Channel');
        $response = $this->get('/threads');
        $response->assertSee($channel->name);
    }
}
