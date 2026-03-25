@extends('layouts.app')

@section('content')
<div class="bg-gray-50 min-h-screen py-16">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="flex flex-col md:flex-row md:justify-between md:items-end mb-12 gap-6">
            <div>
                <h1 class="text-4xl font-black text-gray-900 tracking-tight">Order Tracking</h1>
                <p class="text-sm font-bold text-gray-400 mt-2 uppercase tracking-widest">Monitor your shipments in real-time</p>
            </div>
            <div class="flex items-center gap-4">
                <a href="{{ route('supplies.market') }}" class="inline-flex items-center gap-2 bg-white border-2 border-gray-100 text-gray-700 hover:text-blue-600 hover:border-blue-200 font-black py-3 px-6 rounded-2xl transition-all shadow-sm text-xs uppercase tracking-widest transform hover:-translate-y-1">
                    Continue Shopping
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="bg-green-50 border-l-4 border-green-500 text-green-800 p-5 rounded-r-2xl shadow-sm font-bold mb-10 flex items-center gap-3">
                <svg class="w-6 h-6 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                {{ session('success') }}
            </div>
        @endif

        @if($groupedOrders->count() > 0)
            <div class="space-y-10">
                @foreach($groupedOrders as $invoiceId => $items)
                    @php
                        $invoiceStatus = $items->first()->status;
                        $invoiceTotal = $items->sum('total_price');
                        $orderDate = $items->first()->created_at;
                    @endphp

                    <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden relative">
                        <div class="bg-gray-50/50 p-8 flex flex-col lg:flex-row lg:items-center justify-between gap-8 border-b border-gray-100">
                            <div>
                                <div class="flex items-center gap-4 mb-2">
                                    <span class="text-2xl font-black text-gray-900">{{ $invoiceId }}</span>
                                    <span class="text-[10px] font-black text-blue-600 bg-blue-50 border border-blue-100 px-3 py-1 rounded-lg uppercase tracking-widest">{{ $items->count() }} items</span>
                                </div>
                                <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">{{ $orderDate->format('M d, Y - h:i A') }}</p>
                            </div>

                            <div class="w-full lg:w-3/5">
                                <div class="relative flex items-center justify-between">
                                    <div class="absolute left-0 top-1/2 -translate-y-1/2 w-full h-1.5 bg-gray-100 rounded-full z-0"></div>

                                    @php
                                        $steps = [
                                            'pending' => ['label' => 'Placed', 'active' => in_array($invoiceStatus, ['pending', 'accepted', 'in_way', 'delivered'])],
                                            'accepted' => ['label' => 'Preparing', 'active' => in_array($invoiceStatus, ['accepted', 'in_way', 'delivered'])],
                                            'in_way' => ['label' => 'Out for Delivery', 'active' => in_array($invoiceStatus, ['in_way', 'delivered'])],
                                            'delivered' => ['label' => 'Delivered', 'active' => $invoiceStatus === 'delivered'],
                                        ];
                                    @endphp

                                    @if($invoiceStatus === 'cancelled')
                                        <div class="w-full text-center py-3 relative z-10 bg-white/80 backdrop-blur-sm rounded-xl border border-red-100">
                                            <span class="inline-flex items-center gap-2 text-[10px] font-black uppercase tracking-widest text-red-600">
                                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
                                                Order Cancelled
                                            </span>
                                        </div>
                                    @else
                                        @foreach($steps as $key => $step)
                                            <div class="relative z-10 flex flex-col items-center gap-3 bg-gray-50 px-2 lg:px-4">
                                                <div class="h-8 w-8 rounded-full flex items-center justify-center border-[3px] transition-all duration-300 {{ $step['active'] ? 'border-blue-600 bg-blue-600 text-white shadow-lg shadow-blue-200' : 'border-gray-200 bg-white text-transparent' }}">
                                                    <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                                </div>
                                                <span class="text-[9px] font-black uppercase tracking-widest {{ $step['active'] ? 'text-blue-900' : 'text-gray-400' }}">{{ $step['label'] }}</span>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        </div>

                        @if($invoiceStatus === 'in_way' && $items->first()->driver)
                            <div class="bg-blue-50/50 border-b border-blue-100 px-8 py-5 flex items-center gap-5">
                                <div class="h-12 w-12 rounded-2xl bg-white shadow-sm flex items-center justify-center text-blue-600 border border-blue-100">
                                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                                </div>
                                <div>
                                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Driver Assigned</p>
                                    <p class="text-sm font-black text-blue-900">{{ $items->first()->driver->name }} <span class="text-blue-600 ml-1 font-bold text-xs capitalize">— Out for delivery</span></p>
                                </div>
                            </div>
                        @endif

                        <div class="p-8">
                            <ul class="space-y-6">
                                @foreach($items as $item)
                                    <li class="flex items-center justify-between">
                                        <div class="flex items-center gap-5">
                                            <div class="h-14 w-14 rounded-xl bg-gray-50 border border-gray-100 overflow-hidden flex-shrink-0">
                                                @if($item->supply && $item->supply->image)
                                                    <img src="{{ Storage::url($item->supply->image) }}" class="h-full w-full object-cover">
                                                @endif
                                            </div>
                                            <div>
                                                <h4 class="text-base font-black text-gray-900">{{ $item->supply->name ?? 'Product' }}</h4>
                                                <p class="text-[10px] font-bold text-gray-400 mt-1 uppercase tracking-widest">Qty: {{ $item->quantity }} &nbsp;|&nbsp; By {{ $item->supply->company->name ?? 'Vendor' }}</p>
                                            </div>
                                        </div>
                                        <div class="text-right flex flex-col items-end">
                                            <span class="text-lg font-black text-gray-900">{{ number_format($item->total_price, 2) }} <span class="text-[10px] text-gray-400 uppercase tracking-widest">JOD</span></span>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>

                            <div class="mt-8 pt-6 border-t border-gray-100 flex justify-between items-center">
                                <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Invoice Total</span>
                                <span class="text-2xl font-black text-blue-600">{{ number_format($invoiceTotal, 2) }} <span class="text-sm font-bold text-gray-400 uppercase tracking-widest">JOD</span></span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="p-24 text-center bg-white rounded-[3rem] shadow-sm border border-gray-100">
                <div class="inline-flex items-center justify-center w-24 h-24 rounded-full bg-gray-50 mb-6 border border-gray-100">
                    <svg class="h-10 w-10 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" /></svg>
                </div>
                <h3 class="text-2xl font-black text-gray-900 mb-2">No Active Orders</h3>
                <p class="text-sm font-bold text-gray-400 max-w-md mx-auto mb-8 uppercase tracking-widest">You haven't placed any supply orders yet.</p>
                <a href="{{ route('supplies.market') }}" class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-black py-4 px-10 rounded-2xl shadow-lg hover:shadow-xl transition-all transform active:scale-95 uppercase tracking-widest text-xs">Browse Supplies</a>
            </div>
        @endif

    </div>
</div>
@endsection
