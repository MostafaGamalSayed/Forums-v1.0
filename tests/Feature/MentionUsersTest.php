<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class MentionUsersTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function mentioned_users_in_a_reply_are_notified()
    {
      // Given that mostafa is an authenticated user
      $this->signIn($mostafa = create_factory('App\User'));

      // And another user whose name is Mohamed
      $mohammed = create_factory('App\User', ['name' => 'mohammed']);

      // If mostafa left a reply that mention mohammed
      $reply = make_factory('App\Reply', [
        'body' => 'i will mention you @mohammed'
      ]);

      $this->post(route('reply.store',['channel' => $reply->thread->channel->slug, 'thread' => $reply->thread->id]), $reply->toArray());


      // Then i expect that mohamed will be notified
      $this->assertCount(1, $mohammed->notifications);

    }
}
