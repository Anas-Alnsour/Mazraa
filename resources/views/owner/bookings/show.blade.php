<x-owner-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4 animate-fade-in-up">
            <a href="{{ route('owner.bookings.index') }}" class="p-3 bg-slate-900 border border-slate-800 text-slate-400 hover:text-indigo-400 hover:border-indigo-500/30 hover:bg-indigo-500/10 rounded-2xl transition-all shadow-inner active:scale-95 group">
                <svg class="w-6 h-6 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            <div>
                <h1 class="text-3xl font-black text-white tracking-tighter">Booking <span class="text-indigo-400">#{{ str_pad($booking->id, 5, '0', STR_PAD_LEFT) }}</span></h1>
                <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest mt-1">Review detailed reservation data and logistics.</p>
            </div>
        </div>
    </x-slot>

    <style>
        .fade-in-up { animation: fadeInUp 0.8s cubic-bezier(0.2, 0.8, 0.2, 1) forwards; opacity: 0; }
        @keyframes fadeInUp { 0% { opacity: 0; transform: translateY(30px); } 100% { opacity: 1; transform: translateY(0); } }
    </style>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 pb-24 animate-god-in">
        {{-- Left Column: Core Details --}}
        <div class="lg:col-span-2 space-y-8 fade-in-up" style="animation-delay: 0.1s;">

            {{-- 🌟 Status Card --}}
            <div class="bg-slate-900/60 rounded-[3rem] p-8 md:p-10 border border-slate-800 shadow-2xl backdrop-blur-xl relative overflow-hidden">
                <div class="absolute -left-20 -bottom-20 w-64 h-64 bg-indigo-500/5 blur-[80px] rounded-full pointer-events-none"></div>

                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-6 relative z-10">
                    <div class="flex items-center gap-4">
                        <div class="w-16 h-16 rounded-[1.5rem] bg-indigo-500/10 flex items-center justify-center text-indigo-400 border border-indigo-500/20 shadow-inner">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <div>
                            <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1.5">Current Status</p>
                            @php $status = strtolower($booking->status); @endphp
                            @php
                                $badgeConfig = match($status) {
                                    'confirmed', 'approved', 'completed', 'finished' => 'bg-emerald-500/10 text-emerald-400 border-emerald-500/30 shadow-[0_0_15px_rgba(16,185,129,0.2)]',
                                    'pending' => 'bg-amber-500/10 text-amber-400 border-amber-500/30 animate-pulse shadow-[0_0_15px_rgba(245,158,11,0.2)]',
                                    'pending_payment', 'pending_verification' => 'bg-blue-500/10 text-blue-400 border-blue-500/30',
                                    'cancelled', 'rejected' => 'bg-rose-500/10 text-rose-400 border-rose-500/30',
                                    default => 'bg-slate-800 text-slate-400 border-slate-700',
                                };
                            @endphp
                            <span class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest border {{ $badgeConfig }}">
                                @if($status === 'pending') <span class="w-1.5 h-1.5 rounded-full bg-amber-400"></span> @endif
                                {{ str_replace('_', ' ', $status) }}
                            </span>
                        </div>
                    </div>

                    <div class="flex items-center gap-3">
                        @if($status === 'pending')
                            <form action="{{ route('owner.bookings.approve', $booking->id) }}" method="POST">
                                @csrf @method('PATCH')
                                <button type="submit" class="px-6 py-4 bg-emerald-600 hover:bg-emerald-500 text-slate-950 text-[10px] font-black uppercase tracking-widest rounded-xl transition-all shadow-[0_10px_20px_rgba(16,185,129,0.3)] active:scale-95 border border-emerald-400/50">
                                    Approve Request
                                </button>
                            </form>
                            <form action="{{ route('owner.bookings.reject', $booking->id) }}" method="POST" onsubmit="return confirm('WARNING: Decline this booking request?');">
                                @csrf @method('PATCH')
                                <button type="submit" class="px-6 py-4 bg-slate-950 border border-slate-800 text-rose-500 hover:text-white hover:bg-rose-600 hover:border-rose-500 text-[10px] font-black uppercase tracking-widest rounded-xl transition-all shadow-inner active:scale-95">
                                    Decline
                                </button>
                            </form>
                        @elseif($status === 'confirmed')
                            <form action="{{ route('owner.bookings.complete', $booking->id) }}" method="POST">
                                @csrf @method('PATCH')
                                <button type="submit" class="px-6 py-4 bg-indigo-600 hover:bg-indigo-500 text-white text-[10px] font-black uppercase tracking-widest rounded-xl transition-all shadow-[0_10px_20px_rgba(99,102,241,0.3)] active:scale-95 border border-indigo-400/50">
                                    Mark as Completed
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>

            {{-- 🌟 Guest & Property Details --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                {{-- Guest Card --}}
                <div class="bg-slate-900/60 rounded-[3rem] p-8 border border-slate-800 shadow-2xl backdrop-blur-xl relative overflow-hidden group">
                    <div class="absolute right-0 top-0 w-24 h-24 bg-blue-500/10 blur-[40px] pointer-events-none group-hover:scale-150 transition-transform duration-700"></div>
                    <h3 class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-6 border-b border-slate-800 pb-4">Guest Information</h3>

                    <div class="flex items-center gap-5 mb-6 relative z-10">
                        <div class="w-16 h-16 rounded-2xl bg-slate-950 flex items-center justify-center text-xl font-black text-white border border-slate-700 shadow-inner group-hover:text-blue-400 group-hover:border-blue-500/30 transition-colors uppercase shrink-0">
                            {{ substr($booking->user?->name ?? 'G', 0, 2) }}
                        </div>
                        <div class="min-w-0">
                            <p class="font-black text-white text-lg truncate group-hover:text-blue-400 transition-colors">{{ $booking->user?->name ?? 'Unknown Guest' }}</p>
                            <p class="text-[11px] font-mono text-slate-500 truncate mt-1 flex items-center gap-1.5">
                                <svg class="w-3 h-3 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                {{ $booking->user?->email ?? 'N/A' }}
                            </p>
                        </div>
                    </div>

                    <div class="p-5 bg-slate-950 rounded-[1.5rem] border border-slate-800 flex items-center justify-between shadow-inner relative z-10">
                        <span class="text-[10px] font-black uppercase tracking-widest text-slate-500">Phone Signal</span>
                        <span class="text-sm font-black text-white font-mono tracking-widest">{{ $booking->user->phone ?? 'Not Provided' }}</span>
                    </div>
                </div>

                {{-- Property Card --}}
                <div class="bg-slate-900/60 rounded-[3rem] p-8 border border-slate-800 shadow-2xl backdrop-blur-xl relative overflow-hidden group">
                    <div class="absolute right-0 top-0 w-24 h-24 bg-[#c2a265]/10 blur-[40px] pointer-events-none group-hover:scale-150 transition-transform duration-700"></div>
                    <h3 class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-6 border-b border-slate-800 pb-4">Property Detail</h3>

                    <div class="flex items-center gap-5 mb-6 relative z-10">
                        <div class="w-16 h-16 rounded-[1.2rem] overflow-hidden border-2 border-slate-800 shrink-0">
                            <img src="{{ $booking->farm->main_image ? asset('storage/' . $booking->farm->main_image) : 'https://via.placeholder.com/150' }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500" alt="Property">
                        </div>
                        <div class="min-w-0">
                            <p class="font-black text-white text-lg truncate group-hover:text-[#c2a265] transition-colors">{{ $booking->farm?->name ?? 'N/A' }}</p>
                            <span class="inline-block mt-2 px-3 py-1 bg-slate-950 text-slate-400 border border-slate-700 text-[9px] font-black rounded-lg uppercase tracking-widest shadow-inner">
                                {{ str_replace('_', ' ', $booking->event_type ?? 'Stay') }}
                            </span>
                        </div>
                    </div>

                    <div class="p-5 bg-slate-950 rounded-[1.5rem] border border-slate-800 flex items-center justify-between shadow-inner relative z-10">
                        <span class="text-[10px] font-black uppercase tracking-widest text-slate-500">Region</span>
                        <span class="text-sm font-black text-[#c2a265]">{{ $booking->farm?->governorate ?? 'N/A' }}</span>
                    </div>
                </div>
            </div>

            {{-- 🌟 Timing & Scheduling --}}
            <div class="bg-slate-900/60 rounded-[3rem] p-8 md:p-10 border border-slate-800 shadow-2xl backdrop-blur-xl">
                <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-8 flex items-center gap-2">
                    <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Reservation Timeline
                </h3>
                <div class="flex flex-col md:flex-row gap-8 md:gap-12">
                    <div class="flex-1 border-l-4 border-emerald-500 pl-6 py-2 bg-slate-950/30 rounded-r-3xl p-4">
                        <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1.5">Check In</p>
                        <p class="text-2xl font-black text-white tracking-tighter mb-1">{{ \Carbon\Carbon::parse($booking->start_time)->format('M d, Y') }}</p>
                        <p class="text-sm font-black text-emerald-400 font-mono tracking-widest">{{ \Carbon\Carbon::parse($booking->start_time)->format('H:i') }}</p>
                    </div>
                    <div class="flex-1 border-l-4 border-rose-500 pl-6 py-2 bg-slate-950/30 rounded-r-3xl p-4">
                        <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1.5">Check Out</p>
                        <p class="text-2xl font-black text-white tracking-tighter mb-1">{{ \Carbon\Carbon::parse($booking->end_time)->format('M d, Y') }}</p>
                        <p class="text-sm font-black text-rose-400 font-mono tracking-widest">{{ \Carbon\Carbon::parse($booking->end_time)->format('H:i') }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Right Column: Financials & Extras --}}
        <div class="space-y-8 fade-in-up" style="animation-delay: 0.2s;">
            {{-- 🌟 Financial Summary --}}
            <div class="bg-slate-900 rounded-[3rem] p-8 md:p-10 text-white shadow-2xl relative overflow-hidden group border border-slate-800">
                <div class="absolute top-0 right-0 w-48 h-48 bg-emerald-500/10 rounded-full -mr-16 -mt-16 blur-3xl group-hover:bg-emerald-500/20 transition-all duration-700"></div>

                <h3 class="text-[10px] font-black text-emerald-400 uppercase tracking-widest mb-10 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Financial Summary
                </h3>

                <div class="space-y-6 mb-10 relative z-10">
                    <div class="flex justify-between items-center text-slate-400">
                        <span class="text-sm font-black tracking-tight">Gross Price</span>
                        <span class="font-black text-white">{{ number_format($booking->total_price - $booking->tax_amount, 2) }} <span class="text-[10px] uppercase text-slate-500 ml-1">JOD</span></span>
                    </div>
                    <div class="flex justify-between items-center text-slate-400">
                        <span class="text-sm font-black tracking-tight">Tax</span>
                        <span class="font-black text-slate-300">+{{ number_format($booking->tax_amount, 2) }}</span>
                    </div>
                    <div class="flex justify-between items-center text-slate-400 pt-5 border-t border-slate-800">
                        <span class="text-sm font-black tracking-tight">Total Guest Paid</span>
                        <span class="font-black text-white">{{ number_format($booking->total_price, 2) }}</span>
                    </div>
                    <div class="flex justify-between items-center text-slate-400">
                        <span class="text-sm font-black tracking-tight">Platform Fee</span>
                        <span class="font-black text-rose-500">-{{ number_format($booking->commission_amount, 2) }}</span>
                    </div>
                    <div class="pt-8 border-t border-slate-800 flex justify-between items-end">
                        <span class="text-[11px] font-black text-emerald-400 uppercase tracking-widest">Your Earnings</span>
                        <span class="text-4xl font-black text-white tracking-tighter leading-none">{{ number_format($booking->net_owner_amount, 2) }} <span class="text-xs uppercase text-slate-500 ml-1">JOD</span></span>
                    </div>
                </div>

                <div class="p-5 bg-slate-950 rounded-2xl border border-slate-800 text-center shadow-inner relative z-10">
                    <p class="text-[9px] font-black text-slate-500 uppercase tracking-[0.2em] mb-1.5">Payment Status</p>
                    <p class="text-sm font-black tracking-widest uppercase {{ $booking->payment_status === 'paid' ? 'text-emerald-400' : 'text-amber-400' }}">
                        {{ $booking->payment_status ?? 'Pending' }}
                    </p>
                </div>
            </div>

            {{-- Notes / Internal Actions --}}
            <div class="bg-slate-900/60 rounded-[3rem] p-8 border border-slate-800 shadow-2xl backdrop-blur-xl">
                <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-6 flex items-center gap-2">
                    <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Helpful Tip
                </h3>
                <p class="text-sm text-slate-400 leading-relaxed font-medium">
                    Once the guest checks out, remember to mark the booking as <span class="text-white font-black">Completed</span> to release the funds to your available balance.
                </p>
            </div>
        </div>
    </div>
</x-owner-layout>
