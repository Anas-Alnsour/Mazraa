<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\User;

class PayoutRequestedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $amount;
    public $owner;

    public function __construct(User $owner, $amount)
    {
        $this->owner = $owner;
        $this->amount = $amount;
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Payout Requested',
            'message' => 'Vendor ' . $this->owner->name . ' requested a payout of ' . number_format($this->amount, 2) . ' JOD.',
            'action_url' => route('admin.payouts')
        ];
    }
}
