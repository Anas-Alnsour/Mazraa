<x-owner-layout>
    <x-slot name="header">Reservations Ledger</x-slot>

    <div class="space-y-10 pb-24 animate-god-in">

        {{-- 🌟 Premium Header Section --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 bg-slate-900/80 p-8 md:p-12 rounded-[3rem] border border-slate-800 shadow-2xl relative overflow-hidden backdrop-blur-2xl transition-all hover:border-indigo-500/30">
            <div class="absolute -left-20 -top-20 w-80 h-80 bg-indigo-500/10 blur-[120px] rounded-full pointer-events-none"></div>

            <div class="relative z-10 flex flex-col md:flex-row items-center gap-6 text-center md:text-left">
                <div class="w-16 h-16 rounded-[1.5rem] bg-indigo-500/10 border border-indigo-500/20 flex items-center justify-center text-indigo-400 shadow-[0_0_20px_rgba(99,102,241,0.2)] shrink-0">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                </div>
                <div>
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-slate-950/50 border border-slate-700 text-[9px] font-black text-slate-400 uppercase tracking-widest mb-3 shadow-inner mx-auto md:mx-0">
                        <span class="w-1.5 h-1.5 rounded-full bg-indigo-500 animate-pulse"></span> Client Pipeline
                    </div>
                    <h1 class="text-4xl md:text-5xl font-black text-white tracking-tighter mb-1">Booking <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-400 to-cyan-400">Requests</span></h1>
                    <p class="text-slate-400 text-xs font-bold uppercase tracking-[0.2em] mt-2">Review, audit, and manage active reservations.</p>
                </div>
            </div>

            <div class="relative z-10 w-full md:w-auto mt-6 md:mt-0 flex justify-center md:justify-end">
                <div class="bg-slate-950/80 px-8 py-5 rounded-[2rem] border border-slate-800 shadow-inner text-center">
                    <p class="text-[9px] font-black text-slate-500 uppercase tracking-widest mb-1.5">Total Requests</p>
                    <p class="text-3xl font-black text-white tracking-tighter leading-none">{{ isset($bookings) ? ($bookings->total() ?? $bookings->count()) : 0 }}</p>
                </div>
            </div>
        </div>

        {{-- 🌟 Ledger Table --}}
        @if(isset($bookings) && $bookings->count() > 0)
            <div class="bg-slate-900/60 rounded-[3rem] border border-slate-800 overflow-hidden backdrop-blur-2xl shadow-2xl fade-in-up" style="animation-delay: 0.15s;">
                <div class="p-6 border-b border-slate-800 bg-slate-950/40 flex justify-between items-center">
                    <h2 class="text-sm font-black text-white uppercase tracking-widest flex items-center gap-2 px-4">
                        <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                        Global Ledger
                    </h2>
                </div>

                <div class="w-full overflow-x-auto table-scroll">
                    <table class="w-full text-left border-collapse min-w-[1100px]">
                        <thead>
                            <tr class="bg-slate-950/80 border-b border-slate-800">
                                <th class="px-10 py-7 text-[10px] font-black text-slate-500 uppercase tracking-[0.3em] whitespace-nowrap">Guest Identity</th>
                                <th class="px-10 py-7 text-[10px] font-black text-slate-500 uppercase tracking-[0.3em] whitespace-nowrap">Asset / Schedule</th>
                                <th class="px-10 py-7 text-[10px] font-black text-slate-500 uppercase tracking-[0.3em] whitespace-nowrap text-right">Revenue Yield</th>
                                <th class="px-10 py-7 text-[10px] font-black text-slate-500 uppercase tracking-[0.3em] whitespace-nowrap text-center">Status Matrix</th>
                                <th class="px-10 py-7 text-[10px] font-black text-slate-500 uppercase tracking-[0.3em] whitespace-nowrap text-right">Gateways</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-800/40">
                            @foreach($bookings as $booking)
                                @php $status = strtolower($booking->status); @endphp
                                <tr class="hover:bg-slate-800/40 transition-colors group">
                                    {{-- Guest Identity --}}
                                    <td class="px-10 py-6 whitespace-nowrap">
                                        <div class="flex items-center gap-5">
                                            <div class="w-14 h-14 rounded-[1.2rem] bg-slate-950 border border-slate-700 flex items-center justify-center text-white font-black text-lg shadow-inner group-hover:border-[#c2a265]/50 group-hover:text-[#c2a265] transition-all shrink-0">
                                                {{ substr($booking->user->name ?? 'G', 0, 1) }}
                                            </div>
                                            <div class="min-w-0">
                                                <p class="text-base font-black text-white group-hover:text-[#c2a265] transition-colors truncate tracking-tight mb-0.5">{{ $booking->user->name ?? 'Guest User' }}</p>
                                                <p class="text-[11px] font-mono text-slate-500 flex items-center gap-1.5 truncate">
                                                    <svg class="w-3 h-3 text-slate-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                                    {{ $booking->user->email ?? 'No Contact' }}
                                                </p>
                                            </div>
                                        </div>
                                    </td>

                                    {{-- Asset / Schedule --}}
                                    <td class="px-10 py-6 whitespace-nowrap">
                                        <p class="text-sm font-black text-slate-200 mb-2 truncate max-w-[200px] group-hover:text-white transition-colors">{{ $booking->farm->name ?? 'Luxury Estate' }}</p>
                                        <div class="flex items-center gap-2">
                                            <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest bg-slate-950 border border-slate-800 px-3 py-1.5 rounded-lg shadow-inner">
                                                {{ \Carbon\Carbon::parse($booking->start_time)->format('M d, Y') }}
                                            </span>
                                            <span class="text-[9px] font-black uppercase tracking-widest px-3 py-1.5 rounded-lg border
                                                {{ $booking->event_type === 'morning' ? 'bg-orange-500/10 text-orange-400 border-orange-500/20' : ($booking->event_type === 'evening' ? 'bg-indigo-500/10 text-indigo-400 border-indigo-500/20' : 'bg-amber-500/10 text-amber-400 border-amber-500/20') }}">
                                                {{ str_replace('_', ' ', $booking->event_type) }}
                                            </span>
                                        </div>
                                    </td>

                                    {{-- Revenue Yield --}}
                                    <td class="px-10 py-6 whitespace-nowrap text-right">
                                        <p class="text-2xl font-black text-emerald-400 tracking-tighter">{{ number_format($booking->net_owner_amount ?? 0, 2) }} <span class="text-[10px] text-slate-500 font-bold uppercase tracking-widest ml-0.5">JOD</span></p>
                                        <p class="text-[9px] font-bold text-slate-500 mt-1 uppercase tracking-[0.2em] opacity-80">Gross: {{ number_format($booking->total_price, 2) }}</p>
                                    </td>

                                    {{-- Status Matrix --}}
                                    <td class="px-10 py-6 whitespace-nowrap text-center">
                                        @php
                                            $badgeConfig = match($status) {
                                                'confirmed', 'approved', 'completed', 'finished' => 'bg-emerald-500/10 text-emerald-400 border-emerald-500/30 shadow-[0_0_15px_rgba(16,185,129,0.2)]',
                                                'pending' => 'bg-amber-500/10 text-amber-400 border-amber-500/30 animate-pulse shadow-[0_0_15px_rgba(245,158,11,0.2)]',
                                                'pending_payment', 'pending_verification' => 'bg-blue-500/10 text-blue-400 border-blue-500/30',
                                                'cancelled', 'rejected' => 'bg-rose-500/10 text-rose-400 border-rose-500/30',
                                                default => 'bg-slate-800 text-slate-400 border-slate-700',
                                            };
                                        @endphp
                                        <span class="inline-flex items-center justify-center gap-1.5 px-4 py-2 rounded-xl text-[9px] font-black uppercase tracking-widest border {{ $badgeConfig }}">
                                            @if($status === 'pending') <span class="w-1.5 h-1.5 rounded-full bg-amber-400"></span> @endif
                                            {{ str_replace('_', ' ', $status) }}
                                        </span>
                                    </td>

                                    {{-- Actions Gateways --}}
                                    <td class="px-10 py-6 whitespace-nowrap text-right">
                                        <div class="flex items-center justify-end gap-3 opacity-0 group-hover:opacity-100 transition-all duration-300 transform translate-x-4 group-hover:translate-x-0">
                                            @if($status === 'pending')
                                                <form action="{{ route('owner.bookings.approve', $booking->id) }}" method="POST">
                                                    @csrf @method('PATCH')
                                                    <button type="submit" class="flex items-center gap-2 px-5 py-3.5 bg-emerald-600 hover:bg-emerald-500 text-slate-950 text-[10px] font-black uppercase tracking-widest rounded-xl transition-all shadow-[0_0_15px_rgba(16,185,129,0.3)] active:scale-95" title="Approve Request">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                                        Approve
                                                    </button>
                                                </form>
                                                <form action="{{ route('owner.bookings.reject', $booking->id) }}" method="POST" onsubmit="return confirm('WARNING: Decline this booking request?');">
                                                    @csrf @method('PATCH')
                                                    <button type="submit" class="flex items-center gap-2 px-4 py-3.5 bg-slate-950 text-rose-500 hover:text-white hover:bg-rose-600 border border-slate-800 hover:border-rose-500 text-[10px] font-black uppercase tracking-widest rounded-xl transition-all shadow-sm active:scale-95" title="Reject Request">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"></path></svg>
                                                    </button>
                                                </form>
                                            @elseif($status === 'confirmed')
                                                <form action="{{ route('owner.bookings.complete', $booking->id) }}" method="POST">
                                                    @csrf @method('PATCH')
                                                    <button type="submit" class="flex items-center gap-2 px-5 py-3.5 bg-indigo-600 hover:bg-indigo-500 text-white text-[10px] font-black uppercase tracking-widest rounded-xl transition-all shadow-[0_0_15px_rgba(99,102,241,0.3)] active:scale-95" title="Mark as Completed">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                                        Complete
                                                    </button>
                                                </form>
                                                <form action="{{ route('owner.bookings.reject', $booking->id) }}" method="POST" onsubmit="return confirm('WARNING: Cancel a CONFIRMED booking?');">
                                                    @csrf @method('PATCH')
                                                    <button type="submit" class="flex items-center gap-2 px-4 py-3.5 bg-slate-950 text-rose-500 hover:text-white hover:bg-rose-600 border border-slate-800 hover:border-rose-500 text-[10px] font-black uppercase tracking-widest rounded-xl transition-all shadow-sm active:scale-95" title="Cancel Booking">
                                                        Cancel
                                                    </button>
                                                </form>
                                            @else
                                                <div class="p-3.5 bg-slate-950/50 border border-slate-800 text-slate-600 rounded-xl cursor-not-allowed shadow-inner" title="No actions available">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                                </div>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- 🌟 Pagination --}}
                @if(method_exists($bookings, 'hasPages') && $bookings->hasPages())
                    <div class="px-10 py-10 border-t border-slate-800 bg-slate-950/50 flex flex-col md:flex-row items-center justify-between gap-6 shrink-0">
                        <div class="flex flex-col">
                            <p class="text-[10px] font-black text-slate-500 uppercase tracking-[0.3em] mb-1">Ledger Report</p>
                            <p class="text-xs font-bold text-slate-400">
                                Displaying <span class="text-indigo-400">{{ $bookings->count() }}</span> of <span class="text-white">{{ $bookings->total() }}</span> reservations
                            </p>
                        </div>
                        <div class="custom-pagination">
                            {{ $bookings->links() }}
                        </div>
                    </div>
                @endif
            </div>

        @else
            {{-- 🌟 Empty State --}}
            <div class="py-32 text-center bg-slate-900/40 rounded-[3rem] border border-slate-800 border-dashed flex flex-col items-center shadow-inner relative overflow-hidden">
                <div class="w-28 h-28 bg-slate-950 rounded-[2rem] flex items-center justify-center mb-8 shadow-inner border border-slate-800 relative z-10">
                    <svg class="w-14 h-14 text-slate-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 12v.01"></path></svg>
                </div>
                <h3 class="text-3xl font-black text-white mb-3 tracking-tight relative z-10">Pipeline Empty</h3>
                <p class="text-[11px] font-bold text-slate-500 max-w-sm uppercase tracking-widest leading-relaxed relative z-10 mb-8">When customers reserve your properties, their requests will propagate here for authorization.</p>
                <a href="{{ route('owner.farms.index') }}" class="px-8 py-4 bg-[#c2a265] hover:bg-yellow-600 text-slate-950 font-black text-[10px] uppercase tracking-[0.2em] rounded-2xl shadow-[0_10px_20px_rgba(194,162,101,0.2)] transition-all active:scale-95 relative z-10 flex items-center gap-3">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Manage Assets
                </a>
            </div>
        @endif
    </div>
</x-owner-layout>

<style>
    .custom-pagination nav { @apply flex items-center gap-2; }
    .custom-pagination .page-link { @apply bg-slate-900 border-none text-slate-400 text-[11px] font-black px-5 py-3 rounded-xl transition-all hover:bg-indigo-600 hover:text-white shadow-lg; }
    .custom-pagination .active .page-link { @apply bg-indigo-600 text-white shadow-[0_0_20px_rgba(99,102,241,0.4)]; }
    .custom-pagination .disabled .page-link { @apply bg-transparent opacity-20 text-slate-700 cursor-not-allowed; }
    .table-scroll::-webkit-scrollbar { height: 8px; width: 6px; }
    .table-scroll::-webkit-scrollbar-track { background: rgba(15, 23, 42, 0.5); border-radius: 8px; }
    .table-scroll::-webkit-scrollbar-thumb { background: rgba(51, 65, 85, 0.8); border-radius: 8px; }
</style>
