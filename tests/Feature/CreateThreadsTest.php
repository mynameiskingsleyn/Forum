<?php

namespace Tests\Feature;

use Tests\TestCase;
use Auth;
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

    public function publishThread($overrides = [], $confirmed=false)
    {
        $this->signIn(null, $confirmed);
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
    public function a_user_can_create_new_forum_threads()
    {
        //Given we have a signed in User
        //$this->actingAs(factory('Forum\User')->create(['confirmed'=>true]));
        $this->signIn(null, true);

        //dd($user);
        //$this->be($this->user);
        //when we hit the endpoint to create a new thread.
        //$thread = factory('Forum\Thread')->raw();// creates array..
        //$thread = factory('Forum\Thread')->make();
        $thread = make('Forum\Thread');
        //dd($thread);
        $response = $this->from('/threads')->post('/threads', $thread->toArray());
        //dd($response->headers->get('location'));
        //Then when we visit the thread page.
        $this->get($response->headers->get('location'))
        // We should see the new thread.
                ->assertSee($thread->title)
                ->assertSee($thread->body);
    }
    /** @test **/
    public function a_thread_requires_a_title()
    {
        $this->publishThread(['title'=>null], true)
          ->assertSessionHasErrors('title');
    }
    /** @test **/
    public function a_thread_requires_a_body()
    {
        $this->publishThread(['body'=>null], true)
            ->assertSessionHasErrors('body');
    }
    /** @test **/
    public function a_thread_requires_a_valid_channel_id()
    {
        factory('Forum\Channel', 2)->create();
        $response = $this->publishThread(['channel_id'=>null], true);
        $response->assertSessionHasErrors('channel_id');
        //dd($response->headers->get('location'));
        $this->publishThread(['channel_id'=>3], true)
            ->assertSessionHasErrors('channel_id');
    }
    /** @test **/
    public function an_unauthorized_user_can_not_delete_thread()
    {
        $thread = create('Forum\Thread');
        $response = $this->delete("/threads/{$thread->slug}");
        //$response->assertStatus(204);
        $response->assertRedirect('/login');

        $this->signIn(null, true);
        $this->delete("/threads/{$thread->slug}");
        $this->assertDatabaseHas('threads', ['id'=>$thread->id]);
    }

    /** @test **/
    public function an_authenticated_user_must_first_confirm_their_email_address_before_creating_a_thread()
    {
        //$this->publishThread()
        $user = factory('Forum\User')->states('uncomfirmed')->create();
        $this->signIn($user);
        $thread = make('Forum\Thread', ['user_id'=>$user->id]);
        $this->post('/threads', $thread->toArray())
          ->assertRedirect('threads')
          ->assertSessionHas('flash', 'You must first confirm your email address.');
    }

    /** @test */
    public function authorized_user_can_delete_thread()
    {
        $this->signIn(null, true);
        $thread = create('Forum\Thread', ['user_id'=>auth()->id()]);
        $reply = create('Forum\Reply', ['thread_id'=>$thread->id]);
        //$favorite = create('Forum\Favorite', ['favorited_id' => $reply->id , 'favorited_type' =>get_class($reply)]);

        $response = $this->json('DELETE', "threads/$thread->slug");
        $response->assertStatus(204);


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

    /** @test */
    public function a_thread_requires_a_unique_slug()
    {
        $this->withoutExceptionHandling();
        $title = 'bite me';
        $this->signIn();
        $user = Auth::user();
        $thread = create('Forum\Thread', ['user_id'=>$user->id,'title'=>$title]);
        //dd($thread);
        $this->assertEquals($thread->fresh()->slug, 'bite-me');

        $thread2 = make('Forum\Thread', ['user_id'=>$user->id,'title'=>'bite me']);
        $this->post('/threads', $thread2->toArray());
        //dd(Thread::all());
        $this->assertTrue(Thread::whereSlug('bite-me-1')->exists());
        $this->post('/threads', $thread2->toArray());
        $this->assertTrue(Thread::whereSlug('bite-me-2')->exists());
    }
    /** @test */
    public function a_thread_with_a_title_that_ends_in_number_should_generate_the_proper_slug()
    {
        $this->withoutExceptionHandling();
        $title = 'I am number 24';
        $title2 ='I am number 24 2';
        $this->signIn();
        $user = Auth::user();
        $thread = create('Forum\Thread', ['user_id'=>$user->id,'title'=>$title]);
        $this->assertEquals($thread->fresh()->slug, 'i-am-number-24');
        $thread2 = make('Forum\Thread', ['user_id'=>$user->id,'title'=>$title]);
        $this->post('/threads', $thread2->toArray());
        $this->assertTrue(Thread::whereSlug('i-am-number-24-1')->exists());
        $this->post('/threads', $thread2->toArray());
        $this->assertTrue(Thread::whereSlug('i-am-number-24-2')->exists());
        $thread3 = make('Forum\Thread', ['user_id'=>$user->id,'title'=>$title2]);
        $this->post('/threads', $thread3->toArray());
        $this->assertTrue(Thread::whereSlug('i-am-number-24-2-1')->exists());
    }
}
