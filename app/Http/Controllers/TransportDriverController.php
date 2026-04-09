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
        // Only show UNASSIGNED vehicles
        $availableVehicles = Vehicle::where('company_id', Auth::id())
            ->whereNotIn('id', User::where('role', 'transport_driver')
                ->where('company_id', Auth::id())
                ->whereNotNull('transport_vehicle_id')
                ->pluck('transport_vehicle_id')
            )
            ->get();

        $governorates = config('mazraa.governorates');

        return view('transports.drivers.create', compact('availableVehicles', 'governorates'));
    }

    public function store(StoreDriverRequest $request)
    {
        $validated = $request->validated();

        User::create([
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

        return redirect()->route('transport.drivers.index')
            ->with('success', 'Driver has been added to your fleet successfully.');
    }

    public function edit(User $driver)
    {
        if ($driver->company_id !== Auth::id() || $driver->role !== 'transport_driver') {
            abort(403, 'Unauthorized action.');
        }

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

        $governorates = config('mazraa.governorates');

        return view('transports.drivers.edit', compact('driver', 'availableVehicles', 'governorates'));
    }

    public function update(UpdateDriverRequest $request, User $driver)
    {
        if ($driver->company_id !== Auth::id() || $driver->role !== 'transport_driver') {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validated();

        $driver->update([
            'name'                 => $validated['name'],
            'email'                => $validated['email'],
            'phone'                => $validated['phone'],
            'governorate'          => $validated['governorate'],
            'shift'                => $validated['shift'],
            'transport_vehicle_id' => $validated['transport_vehicle_id'] ?? null,
        ]);

        if ($request->filled('password')) {
            $driver->password = Hash::make($validated['password']);
            $driver->save();
        }

        return redirect()->route('transport.drivers.index')
            ->with('success', 'Driver information updated successfully.');
    }

    public function destroy(User $driver)
    {
        if ($driver->company_id !== Auth::id() || $driver->role !== 'transport_driver') {
            abort(403, 'Unauthorized action.');
        }

        $driver->delete();

        return redirect()->route('transport.drivers.index')
            ->with('success', 'Driver has been removed from your fleet.');
    }
}
