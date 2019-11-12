<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Redis;

class RecordThreadVisitsTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function it_records_thread_visits()
    {
          // Given we have a thread
          $thread = create_factory('App\Thread');

          $thread->visits()->reset();

          // This thread has no visits yet
          $this->assertSame(0, $thread->visits()->count());

          // if a user visits this thread
          //$thread->recordVisit();
          $thread->visits()->record();

          // I expect that the thread visits will be equals '1'
          $this->assertEquals(1, $thread->visits()->count());
    }
}
