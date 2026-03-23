@extends('layouts.supply')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    <div class="mb-10 flex flex-col md:flex-row md:items-end justify-between gap-4">
        <div>
            <h1 class="text-3xl font-black text-gray-900 tracking-tight">Supply Headquarters</h1>
            <p class="text-sm font-bold text-emerald-600 tracking-widest uppercase mt-1">B2B Order Management & Fleet Dispatch</p>
        </div>
        <div class="bg-white px-5 py-3 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-3">
            <div class="w-3 h-3 rounded-full bg-emerald-500 animate-pulse"></div>
            <span class="text-xs font-black text-gray-700 uppercase tracking-widest">System Online</span>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-50 border-l-4 border-green-500 text-green-800 p-5 rounded-r-2xl shadow-sm font-bold mb-8 animate-fade-in flex items-center gap-3">
            <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-12 gap-6 mb-12">

        <div class="md:col-span-4 bg-emerald-600 p-8 rounded-[2rem] shadow-lg flex flex-col justify-center relative overflow-hidden text-white transform hover:-translate-y-1 transition-transform">
            <div class="absolute -right-10 -top-10 w-40 h-40 bg-emerald-500 rounded-full opacity-50 blur-2xl"></div>
            <span class="text-xs font-black text-emerald-100 uppercase tracking-widest mb-2 relative z-10 flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                Net Profit
            </span>
            <p class="text-5xl font-black relative z-10">{{ number_format($financials['net'], 2) }} <span class="text-lg text-emerald-200 uppercase">JOD</span></p>
        </div>

        <div class="md:col-span-4 bg-white p-8 rounded-[2rem] shadow-sm border border-gray-100 flex flex-col justify-between">
            <div class="mb-4">
                <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1 block">Gross Revenue</span>
                <p class="text-2xl font-black text-gray-900">{{ number_format($financials['gross'], 2) }} <span class="text-xs text-gray-500">JOD</span></p>
            </div>
            <div class="pt-4 border-t border-gray-100">
                <span class="text-[10px] font-black text-red-400 uppercase tracking-widest mb-1 block flex items-center gap-1">
                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6" /></svg>
                    Platform Fees
                </span>
                <p class="text-xl font-black text-red-500">- {{ number_format($financials['commission'], 2) }} <span class="text-xs text-red-400">JOD</span></p>
            </div>
        </div>

        <div class="md:col-span-4 bg-white p-8 rounded-[2rem] shadow-sm border border-gray-100 flex flex-col justify-between">
            <div class="grid grid-cols-2 gap-4 h-full">
                <div class="bg-gray-50 rounded-2xl p-4 flex flex-col justify-center items-center text-center">
                    <svg class="w-8 h-8 text-blue-500 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg>
                    <p class="text-2xl font-black text-gray-900 leading-none">{{ $totalSupplies }}</p>
                    <p class="text-[9px] font-bold text-gray-500 uppercase tracking-widest mt-1">Products</p>
                </div>
                <div class="bg-amber-50 rounded-2xl p-4 flex flex-col justify-center items-center text-center border border-amber-100">
                    <svg class="w-8 h-8 text-amber-500 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    <p class="text-2xl font-black text-amber-600 leading-none">{{ $pendingOrders }}</p>
                    <p class="text-[9px] font-bold text-amber-500 uppercase tracking-widest mt-1">Pending</p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">

        <div class="xl:col-span-2">
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center gap-3">
                    <div class="w-2 h-8 bg-gray-800 rounded-full"></div>
                    <h2 class="text-xl font-black text-gray-800 tracking-tight">Order Dispatch Board</h2>
                </div>
                <span class="bg-gray-100 text-gray-600 text-[10px] font-black px-3 py-1.5 rounded-lg uppercase tracking-widest">{{ $totalOrders }} Total Orders</span>
            </div>

            <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 overflow-hidden">
                @if($recentOrders->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full leading-normal">
                            <thead>
                                <tr>
                                    <th class="px-6 py-5 border-b-2 border-gray-100 bg-gray-50 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">Order Details</th>
                                    <th class="px-6 py-5 border-b-2 border-gray-100 bg-gray-50 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">Product & Value</th>
                                    <th class="px-6 py-5 border-b-2 border-gray-100 bg-gray-50 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">Status / Dispatch</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach($recentOrders as $order)
                                    <tr class="hover:bg-gray-50/50 transition-colors duration-200">

                                        <td class="px-6 py-6 bg-transparent">
                                            <div class="flex items-center gap-4">
                                                <div class="h-12 w-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-black text-sm border border-emerald-100">
                                                    #{{ $order->id }}
                                                </div>
                                                <div>
                                                    <p class="text-sm font-black text-gray-900">{{ $order->user->name ?? 'Farm Owner' }}</p>
                                                    <p class="text-[10px] font-bold text-gray-400 mt-1 flex items-center gap-1 uppercase tracking-widest">
                                                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" /></svg>
                                                        {{ $order->user->phone ?? 'No Phone' }}
                                                    </p>
                                                </div>
                                            </div>
                                        </td>

                                        <td class="px-6 py-6 bg-transparent">
                                            <p class="text-sm font-black text-gray-800">{{ $order->supply->name ?? 'Product Removed' }}</p>
                                            <p class="text-[11px] font-bold text-gray-500 mt-1 uppercase tracking-widest">QTY: <span class="text-emerald-600 font-black">{{ $order->quantity }}</span> &nbsp;|&nbsp; <span class="text-gray-900 font-black">{{ number_format($order->total_amount, 2) }} JOD</span></p>
                                        </td>

                                        <td class="px-6 py-6 bg-transparent">
                                            @if($order->status === 'pending')
                                                <form action="{{ route('supplies.assign_driver', $order->id) }}" method="POST" class="flex flex-col gap-2">
                                                    @csrf
                                                    @method('PATCH')
                                                    @if($drivers->count() > 0)
                                                        <div class="flex items-center gap-2">
                                                            <select name="driver_id" required class="w-full bg-white border border-gray-200 text-gray-800 text-[11px] font-bold rounded-lg focus:ring-2 focus:ring-emerald-500 py-2 px-3 appearance-none shadow-sm cursor-pointer">
                                                                <option value="">Select Fleet Driver...</option>
                                                                @foreach($drivers as $driver)
                                                                    <option value="{{ $driver->id }}">{{ $driver->name }}</option>
                                                                @endforeach
                                                            </select>
                                                            <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white text-[10px] font-black uppercase tracking-widest py-2 px-4 rounded-lg shadow-sm transition-all active:scale-95">
                                                                Dispatch
                                                            </button>
                                                        </div>
                                                        @error('driver_id')
                                                            <span class="text-[9px] text-red-500 font-bold uppercase">{{ $message }}</span>
                                                        @enderror
                                                    @else
                                                        <span class="text-[10px] font-bold text-red-500 bg-red-50 px-3 py-1.5 rounded-lg border border-red-100 inline-block uppercase tracking-widest">No Drivers Available. Add to fleet.</span>
                                                    @endif
                                                </form>
                                            @else
                                                @php
                                                    $statusConfig = [
                                                        'in_way' => ['bg-blue-50', 'text-blue-600', 'border-blue-100', 'bg-blue-500'],
                                                        'delivered' => ['bg-emerald-50', 'text-emerald-600', 'border-emerald-100', 'bg-emerald-500'],
                                                        'completed' => ['bg-emerald-50', 'text-emerald-600', 'border-emerald-100', 'bg-emerald-500'],
                                                        'cancelled' => ['bg-red-50', 'text-red-600', 'border-red-100', 'bg-red-500'],
                                                    ];
                                                    $config = $statusConfig[$order->status] ?? ['bg-gray-50', 'text-gray-600', 'border-gray-200', 'bg-gray-400'];
                                                @endphp
                                                <div class="flex flex-col gap-1 items-start">
                                                    <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg text-[10px] font-black uppercase tracking-widest border {{ $config[0] }} {{ $config[1] }} {{ $config[2] }}">
                                                        <span class="w-1.5 h-1.5 rounded-full {{ $config[3] }}"></span>
                                                        {{ str_replace('_', ' ', $order->status) }}
                                                    </span>
                                                    @if($order->driver_id)
                                                        <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest flex items-center gap-1 mt-1">
                                                            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" /></svg>
                                                            {{ $order->driver->name ?? 'Driver Assigned' }}
                                                        </p>
                                                    @endif
                                                </div>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="px-8 py-16 text-center">
                        <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-gray-50 mb-4">
                            <svg class="w-10 h-10 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg>
                        </div>
                        <h3 class="text-lg font-black text-gray-800 mb-1">No Orders Yet</h3>
                        <p class="text-sm font-bold text-gray-400">Incoming B2B orders from farms will appear here.</p>
                    </div>
                @endif
            </div>
        </div>

        <div class="xl:col-span-1">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-2 h-8 bg-blue-500 rounded-full"></div>
                <h2 class="text-xl font-black text-gray-800 tracking-tight">Delivery Fleet</h2>
            </div>

            <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-6 border-b border-gray-100 bg-gray-50/50 flex justify-between items-center">
                    <span class="text-xs font-black text-gray-500 uppercase tracking-widest">Active Drivers</span>
                    <span class="bg-blue-100 text-blue-700 text-[10px] font-black px-2.5 py-1 rounded-md">{{ $drivers->count() }}</span>
                </div>

                <div class="p-2">
                    @if($drivers->count() > 0)
                        <ul class="divide-y divide-gray-50">
                            @foreach($drivers as $driver)
                                <li class="p-4 hover:bg-gray-50 rounded-2xl transition-colors flex items-center gap-4">
                                    <div class="w-10 h-10 rounded-full bg-blue-50 border border-blue-100 flex items-center justify-center text-blue-600 font-black text-sm uppercase">
                                        {{ substr($driver->name, 0, 2) }}
                                    </div>
                                    <div>
                                        <p class="text-sm font-black text-gray-900">{{ $driver->name }}</p>
                                        <p class="text-[10px] font-bold text-gray-400 mt-0.5 uppercase tracking-widest flex items-center gap-1">
                                            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" /></svg>
                                            {{ $driver->phone ?? 'No Phone' }}
                                        </p>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <div class="p-8 text-center">
                            <p class="text-sm font-bold text-gray-400 mb-4">No drivers assigned to your company yet.</p>
                            <span class="text-[10px] font-black text-gray-300 uppercase tracking-widest border border-dashed border-gray-200 p-2 rounded-lg block">Fleet Empty</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
