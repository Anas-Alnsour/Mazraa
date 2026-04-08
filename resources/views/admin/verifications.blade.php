@extends('layouts.admin')

@section('title', 'Verification Center')

@section('content')
<div class="max-w-7xl mx-auto py-10">

    <div class="mb-12 flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div>
            <h1 class="text-4xl font-black text-white tracking-tight">Verification Center</h1>
            <p class="text-sm font-bold text-slate-400 mt-2 uppercase tracking-widest">Review and approve pending requests</p>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-emerald-50 border-l-4 border-emerald-500 text-emerald-800 p-5 rounded-2xl shadow-sm font-bold mb-10 flex items-center gap-3">
            <svg class="w-6 h-6 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-50 border-l-4 border-red-500 text-red-800 p-5 rounded-2xl shadow-sm font-bold mb-10 flex items-center gap-3">
            <svg class="w-6 h-6 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
            {{ session('error') }}
        </div>
    @endif

    <div class="mb-10">
        <h2 class="text-lg font-black text-white flex items-center gap-2 mb-4">
            <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
            New Farm Listings Awaiting Approval
            <span class="bg-emerald-500/20 text-emerald-400 text-xs font-black px-2 py-0.5 rounded-full">{{ $pendingFarms->count() }}</span>
        </h2>

        <div class="bg-slate-800 rounded-[2.5rem] shadow-sm border border-slate-700 overflow-hidden">
            @if($pendingFarms->count() > 0)
                <div class="overflow-x-auto p-4">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr>
                                <th class="px-6 py-5 text-[9px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-700">Farm Details</th>
                                <th class="px-6 py-5 text-[9px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-700">Owner Info</th>
                                <th class="px-6 py-5 text-[9px] font-black text-slate-400 uppercase tracking-widest text-right border-b border-slate-700">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-700">
                            @foreach($pendingFarms as $farm)
                                <tr class="hover:bg-slate-700/50 transition-colors">
                                    <td class="px-6 py-6">
                                        <p class="text-base font-black text-white">{{ $farm->name }}</p>
                                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Price: {{ $farm->price_per_night }} JOD/night</p>
                                    </td>
                                    <td class="px-6 py-6">
                                        <p class="text-sm font-black text-white">{{ $farm->owner->name ?? 'Unknown' }}</p>
                                        <p class="text-[10px] font-bold text-indigo-400 mt-0.5 uppercase tracking-widest">{{ $farm->owner->phone ?? 'No Phone' }}</p>
                                    </td>
                                    <td class="px-6 py-6 text-right">
                                        <form action="{{ route('admin.verifications.handle', ['id' => $farm->id, 'type' => 'farm_approval']) }}" method="POST" class="inline-flex gap-2">
                                            @csrf
                                            <button type="submit" name="action" value="reject" class="px-5 py-3 bg-slate-800 border border-rose-500/30 text-rose-400 hover:bg-rose-500 hover:text-white text-[10px] font-black uppercase tracking-widest rounded-xl transition-all" onclick="return confirm('Reject and delete farm?');">Reject</button>
                                            <button type="submit" name="action" value="approve" class="px-5 py-3 bg-emerald-600 text-white hover:bg-emerald-500 text-[10px] font-black uppercase tracking-widest rounded-xl transition-all shadow-lg shadow-emerald-900/20">Approve</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="p-20 text-center bg-slate-800 rounded-[2.5rem] border border-slate-700/50">
                    <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-emerald-500/10 mb-6 border border-emerald-500/20">
                        <svg class="h-10 w-10 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                    </div>
                    <h3 class="text-2xl font-black text-white mb-2">No Pending Farms</h3>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">All farm listings have been successfully reviewed and processed.</p>
                </div>
            @endif
        </div>
    </div>

    <div class="mb-10">
        <h2 class="text-lg font-black text-white flex items-center gap-2 mb-4">
            <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            Farm Bookings Awaiting CliQ Verification
            <span class="bg-indigo-500/20 text-indigo-400 text-xs font-black px-2 py-0.5 rounded-full">{{ isset($farmBookings) ? $farmBookings->count() : 0 }}</span>
        </h2>

        <div class="bg-slate-800 rounded-[2.5rem] shadow-sm border border-slate-700 overflow-hidden">
            @if(isset($farmBookings) && $farmBookings->count() > 0)
                <div class="overflow-x-auto p-4">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-900/50 border-b border-slate-700">
                                <th class="px-6 py-4 text-[9px] font-black text-slate-400 uppercase tracking-widest">Customer & ID</th>
                                <th class="px-6 py-4 text-[9px] font-black text-slate-400 uppercase tracking-widest">Farm Details</th>
                                <th class="px-6 py-4 text-[9px] font-black text-slate-400 uppercase tracking-widest">Amount Required</th>
                                <th class="px-6 py-4 text-[9px] font-black text-slate-400 uppercase tracking-widest text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-700">
                            @foreach($farmBookings as $booking)
                                <tr class="hover:bg-slate-700/50 transition-colors">
                                    <td class="px-6 py-4">
                                        <span class="block text-sm font-bold text-white">{{ $booking->user->name ?? 'Guest' }}</span>
                                        <span class="block text-xs font-medium text-slate-400">{{ $booking->user->phone ?? 'No phone' }}</span>
                                        <span class="block mt-1 text-[10px] font-mono font-bold text-indigo-400 bg-indigo-500/10 px-2 py-0.5 rounded w-max border border-indigo-500/20">#FB-{{ $booking->id }}</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="block text-sm font-bold text-white">{{ $booking->farm->name }}</span>
                                        <span class="block text-xs font-medium text-slate-400">{{ \Carbon\Carbon::parse($booking->start_time)->format('M d, Y') }} ({{ ucfirst(str_replace('_', ' ', $booking->event_type)) }})</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="text-sm font-black text-indigo-400">{{ number_format($booking->total_price, 2) }} JOD</span>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <form action="{{ route('admin.verifications.handle', ['id' => $booking->id, 'type' => 'farm_booking']) }}" method="POST" class="inline-flex gap-2">
                                            @csrf
                                            <button type="submit" name="action" value="reject" class="px-4 py-2 rounded-lg bg-rose-500/10 text-rose-400 hover:bg-rose-600 hover:text-white border border-rose-500/20 transition-colors text-[10px] font-black uppercase tracking-wider" onclick="return confirm('Reject and cancel this booking?');">Reject</button>
                                            <button type="submit" name="action" value="approve" class="px-4 py-2 rounded-lg bg-emerald-500/10 text-emerald-400 hover:bg-emerald-600 hover:text-white border border-emerald-500/20 transition-colors text-[10px] font-black uppercase tracking-wider" onclick="return confirm('Confirm receipt of CliQ funds?');">Approve</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="p-20 text-center bg-slate-800 rounded-[2.5rem] border border-slate-700/50">
                    <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-indigo-500/10 mb-6 border border-indigo-500/20">
                        <svg class="h-10 w-10 text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </div>
                    <h3 class="text-2xl font-black text-white mb-2">Queue is Empty</h3>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">No pending farm booking payments to verify.</p>
                </div>
            @endif
        </div>
    </div>

    <div class="mb-10">
        <h2 class="text-lg font-black text-white flex items-center gap-2 mb-4">
            <svg class="w-5 h-5 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
            Supply Orders Awaiting CliQ Verification
            <span class="bg-rose-500/20 text-rose-400 text-xs font-black px-2 py-0.5 rounded-full">{{ isset($supplyOrders) ? $supplyOrders->count() : 0 }}</span>
        </h2>

        <div class="bg-slate-800 rounded-[2.5rem] shadow-sm border border-slate-700 overflow-hidden">
            @if(isset($supplyOrders) && $supplyOrders->count() > 0)
                <div class="overflow-x-auto p-4">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-900/50 border-b border-slate-700">
                                <th class="px-6 py-4 text-[9px] font-black text-slate-400 uppercase tracking-widest">Customer & Invoice</th>
                                <th class="px-6 py-4 text-[9px] font-black text-slate-400 uppercase tracking-widest">Supply Details</th>
                                <th class="px-6 py-4 text-[9px] font-black text-slate-400 uppercase tracking-widest">Amount Required</th>
                                <th class="px-6 py-4 text-[9px] font-black text-slate-400 uppercase tracking-widest text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-700">
                            @foreach($supplyOrders as $order)
                                <tr class="hover:bg-slate-700/50 transition-colors">
                                    <td class="px-6 py-4">
                                        <span class="block text-sm font-bold text-white">{{ $order->user->name ?? 'Guest' }}</span>
                                        <span class="block mt-1 text-[10px] font-mono font-bold text-rose-400 bg-rose-500/10 px-2 py-0.5 rounded w-max border border-rose-500/20">{{ $order->order_id }}</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="block text-sm font-bold text-white">{{ $order->supply->name ?? 'Deleted Item' }}</span>
                                        <span class="block text-xs font-medium text-slate-400">Qty: {{ $order->quantity }}</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="text-sm font-black text-indigo-400">{{ number_format($order->total_price, 2) }} JOD</span>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <form action="{{ route('admin.verifications.handle', ['id' => $order->id, 'type' => 'supply_order']) }}" method="POST" class="inline-flex gap-2">
                                            @csrf
                                            <button type="submit" name="action" value="reject" class="px-4 py-2 rounded-lg bg-rose-500/10 text-rose-400 hover:bg-rose-600 hover:text-white border border-rose-500/20 transition-colors text-[10px] font-black uppercase tracking-wider" onclick="return confirm('Reject and cancel this order?');">Reject</button>
                                            <button type="submit" name="action" value="approve" class="px-4 py-2 rounded-lg bg-emerald-500/10 text-emerald-400 hover:bg-emerald-600 hover:text-white border border-emerald-500/20 transition-colors text-[10px] font-black uppercase tracking-wider" onclick="return confirm('Confirm receipt of CliQ funds?');">Approve</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="p-20 text-center bg-slate-800 rounded-[2.5rem] border border-slate-700/50">
                    <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-rose-500/10 mb-6 border border-rose-500/20">
                        <svg class="h-10 w-10 text-rose-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" /></svg>
                    </div>
                    <h3 class="text-2xl font-black text-white mb-2">Queue is Empty</h3>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">No pending supply marketplace orders to verify.</p>
                </div>
            @endif
        </div>
    </div>

</div>
@endsection
