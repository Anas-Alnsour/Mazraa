<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * عرض صفحة صندوق الإشعارات بالكامل مع الترقيم (15 في كل صفحة)
     */
    public function index()
    {
        $notifications = Auth::user()->notifications()->paginate(15);
        return view('notifications.index', compact('notifications'));
    }

    /**
     * تحديد إشعار معين كمقروء وتوجيه المستخدم للرابط المطلوب
     */
    public function markAsReadAndRedirect($id)
    {
        $notification = Auth::user()->notifications()->findOrFail($id);

        // تحديد كـ مقروء
        $notification->markAsRead();
        
        // 💡 صمام أمان مطور: بيبحث في كل المفاتيح الممكنة لضمان التوجيه الصحيح
        $url = $notification->data['url'] 
               ?? $notification->data['action_url'] 
               ?? $notification->data['target_url'] 
               ?? route('dashboard'); // إذا ما لقى شي بيرجعه للداشبورد الأساسي
        
        return redirect($url);
    }

    /**
     * تحديد جميع الإشعارات كمقروءة (زر Clear All)
     */
    public function markAllAsRead()
    {
        Auth::user()->unreadNotifications->markAsRead();
        return back()->with('success', 'All notifications marked as read.');
    }
}