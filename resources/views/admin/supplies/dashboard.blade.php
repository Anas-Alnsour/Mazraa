@extends('layouts.supply')

@section('title', 'Operations & Dispatch')

@section('content')
    <div class="max-w-7xl mx-auto space-y-8">

        @if(session('success'))
            <div class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 rounded-r-xl shadow-sm mb-6">
                <p class="font-bold text-sm tracking-tight">{{ session('success') }}</p>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded-r-xl shadow-sm mb-6">
                <p class="font-bold text-sm tracking-tight">{{ session('error') }}</p>
            </div>
        @endif

        {{-- 1. Financial Reports & Stats --}}
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="bg-white overflow-hidden shadow-sm rounded-2xl border border-gray-100 p-6 transition-transform hover:-translate-y-1">
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Gross Sales</p>
                <p class="text-3xl font-black text-gray-900">{{ number_format($financials['gross'], 2) }} <span class="text-sm text-gray-400">JOD</span></p>
            </div>

            <div class="bg-white overflow-hidden shadow-sm rounded-2xl border border-gray-100 p-6 transition-transform hover:-translate-y-1">
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Platform Fees</p>
                <p class="text-3xl font-black text-red-500">- {{ number_format($financials['commission'], 2) }} <span class="text-sm text-gray-400">JOD</span></p>
            </div>

            <div class="bg-white overflow-hidden shadow-sm rounded-2xl border border-blue-200 bg-blue-50 p-6 transition-transform hover:-translate-y-1">
                <p class="text-[10px] font-black text-blue-600 uppercase tracking-widest mb-1">Net Profit</p>
                <p class="text-3xl font-black text-blue-700">{{ number_format($financials['net'], 2) }} <span class="text-sm text-blue-500">JOD</span></p>
            </div>

            <div class="bg-white overflow-hidden shadow-sm rounded-2xl border border-gray-100 p-6 transition-transform hover:-translate-y-1">
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Active Drivers</p>
                <p class="text-3xl font-black text-gray-900">{{ $drivers->count() }}</p>
            </div>
        </div>

        {{-- 2. Dispatching & Order Management --}}
        <div class="bg-white overflow-hidden shadow-sm rounded-[2rem] border border-gray-100 p-8">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-black text-gray-800 tracking-tight">Dispatch Center & Orders</h3>
                <a href="#" class="px-5 py-2.5 bg-[#1e3a8a] hover:bg-[#1e40af] text-white text-[10px] uppercase tracking-widest font-black rounded-xl transition-all shadow-md active:scale-95">
                    + Add New Driver
                </a>
            </div>

            @if($recentOrders->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full text-left whitespace-nowrap">
                        <thead class="bg-gray-50 border-b border-gray-100 rounded-t-xl">
                            <tr>
                                <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Order ID</th>
                                <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Customer</th>
                                <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Supply Item</th>
                                <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Net Value</th>
                                <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Status</th>
                                <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Assigned Driver</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @foreach($recentOrders as $order)
                                <tr class="hover:bg-gray-50/50 transition-colors">
                                    <td class="px-6 py-4 font-black text-gray-900">#{{ substr($order->id, 0, 8) }}</td>
                                    <td class="px-6 py-4 text-sm font-bold text-gray-700">{{ $order->user->name ?? 'Unknown' }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-600">
                                        <span class="font-bold">{{ $order->supply->name ?? 'N/A' }}</span> <br>
                                        <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Qty: {{ $order->quantity }}</span>
                                    </td>
                                    <td class="px-6 py-4 text-sm font-black text-[#1e3a8a]">
                                        {{ number_format($order->net_company_amount ?? 0, 2) }} JOD
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($order->status == 'completed' || $order->status == 'delivered')
                                            <span class="bg-green-50 text-green-600 text-[10px] font-black px-3 py-1.5 rounded-md border border-green-200 uppercase tracking-widest">Delivered</span>
                                        @elseif($order->status == 'in_way')
                                            <span class="bg-blue-50 text-blue-600 text-[10px] font-black px-3 py-1.5 rounded-md border border-blue-200 uppercase tracking-widest">On Route</span>
                                        @else
                                            <span class="bg-amber-50 text-amber-600 text-[10px] font-black px-3 py-1.5 rounded-md border border-amber-200 uppercase tracking-widest">Pending</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($order->driver_id)
                                            <div class="flex items-center space-x-3">
                                                <div class="w-8 h-8 rounded-full bg-blue-100 text-blue-700 flex items-center justify-center text-xs font-black border border-blue-200">
                                                    {{ strtoupper(substr($order->driver->name, 0, 1)) }}
                                                </div>
                                                <span class="text-sm font-bold text-gray-700">{{ $order->driver->name }}</span>
                                            </div>
                                        @else
                                            {{-- Dispatching Form --}}
                                            <form method="POST" action="{{ route('supplies.assign_driver', $order->id) }}" class="flex items-center space-x-2">
                                                @csrf
                                                @method('PATCH')
                                                <select name="driver_id" required class="text-xs border-gray-200 rounded-lg focus:ring-[#1e3a8a] focus:border-[#1e3a8a] font-bold text-gray-600 py-2 pl-3 pr-8 shadow-sm">
                                                    <option value="">Select Driver...</option>
                                                    @foreach($drivers as $driver)
                                                        <option value="{{ $driver->id }}">{{ $driver->name }}</option>
                                                    @endforeach
                                                </select>
                                                <button type="submit" class="bg-[#1e3a8a] text-white hover:bg-[#1e40af] px-4 py-2 rounded-lg text-[10px] uppercase tracking-widest font-black transition-transform active:scale-95 shadow-sm">
                                                    Dispatch
                                                </button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-12">
                    <svg class="w-16 h-16 text-gray-200 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                    <p class="text-sm font-black uppercase tracking-widest text-gray-400">No orders awaiting dispatch.</p>
                </div>
            @endif
        </div>

    </div>
@endsection
