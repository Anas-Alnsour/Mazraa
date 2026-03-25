<?php

namespace App\Http\Controllers;

use App\Models\Transport;
use App\Models\Farm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // عرض حجوزات المواصلات الخاصة بالزبون
    public function index()
    {
        $transports = Transport::with(['farm', 'company', 'vehicle', 'driver'])
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        // تركتلك هاد المتغير عشان لو واجهاتك القديمة لسا بتعتمد عليه ما يضرب إيرور
        $transports1 = clone $transports;

        return view('transports.index', compact('transports', 'transports1'));
    }

    // عرض فورم حجز رحلة جديدة
    public function create()
    {
        $farms = Farm::where('is_approved', true)->get();
        return view('transports.create', compact('farms'));
    }

    // معالجة طلب الحجز
    public function store(Request $request)
    {
        $validated = $request->validate([
            'transport_type'         => 'required|string|max:255',
            'passengers'             => 'required|integer|min:1|max:50',
            'start_and_return_point' => 'required|string|max:255',
            'farm_id'                => 'required|exists:farms,id',
            'distance'               => 'required|numeric|min:0.1',
            'Farm_Arrival_Time'      => 'required|date|after:now',
            'Farm_Departure_Time'    => 'nullable|date|after:Farm_Arrival_Time',
            'notes'                  => 'nullable|string|max:500'
        ]);

        $calculatedPrice = 10 + ($validated['distance'] * $validated['passengers'] * 0.5);
        $commissionAmount = $calculatedPrice * 0.10;
        $netCompanyAmount = $calculatedPrice - $commissionAmount;

        Transport::create([
            'user_id'                => Auth::id(),
            'company_id'             => null,
            'driver_id'              => null,
            'vehicle_id'             => null,
            'transport_type'         => $validated['transport_type'],
            'passengers'             => $validated['passengers'],
            'start_and_return_point' => $validated['start_and_return_point'],
            'farm_id'                => $validated['farm_id'],
            'distance'               => $validated['distance'],
            'price'                  => $calculatedPrice,
            'commission_amount'      => $commissionAmount,
            'net_company_amount'     => $netCompanyAmount,
            'Farm_Arrival_Time'      => $validated['Farm_Arrival_Time'],
            'Farm_Departure_Time'    => $validated['Farm_Departure_Time'] ?? null,
            'status'                 => 'pending',
            'notes'                  => $validated['notes'],
        ]);

        return redirect()->route('transports.index')
            ->with('success', 'Transport request placed successfully! We are finding a fleet for you.');
    }

    // ==========================================
    // تم إضافة دوال التعديل والتحديث من كودك الأصلي
    // ==========================================

    public function edit(Transport $transport)
    {
        // التأكد إن الطلب للزبون نفسه، وإنه لسا ما تم الموافقة عليه
        if ($transport->user_id !== Auth::id()) abort(403);
        if ($transport->status !== 'pending') {
            return redirect()->route('transports.index')->with('error', 'You cannot edit a request that is already being processed.');
        }

        $farms = Farm::where('is_approved', true)->get();
        return view('transports.edit', compact('transport', 'farms'));
    }

    public function update(Request $request, Transport $transport)
    {
        if ($transport->user_id !== Auth::id() || $transport->status !== 'pending') {
            abort(403);
        }

        $validated = $request->validate([
            'transport_type'         => 'required|string|max:255',
            'passengers'             => 'required|integer|min:1|max:50',
            'start_and_return_point' => 'required|string|max:255',
            'farm_id'                => 'required|exists:farms,id',
            'distance'               => 'required|numeric|min:0.1',
            'Farm_Arrival_Time'      => 'required|date',
            'Farm_Departure_Time'    => 'nullable|date|after_or_equal:Farm_Arrival_Time',
            'notes'                  => 'nullable|string|max:500'
        ]);

        // إعادة حساب السعر والعمولة في حال غير المسافة أو عدد الركاب
        $calculatedPrice = 10 + ($validated['distance'] * $validated['passengers'] * 0.5);
        $commissionAmount = $calculatedPrice * 0.10;
        $netCompanyAmount = $calculatedPrice - $commissionAmount;

        $transport->update([
            'transport_type'         => $validated['transport_type'],
            'passengers'             => $validated['passengers'],
            'start_and_return_point' => $validated['start_and_return_point'],
            'farm_id'                => $validated['farm_id'],
            'distance'               => $validated['distance'],
            'price'                  => $calculatedPrice,
            'commission_amount'      => $commissionAmount,
            'net_company_amount'     => $netCompanyAmount,
            'Farm_Arrival_Time'      => $validated['Farm_Arrival_Time'],
            'Farm_Departure_Time'    => $validated['Farm_Departure_Time'] ?? null,
            'notes'                  => $validated['notes'],
        ]);

        return redirect()->route('transports.index')
            ->with('success', 'Transport request updated successfully!');
    }

    // إلغاء/حذف الطلب
    public function destroy(Transport $transport)
    {
        if ($transport->user_id !== Auth::id()) abort(403);

        // أنا خليتها تعمل delete زي كودك الأصلي عشان ما تتراكم طلبات ملغية بالداتا بيس
        $transport->delete();

        return redirect()->route('transports.index')->with('success', 'Transport request deleted successfully!');
    }

    public function show(Transport $transport)
    {
        if ($transport->user_id !== Auth::id()) abort(403);
        return view('transports.show', compact('transport'));
    }
}
