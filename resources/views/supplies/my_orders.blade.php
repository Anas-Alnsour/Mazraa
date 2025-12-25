@extends('layouts.app')

@section('title', 'My Orders')

@section('content')
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

        <h1 class="text-3xl font-extrabold text-gray-900 mb-8 tracking-tight">Order History</h1>

        @if(session('success'))
            <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded-r-xl flex items-center gap-3 shadow-sm">
                <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                <p class="text-green-800 font-medium">{{ session('success') }}</p>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-r-xl flex items-center gap-3 shadow-sm">
                <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <p class="text-red-800 font-medium">{{ session('error') }}</p>
            </div>
        @endif

        @if($groupedOrders->isEmpty())
            <div class="flex flex-col items-center justify-center py-16 bg-white rounded-[2rem] border border-gray-100 shadow-sm text-center">
                <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mb-4">
                    <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">No orders found</h3>
                <p class="text-gray-500 mb-6">You haven't placed any orders yet.</p>
                <a href="{{ route('supplies.index') }}" class="px-6 py-2.5 bg-green-600 text-white font-bold rounded-xl hover:bg-green-700 shadow-md transition">
                    Start Shopping
                </a>
            </div>
        @else

            <div class="space-y-6">
                @php $totalUnpaid = 0; $counter = 1; @endphp

                @foreach($groupedOrders as $orderId => $orderGroup)
                    @php
                        $groupTotal = $orderGroup->sum('total_price');
                        $firstOrder = $orderGroup->first();
                        $status = $firstOrder->status;
                        $date = $firstOrder->created_at;

                        if(in_array($status, ['placed', 'in_way'])) {
                            $totalUnpaid += $groupTotal;
                        }
                    @endphp

                    <div x-data="{ expanded: false }" class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden transition-all hover:shadow-md">

                        <div @click="expanded = !expanded" class="p-6 cursor-pointer flex flex-col md:flex-row justify-between items-center gap-4 bg-gray-50/50 hover:bg-gray-50 transition">

                            <div class="flex items-center gap-4 w-full md:w-auto">
                                <div class="bg-white border border-gray-200 text-gray-500 w-12 h-12 rounded-full flex items-center justify-center font-bold text-sm shrink-0 shadow-sm">
                                    {{ $counter++ }}
                                </div>

                                <div>
                                    <div class="flex items-center gap-2 mb-1">
                                        <h3 class="text-lg font-bold text-gray-900">Order</h3>
                                        <span class="font-mono text-sm font-medium text-gray-500 bg-gray-200/60 px-2 py-0.5 rounded border border-gray-200">
                                            #ORD-{{ str_pad($orderId, 5, '0', STR_PAD_LEFT) }}
                                        </span>
                                    </div>
                                    <p class="text-sm text-gray-500 flex items-center gap-1">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        {{ $date ? $date->format('M d, Y • h:i A') : 'Date N/A' }}
                                    </p>
                                </div>
                            </div>

                            <div class="flex items-center gap-6 w-full md:w-auto justify-between md:justify-end">

                                <div>
                                    @if($status === 'placed')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <span class="w-2 h-2 mr-1.5 bg-green-400 rounded-full"></span>
                                            Placed
                                        </span>
                                    @elseif($status === 'in_way')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                                            In Way
                                        </span>
                                    @elseif($status === 'paid')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                            Paid
                                        </span>
                                    @else
                                        <span class="px-3 py-1 bg-yellow-100 text-yellow-700 text-xs font-bold uppercase rounded-full tracking-wide">{{ $status }}</span>
                                    @endif
                                </div>

                                <div class="text-right">
                                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">Total</p>
                                    <p class="text-lg font-extrabold text-gray-900">{{ $groupTotal }} <span class="text-xs text-gray-500 font-medium">JD</span></p>
                                </div>

                                <div class="w-8 h-8 rounded-full bg-white border border-gray-200 flex items-center justify-center text-gray-400 transition-transform duration-300" :class="{'rotate-180 bg-gray-100 text-gray-600': expanded}">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                </div>
                            </div>
                        </div>

                        <div x-show="expanded" x-collapse class="border-t border-gray-100 bg-white">
                            <div class="p-6">
                                <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-4">Items Summary</h4>
                                <div class="space-y-3">
                                    @foreach($orderGroup as $item)
                                        <div class="flex justify-between items-center py-3 border-b border-gray-50 last:border-0 hover:bg-gray-50/50 rounded-lg px-2 transition">
                                            <div class="flex items-center gap-3">
                                                <div class="w-12 h-12 rounded-lg bg-gray-100 border border-gray-200 overflow-hidden flex-shrink-0">
                                                    <img src="{{ $item->supply->image ? Storage::url($item->supply->image) : 'https://via.placeholder.com/100' }}" class="w-full h-full object-cover">
                                                </div>
                                                <div>
                                                    <p class="text-sm font-bold text-gray-800">{{ $item->supply->name }}</p>
                                                    <p class="text-xs text-gray-500">Qty: <span class="font-mono font-medium">{{ $item->quantity }}</span></p>
                                                </div>
                                            </div>
                                            <p class="text-sm font-bold text-gray-700">{{ $item->total_price }} <span class="text-xs font-normal text-gray-400">JD</span></p>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                    </div>
                @endforeach

                @if($totalUnpaid > 0)
                    <div class="mt-8 bg-gray-900 text-white rounded-2xl p-6 flex flex-col sm:flex-row justify-between items-center shadow-xl gap-4">
                        <div class="text-center sm:text-left">
                            <p class="text-gray-400 text-xs uppercase tracking-widest font-semibold mb-1">Total Amount Due</p>
                            <p class="text-3xl font-bold">{{ $totalUnpaid }} <span class="text-lg text-gray-400 font-medium">JD</span></p>
                        </div>
                        <button class="w-full sm:w-auto px-8 py-3 bg-white text-gray-900 font-bold rounded-xl text-sm hover:bg-gray-100 transition shadow-md flex items-center justify-center gap-2">
                            <span>Proceed to Payment</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                        </button>
                    </div>
                @endif

            </div>
        @endif
    </div>
@endsection
