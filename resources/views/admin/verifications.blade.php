@extends('layouts.admin')

@section('title', 'Verification Center')

@section('content')
<style>
    .fade-in-up { animation: fadeInUp 0.8s cubic-bezier(0.2, 0.8, 0.2, 1) forwards; opacity: 0; }
    @keyframes fadeInUp { 0% { opacity: 0; transform: translateY(20px); } 100% { opacity: 1; transform: translateY(0); } }
    .glass-panel { background: rgba(15, 23, 42, 0.6); backdrop-filter: blur(24px); -webkit-backdrop-filter: blur(24px); border: 1px solid rgba(255, 255, 255, 0.05); }
    .verify-card { transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1); }
    .verify-card:hover { transform: translateY(-4px) scale(1.01); z-index: 10; }
</style>

<div class="max-w-[96%] xl:max-w-7xl mx-auto py-8 space-y-12 pb-24">

    {{-- 🌟 Premium Header --}}
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 bg-slate-900/80 p-10 rounded-[3rem] border border-slate-800 shadow-2xl relative overflow-hidden fade-in-up">
        <div class="absolute -right-20 -top-20 w-80 h-80 bg-indigo-500/10 blur-[120px] rounded-full pointer-events-none"></div>
        <div class="relative z-10">
            <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-indigo-500/10 border border-indigo-500/30 mb-5 shadow-[0_0_15px_rgba(99,102,241,0.2)]">
                <span class="w-2 h-2 rounded-full bg-indigo-400 animate-pulse"></span>
                <span class="text-[10px] font-black tracking-widest text-indigo-400 uppercase">Action Center</span>
            </div>
            <h1 class="text-4xl md:text-6xl font-black text-white tracking-tighter">Verification <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-400 to-cyan-400">Queue</span></h1>
            <p class="text-sm font-bold text-slate-400 mt-4 uppercase tracking-widest">Review, Audit, and Approve Ecosystem Operations</p>
        </div>
    </div>

    {{-- 🌟 Module 1: Farm Listings --}}
    <div class="fade-in-up" style="animation-delay: 0.1s;">
        <h2 class="text-2xl font-black text-white flex items-center gap-4 mb-8 px-2 border-b border-slate-800 pb-4">
            <div class="p-2.5 bg-emerald-500/10 rounded-xl border border-emerald-500/20 text-emerald-400 shadow-[0_0_15px_rgba(16,185,129,0.2)]">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
            </div>
            New Farm Approvals
            <span class="bg-emerald-500 text-slate-950 shadow-[0_0_15px_rgba(16,185,129,0.6)] text-xs font-black px-4 py-1.5 rounded-full ml-auto uppercase tracking-widest">{{ $pendingFarms->count() }} Pending</span>
        </h2>

        <div class="glass-panel rounded-[3rem] p-6 shadow-2xl">
            @if($pendingFarms->count() > 0)
                <div class="grid grid-cols-1 gap-4">
                    @foreach($pendingFarms as $farm)
                        <div class="verify-card bg-slate-950/80 border border-slate-800 hover:border-emerald-500/30 rounded-[2rem] p-6 flex flex-col lg:flex-row items-center justify-between gap-8 hover:shadow-[0_15px_40px_rgba(0,0,0,0.4)]">

                            <div class="flex items-center gap-6 w-full lg:w-1/3">
                                <div class="w-20 h-20 rounded-2xl bg-emerald-500/10 border border-emerald-500/20 flex items-center justify-center text-emerald-400 flex-shrink-0 shadow-inner">
                                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                </div>
                                <div>
                                    <h3 class="text-xl font-black text-white leading-tight mb-1">{{ $farm->name }}</h3>
                                    <p class="text-[11px] font-black text-emerald-400 uppercase tracking-widest bg-emerald-500/10 px-2 py-0.5 rounded border border-emerald-500/20 inline-block">{{ number_format($farm->price_per_night, 2) }} JOD/Night</p>
                                </div>
                            </div>

                            <div class="w-full lg:w-1/3 bg-slate-900 rounded-xl p-4 border border-slate-800">
                                <p class="text-[9px] font-black text-slate-500 uppercase tracking-[0.2em] mb-1.5">Owner Identity</p>
                                <p class="text-base font-bold text-white mb-0.5">{{ $farm->owner->name ?? 'Unknown' }}</p>
                                <p class="text-xs text-indigo-400 font-mono flex items-center gap-1.5">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                    {{ $farm->owner->phone ?? 'No Phone' }}
                                </p>
                            </div>

                            <div class="w-full lg:w-1/3 flex items-center justify-end gap-3 mt-4 lg:mt-0">
                                <form action="{{ route('admin.verifications.handle', ['id' => $farm->id, 'type' => 'farm_approval']) }}" method="POST" class="flex gap-3 w-full">
                                    @csrf
                                    <button type="submit" name="action" value="reject" class="flex-1 px-6 py-4 bg-slate-900 border border-rose-500/30 text-rose-400 hover:bg-rose-600 hover:text-white hover:border-rose-500 text-[11px] font-black uppercase tracking-widest rounded-xl transition-all shadow-lg active:scale-95 flex items-center justify-center gap-2" onclick="return confirm('Reject and delete farm permanently?');">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"/></svg>
                                        Reject
                                    </button>
                                    <button type="submit" name="action" value="approve" class="flex-1 px-6 py-4 bg-emerald-600 text-white hover:bg-emerald-500 text-[11px] font-black uppercase tracking-widest rounded-xl transition-all shadow-[0_10px_20px_rgba(16,185,129,0.3)] hover:shadow-[0_15px_30px_rgba(16,185,129,0.4)] active:scale-95 flex items-center justify-center gap-2">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                        Approve
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="py-24 text-center">
                    <div class="inline-flex items-center justify-center w-24 h-24 rounded-[2rem] bg-emerald-500/10 mb-6 border border-emerald-500/20 shadow-[inset_0_0_30px_rgba(16,185,129,0.15)]">
                        <svg class="h-12 w-12 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                    </div>
                    <h3 class="text-2xl font-black text-white mb-2 tracking-tight">Queue Clear</h3>
                    <p class="text-[11px] font-bold text-slate-500 uppercase tracking-widest">All farm listings are fully reviewed and active.</p>
                </div>
            @endif
        </div>
    </div>

    {{-- 🌟 Module 2: Farm Bookings (CliQ) --}}
    <div class="fade-in-up" style="animation-delay: 0.2s;">
        <h2 class="text-2xl font-black text-white flex items-center gap-4 mb-8 px-2 border-b border-slate-800 pb-4">
            <div class="p-2.5 bg-indigo-500/10 rounded-xl border border-indigo-500/20 text-indigo-400 shadow-[0_0_15px_rgba(99,102,241,0.2)]">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            Booking Payments (CliQ)
            <span class="bg-indigo-500 text-slate-950 shadow-[0_0_15px_rgba(99,102,241,0.6)] text-xs font-black px-4 py-1.5 rounded-full ml-auto uppercase tracking-widest">{{ isset($farmBookings) ? $farmBookings->count() : 0 }} Pending</span>
        </h2>

        <div class="glass-panel rounded-[3rem] p-6 shadow-2xl">
            @if(isset($farmBookings) && $farmBookings->count() > 0)
                <div class="grid grid-cols-1 gap-4">
                    @foreach($farmBookings as $booking)
                        <div class="verify-card bg-slate-950/80 border border-slate-800 hover:border-indigo-500/30 rounded-[2rem] p-6 flex flex-col lg:flex-row items-center justify-between gap-8 hover:shadow-[0_15px_40px_rgba(0,0,0,0.4)]">

                            <div class="w-full lg:w-1/3">
                                <div class="flex items-center gap-4 mb-3">
                                    <div class="w-12 h-12 rounded-xl bg-indigo-500/20 border border-indigo-500/30 flex items-center justify-center text-indigo-400 font-black text-lg shadow-inner">{{ substr($booking->user->name ?? 'G', 0, 1) }}</div>
                                    <div>
                                        <p class="text-base font-black text-white leading-tight mb-0.5">{{ $booking->user->name ?? 'Guest' }}</p>
                                        <p class="text-[10px] font-mono text-slate-400 flex items-center gap-1"><svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>{{ $booking->user->phone ?? 'No phone' }}</p>
                                    </div>
                                </div>
                                <span class="inline-block text-[10px] font-black text-indigo-400 bg-indigo-500/10 px-3 py-1.5 rounded-lg border border-indigo-500/20 uppercase tracking-[0.2em]">ID: #FB-{{ $booking->id }}</span>
                            </div>

                            <div class="w-full lg:w-1/3 bg-slate-900 rounded-xl p-5 border border-slate-800">
                                <p class="text-[9px] font-black text-slate-500 uppercase tracking-[0.2em] mb-1.5">Asset & Schedule</p>
                                <p class="text-base font-bold text-white truncate mb-1">{{ $booking->farm->name }}</p>
                                <div class="flex items-center gap-2 text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                                    <span class="bg-slate-800 px-2 py-1 rounded">{{ \Carbon\Carbon::parse($booking->start_time)->format('M d, Y') }}</span>
                                    <span>•</span>
                                    <span class="text-indigo-400">{{ ucfirst(str_replace('_', ' ', $booking->event_type)) }}</span>
                                </div>
                            </div>

                            <div class="w-full lg:w-auto flex flex-col items-start lg:items-end">
                                <p class="text-[9px] font-black text-slate-500 uppercase tracking-[0.2em] mb-1.5">Required Funds</p>
                                <p class="text-3xl font-black text-indigo-400 tracking-tighter">{{ number_format($booking->total_price, 2) }} <span class="text-[11px] font-bold text-slate-500 tracking-widest uppercase">JOD</span></p>
                            </div>

                            <div class="w-full lg:w-auto flex items-center justify-end gap-3 mt-4 lg:mt-0">
                                <form action="{{ route('admin.verifications.handle', ['id' => $booking->id, 'type' => 'farm_booking']) }}" method="POST" class="flex gap-3 w-full">
                                    @csrf
                                    <button type="submit" name="action" value="reject" class="flex-1 px-6 py-4 bg-slate-900 border border-rose-500/30 text-rose-400 hover:bg-rose-600 hover:text-white text-[10px] font-black uppercase tracking-widest rounded-xl transition-all shadow-lg active:scale-95 flex items-center justify-center gap-2" onclick="return confirm('Reject and cancel this booking?');">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"/></svg>
                                    </button>
                                    <button type="submit" name="action" value="approve" class="flex-1 px-8 py-4 bg-indigo-600 text-white hover:bg-indigo-500 text-[10px] font-black uppercase tracking-widest rounded-xl transition-all shadow-[0_10px_20px_rgba(99,102,241,0.3)] hover:shadow-[0_15px_30px_rgba(99,102,241,0.4)] active:scale-95 flex items-center justify-center gap-2" onclick="return confirm('Confirm receipt of CliQ funds?');">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                        Verify Funds
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="py-24 text-center">
                    <div class="inline-flex items-center justify-center w-24 h-24 rounded-[2rem] bg-indigo-500/10 mb-6 border border-indigo-500/20 shadow-[inset_0_0_30px_rgba(99,102,241,0.15)]">
                        <svg class="h-12 w-12 text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </div>
                    <h3 class="text-2xl font-black text-white mb-2 tracking-tight">Ledger Clear</h3>
                    <p class="text-[11px] font-bold text-slate-500 uppercase tracking-widest">No pending farm booking payments to verify.</p>
                </div>
            @endif
        </div>
    </div>

    {{-- 🌟 Module 3: Supply Orders (CliQ) --}}
    <div class="fade-in-up" style="animation-delay: 0.3s;">
        <h2 class="text-2xl font-black text-white flex items-center gap-4 mb-8 px-2 border-b border-slate-800 pb-4">
            <div class="p-2.5 bg-rose-500/10 rounded-xl border border-rose-500/20 text-rose-400 shadow-[0_0_15px_rgba(244,63,94,0.2)]">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
            </div>
            Supply Payments (CliQ)
            <span class="bg-rose-500 text-white shadow-[0_0_15px_rgba(244,63,94,0.6)] text-xs font-black px-4 py-1.5 rounded-full ml-auto uppercase tracking-widest">{{ isset($supplyOrders) ? $supplyOrders->count() : 0 }} Pending</span>
        </h2>

        <div class="glass-panel rounded-[3rem] p-6 shadow-2xl">
            @if(isset($supplyOrders) && $supplyOrders->count() > 0)
                <div class="grid grid-cols-1 gap-4">
                    @foreach($supplyOrders as $order)
                        <div class="verify-card bg-slate-950/80 border border-slate-800 hover:border-rose-500/30 rounded-[2rem] p-6 flex flex-col lg:flex-row items-center justify-between gap-8 hover:shadow-[0_15px_40px_rgba(0,0,0,0.4)]">

                            <div class="w-full lg:w-1/3">
                                <div class="flex items-center gap-4 mb-3">
                                    <div class="w-12 h-12 rounded-xl bg-rose-500/20 border border-rose-500/30 flex items-center justify-center text-rose-400 font-black text-lg shadow-inner">{{ substr($order->user->name ?? 'G', 0, 1) }}</div>
                                    <div>
                                        <p class="text-base font-black text-white leading-tight mb-0.5">{{ $order->user->name ?? 'Guest' }}</p>
                                        <p class="text-[10px] font-mono text-slate-400 flex items-center gap-1"><svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>{{ $order->user->phone ?? 'No phone' }}</p>
                                    </div>
                                </div>
                                <span class="inline-block text-[10px] font-black text-rose-400 bg-rose-500/10 px-3 py-1.5 rounded-lg border border-rose-500/20 uppercase tracking-[0.2em]">INV: {{ $order->order_id }}</span>
                            </div>

                            <div class="w-full lg:w-1/3 bg-slate-900 rounded-xl p-5 border border-slate-800">
                                <p class="text-[9px] font-black text-slate-500 uppercase tracking-[0.2em] mb-1.5">SKU Details</p>
                                <p class="text-base font-bold text-white truncate mb-1">{{ $order->supply->name ?? 'Deleted Item' }}</p>
                                <p class="text-[10px] font-bold text-slate-400 mt-1 uppercase">Units Ordered: <span class="text-white font-black bg-slate-800 px-2 py-0.5 rounded">{{ $order->quantity }}</span></p>
                            </div>

                            <div class="w-full lg:w-auto flex flex-col items-start lg:items-end">
                                <p class="text-[9px] font-black text-slate-500 uppercase tracking-[0.2em] mb-1.5">Required Funds</p>
                                <p class="text-3xl font-black text-rose-400 tracking-tighter">{{ number_format($order->total_price, 2) }} <span class="text-[11px] font-bold text-slate-500 tracking-widest uppercase">JOD</span></p>
                            </div>

                            <div class="w-full lg:w-auto flex items-center justify-end gap-3 mt-4 lg:mt-0">
                                <form action="{{ route('admin.verifications.handle', ['id' => $order->id, 'type' => 'supply_order']) }}" method="POST" class="flex gap-3 w-full">
                                    @csrf
                                    <button type="submit" name="action" value="reject" class="flex-1 px-6 py-4 bg-slate-900 border border-slate-700 text-slate-400 hover:bg-rose-600 hover:text-white hover:border-rose-500 text-[10px] font-black uppercase tracking-widest rounded-xl transition-all shadow-lg active:scale-95 flex items-center justify-center gap-2" onclick="return confirm('Reject and cancel this order?');">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"/></svg>
                                    </button>
                                    <button type="submit" name="action" value="approve" class="flex-1 px-8 py-4 bg-rose-600 text-white hover:bg-rose-500 text-[10px] font-black uppercase tracking-widest rounded-xl transition-all shadow-[0_10px_20px_rgba(244,63,94,0.3)] hover:shadow-[0_15px_30px_rgba(244,63,94,0.4)] active:scale-95 flex items-center justify-center gap-2" onclick="return confirm('Confirm receipt of CliQ funds?');">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                        Verify Funds
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="py-24 text-center">
                    <div class="inline-flex items-center justify-center w-24 h-24 rounded-[2rem] bg-rose-500/10 mb-6 border border-rose-500/20 shadow-[inset_0_0_30px_rgba(244,63,94,0.15)]">
                        <svg class="h-12 w-12 text-rose-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" /></svg>
                    </div>
                    <h3 class="text-2xl font-black text-white mb-2 tracking-tight">Ledger Clear</h3>
                    <p class="text-[11px] font-bold text-slate-500 uppercase tracking-widest">No pending supply orders to verify.</p>
                </div>
            @endif
        </div>
    </div>

</div>
@endsection
