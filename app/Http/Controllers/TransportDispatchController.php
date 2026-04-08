<?php

namespace App\Http\Controllers;

use App\Models\Transport;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransportDispatchController extends Controller
{
    /**
     * Display the Dispatch Board (Kanban/Categorized List).
     */
    public function index()
    {
        $companyId = Auth::id();
        $company = Auth::user();

        // 1. Pending Market Jobs
        $pendingJobs = Transport::where('status', 'pending')
            ->where(function($query) use ($companyId, $company) {
                $query->where('company_id', $companyId)
                      ->orWhere(function($q) use ($company) {
                          $q->whereNull('company_id')
                            ->where('destination_governorate', $company->governorate);
                      });
            })
            ->with(['user', 'farmBooking.farm'])
            ->orderBy('Farm_Arrival_Time', 'asc')
            ->get();

        // 2. Accepted Jobs
        $acceptedJobs = Transport::where('company_id', $companyId)
            ->where('status', 'accepted')
            ->with(['user', 'farmBooking.farm'])
            ->orderBy('Farm_Arrival_Time', 'asc')
            ->get();

        // 3. Assigned Jobs
        $assignedJobs = Transport::where('company_id', $companyId)
            ->where('status', 'assigned')
            ->with(['user', 'farmBooking.farm', 'driver', 'vehicle'])
            ->orderBy('Farm_Arrival_Time', 'asc')
            ->get();

        // 4. In Progress / In Way Jobs
        $inProgressJobs = Transport::where('company_id', $companyId)
            ->whereIn('status', ['in_progress', 'in_way'])
            ->with(['user', 'farmBooking.farm', 'driver', 'vehicle'])
            ->orderBy('Farm_Arrival_Time', 'asc')
            ->get();

        return view('transports.dispatch.index', compact('pendingJobs', 'acceptedJobs', 'assignedJobs', 'inProgressJobs'));
    }

    /**
     * Accept a pending job from the market.
     */
    public function acceptJob(Request $request, $id)
    {
        $job = Transport::findOrFail($id);

        if ($job->status !== 'pending') {
            return redirect()->back()->with('error', 'This job is no longer available.');
        }

        // 💡 التعديل الصحيح لدالة acceptJob: فقط تحديث الـ company_id والحالة
        $job->update([
            'company_id' => Auth::id(),
            'status' => 'accepted'
        ]);

        return redirect()->route('transport.dispatch.index')->with('success', 'Job accepted! Please assign a driver and vehicle.');
    }

    /**
     * Show the form to assign a Driver and Vehicle to an accepted job.
     */
    public function edit($id)
    {
        $job = Transport::with(['user', 'farmBooking.farm'])->findOrFail($id);

        if ($job->company_id !== Auth::id() || !in_array($job->status, ['accepted', 'assigned'])) {
            abort(403, 'Unauthorized or invalid job status.');
        }

        // Load drivers WITH their linked vehicle so UI can display it
        $drivers = User::where('company_id', Auth::id())
            ->where('role', 'transport_driver')
            ->with('transportVehicle')
            ->get();

        return view('transports.dispatch.edit', compact('job', 'drivers'));
    }

    /**
     * Update the job with the assigned Driver and Vehicle.
     */
    public function update(Request $request, $id)
    {
        $job = Transport::findOrFail($id);

        if ($job->company_id !== Auth::id() || !in_array($job->status, ['accepted', 'assigned'])) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'driver_id' => 'required|exists:users,id',
        ]);

        $driver = User::where('company_id', Auth::id())
            ->where('role', 'transport_driver')
            ->with('transportVehicle')
            ->find($request->driver_id);

        if (!$driver) {
            return back()->withErrors(['driver_id' => 'Invalid driver selection.'])->withInput();
        }

        if (!$driver->transportVehicle) {
            return back()->withErrors(['driver_id' => 'This driver has no vehicle assigned. Please assign a vehicle to this driver first.'])->withInput();
        }

        // Increment driver's active TRIPS count
        if ($job->status === 'accepted') {
            $driver->increment('trips_count');
        }

        // Auto-pull vehicle from driver's permanent link — no manual input needed
        $job->update([
            'driver_id'  => $driver->id,
            'vehicle_id' => $driver->transportVehicle->id,
            'status'     => $request->status ?? 'assigned'
        ]);

        // Mark vehicle as in_use
        $driver->transportVehicle->update(['status' => 'in_use']);

        return redirect()->route('transport.dispatch.index')->with('success', 'Driver assigned successfully. Vehicle auto-linked from driver profile.');
    }
}
