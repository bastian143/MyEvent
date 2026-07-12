<?php

namespace App\Notifications;

use App\Models\TeamJoinRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TeamInvitationNotification extends Notification
{
    use Queueable;

    public $invitation;

    /**
     * Create a new notification instance.
     */
    public function __construct(TeamJoinRequest $invitation)
    {
        $this->invitation = $invitation;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database']; // We only need database notifications for now
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'team_id' => $this->invitation->team_id,
            'event_id' => $this->invitation->team->event_id,
            'message' => "You have been invited to join team '{$this->invitation->team->name}' for the event '{$this->invitation->team->event->title}'.",
            'invitation_id' => $this->invitation->id,
            'url' => route('notifications.read', ['id' => $this->id, 'redirect_url' => route('front.events.show', $this->invitation->team->event->slug)])
        ];
    }
}
