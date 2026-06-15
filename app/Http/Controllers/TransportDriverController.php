<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Vehicle;
use App\Http\Requests\StoreDriverRequest;
use App\Http\Requests\UpdateDriverRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class TransportDriverController extends Controller
{
    public function index()
    {
        $drivers = User::where('role', 'transport_driver')
            ->where('company_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('transports.drivers.index', compact('drivers'));
    }

    public function create()
    {
        // عرض المركبات غير المرتبطة بسائقين فقط (متاحة للربط)
        $availableVehicles = Vehicle::where('company_id', Auth::id())
            ->whereNotIn('id', User::where('role', 'transport_driver')
                ->where('company_id', Auth::id())
                ->whereNotNull('transport_vehicle_id')
                ->pluck('transport_vehicle_id')
            )
            ->get();

        // تأكد أن مصفوفة المحافظات موجودة في ملف config/mazraa.php
        $governorates = config('mazraa.governorates') ?? [];

        return view('transports.drivers.create', compact('availableVehicles', 'governorates'));
    }

    public function store(StoreDriverRequest $request)
    {
        $validated = $request->validated();

        // 1. إنشاء السائق
        $driver = User::create([
            'name'                 => $validated['name'],
            'email'                => $validated['email'],
            'phone'                => $validated['phone'],
            'governorate'          => $validated['governorate'],
            'shift'                => $validated['shift'],
            'password'             => Hash::make($validated['password']),
            'role'                 => 'transport_driver',
            'company_id'           => Auth::id(),
            'transport_vehicle_id' => $validated['transport_vehicle_id'] ?? null,
        ]);

        // 2. مزامنة المركبة: إذا تم اختيار مركبة، قم بتسجيل الـ driver_id بداخلها
        if (!empty($validated['transport_vehicle_id'])) {
            Vehicle::where('id', $validated['transport_vehicle_id'])
                   ->update(['driver_id' => $driver->id]);
        }

        return redirect()->route('transport.drivers.index')
            ->with('success', 'Driver has been added to your fleet successfully.');
    }

    public function edit(User $driver)
    {
        if ($driver->company_id !== Auth::id() || $driver->role !== 'transport_driver') {
            abort(403, 'Unauthorized action.');
        }

        // جلب المركبات المتاحة بالإضافة للمركبة الحالية المربوطة بهذا السائق
        $availableVehicles = Vehicle::where('company_id', Auth::id())
            ->where(function ($q) use ($driver) {
                $q->whereNotIn('id', User::where('role', 'transport_driver')
                    ->where('company_id', Auth::id())
                    ->whereNotNull('transport_vehicle_id')
                    ->where('id', '!=', $driver->id)
                    ->pluck('transport_vehicle_id')
                )->orWhere('id', $driver->transport_vehicle_id);
            })
            ->get();

        $governorates = config('mazraa.governorates') ?? [];

        return view('transports.drivers.edit', compact('driver', 'availableVehicles', 'governorates'));
    }

    public function update(UpdateDriverRequest $request, User $driver)
    {
        if ($driver->company_id !== Auth::id() || $driver->role !== 'transport_driver') {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validated();

        // الاحتفاظ بمعرف المركبة القديمة لمقارنته
        $oldVehicleId = $driver->transport_vehicle_id;
        $newVehicleId = $validated['transport_vehicle_id'] ?? null;

        // 1. تحديث بيانات السائق
        $driver->update([
            'name'                 => $validated['name'],
            'email'                => $validated['email'],
            'phone'                => $validated['phone'],
            'governorate'          => $validated['governorate'],
            'shift'                => $validated['shift'],
            'transport_vehicle_id' => $newVehicleId,
        ]);

        if ($request->filled('password')) {
            $driver->password = Hash::make($validated['password']);
            $driver->save();
        }

        // 2. مزامنة المركبة (إذا تغيرت المركبة)
        if ($oldVehicleId !== $newVehicleId) {
            // تفريغ حقل driver_id من المركبة القديمة (إن وجدت)
            if ($oldVehicleId) {
                Vehicle::where('id', $oldVehicleId)->update(['driver_id' => null]);
            }

            // تعيين السائق للمركبة الجديدة (إن وجدت)
            if ($newVehicleId) {
                Vehicle::where('id', $newVehicleId)->update(['driver_id' => $driver->id]);
            }
        }

        return redirect()->route('transport.drivers.index')
            ->with('success', 'Driver information updated successfully.');
    }

    public function destroy(User $driver)
    {
        if ($driver->company_id !== Auth::id() || $driver->role !== 'transport_driver') {
            abort(403, 'Unauthorized action.');
        }

        // تفريغ حقل driver_id من المركبة قبل الحذف (لتجنب بقاء بيانات معلقة)
        if ($driver->transport_vehicle_id) {
            Vehicle::where('id', $driver->transport_vehicle_id)->update(['driver_id' => null]);
        }

        $driver->delete();

        return redirect()->route('transport.drivers.index')
            ->with('success', 'Driver has been removed from your fleet.');
    }
}
