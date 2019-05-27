<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use DatabaseMigrations;


    /** @test */
    public function a_user_has_a_profile()
    {
        // Given we have a user
        $user = create_factory('App\User');

        // Then the user should see his info. details like his name,..etc on his profile page
        $this->get(route('user.profile', $user->name))
            ->assertSee($user->name);
    }

    /** @test */
    public function it_shows_user_activities()
    {
        // Given we have a user
         $this->signIn();

        // If the user create a thread
        $thread = create_factory('App\Thread', ['user_id' => auth()->id()]);

        // Then when visit the user profile, it is expected to see the thread
        $this->get(route('user.profile', auth()->user()->name))
            ->assertSee($thread->title);

    }


}
