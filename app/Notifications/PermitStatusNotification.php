<?php

namespace App\Notifications;

use App\Models\Permit;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\DatabaseMessage;
use Illuminate\Notifications\Notification;

class PermitStatusNotification extends Notification
{
    use Queueable;

    protected $permit;
    protected $title;
    protected $body;
    protected $type;
    protected $rejectionReason;

    /**
     * Create a new notification instance.
     */
    public function __construct(Permit $permit, string $title, string $body, string $type = 'info', ?string $rejectionReason = null)
    {
        $this->permit = $permit;
        $this->title = $title;
        $this->body = $body;
        $this->type = $type;
        $this->rejectionReason = $rejectionReason;
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
     * Get the database representation of the notification.
     */
    public function toDatabase(object $notifiable): array
    {
        return [
            'title' => $this->title,
            'body' => $this->body,
            'type' => $this->type,
            'permit_id' => $this->permit->permit_id,
            'rejection_reason' => $this->rejectionReason,
            'action' => [
                'label' => 'Lihat Permit',
                'url' => route('filament.admin.resources.permits.index')
            ]
        ];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'title' => $this->title,
            'body' => $this->body,
            'type' => $this->type,
            'permit_id' => $this->permit->permit_id,
            'rejection_reason' => $this->rejectionReason,
        ];
    }
}