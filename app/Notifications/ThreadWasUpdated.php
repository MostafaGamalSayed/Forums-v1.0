<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class ThreadWasUpdated extends Notification
{
    use Queueable;

    protected $thread;
    protected $reply;
    protected $username;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($thread, $reply)
    {
        $this->thread = $thread;
        $this->reply = $reply;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database','mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
      return (new MailMessage)
                  ->line('Hello,A thread you have been watching was updated by')
                  ->action('Notification Action', url(route('thread.show', ['channel' => $this->thread->channel->slug, 'thread'=> $this->thread->id])))
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
            'thread' => $this->thread,
            'message' => 'a thread you have been watching was updated by' . ' ' . $this->reply->owner->name,
        ];
    }
}
