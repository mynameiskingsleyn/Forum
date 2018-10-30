<?php

namespace Tests\Feature;

use Tests\TestCase;
use Tests\Feature\Auth;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransaction;
//use Forum\Reply;
use Forum\Activity;
use Forum\Reply;
use Forum\Thread;

class CreateThreadsTest extends TestCase
{
    use DatabaseMigrations;
    public $thread;
    public $user;

    public function publishThread($overrides = [])
    {
        $this->signIn();
        $thread = make('Forum\Thread', $overrides);
        return $this->from('/threads')->post('/threads', $thread->toArray());
    }

    /*  public function setUp()
      {
          parent::setUp();
          $this->thread = factory('Forum\Thread')->make();
          $this->user = factory('Forum\User')->create();
      } */
    /** @test **/
    public function guests_may_not_create_threads()
    {
        $this->get('/threads/create')
            ->assertRedirect('/login');

        $this->post('/threads')
              ->assertRedirect('/login');
    }


    /** @test **/
    public function an_authenticated_user_can_create_new_forum_threads()
    {
        //Given we have a signed in User
        //$this->actingAs(factory('Forum\User')->create());
        $this->signIn();
        //$this->be($this->user);
        //when we hit the endpoint to create a new thread.
        //$thread = factory('Forum\Thread')->raw();// creates array..
        //$thread = factory('Forum\Thread')->make();
        $thread = make('Forum\Thread');

        $response = $this->from('/threads')->post('/threads', $thread->toArray());
        dd($response->headers->get('location'));
        //Then when we visit the thread page.
        $this->get($response->headers->get('location'))
        // We should see the new thread.
                ->assertSee($thread->title)
                ->assertSee($thread->body);
    }
    /** @test **/
    public function a_thread_requires_a_title()
    {
        $this->publishThread(['title'=>null])
          ->assertSessionHasErrors('title');
    }
    /** @test **/
    public function a_thread_requires_a_body()
    {
        $this->publishThread(['body'=>null])
            ->assertSessionHasErrors('body');
    }
    /** @test **/
    public function a_thread_requires_a_valid_channel_id()
    {
        factory('Forum\Channel', 2)->create();
        $response = $this->publishThread(['channel_id'=>null]);
        $response->assertSessionHasErrors('channel_id');
        //dd($response->headers->get('location'));
        $this->publishThread(['channel_id'=>3])
            ->assertSessionHasErrors('channel_id');
    }
    /** @test **/
    public function an_unauthorized_user_can_not_delete_thread()
    {
        $thread = create('Forum\Thread');
        $response = $this->delete("/threads/{$thread->id}");
        //$response->assertStatus(204);
        $response->assertRedirect('/login');

        $this->signIn();
        $this->delete("/threads/{$thread->id}");
        $this->assertDatabaseHas('threads', ['id'=>$thread->id]);
    }

    /** @test */
    public function authorized_user_can_delete_thread()
    {
        $this->signIn();
        $thread = create('Forum\Thread', ['user_id'=>auth()->id()]);
        $reply = create('Forum\Reply', ['thread_id'=>$thread->id]);
        //$favorite = create('Forum\Favorite', ['favorited_id' => $reply->id , 'favorited_type' =>get_class($reply)]);

        $response = $this->json('DELETE', "threads/$thread->id");
        $response->assertStatus(204);
        //dd(Reply::all()->toArray());
        // $this->assertDatabaseMissing('threads', $thread->toArray());
        // $this->assertDatabaseMissing('replies', $reply->toArray());  //or

        $this->assertDatabaseMissing('threads', ['id'=>$thread->id]);
        $this->assertDatabaseMissing('replies', ['id'=>$reply->id]);
        // $this->assertDatabaseMissing('activities', [
        //     'subject_id'=>$thread->id,
        //     'subject_type' => get_class($thread)
        // ]);
        // $this->assertDatabaseMissing('activities', [
        //     'subject_id'=>$reply->id,
        //     'subject_type' => get_class($reply)
        // ]);
        //  dd(Reply::all());
        // dd(Activity::all()->toArray());
        $this->assertEquals(0, Activity::count());
    }
}
