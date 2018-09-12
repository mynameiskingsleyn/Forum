<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class FavoritesTest extends TestCase
{
    use DatabaseMigrations;
    /** @test */
    public function an_authenticated_user_can_favorite_any_reply()
    {
        $this->signIn();
        $reply = create('Forum\Reply');
        //If i Post a favorite endpoint
        $this->post('replies/'.$reply->id.'/favorites');

        // it should be recorded in the database.
        //dd(\DB::getQueryLog());
        //dd(\Forum\Favorite::all());
        $this->assertCount(1, $reply->favorites);
    }
    /** @test */
    public function an_authenticated_user_can_unfavorite_any_reply()
    {
        $this->signIn();
        $reply = create('Forum\Reply');
        //If i Post a favorite endpoint
        //$this->post('replies/'.$reply->id.'/favorites');
        $reply->favorite();
        $this->assertCount(1, $reply->favorites);
        $this->delete('replies/'.$reply->id.'/favorites');
        // it should be recorded in the database.
        //dd(\DB::getQueryLog());
        //dd(\Forum\Favorite::all());
        $this->assertCount(0, $reply->fresh()->favorites);
    }
    /** @test */
    public function a_guest_cannot_favorite_anything()
    {
        //$reply = create('Forum\Reply');
        //If i Post a favorite endpoint
        $this->post('replies/1/favorites')
            ->assertRedirect('/login');
    }

    /** @test */
    public function an_authenticated_user_may_only_favorite_a_reply_once()
    {
        $this->signIn();
        $reply = create('Forum\Reply');
        //If i Post a favorite endpoint
        try {
            $this->post('replies/'.$reply->id.'/favorites');
            $this->post('replies/'.$reply->id.'/favorites');
        } catch (\Exception $e) {
            $this->fail('can only like ones');
        }


        $this->assertCount(1, $reply->favorites);
    }
}
