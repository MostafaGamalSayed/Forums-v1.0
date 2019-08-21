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

}
