<?php

namespace App\Notifications;

use App\Models\Permit;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PermitStatusNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Permit $permit,
        public string $title,
        public string $body,
        public string $type = 'info',
        public ?string $rejectionReason = null
    ) {}

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $mailMessage = (new MailMessage)
            ->subject($this->title)
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line($this->body);

        if ($this->permit) {
            $mailMessage->action('View Permit', route('filament.admin.resources.permits.index'));
        }

        if ($this->rejectionReason) {
            $mailMessage->line('Rejection Reason: ' . $this->rejectionReason);
        }

        return $mailMessage->line('Thank you for using JARTI Portal!');
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
            'action' => [
                'label' => 'Lihat Permit',
                'url' => route('filament.admin.resources.permits.index'),
            ],
        ];
    }
}
