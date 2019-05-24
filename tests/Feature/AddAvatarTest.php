<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Exceptions\Handler;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class AddAvatarTest extends TestCase
{
    use DatabaseMigrations;

    public function setup()
    {
        parent::setup();
    }

    /** @test */
    public function only_members_can_add_avatars()
    {
        //$this->withoutExceptionHandling();
        //401 unauthorized..
        $this->json('POST', 'api/users/1/avatar')
              ->assertStatus(401);
    }
    /** @test */
    public function a_valid_avatar_must_be_provided()
    {
        //$this->withoutExceptionHandling();
        $this->signIn();
        $this->json(
            'POST',
            'api/users/'.auth()->id().'/avatar',
                ['avatar' => 'not-an-image']
              )->assertStatus(422);// unprocessable data
    }
    /** @test */
    public function a_valid_user_may_add_avatar_to_their_profile()
    {
        $this->signIn();
        // $file = 'avatar'.auth()->id().'.jpg';
        Storage::fake('public');
        $this->json(
          'POST',
          'api/users/'.auth()->id().'/avatar',
              ['avatar' =>$file= UploadedFile::fake()->image('avatar.jpg')]
            );
        //dd($file);
        $this->assertEquals(asset('avatars/'.$file->hashName()), auth()->user()->avatar_path);
        Storage::disk('public')->assertExists('avatars/'.$file->hashName());
    }
}
