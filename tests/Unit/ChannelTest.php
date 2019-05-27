<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class ChannelTest extends TestCase
{
    use DatabaseMigrations;


    /** @test */
    public function a_channel_can_has_many_threads()
    {
        $channel = create_factory('App\Channel');
        $thread = create_factory('App\Thread', ['channel_id' => $channel->id]);

        $this->assertTrue($channel->threads->contains($thread));
    }


}
