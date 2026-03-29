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

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('New Transport Dispatch Assigned')
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line('You have been assigned to a new transport trip.')
            ->line('Pickup Location: ' . $this->transport->start_and_return_point)
            ->line('Destination Farm: ' . ($this->transport->farm->name ?? 'N/A'))
            ->line('Pickup Time: ' . $this->transport->Farm_Arrival_Time->format('M d, Y h:i A'))
            ->line('Number of Passengers: ' . $this->transport->passengers)
            ->action('View Trip Details', route('transport.driver.dashboard'))
            ->line('Please be ready at the pickup location on time.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'transport_id' => $this->transport->id,
            'message' => 'You have been assigned to a new trip to ' . ($this->transport->farm->name ?? 'Farm'),
            'pickup_time' => $this->transport->Farm_Arrival_Time->format('Y-m-d H:i:s'),
            'passengers' => $this->transport->passengers,
        ];
    }
}
