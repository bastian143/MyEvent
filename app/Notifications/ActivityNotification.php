<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ActivityNotification extends Notification
{
    use Queueable;

    public $message;
    public $redirectUrl;

    /**
     * Create a new notification instance.
     */
    public function __construct(string $message, string $redirectUrl = null)
    {
        $this->message = $message;
        $this->redirectUrl = $redirectUrl ?: route('dashboard');
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'message' => $this->message,
            'url' => route('notifications.read', ['id' => $this->id, 'redirect_url' => $this->redirectUrl])
        ];
    }
}
