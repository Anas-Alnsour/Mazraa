<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class SupplyDriverController extends Controller
{
    public function index()
    {
        $drivers = User::where('role', 'supply_driver')
            ->where('company_id', Auth::id())
            ->with('vehicle')
            ->latest()
            ->paginate(10);

        return view('supplies.drivers.index', compact('drivers'));
    }

    public function create()
    {
        return view('supplies.drivers.create');
    }

    public function store(Request $request)
    {
        $company = Auth::user();

        // 1. تحقق قوي ومباشر مع الـ Unique لتفادي Error 500
        $validated = $request->validate([
            'name'          => 'required|string|max:255',
            'email'         => 'required|string|email|max:255|unique:users,email',
            'phone'         => 'required|string|max:20|unique:users,phone',
            'password'      => 'required|string|min:8|confirmed',
            'shift'         => 'required|string|in:morning,evening',
            'license_plate' => 'required|string|max:255|unique:vehicles,license_plate',
            'type'          => 'required|string|max:255',
            // ❌ تم إزالة capacity
        ]);

        // 2. إنشاء السائق
        $driver = User::create([
            'name'       => $validated['name'],
            'email'      => $validated['email'],
            'phone'      => $validated['phone'],
            'password'   => Hash::make($validated['password']),
            'shift'      => $validated['shift'],
            'role'       => 'supply_driver',
            'company_id' => $company->id,
            // STRICT BUSINESS LOGIC: Inherent from Company Branch
            'governorate' => $company->governorate,
            'latitude'    => $company->latitude,
            'longitude'   => $company->longitude,
        ]);

        // 3. إنشاء المركبة
        $vehicle = Vehicle::create([
            'license_plate' => $validated['license_plate'],
            'type'          => $validated['type'],
            'capacity'      => 0, // ✅ قيمة افتراضية لحماية الداتا بيس
            'status'        => 'Available',
            'driver_id'     => $driver->id,
            'company_id'    => $company->id,
        ]);

        // 4. ربط المركبة بالسائق
        $driver->update([
            'transport_vehicle_id' => $vehicle->id,
        ]);

        return redirect()->route('supplies.drivers.index')
            ->with('success', 'Local delivery driver and vehicle added to your fleet successfully.');
    }

    public function edit(User $driver)
    {
        if ($driver->company_id !== Auth::id() || $driver->role !== 'supply_driver') {
            abort(403, 'Unauthorized action.');
        }

        return view('supplies.drivers.edit', compact('driver'));
    }

    public function update(Request $request, User $driver)
    {
        if ($driver->company_id !== Auth::id() || $driver->role !== 'supply_driver') {
            abort(403, 'Unauthorized action.');
        }

        $company = Auth::user();

        // 1. التحقق الآمن مع استثناء الـ ID الحالي لمنع تعارض الـ Unique
        $validated = $request->validate([
            'name'  => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($driver->id)],
            'phone' => ['required', 'string', 'max:20', Rule::unique('users')->ignore($driver->id)],
            'shift' => 'required|string|in:morning,evening',
        ]);

        // 2. تحديث البيانات
        $driver->update([
            'name'  => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'shift' => $validated['shift'],
            // Re-enforce inheritance just in case company branch moved
            'governorate' => $company->governorate,
            'latitude'    => $company->latitude,
            'longitude'   => $company->longitude,
        ]);

        if ($request->filled('password')) {
            $request->validate(['password' => 'string|min:8|confirmed']);
            $driver->password = Hash::make($request->password);
            $driver->save();
        }

        // ملاحظة: إذا كان هناك فورم تعديل بيانات المركبة في نفس شاشة الـ edit،
        // يجب إضافة كود تحديثها هنا. لكن بناءً على الكود الأصلي لا يوجد.

        return redirect()->route('supplies.drivers.index')
            ->with('success', 'Driver profile updated successfully.');
    }

    public function destroy(User $driver)
    {
        if ($driver->company_id !== Auth::id() || $driver->role !== 'supply_driver') {
            abort(403, 'Unauthorized action.');
        }

        // إذا كان هناك مركبة مرتبطة، نقوم بفك الارتباط أو حذفها حسب الـ Logic
        if ($driver->vehicle) {
            $driver->vehicle->update(['driver_id' => null]);
            // أو $driver->vehicle->delete();
        }

        $driver->delete();

        return redirect()->route('supplies.drivers.index')
            ->with('success', 'Driver removed from your fleet.');
    }
}
