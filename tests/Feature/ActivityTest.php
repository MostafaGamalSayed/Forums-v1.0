<?php

namespace Tests\Feature;

use App\Activity;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class ActivityTest extends TestCase
{
    use DatabaseMigrations;


    /** @test */
    public function it_records_a_created_thread()
    {
        // Given we have an authenticated user
        $this->signIn();

        // If a thread created by the authenticated user
        $thread = create_factory('App\Thread', ['user_id' => auth()->id()]);

        // Then a created thread activity should be recorded
        $this->assertDatabaseHas('activities', ['subject_id' => $thread->id]);
    }

    /** @test */
    public function it_records_a_created_reply()
    {
        // Given we have an authenticated user
        $this->signIn();

        // If a reply with associated thread are created by the authenticated user
        create_factory('App\Reply', ['user_id' => auth()->id()]);

        $this->assertEquals(2, Activity::count());
    }


    /** @test */
    public function it_fetches_a_feed_for_a_user_in_appropriate_format()
    {
        // Given we have an authenticated user
        $this->signIn();

        // and two threads created by this authenticated user
        create_factory('App\Thread', ['user_id' => auth()->id()], 2);

        // Assume that the first thread was created a week before the first thread
        auth()->user()
            ->activities()
            ->first()
            ->update(['created_at' => Carbon::now()->subWeek()]);

        // Then the activity feed of the user should be returned in appropriate format
        $feed = auth()->user()->getUserActivityFeed();

        $this->assertTrue($feed->keys()->contains(Carbon::now()->format('y-m-d')));

        $this->assertTrue($feed->keys()->contains(Carbon::now()->subWeek()->format('y-m-d')));
    }

}
