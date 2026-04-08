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
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->subject('Payout Successfully Processed')
                    ->view('mail.payout-processed', [
                        'transaction' => $this->transaction,
                        'notifiable' => $notifiable
                    ]);
    }

    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Payout Processed',
            'message' => 'A payout of ' . number_format($this->transaction->amount ?? 0, 2) . ' JOD was successfully processed.',
            'action_url' => $notifiable->role === 'farm_owner' ? route('owner.financials') : ($notifiable->role === 'supply_company' ? route('supplies.dashboard') : route('transport.dashboard'))
        ];
    }
}
