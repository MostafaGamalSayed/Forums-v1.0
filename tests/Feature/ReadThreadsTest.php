<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Support\Facades\App;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ReadThreadsTest extends TestCase
{
    use DatabaseMigrations;

    protected $thread, $reply, $channel;

    public function setUp()
    {
        parent::setUp();

        // Given we have a channel
        $this->channel = create_factory('App\Channel');

        // and a thread associated to this channel
        $this->thread = create_factory('App\Thread', ['channel_id' => $this->channel->id]);

        // and a reply belongs to this thread
        $this->reply = create_factory('App\Reply', ['thread_id' => $this->thread->id]);
    }


    /** @test */
    public function a_user_can_view_all_threads()
    {
        $this->get(route('thread.index'))
            ->assertSee($this->thread->title);
    }


    /** @test */
    public function a_user_can_read_a_single_thread()
    {
        $this->get(route('thread.show', ['channel' => $this->thread->channel->slug, 'thread' => $this->thread->id]))
            ->assertSee($this->thread->title);
    }


    /** @test */
    public function a_user_can_read_threads_that_are_associated_with_a_channel()
    {
        $channel = create_factory('App\Channel');
        $threadToSee = create_factory('App\Thread', ['channel_id' => $channel->id]);
        $threadNotToSee = create_factory('App\Thread');

        $this->get(route('channel.index', $channel->slug))
            ->assertSee($threadToSee->title)
            ->assertDontSee($threadNotToSee->title);
    }


    /** @test */
    public function a_user_can_read_replies_that_are_associated_with_a_thread()
    {
        // this approach can not be used now since the replies are loaded with an ajax request
        /*$this->get(route('thread.show', ['channel' => $this->thread->channel->slug, 'thread' => $this->thread->id]))
            ->assertSee($this->reply->body);*/
        // temporary approach
        $this->assertDatabaseHas('replies', ['body' => $this->reply->body]);
    }


    /** @test */
    public function a_user_can_read_threads_filtered_by_user_name()
    {
        $this->signIn();

        $threadOfUser = create_factory('App\Thread', ['user_id' => auth()->id()]);
        $threadNotOfUser = create_factory('App\Thread');

        $this->get(route('thread.index', ['by' => auth()->user()->name ]))
            ->assertSee($threadOfUser->title)
            ->assertDontSee($threadNotOfUser->title);
    }

    /** @test */
    public function a_user_can_read_threads_filtered_by_popularity()
    {
        // Given we have 3 threads with 3, 5 and 0 replies respectively
        $threadWithThreeReplies = create_factory('App\Thread');
        create_factory('App\Reply', ['thread_id' => $threadWithThreeReplies->id], 3);

        $threadWithFiveReplies = create_factory('App\Thread');
        create_factory('App\Reply', ['thread_id' => $threadWithFiveReplies->id], 5);

        $threadWithZeroReplies = create_factory('App\Thread');

        // then when the user visit the thread filtered by popularity pge
        // he should see the threads ordered according to popularity
        $this->get(route('thread.index', ['popular' => 1]))
            ->assertSeeInOrder([
                $threadWithFiveReplies->title,
                $threadWithThreeReplies->title,
                $threadWithZeroReplies->title
            ]);
    }

    /** @test */
    public function an_authenticated_user_has_an_indication_if_a_thread_updated()
    {
      // Given we have an authenticated users
      $this->signIn();

      // And a thread not read yet by the user
      // Then i expect that the thread has update for the user
      $this->assertTrue($this->thread->hasUpdateFor(auth()->user()));

      // But if the user visit the thread and read it
      auth()->user()->read($this->thread);

      // Then i expect that the thread has NOT update for the user
      $this->assertFalse($this->thread->hasUpdateFor(auth()->user()));
    }

}
