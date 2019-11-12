<?php

namespace Tests\Feature;

use App\Notifications\ThreadWasUpdated;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;
use Carbon\Carbon;

class NotificationTest extends TestCase
{
    use DatabaseMigrations;


    /** @test */
    public function a_notification_created_to_a_subscribed_thread_if_any_user_except_the_subscribed_user_left_a_reply()
    {
        // Given we have an authenticated user
        $this->signIn();

        // and a thread
        $thread = create_factory('App\Thread');

        // and the user subscribed to the thread
        $thread->subscribe($user = auth()->user());

        $this->assertEquals(0 , auth()->user()->notifications->count());

        // then if any user left a reply to the subscribed thread
        $reply = make_factory('App\Reply', [
          'created_at' => Carbon::now()
        ]);

        $this->json('POST',route('reply.store', ['channel' => $thread->channel->slug, 'thread' => $thread->id]), $reply->toArray());

        // Now i expect the user notifications will be 1
        $this->assertEquals(1, auth()->user()->fresh()->notifications->count()); //Don't forget to use fresh()
        //$this->assertDatabaseHas('notifications', ['type' => ThreadWasUpdated::class, 'notifiable_type' => get_class(auth()->user()), 'notifiable_id' => auth()->id()]);
    }

    /** @test */
    public function a_user_can_fetch_their_unread_notifications()
    {
        // Given we have an authenticated user
        $this->signIn();

        create_factory(DatabaseNotification::class);

        //The user can fetch the notification for this reply
        $response = $this->json('GET', route('notification.index', ['user' => auth()->user()->name]));
        $response->assertStatus(200)
            ->assertJsonStructure(
                [
                    [
                        'id',
                        'type',
                        'notifiable_type',
                        'notifiable_id',
                        'data',
                        'read_at',
                        'created_at',
                        'updated_at',
                    ]
                ]
            );
        $notificationData = $response->getData('data')[0];
        $this->assertEquals($notificationData['notifiable_id'], auth()->id());
    }

    /** @test */
    public function a_user_can_mark_a_notification_as_read()
    {
        $this->signIn();

        $notification = create_factory(DatabaseNotification::class);

        $response = $this->json('DELETE', route('notification.destroy', ['user' => auth()->user()->name, 'notification' => $notification->id]));

        $response->assertStatus(200);

    }

    /** @test */
    public function a_notification_is_created_for_a_user_when_their_reply_is_favorite_by_another_user()
    {
        // Given we have an authenticated user has no notifications
        $this->signIn();

        $this->assertEquals(0, auth()->user()->notifications->count());

        // This user create a reply
        $reply = create_factory('App\Reply', ['user_id' => auth()->id()]);

        // If the reply is favorite by another user
        $this->json('POST', route('reply.favorite', ['reply' => $reply->id]));

        // Then the user should receive a notification when his reply is favorite
        $this->assertEquals(1, auth()->user()->fresh()->notifications->count());
    }


    /** @test */
    public function all_subscribers_of_a_thread_receives_notification_when_a_reply_is_added()
    {
        Notification::fake();
        Notification::assertNothingSent();

        $this->signIn();

        // and a thread
        $thread = create_factory('App\Thread');

        // and the user subscribed to the thread
        $thread->subscribe();

        // then if any user left a reply to the subscribed thread
        $reply = create_factory('App\Reply');
        $this->post(route('reply.store', ['channel' => $thread->channel->slug,'thread_id' => $thread->id]), $reply->toArray());

        Notification::assertSentTo(auth()->user() ,ThreadWasUpdated::class);
    }





}
