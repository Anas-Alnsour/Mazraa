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
                    ->subject('Booking Confirmed: ' . ($this->booking->farm->name ?? 'Farm'))
                    ->view('mail.booking-confirmed', [
                        'booking' => $this->booking,
                        'notifiable' => $notifiable
                    ]);
    }

    public function toArray(object $notifiable): array
    {
        return [
            // توحيد المفاتيح حسب النظام الجديد
            'title' => 'Booking Confirmed',
            'message' => 'The reservation for ' . ($this->booking->farm->name ?? 'the farm') . ' has been successfully confirmed.',
            'url' => $notifiable->role === 'farm_owner' 
                ? route('owner.bookings.show', $this->booking->id) 
                : route('bookings.show', $this->booking->id)
        ];
    }
}