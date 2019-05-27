<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class createThreadsTest extends TestCase
{
    use DatabaseMigrations;

    protected $thread;

    public function setUp()
    {
        parent::setUp();

        $this->thread = make_factory('App\Thread');

    }


    /** @test */
    public function an_authenticated_user_can_create_a_thread()
    {
        $this->signIn();
        $thread = [
            'title' => 'Test title',
            'body' => 'Test body',
            'channel' => create_factory('App\Channel')->id
        ];
        $response = $this->post(route('thread.store'), $thread);
        $this->get($response->headers->get('Location'))
            ->assertSee($thread['title'])
            ->assertSee($thread['body']);
    }


    /** @test */
    public function unauthenticated_user_can_not_create_a_thread()
    {
        $this->expectException('Illuminate\Auth\AuthenticationException');

        $this->post(route('thread.store'), $this->thread->toArray());
    }

    /** @test */
   /*public function an_authenticated_user_will_redirect_to_show_thread_page_after_create_a_thread()
    {
        // Given we have an authenticated user
        $this->signIn();

        // and the user create a new thread
        $thread = [
            'title' => 'Test title',
            'body' => 'Test body',
            'channel' => create_factory('App\Channel')->id
        ];
        $response = $this->post(route('thread.store'), $thread);

        dd($response->headers->get('Location'));

        //then the system should redirect to show thread page
        $response->assertRedirect($response->headers->get('Location'));
    }*/


    /** @test */
    public function a_thread_requires_a_title()
    {
        $this->publishThread(['title' => null])
            ->assertSessionHasErrors('title');
    }


    /** @test */
    public function a_thread_requires_a_body()
    {
        $this->publishThread(['body' => null])
            ->assertSessionHasErrors('body');
    }


    /** @test */
    public function a_thread_requires_a_channel()
    {
        $this->publishThread(['channel' => ''])
            ->assertSessionHasErrors('channel');
    }


    /** @test */
    public function a_thread_requires_a_valid_channel()
    {
        $channel = factory('App\Channel', 2)->create();

        $this->publishThread(['channel' => '10000000'])
            ->assertSessionHasErrors('channel');
    }


    public function publishThread($attributes = [])
    {
        return $this->withExceptionHandling()
                ->signIn()
                ->post(route('thread.store'), make_factory('App\Thread', $attributes)->toArray());
    }
}
