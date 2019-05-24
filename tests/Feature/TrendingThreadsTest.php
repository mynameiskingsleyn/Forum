<?php

namespace Tests\Feature;

use Tests\TestCase;
use Tests\Feature\Auth;
use Tests\HomeMadeFakes\FakeTrending;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Redis;
use Forum\Classes\Trending;

use Forum\Reply;
use Forum\Thread;

class TrendingThreadsTest extends TestCase
{
    use DatabaseMigrations;
    protected $trending;
    protected $trend;
    protected function setUp()
    {
        parent::setUp();
        $this->trending = 'threads';
        $trend =  app(Trending::class);
        $trend->reset('threads');
        //app()->instance(\Forum\Classes\Trending::class, new FakeTrending());
        //Redis::del($this->trending);
    }

    /** @test */

    public function it_increments_a_thread_score_each_time_it_is_read()
    {
        //dd($this->trend);
        //$this->assertCount(0, Redis::zrevrange($this->trending, 0, -1));
        //$this->assertEmpty(Redis::zrevrange($this->trending, 0, -1));
        $trend = app(Trending::class);
        $trend->assertCount(0, $this->trending);
        //dd($trend);
        $trend->assertisEmpty('threads');
        $thread = create('Forum\Thread');

        $this->call('GET', $thread->path());
        $trend->assertCount(1, $this->trending);
        $trending = $trend->get($this->trending);
        $this->assertEquals($thread->title, json_decode($trending[0])->title);
        //dd($thread->path());

        //$this->assertCount(1, Redis::zrevrange($this->trending, 0, -1));
        // $trend->assertCount(1, $this->trending);
        // $trending = Redis::zrevrange($this->trending, 0, -1);
        // $this->assertCount(1, $trending);
        //dd($trending);
        //$this->assertEquals($thread->title, json_decode($trending[0])->title);
        //dd($d);
    }
}
