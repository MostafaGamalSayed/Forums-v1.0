<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ParticipateInForumTest extends TestCase
{
    use DatabaseMigrations;

    protected $thread, $reply;

    public function setUp()
    {
        parent::setUp();
        $this->thread = create_factory('App\Thread');
        $this->reply = create_factory('App\Reply');

    }


   /** @test */
    public function an_authenticated_user_can_create_a_reply()
    {

        $this->signIn($user = create_factory('App\User'));


        $reply = make_factory('App\Reply');

        $this->post(route('reply.store', ['channel' => $this->thread->channel->slug,'thread_id' => $this->thread->id]), $reply->toArray());

        $this->assertDatabaseHas('replies', ['body' => $reply->body]);
    }


    /** @test */
    public function unauthenticated_user_can_not_participate_in_forum()
    {
        $this->expectException('Illuminate\Auth\AuthenticationException');

        $this->post(route('reply.store', ['channel' => $this->thread->channel->slug,'thread_id' => $this->thread->id]), $this->reply->toArray());
    }


    /** @test */
    public function a_reply_requires_a_body()
    {
        $this->withExceptionHandling()->signIn();

        $reply = create_factory('App\Reply', ['body' => '']);

        $this->post(route('reply.store',['channel' => $this->thread->channel->slug, 'thread' => $this->thread->id]), $reply->toArray())
            ->assertSessionHasErrors('body');


    }


    /** @test */
    public function an_authenticated_user_can_favourite_a_reply()
    {
        // Given we have an authenticated user
        $this->signIn();

        // if the user favourite a reply
        $this->json('POST', route('reply.favorite', $this->reply->id));

        // Then the favourites of a this reply will be increased
        $this->assertCount(1, $this->reply->favorites);

    }

    /** @test */
    public function a_guest_can_not_favorite_a_reply()
    {
        // if a guest user favourite a reply, Then the user will be redirected to login page
        $this->withExceptionHandling()->post(route('reply.favorite', $this->reply->id), $this->reply->toArray())
            ->assertRedirect(route('login'));
    }

    /** @test */
    public function a_user_can_un_favorite_a_reply()
    {
        // Given we have an authenticated user
        $this->signIn();

        // if the user favorite a reply
        $reply = create_factory('App\Reply');
        $reply->favorite();

        // I expect the count of the reply's favorites to be 1
        $this->assertCount(1, $reply->favorites);

        // Then if the user can un-favorite the reply
        $this->json('DELETE', route('reply.unFavorite', $reply->id));
        //$this->delete(route('reply.unFavorite', $reply->id)); //this method will not work i don't know why!!->TODO

        // i expect the reply will has no favorites
        $this->assertDatabaseMissing('favorites', ['user_id' => auth()->id(), 'favoritable_id' => $reply->id]);


    }


    /** @test */
    public function a_guest_or_un_authorized_user_can_not_delete_a_reply()
    {
        $this->withExceptionHandling();

        // Given we have a reply
        $reply = create_factory('App\Reply');

        // If a guest try to delete a reply
        $response = $this->delete(route('reply.destroy', ['thread' => $reply->thread->id, 'reply' => $reply->id]));

        // Then it should be redirected to the login page
        $response->assertRedirect(route('login'));

        // If the user signed in
        $this->signIn();

        // The authenticated user try to delete a reply of another user
        $response = $this->delete(route('reply.destroy', ['thread' => $reply->thread->id, 'reply' => $reply->id]));

        // Then the response should return [403 forbidden error]
        $response->assertStatus(403);
    }


    /** @test */
    public function an_authorized_user_can_delete_a_reply()
    {
        // Given we have an authenticated user
        $this->signIn();

        // The authenticated user created a reply
        $reply = create_factory('App\Reply', ['user_id' => auth()->id()]);

        // If the user delete his own reply
        $this->delete(route('reply.destroy', ['thread' => $reply->thread->id, 'reply' => $reply->id]));

        // Then the reply will be deleted from the database
        $this->assertDatabaseMissing('replies', ['id' => $reply->id]);

        // Also the activities associated with the reply should be deleted
        $this->assertDataBaseMissing('activities', [
            'subject_id' => $reply->id,
            'user_id' => auth()->id(),
        ]);
    }


    /** @test */
    public function authorized_user_can_update_a_reply()
    {
        // Given we have an authenticated user
        $this->signIn();

        // The authenticated user created a reply
        $reply = create_factory('App\Reply', ['user_id' => auth()->id()]);

        $updatedValue = 'The reply is updated';

        // If the user update his own reply
        $this->patch(route('reply.update', ['thread' => $reply->thread->id, 'reply' => $reply->id]), ['body' => $updatedValue]);

        // Then i expect the database to reflect this change
        $this->assertDatabaseHas('replies', [
            'body' => $updatedValue
        ]);
    }

    /** @test */
    public function a_guest_and_un_authorized_user_can_not_update_a_reply()
    {
        $this->withExceptionHandling();

        // Given we have a reply
        $reply = create_factory('App\Reply');

        $updatedValue = 'The reply is updated';

        // If a guest try to update the reply
        $response = $this->patch(route('reply.update', ['thread' => $reply->thread->id, 'reply' => $reply->id]), ['body' => $updatedValue]);

        // Then i expect to redirect to the login page
        $response->assertRedirect(route('login'));

        // If the user signed in
        $this->signIn();

        // If un authorized user try to update a reply
        $response = $this->patch(route('reply.update', ['thread' => $reply->thread->id, 'reply' => $reply->id]), ['body' => $updatedValue]);

        // Then i expect the response to return a 403 forbidden error
        $response->assertStatus(403);


    }


    /** @test */
    /*public function an_authenticated_user_can_not_favorite_a_reply_twice()
    {
        // Given we have an authenticated user
        $this->signIn();

        // if the user favourite a reply twice
        $this->post(route('reply.favorite', $this->reply->id), $this->reply->toArray());
        $this->post(route('reply.favorite', $this->reply->id), $this->reply->toArray());
    }*/
}
