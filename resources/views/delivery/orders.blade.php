@extends('layouts.driver')

@section('content')
    <div class="mb-10">
        <h1 class="text-3xl font-black text-gray-900 tracking-tight">Supply Assignments</h1>
        <p class="text-sm font-bold text-gray-400 mt-1 uppercase tracking-widest">Manage your active delivery routes</p>
    </div>

    @if(session('success'))
        <div class="bg-green-50 border-l-4 border-green-500 text-green-800 p-5 rounded-2xl shadow-sm font-bold mb-10 flex items-center gap-3">
            <svg class="w-6 h-6 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-50 border-l-4 border-red-500 text-red-800 p-5 rounded-2xl shadow-sm font-bold mb-10 flex items-center gap-3">
            <svg class="w-6 h-6 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            {{ session('error') }}
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6 flex items-center transition-transform hover:-translate-y-1">
            <div class="p-4 rounded-2xl bg-blue-50 text-blue-600 mr-5 shadow-inner">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
            </div>
            <div>
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Total</p>
                <p class="text-3xl font-black text-gray-900">{{ $totalDeliveries ?? 0 }}</p>
            </div>
        </div>

        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6 flex items-center transition-transform hover:-translate-y-1">
            <div class="p-4 rounded-2xl bg-amber-50 text-amber-500 mr-5 shadow-inner">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <div>
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Active</p>
                <p class="text-3xl font-black text-amber-600">{{ $pendingDeliveries ?? 0 }}</p>
            </div>
        </div>

        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6 flex items-center transition-transform hover:-translate-y-1">
            <div class="p-4 rounded-2xl bg-emerald-50 text-emerald-500 mr-5 shadow-inner">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
            </div>
            <div>
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Completed</p>
                <p class="text-3xl font-black text-emerald-600">{{ $completedDeliveries ?? 0 }}</p>
            </div>
        </div>
    </div>

    <div class="space-y-6">
        @if(isset($groupedOrders) && $groupedOrders->count() > 0)
            @foreach($groupedOrders as $invoiceId => $items)
                @php
                    $invoiceStatus = $items->first()->status;
                    $customer = $items->first()->user;
                    $booking = $items->first()->booking;
                    $isDelivered = in_array($invoiceStatus, ['completed', 'delivered']);
                @endphp

                <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 overflow-hidden {{ $isDelivered ? 'opacity-70 grayscale-[20%]' : '' }}">
                    <div class="bg-gray-50/50 p-6 flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-gray-100">
                        <div>
                            <div class="flex items-center gap-3 mb-1">
                                <span class="text-xl font-black text-gray-900">{{ $invoiceId }}</span>

                                @if($isDelivered)
                                    <span class="inline-flex items-center px-2 py-1 rounded bg-emerald-50 text-emerald-600 border border-emerald-100 text-[9px] font-black uppercase tracking-widest">
                                        Delivered
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-1 rounded bg-amber-50 text-amber-600 border border-amber-100 text-[9px] font-black uppercase tracking-widest">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse mr-1.5"></span>
                                        Out for Delivery
                                    </span>
                                @endif
                            </div>
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Assigned: {{ $items->first()->created_at->format('M d, Y - h:i A') }}</p>
                        </div>

                        @if(!$isDelivered)
                            <form action="{{ route('delivery.mark_delivered', $invoiceId) }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full md:w-auto bg-blue-600 hover:bg-blue-700 text-white font-black py-3 px-6 rounded-xl shadow-md transition-all flex items-center justify-center gap-2 uppercase tracking-widest text-[10px] transform active:scale-95">
                                    <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>
                                    Mark as Delivered
                                </button>
                            </form>
                        @endif
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-0 border-b border-gray-100">
                        <div class="p-6 md:border-r border-gray-100 bg-white">
                            <h4 class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-3 flex items-center gap-2">
                                <svg class="w-3.5 h-3.5 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                                Customer Details
                            </h4>
                            <p class="text-lg font-black text-gray-900">{{ $customer->name ?? 'N/A' }}</p>
                            <p class="text-xs font-bold text-blue-600 mt-1 flex items-center gap-1.5">
                                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                {{ $customer->phone ?? 'No phone provided' }}
                            </p>
                        </div>
                        <div class="p-6 bg-gray-50/50">
                            <h4 class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-3 flex items-center gap-2">
                                <svg class="w-3.5 h-3.5 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                Delivery Destination
                            </h4>
                            @if($booking && $booking->farm)
                                <p class="text-lg font-black text-gray-900">{{ $booking->farm->name }}</p>
                                <p class="text-xs font-bold text-gray-500 mt-1 line-clamp-2">{{ $booking->farm->address ?? 'Address not specified' }}</p>
                            @else
                                <p class="text-xs font-bold text-gray-600 italic bg-gray-100 px-3 py-1.5 rounded-lg inline-block">Direct Delivery to Customer</p>
                            @endif
                        </div>
                    </div>

                    <div class="p-6">
                        <h4 class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-4">Items to Drop Off</h4>
                        <ul class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            @foreach($items as $item)
                                <li class="flex items-center gap-4 bg-gray-50 border border-gray-100 p-3 rounded-xl shadow-sm">
                                    <div class="h-12 w-12 rounded-lg bg-white border border-gray-100 overflow-hidden flex-shrink-0 flex items-center justify-center">
                                        @if($item->supply && $item->supply->image)
                                            <img src="{{ Storage::url($item->supply->image) }}" class="h-full w-full object-cover">
                                        @else
                                            <svg class="h-5 w-5 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                        @endif
                                    </div>
                                    <div>
                                        <h5 class="text-xs font-black text-gray-900 line-clamp-1">{{ $item->supply->name ?? 'Product' }}</h5>
                                        <p class="text-[9px] font-black text-gray-500 mt-1 uppercase tracking-widest">Qty: <span class="text-blue-600 text-xs">{{ $item->quantity }}</span></p>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endforeach
        @else
            <div class="p-16 text-center bg-white rounded-[3rem] shadow-sm border border-gray-100">
                <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-blue-50 mb-6 border border-blue-100">
                    <svg class="h-10 w-10 text-blue-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" /></svg>
                </div>
                <h3 class="text-2xl font-black text-gray-900 mb-2">No Active Assignments</h3>
                <p class="text-xs font-bold text-gray-400 max-w-md mx-auto uppercase tracking-widest">You currently have no supply deliveries assigned to you. Enjoy your break!</p>
            </div>
        @endif
    </div>
@endsection
