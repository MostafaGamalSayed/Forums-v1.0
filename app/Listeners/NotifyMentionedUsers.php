<?php

namespace App\Listeners;

use App\Events\ReplyWasCreated;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\User;
use App\Notifications\YouWereMentioned;

class NotifyMentionedUsers
{

    /**
     * Handle the event.
     *
     * @param  ReplyWasCreated  $event
     * @return void
     */
    public function handle(ReplyWasCreated $event)
    {
        User::whereIn('name', $event->reply->getMentionedUsers())
          ->get()
          ->each(function($user) use ($event){
              $user->notify(new YouWereMentioned($event->reply));
          });
    }
}
