<?php

namespace App\Http\Controllers;

use App\Models\User;
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

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'required|string|max:20',
            'password' => 'required|string|min:8|confirmed',
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'password' => Hash::make($validated['password']),
            'role' => 'supply_driver',
            'company_id' => Auth::id(),
        ]);

        return redirect()->route('supplies.drivers.index')
            ->with('success', 'Delivery driver added successfully.');
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

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($driver->id)],
            'phone' => 'required|string|max:20',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $driver->name = $validated['name'];
        $driver->email = $validated['email'];
        $driver->phone = $validated['phone'];

        if ($request->filled('password')) {
            $driver->password = Hash::make($validated['password']);
        }

        $driver->save();

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
