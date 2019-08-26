<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class UserTest extends TestCase
{

    use DatabaseMigrations;


  /** @test */
  public function a_user_can_fetch_the_most_recent_reply()
  {
    // Given we have a user
    $user = create_factory('App\User');

    // If the user create a reply
    $reply = create_factory('App\Reply', ['user_id' => $user->id]);

    // Then i expect the user last reply will be this reply
    $this->assertEquals($reply->id, $user->lastReply->id);

  }


  /** @test */
  public function a_user_can_get_his_avatar()
  {
    // Given we have a user who is just created
    $user = create_factory('App\User');

    // Then i expect the user avatar is a default one
    $this->assertEquals('avatars/default.jpg', $user->avatar());

    // if the user update his avatar
    $user->avatar_path = 'avatar/me.jpg';

    // Then i expect the user avatar is the chosen or uploaded avatar
    $this->assertEquals('avatar/me.jpg', $user->avatar());
  }

}
