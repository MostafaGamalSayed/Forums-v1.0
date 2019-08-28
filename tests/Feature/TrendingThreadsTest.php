<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Redis;

class TrendingThreadsTest extends TestCase
{
    use DatabaseMigrations;


    public function setUp()
    {
        Parent::setUp();

        // Clear my trending_threads array before running any test
        Redis::del('trending_threads');
    }

    /** @test */
    public function A_thread_score_is_incremented_if_the_the_user_read_it()
    {
        // Given that trending threads array is empty
        $this->assertEmpty(Redis::zrevrange('trending_threads', 0, -1));

        // And a new thread was created
        $thread = factory('App\Thread')->create();

        // If a user read this thread
        $this->get(route('thread.show', ['channel' => $thread->channel->slug, 'thread' => $thread->id]));

        // Then i expect the score of this thread will increment by one
        $this->assertCount(1, Redis::zrevrange('trending_threads', 0, -1));
    }
}
