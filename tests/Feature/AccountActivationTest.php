<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Mail;
use App\Mail\AccountActivation;
use App\User;

class AccountActivationTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function an_email_is_sent_to_the_user_after_registeration()
    {
      Mail::fake();

      // Given that a new user registered
      $this->post(route('register'), [
        'name' => 'Mostafa-Gamal',
        'email' => 'mostafa@example.com',
        'password' => 'secret',
        'password_confirmation' => 'secret'
      ]);

      // Then i expect that the user will recieve an email to activate his account
      Mail::assertSent(AccountActivation::class);
    }

    /** @test */
    public function a_user_can_activate_his_account()
    {
      Mail::fake();

      // Given that we have a new registered user
      $this->post(route('register'), [
        'name' => 'Mostafa-Gamal',
        'email' => 'mostafa@example.com',
        'password' => 'secret',
        'password_confirmation' => 'secret'
      ]);

      // Then i expect the new user account not activated yet and the activation token is not null
      $user = User::whereName('Mostafa-Gamal')->first();
      $this->assertFalse($user->confirmed);
      $this->assertNotEmpty($user->confirmation_token);

      // If the user activate his account
      $this->get(route('register.activate', ['token' => $user->confirmation_token]));

      // I expect that the user is now confirmed an his activation token will be NULL
      $this->assertTrue($user->fresh()->confirmed);
      $this->assertEmpty($user->fresh()->confirmation_token);
    }

}
