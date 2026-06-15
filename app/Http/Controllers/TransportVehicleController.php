<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class TransportVehicleController extends Controller
{
    public function index()
    {
        // عرض المركبات مع السائقين (في حال تم ربطهم لاحقاً من صفحة السائقين)
        $vehicles = Vehicle::with('driver')
            ->where('company_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('transports.vehicles.index', compact('vehicles'));
    }

    public function create()
    {
        // لم نعد بحاجة لجلب السائقين هنا، الإضافة مخصصة للمركبة فقط
        return view('transports.vehicles.create');
    }

    public function store(Request $request)
    {
        // 1. التحقق من بيانات المركبة (بما فيها الـ capacity)
        $validated = $request->validate([
            'license_plate' => 'required|string|max:255|unique:vehicles,license_plate',
            'type'          => 'required|string|max:255',
            'capacity'      => 'required|integer|min:1', // إضافة السعة للتحقق
        ]);

        // 2. إنشاء المركبة (مع السعة المدخلة)
        Vehicle::create([
            'license_plate' => $validated['license_plate'],
            'type'          => $validated['type'],
            'capacity'      => $validated['capacity'], // حفظ السعة الفعلية بدلاً من الصفر
            'status'        => 'available', // حالة افتراضية
            'driver_id'     => null, // لا يوجد سائق مبدئياً
            'company_id'    => Auth::id(),
        ]);

        return redirect()->route('transport.vehicles.index')
            ->with('success', 'Vehicle added successfully to the fleet.');
    }

    public function edit(Vehicle $vehicle)
    {
        if ($vehicle->company_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        return view('transports.vehicles.edit', compact('vehicle'));
    }

    public function update(Request $request, Vehicle $vehicle)
    {
        if ($vehicle->company_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // التحقق من البيانات المرسلة للتحديث (بما فيها الـ capacity)
        $validated = $request->validate([
            'type'          => 'required|string|max:255',
            'license_plate' => ['required', 'string', 'max:255', Rule::unique('vehicles')->ignore($vehicle->id)],
            'capacity'      => 'required|integer|min:1', // إضافة السعة للتحقق
            'status'        => 'required|string|in:available,maintenance,in_use',
        ]);

        // تحديث المركبة بالبيانات الجديدة
        $vehicle->update($validated);

        return redirect()->route('transport.vehicles.index')
            ->with('success', 'Vehicle updated successfully.');
    }

    public function destroy(Vehicle $vehicle)
    {
        if ($vehicle->company_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // تفريغ ربط السائق قبل حذف المركبة (لضمان عدم وجود أخطاء في الـ Database)
        if ($vehicle->driver_id) {
            \App\Models\User::where('id', $vehicle->driver_id)->update(['transport_vehicle_id' => null]);
        }

        $vehicle->delete();

        return redirect()->route('transport.vehicles.index')
            ->with('success', 'Vehicle removed successfully.');
    }
}
