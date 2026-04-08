<?php

namespace App\Notifications;

use App\Models\SupplyOrder;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SupplyDriverAssignedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $invoiceId;

    public function __construct($invoiceId)
    {
        $this->invoiceId = $invoiceId;
    }

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('New Supply Dispatch Assigned')
            ->view('mail.supply-driver-assigned', [
                'invoiceId' => $this->invoiceId,
                'notifiable' => $notifiable
            ]);
    }

    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'New Driver Assignment',
            'message' => 'You have been assigned to deliver supply order #' . $this->invoiceId,
            'action_url' => $notifiable->role === 'supply_driver' ? route('supply.driver.dashboard') : ($notifiable->role === 'admin' ? route('admin.dashboard') : route('supplies.dashboard'))
        ];
    }
}
