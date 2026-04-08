<?php

namespace App\Notifications;

use App\Models\Farm;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class FarmApprovedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $farm;

    public function __construct(Farm $farm)
    {
        $this->farm = $farm;
    }

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->subject('Congratulations! Your Farm is Approved')
                    ->view('mail.farm-approved', [
                        'farm' => $this->farm,
                        'notifiable' => $notifiable
                    ]);
    }

    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Farm Approved',
            'message' => 'Your farm listing "' . ($this->farm->name ?? 'N/A') . '" has been approved.',
            'action_url' => route('owner.dashboard')
        ];
    }
}
