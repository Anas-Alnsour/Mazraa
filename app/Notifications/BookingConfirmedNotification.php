<?php

namespace App\Notifications;

use App\Models\FarmBooking;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BookingConfirmedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $booking;

    public function __construct(FarmBooking $booking)
    {
        $this->booking = $booking;
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->subject('Booking Confirmed: ' . $this->booking->farm->name)
                    ->greeting('Hello ' . $notifiable->name . ',')
                    ->line('Your booking for "' . $this->booking->farm->name . '" has been successfully confirmed.')
                    ->line('Check-in: ' . \Carbon\Carbon::parse($this->booking->start_time)->format('F j, Y, g:i a'))
                    ->line('Check-out: ' . \Carbon\Carbon::parse($this->booking->end_time)->format('F j, Y, g:i a'))
                    ->line('Total Paid: ' . number_format($this->booking->total_price, 2) . ' JOD')
                    ->action('View My Bookings', url('/my-bookings-list'))
                    ->line('Thank you for choosing Mazraa.com!');
    }
}
