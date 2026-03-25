<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transport;
use Illuminate\Support\Facades\Auth;

class TransportDriverDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // حماية: التأكد إن اللي فات هو سائق ركاب
        if ($user->role !== 'transport_driver') {
            abort(403, 'Unauthorized access.');
        }

        // جلب الرحلات المسندة لهذا السائق تحديداً
        $myTrips = Transport::with(['user', 'farm', 'vehicle'])
            ->where('driver_id', $user->id)
            ->orderBy('Farm_Arrival_Time', 'asc')
            ->paginate(15);

        // إحصائيات السائق
        $totalTrips = Transport::where('driver_id', $user->id)->count();

        $upcomingTrips = Transport::where('driver_id', $user->id)
            ->whereIn('status', ['assigned', 'in_progress'])
            ->count();

        $completedTrips = Transport::where('driver_id', $user->id)
            ->whereIn('status', ['completed', 'finished'])
            ->count();

        return view('shuttle.trips', compact(
            'myTrips',
            'totalTrips',
            'upcomingTrips',
            'completedTrips'
        ));
    }

    // تحديث حالة الرحلة (من السائق نفسه)
    public function updateStatus(Request $request, $id)
    {
        $user = Auth::user();

        if ($user->role !== 'transport_driver') {
            abort(403, 'Unauthorized access.');
        }

        $trip = Transport::findOrFail($id);

        if ($trip->driver_id !== $user->id) {
            abort(403, 'You are not assigned to this trip.');
        }

        $validated = $request->validate([
            'status' => 'required|string|in:in_progress,completed'
        ]);

        $trip->update(['status' => $validated['status']]);

        $message = $validated['status'] === 'in_progress'
            ? 'Trip started successfully. Drive safely!'
            : 'Trip marked as completed. Great job!';

        return back()->with('success', $message);
    }
}
