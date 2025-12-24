<?php

namespace App\Http\Controllers;

use App\Models\Driver;
use App\Models\Transport;
use App\Models\Farm;    
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
    $baseQuery = Transport::with(['driver','farm'])
        ->where('user_id', Auth::id())
        ->latest();

    $transports  = $baseQuery->get();   // تستخدمه الواجهة الحالية
    $transports1 = $transports;         // تظل متوفرة لو في أماكن بتعتمد عليها

    return view('transports.index', compact('transports','transports1'));
}

    public function create()
    {
        $Drivers = Driver::all();
        $farms = Farm::all();
       return view('transports.create', compact('Drivers', 'farms'));
    }

    public function store(Request $request)
{
    $validated = $request->validate([
        'transport_type' => 'required|string|max:255',
        'passengers'     => 'required|integer|min:1',
        'driver_id'      => 'required|exists:drivers,id',
     // 'start_point'    => 'required|string|max:255',
        'start_and_return_point'    => 'required|string|max:255',                         
     // 'destination'    => 'required|string|max:255',
        'farm_id' => 'required|exists:farms,id', 
        'distance'       => 'required|numeric|min:0.1',
        'price'          => 'required|numeric',
      //'departure_time' => 'required|date',
      //'arrival_time'   => 'required|date|after:departure_time',
        'Farm_Arrival_Time' => 'required|date',
        'Farm_Departure_Time'   => 'required|date|after:Farm_Arrival_Time',
        'status'         => 'required|string',
        'notes'          => 'nullable|string'
    ]);

    $validated['user_id'] = Auth::id();

    Transport::create($validated);

    return redirect()->route('transports.index')->with('success', 'Transport created successfully!');
}

//     public function store(Request $request)
//     {
//         $request->validate([
//             'transport_type' => 'required|string|max:255',
//             'passengers' => 'required|integer|min:1',
//             'driver_id' => 'required|exists:users,id',
//             'start_point' => 'required|string|max:255',
//             'destination' => 'required|string|max:255',
//             'distance' => 'required|numeric|min:0.1',
//             'departure_time' => 'required|date',
//             'arrival_time' => 'required|date|after:departure_time',
//             'status' => 'required|string',
//         ]);

// Transport::create([
//     'user_id' => Auth::id(), // <-- هذا مهم
//     'transport_type' => $request->transport_type,
//     'passengers' => $request->passengers,
//     'driver_id' => $request->driver_id,
//     'start_point' => $request->start_point,
//     'destination' => $request->destination,
//     'distance' => $request->distance,
//     'price' => $request->distance * $request->passengers * 2, // مثال
//     'departure_time' => $request->departure_time,
//     'arrival_time' => $request->arrival_time,
//     'status' => $request->status,
//     'notes' => $request->notes,
// ]);

//         return redirect()->route('transports.index')->with('success', 'Transport created successfully!');
//     }



    public function edit($id)
{
    // الحصول على النقل مع السائق
    $transport = Transport::with('driver')->findOrFail($id);

    // الحصول على جميع السائقين
    $drivers = Driver::all();
    
    // الحصول على جميع المزارع
    $farms = Farm::all();

    // تمرير المتغيرات إلى الـ View
    return view('transports.edit', compact('transport', 'drivers', 'farms'));
}

       public function update(Request $request, Transport $transport)
{
    $this->authorize('update', $transport);

    $validated = $request->validate([
        'transport_type'           => 'required|string|max:255',
        'passengers'               => 'required|integer|min:1',
        'driver_id'                => 'required|exists:drivers,id',
        'farm_id'                  => 'required|exists:farms,id',
        'start_and_return_point'   => 'required|string|max:255',
        'distance'                 => 'required|numeric|min:0',
        'price'                    => 'required|numeric|min:0',
        'Farm_Arrival_Time'        => 'required|date',
        'Farm_Departure_Time'      => 'required|date|after_or_equal:Farm_Arrival_Time',
        'notes'                    => 'nullable|string',
        'status'                   => 'required|string',
    ]);

    $transport->update($request->only([
        'transport_type',
        'passengers',
        'driver_id',
        'farm_id',
        'start_and_return_point',
        'distance',
        'price',
        'Farm_Arrival_Time',
        'Farm_Departure_Time',
        'notes',
        'status',
    ]));

    return redirect()->route('transports.index')
        ->with('success', 'Transport request updated successfully!');
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