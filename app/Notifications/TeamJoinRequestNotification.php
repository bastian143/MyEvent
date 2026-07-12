<?php

namespace App\Notifications;

use App\Models\TeamJoinRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TeamJoinRequestNotification extends Notification
{
    use Queueable;

    public $joinRequest;

    /**
     * Create a new notification instance.
     */
    public function __construct(TeamJoinRequest $joinRequest)
    {
        $this->joinRequest = $joinRequest;
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
            'team_id' => $this->joinRequest->team_id,
            'event_id' => $this->joinRequest->team->event_id,
            'message' => "{$this->joinRequest->user->name} has requested to join your team '{$this->joinRequest->team->name}'.",
            'request_id' => $this->joinRequest->id,
            'url' => route('notifications.read', ['id' => $this->id, 'redirect_url' => route('user.teams.show', $this->joinRequest->team_id)])
        ];
    }
}
