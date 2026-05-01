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
        // تغيير نص الرسالة ليتناسب مع دور المتلقي
        $message = $notifiable->role === 'supply_driver'
            ? 'You have been assigned to deliver supply order #' . $this->invoiceId . '.'
            : 'A driver has been assigned to deliver supply order #' . $this->invoiceId . '.';

        return [
            'invoice_id' => $this->invoiceId,
            'title' => 'Driver Assigned',
            'message' => $message,
            'url' => match($notifiable->role) {
                'supply_driver' => route('supply.driver.dashboard'),
                'supply_company' => route('supplies.dashboard'),
                'admin' => route('admin.dashboard'),
                default => route('dashboard'),
            }
        ];
    }
}
