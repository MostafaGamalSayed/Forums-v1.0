<?php

namespace App\Notifications;

use App\Reply;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class ReplyWasFavorite extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    protected $reply;
    /**
     * @var
     */
    protected $userName;

    public function __construct(Reply $reply, $userName)
    {
        $this->reply = $reply;
        $this->userName = $userName;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    //TODO
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->line('Hello' . $this->userName . ',the thread you were watching has been updated')
                    ->action('Notification Action', url(route('thread.show', ['channel' => $this->reply->thread->channel->slug, 'thread'=> $this->reply->thread->slug])))
                    ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'message' =>$this->userName  . ' ' . 'favorite your reply',
            'thread' => $this->reply->thread,
        ];
    }
}
