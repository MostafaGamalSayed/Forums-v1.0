<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class SubscriptionTest extends TestCase
{
    use DatabaseMigrations;


    /** @test */
    public function an_authenticated_user_can_subscribe_to_a_thread()
    {
        // Given we have an authenticated user
        $this->signIn();

        // and a thread created by any user
        $thread = create_factory('App\Thread');

        // If the authenticated user subscribe to this thread
        $this->postJson(route('thread.subscribe', ['channel' => $thread->channel->slug, 'thread' => $thread->slug]));

        // Then i expect the subscription list of the user will be [1]
        $this->assertEquals(1, $thread->subscriptions()->where('user_id', auth()->id())->count());

        // Also it will return true if check whether the thread is subscribed
        $this->assertTrue($thread->isSubscribed);

    }
}
