<?php

namespace App\Notifications;

use App\Models\FarmBooking;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BookingConfirmedNotification extends Notification
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
                    ->subject('Booking Confirmed: ' . $this->booking->farm->name)
                    ->view('mail.booking-confirmed', [
                        'booking' => $this->booking,
                        'notifiable' => $notifiable
                    ]);
    }

    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'New Booking Confirmed',
            'message' => 'Farm: ' . $this->booking->farm->name,
            'action_url' => route('bookings.show', $this->booking->id)
        ];
    }
}
