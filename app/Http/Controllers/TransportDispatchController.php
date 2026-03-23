<?php

namespace App\Http\Controllers;

use App\Models\Transport;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransportDispatchController extends Controller
{
    // تم نقل اللوجيك تبع الـ index لـ TransportCompanyDashboardController
    public function index()
    {
        return redirect()->route('transport.dashboard');
    }

    // قبول طلب من السوق
    public function acceptJob($id)
    {
        $job = Transport::findOrFail($id);

        if ($job->company_id !== null) {
            return redirect()->back()->with('error', 'Job already taken by another company.');
        }

        $job->update([
            'company_id' => Auth::id(),
            'status' => 'accepted'
        ]);

        return redirect()->route('transport.dispatch.edit', $job->id)
            ->with('success', 'Job accepted! Please assign a vehicle and driver.');
    }

    // شاشة تعيين السائق والمركبة للطلب
    public function edit(Transport $dispatch)
    {
        if ($dispatch->company_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // جلب سائقين ومركبات الشركة
        $drivers = User::where('company_id', Auth::id())->where('role', 'transport_driver')->get();
        $vehicles = Vehicle::where('company_id', Auth::id())->where('status', 'available')->get();

        return view('transports.dispatch.edit', compact('dispatch', 'drivers', 'vehicles'));
    }

    // حفظ تعيين السائق والمركبة
    public function update(Request $request, Transport $dispatch)
    {
        if ($dispatch->company_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'driver_id' => 'required|exists:users,id',
            'vehicle_id' => 'required|exists:vehicles,id',
            'status' => 'required|string|in:accepted,assigned,in_progress,completed,cancelled',
        ]);

        $driver = User::findOrFail($validated['driver_id']);
        if ($driver->company_id !== Auth::id() || $driver->role !== 'transport_driver') {
            return back()->withErrors(['driver_id' => 'Invalid driver selected.']);
        }

        $vehicle = Vehicle::findOrFail($validated['vehicle_id']);
        if ($vehicle->company_id !== Auth::id()) {
            return back()->withErrors(['vehicle_id' => 'Invalid vehicle selected.']);
        }

        $dispatch->update([
            'driver_id' => $validated['driver_id'],
            'vehicle_id' => $validated['vehicle_id'],
            'status' => $validated['status'] === 'accepted' ? 'assigned' : $validated['status'],
        ]);

        // 👇 التعديل صار هون: يرجعك لداشبورد الشركة بدل صفحة الديسباتش القديمة 👇
        return redirect()->route('transport.dashboard')
            ->with('success', 'Dispatch updated successfully.');
    }
}
