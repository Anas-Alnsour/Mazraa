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
                    ->subject('New Booking Request: ' . ($this->booking->farm->name ?? 'Your Farm'))
                    ->view('mail.new-booking-received', [
                        'booking' => $this->booking,
                        'notifiable' => $notifiable
                    ]);
    }

    public function toArray(object $notifiable): array
    {
        return [
            // توحيد شكل البيانات
            'title' => 'New Booking Request',
            'message' => 'A new booking request for ' . ($this->booking->farm->name ?? 'your farm') . ' needs your approval.',
            'url' => route('owner.bookings.show', $this->booking->id)
        ];
    }
}