<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Carbon\Carbon;

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
        'body' => 'i will mention you @mohammed',
        'created_at' => Carbon::now(),
        'updated_at' => Carbon::now()
      ]);

      $this->post(route('reply.store',['channel' => $reply->thread->channel->slug, 'thread' => $reply->thread->id]), $reply->toArray());


      // Then i expect that mohamed will be notified
      $this->assertCount(1, $mohammed->notifications);

    }


    /** @test */
    public function it_can_fetch_all_mentioned_users_starting_with_the_given_characters()
    {
        create_factory('App\User', ['name' => 'johndoe']);
        create_factory('App\User', ['name' => 'johndoe2']);
        create_factory('App\User', ['name' => 'janedoe']);
        $results = $this->json('GET', '/api/users', ['name' => 'john']);
        $this->assertCount(2, $results->json());
    }
}
