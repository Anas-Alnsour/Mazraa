<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Farm;
use App\Models\FarmBooking; // تأكد أن اسم الموديل عندك FarmBooking وليس Booking
use Illuminate\Support\Facades\Auth;

class OwnerDashboardController extends Controller
{
    /**
     * عرض لوحة تحكم صاحب المزرعة مع الإحصائيات الحقيقية
     */
    public function index()
    {
        $user = Auth::user();

        // [1] التحقق من الصلاحيات (Security Check)
        if ($user->role !== 'farm_owner') {
            abort(403, 'Unauthorized access.');
        }

        // [2] جلب مزارع هذا المالك فقط
        $farms = Farm::where('owner_id', $user->id)->get();
        $farmIds = $farms->pluck('id');

        // [3] إحصائيات المزارع
        $totalFarms = $farms->count();

        // [4] الحجوزات النشطة (التي تنتظر الموافقة أو المؤكدة)
        // عرضنا في التصميم Active Bookings و Pending Approval
        $activeBookingsCount = FarmBooking::whereIn('farm_id', $farmIds)
            ->whereIn('status', ['confirmed', 'pending'])
            ->count();

        $pendingApprovalCount = FarmBooking::whereIn('farm_id', $farmIds)
            ->where('status', 'pending')
            ->count();

        // [5] حساب صافي الأرباح (Net Revenue)
        // نحسب فقط الحجوزات المؤكدة (confirmed)
        $totalRevenue = FarmBooking::whereIn('farm_id', $farmIds)
            ->where('status', 'confirmed')
            ->sum('net_profit');

        // [6] جلب آخر 5 نشاطات حجوزات مع بيانات المستخدم والمزرعة
        $recentBookings = FarmBooking::with(['user', 'farm'])
            ->whereIn('farm_id', $farmIds)
            ->latest()
            ->take(5)
            ->get();

        // [7] تمرير البيانات للـ View
        return view('owner.dashboard', [
            'totalFarms' => $totalFarms,
            'activeBookingsCount' => $activeBookingsCount,
            'pendingApprovalCount' => $pendingApprovalCount,
            'totalRevenue' => $totalRevenue,
            'recentBookings' => $recentBookings,
            'farms' => $farms // في حال احتجت قائمة المزارع في الدروب داون مثلاً
        ]);
    }
    /**
     * عرض جميع الحجوزات الواردة لمزارع المالك (مع الفلترة)
     */
    public function bookings(Request $request)
    {
        $user = Auth::user();

        // جلب معرفات مزارع المالك
        $farmIds = Farm::where('owner_id', $user->id)->pluck('id');

        // بناء الاستعلام مع العلاقات
        $query = FarmBooking::with(['farm', 'user'])
            ->whereIn('farm_id', $farmIds);

        // تطبيق الفلتر حسب الحالة إذا وجد
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // ترتيب حسب أحدث الحجوزات وعمل Pagination
        $bookings = $query->orderBy('created_at', 'desc')->paginate(15);

        // الاحتفاظ بالفلتر في روابط الـ Pagination
        $bookings->appends($request->all());

        return view('owner.bookings.index', compact('bookings'));
    }
    // الموافقة على الحجز
public function approveBooking($id)
{
    $booking = FarmBooking::findOrFail($id);

    // التحقق من الملكية (لضمان أن المالك لا يوافق على حجز لمزرعة غير تابعة له)
    if ($booking->farm->owner_id !== auth()->id()) {
        abort(403);
    }

    $booking->update(['status' => 'confirmed']);

    return back()->with('success', 'Booking confirmed successfully!');
}

// رفض الحجز
public function rejectBooking($id)
{
    $booking = FarmBooking::findOrFail($id);

    if ($booking->farm->owner_id !== auth()->id()) {
        abort(403);
    }

    $booking->update(['status' => 'cancelled']);

    return back()->with('error', 'Booking has been rejected.');
}
}
