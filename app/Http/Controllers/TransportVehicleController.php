<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class TransportVehicleController extends Controller
{
    public function index()
    {
        // سحب المركبات مع السائقين المرتبطين فيها
        $vehicles = Vehicle::with('driver')
            ->where('company_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('transports.vehicles.index', compact('vehicles'));
    }

    public function create()
    {
        // جلب سائقين الشركة عشان نختار منهم
        $drivers = User::where('role', 'transport_driver')
            ->where('company_id', Auth::id())
            ->get();

        return view('transports.vehicles.create', compact('drivers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|string|max:255',
            'license_plate' => 'required|string|max:255|unique:vehicles',
            'capacity' => 'required|integer|min:1',
            'status' => 'required|string|in:available,maintenance,booked',
            'driver_id' => 'nullable|exists:users,id', // تحقق من السائق
        ]);

        Vehicle::create([
            'company_id' => Auth::id(),
            'driver_id' => $validated['driver_id'],
            'type' => $validated['type'],
            'license_plate' => $validated['license_plate'],
            'capacity' => $validated['capacity'],
            'status' => $validated['status'],
        ]);

        return redirect()->route('transport.vehicles.index')
            ->with('success', 'Vehicle added and linked to driver successfully.');
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
            'status' => 'required|string|in:available,maintenance,booked',
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
