<?php

namespace App\Notifications;

use App\Models\FinancialTransaction;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PayoutProcessedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $transaction;

    public function __construct(FinancialTransaction $transaction)
    {
        $this->transaction = $transaction;
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->subject('Payout Successfully Processed')
                    ->greeting('Hello ' . $notifiable->name . ',')
                    ->line('We have successfully processed a payout to your account.')
                    ->line('Amount Transferred: ' . number_format($this->transaction->amount, 2) . ' JOD')
                    ->line('The funds should appear in your bank account shortly.')
                    ->action('Login to Portal', url('/portal/login'))
                    ->line('Thank you for partnering with Mazraa.com!');
    }
}
