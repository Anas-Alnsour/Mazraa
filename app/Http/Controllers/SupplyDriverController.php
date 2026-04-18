<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Vehicle;
use App\Http\Requests\StoreDriverRequest;
use App\Http\Requests\UpdateDriverRequest;
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
            ->latest()
            ->paginate(10);

        return view('supplies.drivers.index', compact('drivers'));
    }

    public function create()
    {
        return view('supplies.drivers.create');
    }

    public function store(StoreDriverRequest $request)
    {
        $validated = $request->validated();
        $company = Auth::user();
       
        // 1. إنشاء السائق
        $driver = User::create([
            'name'       => $validated['name'],
            'email'      => $validated['email'],
            'phone'      => $validated['phone'],
            'password'   => Hash::make($validated['password']),
            'shift'      => $validated['shift'],
            'role'       => 'supply_driver',
            'company_id' => $company->id,
            // 💡 STRICT BUSINESS LOGIC: Inherent from Company Branch
            'governorate' => $company->governorate,
            'latitude'    => $company->latitude,
            'longitude'   => $company->longitude,
        ]);

        // 2. إنشاء المركبة
        $vehicle = Vehicle::create([
        'license_plate' => $request->license_plate,
        'type' => $request->type,
        'capacity' => $request->capacity,
        'status' => 'Available',
        'driver_id' => $driver->id, // ربط المركبة بالسائق
        'company_id'    => $company->id,
        ]);
        
        // 3. تحديث السائق وربطه بالمركبة
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

    public function update(UpdateDriverRequest $request, User $driver)
    {
        if ($driver->company_id !== Auth::id() || $driver->role !== 'supply_driver') {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validated();
        $company = Auth::user();

        $driver->update([
            'name'       => $validated['name'],
            'email'      => $validated['email'],
            'phone'      => $validated['phone'],
            'shift'      => $validated['shift'],
            // 💡 Re-enforce inheritance just in case company branch moved
            'governorate' => $company->governorate,
            'latitude'    => $company->latitude,
            'longitude'   => $company->longitude,
        ]);

        if ($request->filled('password')) {
            $driver->password = Hash::make($validated['password']);
            $driver->save();
        }

        return redirect()->route('supplies.drivers.index')
            ->with('success', 'Driver profile updated successfully.');
    }

    public function destroy(User $driver)
    {
        if ($driver->company_id !== Auth::id() || $driver->role !== 'supply_driver') {
            abort(403, 'Unauthorized action.');
        }

        $driver->delete();

        return redirect()->route('supplies.drivers.index')
            ->with('success', 'Driver removed from your fleet.');
    }
}
