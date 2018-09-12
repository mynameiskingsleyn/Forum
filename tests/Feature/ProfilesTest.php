<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Forum\Activity;

class ProfilesTest extends TestCase
{
    use DatabaseMigrations;

    /** @test **/
    public function a_user_has_profile()
    {
        $user = create('Forum\User');

        $this->get("/profiles/$user->name")
          ->assertSee($user->name);
    }

    /** @test */
    public function profile_display_all_threads_created_by_the_associated_user()
    {
        //$this->signIn();
        $user = create('Forum\User');
        $this->signIn($user);
        $thread = create('Forum\Thread', ['user_id'=>$user->id]);
        $this->get("/profiles/$user->name")
        ->assertSee($thread->title);
    }
}
