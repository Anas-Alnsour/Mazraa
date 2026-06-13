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

        // 🚀 1. حماية الـ RAM: استخدام Limit لطلبات السوق المعلقة
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
            ->limit(50) // حد أقصى لحماية الأداء
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
        // 🚀 2. حماية الـ Race Condition (Atomic Update)
        // هذا يضمن أن شركة واحدة فقط يمكنها قبول الطلب حتى لو نقر شخصان بنفس اللحظة
        $updated = Transport::where('id', $id)
            ->where('status', 'pending')
            ->whereNull('company_id')
            ->update([
                'company_id' => Auth::id(),
                'status' => 'accepted'
            ]);

        if (!$updated) {
            return redirect()->back()->with('error', 'This job is no longer available or was already accepted by another company.');
        }

        return redirect()->route('transport.dispatch.index')->with('success', 'Job accepted! Please assign a driver and vehicle.');
    }

    /**
     * Show the form to assign a Driver and Vehicle to an accepted job.
     */
    public function edit($id)
    {
        $job = Transport::with(['user', 'farmBooking.farm'])->findOrFail($id);

        if ($job->company_id !== Auth::id() || in_array($job->status, ['completed', 'cancelled'])) {
            abort(403, 'Unauthorized or invalid job status. This job is archived or completed.');
        }

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

        if ($job->company_id !== Auth::id() || in_array($job->status, ['completed', 'cancelled'])) {
            abort(403, 'Unauthorized action. Job is archived.');
        }

        $request->validate([
            'driver_id' => 'required|exists:users,id',
        ]);

        $newDriver = User::where('company_id', Auth::id())
            ->where('role', 'transport_driver')
            ->with('transportVehicle')
            ->find($request->driver_id);

        if (!$newDriver) {
            return back()->withErrors(['driver_id' => 'Invalid driver selection.'])->withInput();
        }

        if (!$newDriver->transportVehicle) {
            return back()->withErrors(['driver_id' => 'This driver has no vehicle assigned. Please assign a vehicle to this driver first.'])->withInput();
        }

        // 🚀 3. تحديث العدادات بأمان تام (معالجة تغيير السائق)
        // إذا كان هناك سائق قديم (تم تعديل التعيين)
        if ($job->driver_id && $job->driver_id !== $newDriver->id) {
            User::where('id', $job->driver_id)->where('trips_count', '>', 0)->decrement('trips_count');

            // تحرير المركبة القديمة إذا لزم الأمر
            if ($job->vehicle_id) {
                 Vehicle::where('id', $job->vehicle_id)->update(['status' => 'available']);
            }
        }

        // زيادة عداد السائق الجديد إذا لم يكن هو السائق الحالي
        if ($job->driver_id !== $newDriver->id) {
             $newDriver->increment('trips_count');
        }

        // Auto-pull vehicle from driver's permanent link
        $job->update([
            'driver_id'  => $newDriver->id,
            'vehicle_id' => $newDriver->transportVehicle->id,
            'status'     => $request->status ?? 'assigned'
        ]);

        // Mark vehicle as in_use
        $newDriver->transportVehicle->update(['status' => 'in_use']);

        return redirect()->route('transport.dashboard')->with('success', 'Dispatch configuration saved successfully.');
    }
}
