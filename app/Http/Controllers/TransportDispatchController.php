<?php

namespace App\Http\Controllers;

use App\Models\Transport;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransportDispatchController extends Controller
{
    // ==========================================
    // ACTION 1: قبول الطلب من السوق
    // ==========================================
    public function acceptJob($id)
    {
        $user = Auth::user();

        if ($user->role !== 'transport_company') {
            abort(403, 'Unauthorized access.');
        }

        $job = Transport::findOrFail($id);

        if ($job->company_id !== null) {
            return redirect()->back()->with('error', 'Job already taken by another company.');
        }

        $job->update([
            'company_id' => $user->id,
            'status' => 'accepted'
        ]);

        return redirect()->route('transport.dispatch.edit', $job->id)
            ->with('success', 'Job accepted! Please assign a vehicle and driver from your fleet.');
    }

    // ==========================================
    // ACTION 2: عرض صفحة تعيين السائق والمركبة
    // ==========================================
    public function edit($id)
    {
        $user = Auth::user();

        if ($user->role !== 'transport_company') {
            abort(403, 'Unauthorized access.');
        }

        $dispatch = Transport::with(['farm', 'user'])->findOrFail($id);

        if ($dispatch->company_id !== $user->id) {
            abort(403, 'You do not own this dispatch job.');
        }

        // جلب سائقي وسيارات هذه الشركة فقط
        $drivers = User::where('company_id', $user->id)->where('role', 'transport_driver')->get();
        // تأكد من وجود موديل Vehicle بالداتا بيس عندك، وإلا استخدم مصفوفة فارغة
        $vehicles = Vehicle::where('company_id', $user->id)->where('status', 'available')->get();

        return view('transports.dispatch.edit', compact('dispatch', 'drivers', 'vehicles'));
    }

    // ==========================================
    // ACTION 3: حفظ تعيين السائق والمركبة
    // ==========================================
    public function update(Request $request, $id)
    {
        $user = Auth::user();

        if ($user->role !== 'transport_company') {
            abort(403, 'Unauthorized access.');
        }

        $dispatch = Transport::findOrFail($id);

        if ($dispatch->company_id !== $user->id) {
            abort(403, 'You do not own this dispatch job.');
        }

        $validated = $request->validate([
            'driver_id' => 'required|exists:users,id',
            'vehicle_id' => 'required|exists:vehicles,id',
            'status' => 'required|string|in:accepted,assigned,in_progress,completed,cancelled',
        ]);

        // التأكد إن السائق والسيارة تابعين لهي الشركة
        $driver = User::findOrFail($validated['driver_id']);
        if ($driver->company_id !== $user->id || $driver->role !== 'transport_driver') {
            return back()->withErrors(['driver_id' => 'Invalid driver selected.']);
        }

        $vehicle = Vehicle::findOrFail($validated['vehicle_id']);
        if ($vehicle->company_id !== $user->id) {
            return back()->withErrors(['vehicle_id' => 'Invalid vehicle selected.']);
        }

        $dispatch->update([
            'driver_id' => $validated['driver_id'],
            'vehicle_id' => $validated['vehicle_id'],
            'status' => $validated['status'] === 'accepted' ? 'assigned' : $validated['status'],
        ]);

        return redirect()->route('transport.dashboard')
            ->with('success', 'Fleet assignment updated successfully.');
    }

    // يمكننا إضافة دالة index لاحقاً إذا أردت عرض جميع الـ dispatches في صفحة منفصلة
    public function index()
    {
        return redirect()->route('transport.dashboard');
    }
}
