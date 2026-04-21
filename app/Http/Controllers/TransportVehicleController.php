<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class TransportVehicleController extends Controller
{
    public function index()
    {
        // سحب المركبات مع السائقين المرتبطين فيها (كودك الأصلي ممتاز)
        $vehicles = Vehicle::with('driver')
            ->where('company_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('transports.vehicles.index', compact('vehicles'));
    }

    public function create()
    {
        // جلب سائقين الشركة عشان نختار منهم (مهم جداً)
        $drivers = User::where('role', 'transport_driver')
            ->where('company_id', Auth::id())
            ->get();

        return view('transports.vehicles.create', compact('drivers'));
    }

    public function store(Request $request)
    {
        $company = Auth::user();
        
        $validated = $request->validate([
            // بيانات السائق
            'name'        => 'required|string|max:255',
            'email'       => 'required|string|email|max:255|unique:users',
            'phone'       => 'required|string|max:20',
            'governorate' => 'required|string|max:255',
            'shift'       => 'required|string|in:morning,evening',
            'password'    => 'required|string|min:8|confirmed',

            // بيانات المركبة
            'license_plate' => 'required|string|max:255|unique:vehicles',
            'type'          => 'required|string|max:255',
            'capacity'      => 'required|integer|min:1',
            // 'status'        => 'required|string|in:available,maintenance,booked',

            // ربط السائق بالمركبة
            'driver_id'             => 'nullable|exists:users,id',
            'transport_vehicle_id'  => 'nullable|exists:vehicles,id',
        ]);

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
            // 💡 STRICT BUSINESS LOGIC: Inherent from Company Branch
            // 'governorate' => $company->governorate,
            // 'latitude'    => $company->latitude,
            // 'longitude'   => $company->longitude,
        ]);

        // 2. إنشاء المركبة
        $vehicle = Vehicle::create([
            'license_plate' => $request->license_plate,
            'type' => $request->type,
            'capacity' => $request->capacity,
            'status' => 'Available',
            'driver_id' => $driver->id, // ربط المركبة بالسائق
            'company_id'           => Auth::id(),
        ]);

        // 3. تحديث السائق وربطه بالمركبة
        $driver->update([
            'transport_vehicle_id' => $vehicle->id,
        ]);

        return redirect()->route('transport.vehicles.index')
            ->with('success', 'Vehicle and Driver added and linked successfully.');
    }

    public function edit(Vehicle $vehicle)
    {
        if ($vehicle->company_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $drivers = User::where('role', 'transport_driver')
            ->where('company_id', Auth::id())
            ->get();

        return view('transports.vehicles.edit', compact('vehicle', 'drivers'));
    }

    public function update(Request $request, Vehicle $vehicle)
    {
        if ($vehicle->company_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'type' => 'required|string|max:255',
            'license_plate' => ['required', 'string', 'max:255', Rule::unique('vehicles')->ignore($vehicle->id)],
            'capacity' => 'required|integer|min:1',
            'status' => 'required|string|in:available,maintenance,in_use',
            'driver_id' => 'nullable|exists:users,id',
        ]);

        $vehicle->update($validated);

        return redirect()->route('transport.vehicles.index')
            ->with('success', 'Vehicle updated successfully.');
    }

    public function destroy(Vehicle $vehicle)
    {
        if ($vehicle->company_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $vehicle->delete();

        return redirect()->route('transport.vehicles.index')
            ->with('success', 'Vehicle removed successfully.');
    }
}
