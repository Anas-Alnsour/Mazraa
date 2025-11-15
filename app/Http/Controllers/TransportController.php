<?php

namespace App\Http\Controllers;

use App\Models\Transport;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth'); // تأكد من تفعيل auth middleware
    }

    public function index()
    {
        $transports = Transport::where('user_id', Auth::id())->get();
        return view('transports.index', compact('transports'));
    }

    public function create()
    {
        $randomDrivers = User::inRandomOrder()->take(5)->get();
        return view('transports.create', compact('randomDrivers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'transport_type' => 'required|string|max:255',
            'passengers' => 'required|integer|min:1',
            'driver_id' => 'required|exists:users,id',
            'start_point' => 'required|string|max:255',
            'destination' => 'required|string|max:255',
            'distance' => 'required|numeric|min:0.1',
            'departure_time' => 'required|date',
            'arrival_time' => 'required|date|after:departure_time',
            'status' => 'required|string',
        ]);

Transport::create([
    'user_id' => Auth::id(), // <-- هذا مهم
    'transport_type' => $request->transport_type,
    'passengers' => $request->passengers,
    'driver_id' => $request->driver_id,
    'start_point' => $request->start_point,
    'destination' => $request->destination,
    'distance' => $request->distance,
    'price' => $request->distance * $request->passengers * 2, // مثال
    'departure_time' => $request->departure_time,
    'arrival_time' => $request->arrival_time,
    'status' => $request->status,
    'notes' => $request->notes,
]);

        return redirect()->route('transports.index')->with('success', 'Transport created successfully!');
    }



    public function edit(Transport $transport)
    {
        $this->authorize('update', $transport);
        return view('transports.edit', compact('transport'));
    }

    public function update(Request $request, Transport $transport)
    {
        $this->authorize('update', $transport);

        $request->validate([
            'transport_type' => 'required|string|max:255',
            'passengers' => 'required|integer|min:1',
            'driver_id' => 'required|exists:users,id',
            'start_point' => 'required|string|max:255',
            'destination' => 'required|string|max:255',
            'distance' => 'required|numeric|min:0',
            'price' => 'required|numeric|min:0',
            'departure_time' => 'required|date',
        ]);

$transport->update($request->only([
    'transport_type', 'passengers', 'driver_id', 'start_point',
    'destination', 'distance', 'price', 'departure_time', 'arrival_time', 'notes', 'status'
]));

        return redirect()->route('transports.index')->with('success', 'Transport request updated successfully!');
    }

    public function destroy(Transport $transport)
    {
        $this->authorize('delete', $transport);
        $transport->delete();

        return redirect()->route('transports.index')->with('success', 'Transport request deleted successfully!');
    }

    public function show(Transport $transport)
    {
        $this->authorize('view', $transport);
        return view('transports.show', compact('transport'));
    }


}
