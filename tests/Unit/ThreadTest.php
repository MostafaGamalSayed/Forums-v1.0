<?php

namespace Tests\Unit;

use App\Channel;
use App\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ThreadTest extends TestCase
{
    use DatabaseMigrations;

    protected $thread;


    public function setUp()
    {
        parent::setUp();

        $this->thread = create_factory('App\Thread');
    }


    /** @test */
    public function a_thread_has_owner()
    {
        $this->assertInstanceOf(User::class, $this->thread->owner);
    }


    /** @test */
    public function a_thread_belongs_to_a_channel()
    {
        $this->assertInstanceOf(Channel::class, $this->thread->channel);
    }


    /** @test */
    public function a_thread_can_has_replies()
    {
        $this->assertInstanceOf(Collection::class, $this->thread->replies);
    }


    /** @test */
    public function a_thread_can_add_a_reply()
    {
        $this->thread->addReply([
            'user_id' => 1,
            'body' => 'test body'
        ]);

        $this->assertCount(1, $this->thread->replies);
    }


    /** @test */
    public function a_user_can_add_a_thread()
    {
        $this->signIn($user = create_factory('App\User'));

        $user->addThread([
            'title' => 'Test title',
            'body' => 'Test body',
            'channel_id' => '1'
        ]);

        $this->assertCount(1, $user->threads);
    }

    /** @test*/
    public function a_thread_can_be_subscribed_to()
    {
        // Given we have an authenticated user
        $this->signIn();

        // a thread created by any user
        $thread = create_factory('App\Thread');

        // If the authenticated user subscribed to the given thread
        $thread->subscribe();

        // Then the subscription list of the user will be updated
        $this->assertEquals(1, $thread->subscriptions()->where('user_id', auth()->id())->count());
    }

    /** @test */
    public function a_thread_can_be_un_subscribed_from()
    {
        // Given we have an authenticated user
        $this->signIn();

        // a thread created by any user
        $thread = create_factory('App\Thread');

        // If the authenticated user subscribed to the given thread
        $thread->subscribe();

        // Then i expect the thread subscriptions count will be 1
        $this->assertEquals(1, $thread->subscriptions()->where('user_id', auth()->id())->count());

        // if the user un-subscribed from the thread
        $thread->unSubscribe();

        // Then i expect the thread subscriptions count will be 0
        $this->assertEquals(0, $thread->subscriptions()->where('user_id', auth()->id())->count());
    }

    /** @test */
    public function it_knows_if_a_thread_is_subscribed_by_an_authenticated_user()
    {
        // Given we have an authenticated user
        $this->signIn();

        // And a thread
        $thread = create_factory('App\Thread');

        //If the thread subscribed by the user
        $thread->subscribe();

        // Then i expect it will return [True] when check whether the thread is subscribed
        $this->assertTrue($thread->isSubscribed);
    }
}
