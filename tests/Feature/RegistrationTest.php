<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Mail;
use Forum\Mail\PleaseConfirmEmail;
use Forum\User;
use \Exception;
use Forum\Exceptions\Handler;
use Illuminate\Contracts\Debug\ExceptionHandler;

class RegistrationTest extends TestCase
{
    use DatabaseMigrations;

    /** @test **/
    public function a_confirmation_email_is_sent_upon_registration()
    {
        Mail::fake();
        $input = ['name'=>'Steven doe','email'=>'steve@yahoo.com','password'=>'password123',
                'password_confirmation'=>'password123'];
        $this->post(route('register'), $input);
        //event(new Registered(create('Forum\User')));

        //$mailables=  Mail::getMailables();
        //dd($mailables);
        Mail::assertSent(PleaseConfirmEmail::class);
    }

    /** @test **/
    public function users_can_fully_confirm_their_email_address()
    {
        Mail::fake();
        $input = ['name'=>'Steven doe','email'=>'steve@yahoo.com','password'=>'password123',
                'password_confirmation'=>'password123'];
        $this->post(route('register'), $input);

        $user = User::whereName('Steven doe')->first();

        $this->assertFalse($user->confirmed);
        //dd($user->refresh());

        $this->assertNotNull($user->confirmation_token);
        // let the user confirm their account.

        //link they would click..
        // $response = $this->get('/register/confirm?token='.$user->confirmation_token);
        $response = $this->get(route('register.confirm', ['token'=>$user->confirmation_token]));
        tap($user->fresh(), function ($user) {
            $this->assertTrue($user->confirmed);

            $this->assertNull($user->confirmation_token);
        });
        $response->assertRedirect('/threads');
    }

    /** @test */
    public function confirming_an_invalid_token()
    {
        $this->withoutExceptionHandling();
        $this->get(route('register.confirm', ['token'=>'Invalid']))
              ->assertRedirect('/threads')
              ->assertSessionHas('flash', 'Your account could not be confirmed, you must have a wrong or expired token.');
    }
}
