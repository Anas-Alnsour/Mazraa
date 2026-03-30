<?php

namespace App\Http\Controllers\Driver;

use App\Http\Controllers\Controller;
use App\Models\SupplyOrder;
use Illuminate\Http\Request;

class SupplyDriverController extends Controller
{
    public function dashboard()
    {
        $orders = SupplyOrder::with(['farmBooking.farm', 'user'])->where('driver_id', auth()->id())->latest()->get();
        // انتبه للمسار بناءً على صورك
        return view('supplies.drivers.supply_dashboard', compact('orders'));
    }

    public function updateStatus(Request $request, $id)
    {
        $order = SupplyOrder::where('id', $id)->where('driver_id', auth()->id())->firstOrFail();
        $order->update(['status' => $request->status]);
        return back()->with('success', 'Order status updated!');
    }
}
