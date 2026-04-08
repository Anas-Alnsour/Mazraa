<?php

namespace App\Notifications;

use App\Models\Transport;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DriverAssignedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $transport;

    /**
     * Create a new notification instance.
     */
    public function __construct(Transport $transport)
    {
        $this->transport = $transport;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('New Transport Dispatch Assigned')
            ->view('mail.driver-assigned', [
                'transport' => $this->transport,
                'notifiable' => $notifiable
            ]);
    }

    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'New Driver Assignment',
            'message' => 'You have been assigned to a new trip to ' . ($this->transport->farm->name ?? 'Farm'),
            'action_url' => route('transport.driver.dashboard')
        ];
    }
}
