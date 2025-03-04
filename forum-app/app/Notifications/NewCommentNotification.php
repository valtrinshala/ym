<?php

namespace App\Notifications;

use App\Models\Comment;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewCommentNotification extends Notification
{
    use Queueable;

    protected $comment;

    /**
     * Create a new notification instance.
     */
    public function __construct(Comment $comment)
    {
        $this->comment = $comment;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via($notifiable)
    {
        // We want to send email notifications.
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable)
    {
        // Create a mail message.
        return (new MailMessage)
            ->subject('New Comment on Your Post')
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line('A new comment has been added to your post titled "' . $this->comment->post->title . '".')
            ->line('Comment: "' . $this->comment->body . '"')
            ->action('View Post', url(route('posts.show', $this->comment->post->id)))
            ->line('Thank you for using our forum!');
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray($notifiable)
    {
        return [
            'comment_id' => $this->comment->id,
            'post_id'    => $this->comment->post->id,
            'body'       => $this->comment->body,
        ];
    }
}
