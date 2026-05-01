<?php

namespace App\Notifications;

use App\Models\Transport;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class DriverAssignedNotification extends Notification
{
    use Queueable;

    protected $transport;

    public function __construct(Transport $transport)
    {
        $this->transport = $transport;
    }

    public function via(object $notifiable): array
    {
        return ['database']; // شلنا الإيميل والـ Queue عشان يشتغل فوراً بالـ Localhost
    }

    public function toArray(object $notifiable): array
    {
        $message = $notifiable->role === 'transport_driver'
            ? 'You have been assigned a new trip to ' . ($this->transport->farm->name ?? 'a farm') . '. Click for details.'
            : 'Captain ' . ($this->transport->driver->user->name ?? 'assigned') . ' is confirmed for your trip to ' . ($this->transport->farm->name ?? 'the farm') . '.';

        return [
            'title' => 'Trip Assignment Update',
            'message' => $message,
            'url' => match($notifiable->role) {
                'transport_driver' => route('transport.driver.dashboard'),
                'transport_company' => route('transport.dashboard'),
                'user' => route('bookings.my_bookings'),
                default => route('dashboard'),
            }
        ];
    }
}