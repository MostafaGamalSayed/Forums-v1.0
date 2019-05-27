<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class ManageThreadsTest extends TestCase
{
    use DatabaseMigrations;


    /** @test */
    public function an_authenticated_and_authorized_user_can_delete_a_thread()
    {
        // Given we have an authenticated user,
        $this->signIn();

        // a thread created by the authenticated user,
        $threadCreatedByAuthUser = create_factory('App\Thread', ['user_id' => auth()->id()]);

        // another thread created by another user[Not auth user],
        $threadCreatedByAnotherUser = create_factory('App\Thread');

        // and a reply associated wih with the thread which created by the authenticated user
        $reply = create_factory('App\Reply', ['thread_id' => $threadCreatedByAuthUser->id]);

        // If the auth user delete his own thread,
        $this->delete(route('thread.destroy', $threadCreatedByAuthUser->id));

        // Then the thread will be deleted from the database,
        $this->assertDatabaseMissing('threads', ['id' => $threadCreatedByAuthUser->id]);

        // also any reply associated with the deleted thread will be deleted
        $this->assertDatabaseMissing('replies', ['id' => $reply->id]);

        // Enable exception handling
        $this->withExceptionHandling();

        // But if the auth user try to delete a thread created by another user
        $response =  $this->delete(route('thread.destroy', $threadCreatedByAnotherUser->id));

        // Then the thread will - NOT - be deleted from the database
        $this->assertDatabaseHas('threads', ['id' => $threadCreatedByAnotherUser->id]);

        // and the response return [A 403 Forbidden error]
        $response->assertStatus(403);
    }

    /** @test */
    public function delete_a_thread_or_a_reply_will_delete_the_user_activities_associated_with_it()
    {
        // Given we have an authenticated user,
        $this->signIn();

        // a thread created by the authenticated user,
        $thread = create_factory('App\Thread', ['user_id' => auth()->id()]);

        $reply = create_factory('App\Reply', ['thread_id' => $thread->id]);

        // If the auth user delete his own thread,
        $this->delete(route('thread.destroy', $thread->id));


        // Then the thread will be deleted from the database,
        $this->assertDatabaseMissing('threads', ['id' => $thread->id]);

        // The user activity of the the thread should be also deleted
        $this->assertDatabaseMissing('activities', [
            'subject_id' => $thread->id,
            'user_id' => auth()->id(),
            'type' => 'created_thread'
        ]);

        // The user activity associated with a deleted reply should be deleted
        $this->assertDatabaseMissing('activities', [
            'subject_id' => $reply->id,
            'type' => 'created_reply',
            'user_id' => auth()->id()
        ]);
    }

}
