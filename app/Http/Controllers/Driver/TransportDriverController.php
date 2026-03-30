<?php

namespace App\Http\Controllers\Driver;

use App\Http\Controllers\Controller;
use App\Models\Transport;
use Illuminate\Http\Request;

class TransportDriverController extends Controller
{
    public function dashboard()
    {
        $trips = Transport::with('user')->where('driver_id', auth()->id())->latest()->get();
        // انتبه للمسار بناءً على صورك
        return view('transports.drivers.transport_dashboard', compact('trips'));
    }

    public function updateStatus(Request $request, $id)
    {
        $trip = Transport::where('id', $id)->where('driver_id', auth()->id())->firstOrFail();
        $trip->update(['status' => $request->status]);
        return back()->with('success', 'Trip status updated!');
    }
}
