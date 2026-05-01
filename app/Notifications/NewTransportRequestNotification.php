<?php

namespace App\Notifications;

use App\Models\Transport;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewTransportRequestNotification extends Notification
{
    use Queueable;
    protected $transport;

    public function __construct(Transport $transport) 
    { 
        $this->transport = $transport; 
    }

    public function via(object $notifiable): array 
    { 
        // نستخدم قاعدة البيانات فقط لضمان السرعة في البيئة المحلية
        return ['database']; 
    }
    
    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'New Transit Request',
            'message' => 'A new transport request to ' . ($this->transport->farm->name ?? 'a farm') . ' is waiting for assignment.',
            // التأكد من أن الرابط يوجه لداشبورد شركة التوصيل لرؤية الطلبات المعلقة
            'url' => route('transport.dashboard') 
        ];
    }
}