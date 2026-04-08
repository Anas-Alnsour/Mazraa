<?php

namespace App\Notifications;

use App\Models\FarmBooking;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewBookingReceivedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $booking;

    public function __construct(FarmBooking $booking)
    {
        $this->booking = $booking;
    }

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->subject('New Booking Received: ' . $this->booking->farm->name)
                    ->view('mail.new-booking-received', [
                        'booking' => $this->booking,
                        'notifiable' => $notifiable
                    ]);
    }

    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'New Booking Received',
            'message' => 'You have received a new paid booking for ' . ($this->booking->farm->name ?? 'your farm') . '.',
            'action_url' => route('owner.bookings.index')
        ];
    }
}
