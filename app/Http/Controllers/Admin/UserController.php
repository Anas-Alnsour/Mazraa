<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the users with Search and Filtering.
     */
    public function index(Request $request)
    {
        $query = User::query();

        // 🚀 1. تفكيك قنبلة اختفاء المستخدمين: إضافة نظام بحث ديناميكي
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // 🚀 فلترة حسب الدور (Role)
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        // استخدام appends للحفاظ على الفلاتر عند الانتقال بين الصفحات
        $users = $query->latest()->paginate(15)->appends($request->all());

        return view('admin.users.index', compact('users'));
    }

    /**
     * Update the specified user's role.
     */
    public function update(Request $request, User $user)
    {
        // 🚀 2. تفكيك قنبلة انتحار الآدمن: منعه من تغيير رتبته وطرد نفسه من النظام
        if ($user->id === auth()->id() && $request->role !== 'admin') {
            return redirect()->back()->with('error', "Security Alert: You cannot demote your own admin account.");
        }

        // 🚀 3. تفكيك قنبلة الأدوار الميتة: تحديث الأدوار لتتطابق مع الـ Specialized Roles
        $validated = $request->validate([
            'role' => 'required|in:admin,user,farm_owner,supply_company,transport_company,transport_driver,supply_driver',
        ]);

        $user->update([
            'role' => $validated['role'],
        ]);

        return redirect()->back()->with('success', "User role updated successfully to {$validated['role']}.");
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return redirect()->back()->with('error', "You cannot delete your own admin account.");
        }

        // 💡 ملاحظة: بما أنك تستخدم SoftDeletes في النظام، هذه الدالة ستقوم بإخفاء المستخدم فقط
        // ولن تحذفه نهائياً، وهذا ممتاز جداً للحفاظ على السجلات المالية (Financial Transactions).
        $user->delete();

        return redirect()->back()->with('success', "User account deleted successfully.");
    }
}
