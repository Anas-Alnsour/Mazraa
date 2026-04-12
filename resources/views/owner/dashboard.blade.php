<x-owner-layout>
    <x-slot name="header">Command Center Overview</x-slot>

    <div class="space-y-10 pb-12 animate-god-in">

        {{-- 🌟 1. Hero Welcome Section (Ultra Premium) --}}
        <div class="relative overflow-hidden bg-slate-900/80 rounded-[3.5rem] p-10 md:p-16 border border-slate-800 backdrop-blur-2xl shadow-2xl isolate group">
            {{-- Animated Background Orbs --}}
            <div class="absolute inset-0 z-0 opacity-30 mix-blend-screen group-hover:scale-105 transition-transform duration-1000" style="background-image: radial-gradient(circle at 80% 0%, rgba(194,162,101,0.15) 0%, transparent 50%), radial-gradient(circle at 10% 100%, rgba(16,185,129,0.1) 0%, transparent 40%);"></div>
            <div class="absolute top-0 right-0 w-96 h-96 bg-gradient-to-br from-[#c2a265]/10 to-transparent rounded-full blur-[100px] pointer-events-none"></div>

            <div class="relative z-10 flex flex-col lg:flex-row items-center justify-between gap-12">
                <div class="max-w-2xl text-center lg:text-left w-full">
                    <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-emerald-500/10 border border-emerald-500/20 mb-6 backdrop-blur-md shadow-[0_0_15px_rgba(16,185,129,0.1)]">
                        <span class="flex h-2 w-2 relative">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                        </span>
                        <span class="text-[10px] font-black tracking-[0.2em] text-emerald-400 uppercase">Portal Online & Secured</span>
                    </div>
                    <h2 class="text-4xl sm:text-5xl md:text-6xl font-black text-white tracking-tighter mb-4 leading-tight">
                        Welcome, <span class="text-transparent bg-clip-text bg-gradient-to-r from-[#c2a265] to-yellow-400">{{ Auth::user()->name ?? 'Partner' }}</span>
                    </h2>
                    <p class="text-slate-400 text-sm sm:text-base font-medium leading-relaxed max-w-lg mx-auto lg:mx-0">
                        Here's your real-time property overview. Manage incoming bookings, track your financial growth, and scale your luxury farm business.
                    </p>

                    <div class="mt-10 flex flex-wrap gap-4 justify-center lg:justify-start">
                        <a href="{{ route('owner.farms.create') }}" class="inline-flex items-center gap-3 px-8 py-4 bg-gradient-to-r from-[#c2a265] to-[#b09053] hover:to-[#9a7b42] text-[#020617] text-[11px] font-black uppercase tracking-[0.2em] rounded-2xl transition-all shadow-[0_10px_25px_rgba(194,162,101,0.3)] transform hover:-translate-y-1 active:scale-95">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
                            Deploy New Farm
                        </a>
                        <a href="{{ route('owner.bookings.index') }}" class="inline-flex items-center gap-3 px-8 py-4 bg-slate-950/80 hover:bg-slate-900 text-slate-300 hover:text-white border border-slate-700 text-[11px] font-black uppercase tracking-[0.2em] rounded-2xl backdrop-blur-md transition-all shadow-lg hover:border-[#c2a265]/50 transform hover:-translate-y-1 active:scale-95">
                            View Full Ledger
                        </a>
                    </div>
                </div>

                <div class="hidden lg:flex flex-col items-end justify-center bg-slate-950/50 p-10 rounded-[3rem] border border-slate-800 backdrop-blur-xl shadow-2xl min-w-[300px]">
                    <p class="text-[10px] font-black uppercase tracking-[0.3em] text-slate-500 mb-2">System Timestamp</p>
                    <p class="text-4xl font-black text-white tracking-tighter mb-1">{{ now()->format('F j, Y') }}</p>
                    <p class="text-xs font-bold text-[#c2a265] tracking-widest uppercase">{{ now()->format('l') }}</p>
                </div>
            </div>
        </div>

        {{-- 🌟 2. Top KPI Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">

            {{-- Total Earnings (Gold Palette) --}}
            <div class="bg-slate-900/60 border border-slate-800 rounded-[3rem] p-10 shadow-2xl hover:border-[#c2a265]/40 hover:-translate-y-2 transition-all duration-500 group relative overflow-hidden cursor-default backdrop-blur-xl">
                <div class="absolute -right-10 -top-10 w-40 h-40 bg-[#c2a265]/10 rounded-full blur-[60px] opacity-0 group-hover:opacity-100 transition-opacity duration-700"></div>
                <div class="flex items-center justify-between mb-8 relative z-10">
                    <div class="w-16 h-16 bg-slate-950 rounded-[1.5rem] flex items-center justify-center text-[#c2a265] border border-slate-800 shadow-inner group-hover:scale-110 transition-transform duration-500">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <div class="flex items-center gap-1.5 px-4 py-2 bg-slate-950 text-[#c2a265] border-slate-800 rounded-xl text-[9px] font-black uppercase tracking-widest border shadow-inner">
                        Total Yield
                    </div>
                </div>
                <div class="relative z-10">
                    <p class="text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] mb-2">Lifetime Earnings</p>
                    <div class="flex items-baseline gap-2">
                        <h3 class="text-5xl font-black text-white tracking-tighter">{{ number_format($totalEarnings ?? 0, 2) }}</h3>
                        <span class="text-xs font-bold text-slate-500 uppercase tracking-widest">JOD</span>
                    </div>
                </div>
            </div>

            {{-- Active Bookings (Emerald Palette) --}}
            <div class="bg-slate-900/60 border border-slate-800 rounded-[3rem] p-10 shadow-2xl hover:border-emerald-500/40 hover:-translate-y-2 transition-all duration-500 group relative overflow-hidden cursor-default backdrop-blur-xl">
                <div class="absolute -right-10 -top-10 w-40 h-40 bg-emerald-500/10 rounded-full blur-[60px] opacity-0 group-hover:opacity-100 transition-opacity duration-700"></div>
                <div class="flex items-center justify-between mb-8 relative z-10">
                    <div class="w-16 h-16 bg-slate-950 rounded-[1.5rem] flex items-center justify-center text-emerald-400 border border-slate-800 shadow-inner group-hover:scale-110 transition-transform duration-500">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    </div>
                    @if(isset($activeBookingsCount) && $activeBookingsCount > 0)
                    <div class="flex items-center gap-1.5 px-4 py-2 bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 rounded-xl text-[9px] font-black uppercase tracking-widest shadow-[0_0_15px_rgba(16,185,129,0.15)] animate-pulse">
                        <span class="w-1.5 h-1.5 rounded-full bg-current"></span> Action Req
                    </div>
                    @endif
                </div>
                <div class="relative z-10">
                    <p class="text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] mb-2">Active Bookings</p>
                    <div class="flex items-baseline gap-2">
                        <h3 class="text-5xl font-black text-white tracking-tighter">{{ $activeBookingsCount ?? '0' }}</h3>
                        <span class="text-xs font-bold text-slate-500 uppercase tracking-widest">Shifts</span>
                    </div>
                </div>
            </div>

            {{-- Listed Properties (Indigo/Blue Palette) --}}
            <div class="bg-slate-900/60 border border-slate-800 rounded-[3rem] p-10 shadow-2xl hover:border-indigo-500/40 hover:-translate-y-2 transition-all duration-500 group relative overflow-hidden cursor-default backdrop-blur-xl">
                <div class="absolute -right-10 -top-10 w-40 h-40 bg-indigo-500/10 rounded-full blur-[60px] opacity-0 group-hover:opacity-100 transition-opacity duration-700"></div>
                <div class="flex items-center justify-between mb-8 relative z-10">
                    <div class="w-16 h-16 bg-slate-950 rounded-[1.5rem] flex items-center justify-center text-indigo-400 border border-slate-800 shadow-inner group-hover:scale-110 transition-transform duration-500">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                    </div>
                    <div class="flex items-center gap-1.5 px-4 py-2 bg-slate-950 text-indigo-400 border border-slate-800 rounded-xl text-[9px] font-black uppercase tracking-widest shadow-inner">
                        Portfolio
                    </div>
                </div>
                <div class="relative z-10">
                    <p class="text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] mb-2">Listed Properties</p>
                    <div class="flex items-baseline gap-2">
                        <h3 class="text-5xl font-black text-white tracking-tighter">{{ $totalFarms ?? '0' }}</h3>
                        <span class="text-xs font-bold text-slate-500 uppercase tracking-widest">Estates</span>
                    </div>
                </div>
            </div>

        </div>

        {{-- 🌟 3. Main Layout Split (Table + Shortcuts) --}}
        <div class="grid grid-cols-1 xl:grid-cols-12 gap-8 items-start">

            {{-- Left: Recent Bookings Table (Col-span-8 to fix layout) --}}
            <div class="xl:col-span-8 bg-slate-900/60 rounded-[3.5rem] shadow-2xl border border-slate-800 overflow-hidden flex flex-col backdrop-blur-2xl w-full">

                <div class="p-8 md:p-10 border-b border-slate-800 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-6 bg-slate-950/40">
                    <div>
                        <h3 class="text-2xl font-black text-white tracking-tight flex items-center gap-3">
                            <svg class="w-6 h-6 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"></path></svg>
                            Recent Requests
                        </h3>
                        <p class="text-[10px] font-black text-slate-500 mt-2 uppercase tracking-[0.2em] ml-1">Action needed on latest reservations</p>
                    </div>
                    <a href="{{ route('owner.bookings.index') }}" class="group flex items-center gap-2 text-[10px] font-black uppercase tracking-[0.2em] text-[#c2a265] bg-[#c2a265]/10 hover:bg-[#c2a265]/20 border border-[#c2a265]/30 px-6 py-3 rounded-2xl transition-all shadow-sm">
                        View Ledger
                        <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                    </a>
                </div>

                @if(isset($recentBookings) && $recentBookings->count() > 0)
                    <div class="overflow-x-auto flex-1 w-full table-scroll">
                        <table class="w-full text-left border-collapse min-w-[700px]">
                            <thead>
                                <tr class="bg-slate-950/80 border-b border-slate-800">
                                    <th class="px-8 py-7 text-[9px] font-black text-slate-500 uppercase tracking-[0.3em] whitespace-nowrap">Guest & ID</th>
                                    <th class="px-8 py-7 text-[9px] font-black text-slate-500 uppercase tracking-[0.3em] whitespace-nowrap">Property</th>
                                    <th class="px-8 py-7 text-[9px] font-black text-slate-500 uppercase tracking-[0.3em] whitespace-nowrap text-center">Status</th>
                                    <th class="px-8 py-7 text-[9px] font-black text-slate-500 uppercase tracking-[0.3em] text-right whitespace-nowrap">Reservation Dates</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-800/50">
                                @foreach($recentBookings as $booking)
                                <tr class="group hover:bg-slate-800/40 transition-colors duration-300">
                                    <td class="px-8 py-6 whitespace-nowrap">
                                        <div class="flex items-center gap-5">
                                            <div class="w-12 h-12 bg-slate-950 rounded-2xl flex items-center justify-center text-slate-400 font-black text-sm border border-slate-700 shadow-inner group-hover:border-[#c2a265]/50 group-hover:text-[#c2a265] transition-all uppercase shrink-0">
                                                {{ substr($booking->user->name ?? 'G', 0, 2) }}
                                            </div>
                                            <div class="min-w-0">
                                                <p class="text-sm font-black text-white group-hover:text-[#c2a265] transition-colors truncate">{{ $booking->user->name ?? 'Guest User' }}</p>
                                                <p class="text-[9px] font-black text-slate-500 mt-1 tracking-widest uppercase truncate">ID: #{{ substr($booking->id, -4) }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-8 py-6 whitespace-nowrap">
                                        <span class="text-sm font-black text-slate-200 group-hover:text-white transition-colors">{{ $booking->farm->name ?? 'Luxury Estate' }}</span>
                                    </td>
                                    <td class="px-8 py-6 whitespace-nowrap text-center">
                                        @php
                                            $statusClasses = $booking->status === 'pending' ? 'bg-amber-500/10 text-amber-500 border border-amber-500/30 animate-pulse shadow-[0_0_15px_rgba(245,158,11,0.2)]' : 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/30 shadow-[0_0_15px_rgba(16,185,129,0.1)]';
                                        @endphp
                                        <span class="px-4 py-2 rounded-xl text-[9px] font-black uppercase tracking-widest {{ $statusClasses }}">
                                            {{ str_replace('_', ' ', $booking->status) }}
                                        </span>
                                    </td>
                                    <td class="px-8 py-6 text-right whitespace-nowrap">
                                        <p class="text-xs font-black text-slate-300 bg-slate-950 px-4 py-2 rounded-xl border border-slate-800 inline-block shadow-inner">{{ \Carbon\Carbon::parse($booking->start_time)->format('M d, Y') }}</p>
                                        <p class="text-[9px] font-bold text-slate-500 uppercase mt-2 tracking-[0.2em]">{{ str_replace('_', ' ', $booking->event_type) }}</p>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="py-32 text-center">
                        <div class="w-24 h-24 bg-slate-950 rounded-[2rem] flex items-center justify-center mx-auto mb-6 border border-slate-800 shadow-inner">
                            <svg class="w-12 h-12 text-slate-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        </div>
                        <h3 class="text-xl font-black text-white tracking-tight mb-2">Ledger Empty</h3>
                        <p class="text-[10px] font-black text-slate-500 uppercase tracking-[0.2em]">No active requests found in the pipeline.</p>
                    </div>
                @endif
            </div>

            {{-- Right: Shortcuts Box (Col-span-4 to fix layout) --}}
            <div class="xl:col-span-4 space-y-6">
                <div class="bg-slate-900/60 rounded-[3.5rem] shadow-2xl border border-slate-800 p-8 md:p-10 flex flex-col relative overflow-hidden backdrop-blur-xl">
                    <div class="absolute -right-10 -top-10 w-48 h-48 bg-[#c2a265]/10 rounded-full blur-[80px] pointer-events-none"></div>

                    <h3 class="text-[11px] font-black text-slate-500 tracking-[0.3em] uppercase mb-8 border-b border-slate-800 pb-5 relative z-10 flex items-center gap-3">
                        <svg class="w-5 h-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                        Ecosystem Shortcuts
                    </h3>

                    <div class="space-y-5 flex-1 relative z-10">
                        <a href="{{ route('owner.farms.create') }}" class="group flex items-center gap-5 p-6 rounded-[2rem] border border-slate-800 bg-slate-950/80 hover:border-[#c2a265]/50 hover:bg-slate-900 shadow-inner transition-all duration-300">
                            <div class="w-14 h-14 rounded-2xl bg-[#c2a265]/10 flex items-center justify-center text-[#c2a265] group-hover:scale-110 group-hover:-rotate-3 transition-transform duration-300 border border-[#c2a265]/20 shadow-[0_0_15px_rgba(194,162,101,0.2)] shrink-0">
                                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                            </div>
                            <div>
                                <h4 class="text-base font-black text-white tracking-tight">Publish Listing</h4>
                                <p class="text-[9px] font-black tracking-[0.2em] uppercase text-slate-500 mt-1.5">Deploy new asset</p>
                            </div>
                        </a>

                        <a href="{{ route('owner.profile.edit') }}" class="group flex items-center gap-5 p-6 rounded-[2rem] border border-slate-800 bg-slate-950/80 hover:border-indigo-500/50 hover:bg-slate-900 shadow-inner transition-all duration-300">
                            <div class="w-14 h-14 rounded-2xl bg-indigo-500/10 flex items-center justify-center text-indigo-400 group-hover:scale-110 group-hover:rotate-3 transition-transform duration-300 border border-indigo-500/20 shadow-[0_0_15px_rgba(99,102,241,0.2)] shrink-0">
                                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            </div>
                            <div>
                                <h4 class="text-base font-black text-white tracking-tight">Account Security</h4>
                                <p class="text-[9px] font-black tracking-[0.2em] uppercase text-slate-500 mt-1.5">Manage protocols</p>
                            </div>
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-owner-layout>
