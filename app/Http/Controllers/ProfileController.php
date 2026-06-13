<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Models\FarmBooking;
use App\Models\Transport;
use App\Models\SupplyOrder;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();

        // 1. تعبئة البيانات الأساسية (الاسم والإيميل)
        $user->fill($request->validated());

        // 🚀 2. تفكيك قنبلة ضياع البيانات: حفظ الحقول المخصصة (للملاك، السائقين، الشركات)
        $extraData = $request->validate([
            'phone' => 'nullable|string|max:20',
            'governorate' => 'nullable|string|max:100',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'bank_name' => 'nullable|string|max:100',
            'account_holder_name' => 'nullable|string|max:100',
            'iban' => 'nullable|string|max:100',
        ]);

        $user->fill($extraData);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return Redirect::back()->with('success', 'Profile updated successfully!')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        // =========================================================
        // 🚀 3. تفكيك قنبلة الهروب: منع حذف الحساب إذا كان هناك التزامات نشطة
        // =========================================================

        // حماية الملاك (Farm Owners)
        if ($user->role === 'farm_owner') {
            $hasActiveBookings = FarmBooking::whereHas('farm', function($q) use ($user) {
                $q->where('owner_id', $user->id);
            })->whereIn('status', ['pending_payment', 'pending', 'confirmed'])->exists();

            if ($hasActiveBookings) {
                return Redirect::back()->with('error', 'Cannot delete account: You have active farm bookings that must be completed or cancelled first.');
            }
        }

        // حماية السائقين (Drivers)
        if (in_array($user->role, ['transport_driver', 'supply_driver'])) {
            $hasActiveTrips = Transport::where('driver_id', $user->id)
                ->whereIn('status', ['assigned', 'accepted', 'in_progress', 'in_way'])->exists();

            $hasActiveSupplies = SupplyOrder::where('driver_id', $user->id)
                ->whereIn('status', ['waiting_driver', 'in_way'])->exists();

            if ($hasActiveTrips || $hasActiveSupplies) {
                return Redirect::back()->with('error', 'Cannot delete account: You are currently assigned to active trips or deliveries.');
            }
        }

        // حماية الزبائن (Users)
        if ($user->role === 'user') {
            $hasActiveBookings = FarmBooking::where('user_id', $user->id)
                ->whereIn('status', ['pending_payment', 'pending', 'confirmed'])->exists();

            if ($hasActiveBookings) {
                 return Redirect::back()->with('error', 'Cannot delete account: You have upcoming reservations.');
            }
        }

        // =========================================================

        Auth::logout();

        // استخدام الـ SoftDelete لحماية السجلات المالية السابقة
        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/')->with('success', 'Your account has been successfully deleted.');
    }

    /**
     * Display the owner's dedicated profile form.
     */
    public function ownerEdit(Request $request): \Illuminate\View\View
    {
        return view('owner.profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Display the driver's dedicated profile form.
     */
    public function driverEdit(Request $request): \Illuminate\View\View
    {
        return view('driver.profile.edit', [
            'user' => $request->user(),
        ]);
    }
}
