<?php

namespace App\Notifications;

use App\Models\SupplyOrder;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewSupplyOrderNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $order;

    public function __construct(SupplyOrder $order)
    {
        $this->order = $order;
    }

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->subject('New Supply Order Assigned: #' . $this->order->order_id)
                    ->line('You have been assigned a new supply delivery order.')
                    ->line('Order ID: ' . $this->order->order_id)
                    ->line('Item: ' . ($this->order->supply->name ?? 'Supply Item'))
                    ->action('View Order', route('supply.driver.dashboard'))
                    ->line('Thank you for using Mazraa!');
    }

    public function toArray(object $notifiable): array
    {
        return [
            // تعديل المفتاح إلى url ليتوافق مع الـ Dropdown الجديد
            'title' => 'New Supply Order',
            'message' => 'Action Required: You have been assigned to deliver supply order #' . $this->order->order_id . '.',
            'url' => route('supply.driver.dashboard')
        ];
    }
}