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
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->subject('Congratulations! Your Farm is Approved')
                    ->greeting('Hello ' . $notifiable->name . ',')
                    ->line('Great news! Your farm listing for "' . $this->farm->name . '" has been reviewed and approved by our moderation team.')
                    ->line('It is now live on Mazraa.com and available for bookings.')
                    ->action('Go to Owner Dashboard', url('/owner/dashboard'))
                    ->line('We are excited to have you as a partner!');
    }
}
