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
        preg_match_all('/\@([^\s\.]+)/', $event->reply->body, $matches);

        $names = $matches[1];

        foreach($names as $name){
          $user = User::where('name', $name)->first();

          if($user){
            $user->notify(new YouWereMentioned($event->reply));
          }
        }
    }
}
