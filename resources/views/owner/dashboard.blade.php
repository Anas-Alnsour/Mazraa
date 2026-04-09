<x-owner-layout>
    <x-slot name="header">
        Dashboard Overview
    </x-slot>

    <div class="space-y-10 pb-12">

        <div class="relative overflow-hidden bg-[#020617] rounded-[2.5rem] shadow-2xl shadow-gray-900/30 border border-gray-800/80 isolate group">
            <div class="absolute inset-0 z-0 opacity-40 mix-blend-screen group-hover:scale-105 transition-transform duration-1000" style="background-image: radial-gradient(circle at 80% 0%, #1d5c42 0%, transparent 50%), radial-gradient(circle at 10% 100%, #c2a265 0%, transparent 40%);"></div>
            <div class="absolute top-0 right-0 w-72 h-72 bg-gradient-to-br from-[#1d5c42]/40 to-transparent rounded-full blur-3xl -mr-20 -mt-20"></div>

            <div class="relative z-10 px-8 py-12 sm:px-12 sm:py-16 flex flex-col md:flex-row items-center justify-between gap-10">
                <div class="max-w-2xl text-center md:text-left">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/5 border border-white/10 mb-6 backdrop-blur-md">
                        <span class="flex h-2 w-2 relative">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-[#c2a265] opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2 w-2 bg-[#c2a265]"></span>
                        </span>
                        <span class="text-[10px] font-black tracking-widest text-[#c2a265] uppercase">Owner Portal Active</span>
                    </div>
                    <h2 class="text-4xl sm:text-5xl font-black text-white tracking-tight mb-4">
                        Welcome back, <span class="text-transparent bg-clip-text bg-gradient-to-r from-[#c2a265] via-yellow-400 to-[#c2a265] animate-gradient-x">{{ Auth::user()->name ?? 'Partner' }}</span>
                    </h2>
                    <p class="text-gray-400 text-sm sm:text-base font-medium leading-relaxed max-w-lg">
                        Here's your real-time property overview. Manage incoming bookings, track your financial growth, and scale your luxury farm business.
                    </p>
                    <div class="mt-8 flex flex-wrap gap-4 justify-center md:justify-start">
                        <a href="{{ route('owner.farms.create') }}" class="inline-flex items-center gap-3 px-6 py-3.5 bg-gradient-to-r from-[#c2a265] to-[#b09053] text-[#020617] text-xs font-black uppercase tracking-widest rounded-2xl transition-all shadow-lg shadow-[#c2a265]/25 hover:shadow-[#c2a265]/40 hover:-translate-y-1 active:scale-95">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
                            List New Farm
                        </a>
                        <a href="{{ route('owner.bookings.index') }}" class="inline-flex items-center gap-3 px-6 py-3.5 bg-white/5 hover:bg-white/10 text-white border border-white/10 text-xs font-black uppercase tracking-widest rounded-2xl backdrop-blur-md transition-all hover:shadow-xl hover:-translate-y-1 active:scale-95">
                            View Schedule
                        </a>
                    </div>
                </div>

                <div class="hidden lg:flex flex-col items-end gap-2 bg-white/5 p-6 rounded-[2rem] border border-white/10 backdrop-blur-md shadow-2xl">
                    <p class="text-[10px] font-black uppercase tracking-[0.2em] text-gray-400 mb-1">Today's Date</p>
                    <p class="text-2xl font-black text-white tracking-tighter">{{ now()->format('F j, Y') }}</p>
                    <p class="text-sm font-bold text-[#c2a265]">{{ now()->format('l') }}</p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

            {{-- Stat Card 1: Total Earnings (Gold Palette) --}}
            <div class="bg-white border border-slate-100 rounded-[2rem] p-8 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-500 group relative overflow-hidden cursor-default">
                <div class="absolute -right-8 -top-8 w-32 h-32 bg-amber-50 rounded-full blur-3xl opacity-0 group-hover:opacity-100 transition-opacity duration-700"></div>
                <div class="flex items-center justify-between mb-8 relative z-10">
                    <div class="w-14 h-14 bg-amber-50 rounded-2xl flex items-center justify-center text-amber-600 border border-amber-100 shadow-inner group-hover:scale-110 group-hover:rotate-3 transition-transform duration-500">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <div class="flex items-center gap-1.5 px-3 py-1.5 bg-amber-50 text-amber-700 border-amber-100 rounded-full text-[10px] font-black uppercase tracking-widest border shadow-sm">
                        Total Yield
                    </div>
                </div>
                <div class="relative z-10">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2">Lifetime Earnings</p>
                    <div class="flex items-baseline gap-2">
                        <h3 class="text-4xl font-black text-slate-900 tracking-tighter">{{ $totalEarnings ?? '0.00' }}</h3>
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-widest">JOD</span>
                    </div>
                </div>
            </div>

            {{-- Stat Card 2: Active Bookings (Emerald Palette) --}}
            <div class="bg-white border border-slate-100 rounded-[2rem] p-8 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-500 group relative overflow-hidden cursor-default">
                <div class="absolute -right-8 -top-8 w-32 h-32 bg-[#1d5c42]/10 rounded-full blur-3xl opacity-0 group-hover:opacity-100 transition-opacity duration-700"></div>
                <div class="flex items-center justify-between mb-8 relative z-10">
                    <div class="w-14 h-14 bg-[#1d5c42]/10 rounded-2xl flex items-center justify-center text-[#1d5c42] border border-[#1d5c42]/20 shadow-inner group-hover:scale-110 group-hover:rotate-3 transition-transform duration-500">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    </div>
                    @if(isset($activeBookingsCount) && $activeBookingsCount > 0)
                    <div class="flex items-center gap-1.5 px-3 py-1.5 bg-emerald-50 text-emerald-700 border-emerald-100 rounded-full text-[10px] font-black uppercase tracking-widest border shadow-sm animate-pulse">
                        <span class="w-1.5 h-1.5 rounded-full bg-current"></span> Action Req
                    </div>
                    @endif
                </div>
                <div class="relative z-10">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2">Active Bookings</p>
                    <div class="flex items-baseline gap-2">
                        <h3 class="text-4xl font-black text-slate-900 tracking-tighter">{{ $activeBookingsCount ?? '0' }}</h3>
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-widest">Shifts</span>
                    </div>
                </div>
            </div>

            {{-- Stat Card 3: Total Farms (Blue Palette) --}}
            <div class="bg-white border border-slate-100 rounded-[2rem] p-8 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-500 group relative overflow-hidden cursor-default">
                <div class="absolute -right-8 -top-8 w-32 h-32 bg-blue-50 rounded-full blur-3xl opacity-0 group-hover:opacity-100 transition-opacity duration-700"></div>
                <div class="flex items-center justify-between mb-8 relative z-10">
                    <div class="w-14 h-14 bg-blue-50 rounded-2xl flex items-center justify-center text-blue-600 border border-blue-100 shadow-inner group-hover:scale-110 group-hover:rotate-3 transition-transform duration-500">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                    </div>
                </div>
                <div class="relative z-10">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2">Listed Properties</p>
                    <div class="flex items-baseline gap-2">
                        <h3 class="text-4xl font-black text-slate-900 tracking-tighter">{{ $totalFarms ?? '0' }}</h3>
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-widest">Estates</span>
                    </div>
                </div>
            </div>

        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <div class="lg:col-span-2 bg-white rounded-[2rem] shadow-sm border border-slate-100 overflow-hidden flex flex-col">
                <div class="p-8 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                    <div>
                        <h3 class="text-lg font-black text-slate-900 tracking-tight">Recent Booking Requests</h3>
                        <p class="text-xs font-bold text-slate-400 mt-1 uppercase tracking-widest">Action needed on latest reservations</p>
                    </div>
                    <a href="{{ route('owner.bookings.index') }}" class="group flex items-center gap-2 text-[10px] font-black uppercase tracking-widest text-[#1d5c42] bg-[#1d5c42]/10 hover:bg-[#1d5c42]/20 px-4 py-2 rounded-xl transition-all">
                        View Ledger
                        <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"></path></svg>
                    </a>
                </div>

                @if(isset($recentBookings) && $recentBookings->count() > 0)
                    <div class="overflow-x-auto flex-1">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-50/80 border-b border-slate-100">
                                    <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest whitespace-nowrap">Guest & ID</th>
                                    <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest whitespace-nowrap">Property</th>
                                    <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest whitespace-nowrap">Status</th>
                                    <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right whitespace-nowrap">Reservation Dates</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50">
                                @foreach($recentBookings as $booking)
                                <tr class="group hover:bg-[#1d5c42]/5 transition-colors duration-300">
                                    <td class="px-8 py-6 whitespace-nowrap">
                                        <div class="flex items-center gap-4">
                                            <div class="w-10 h-10 bg-slate-100 rounded-xl flex items-center justify-center text-slate-500 font-black text-xs border border-slate-200 shadow-inner group-hover:bg-white group-hover:text-[#1d5c42] group-hover:border-[#1d5c42]/30 transition-all uppercase">
                                                {{ substr($booking->user->name ?? 'G', 0, 2) }}
                                            </div>
                                            <div>
                                                <p class="text-sm font-black text-slate-900 group-hover:text-[#1d5c42] transition-colors">{{ $booking->user->name ?? 'Guest User' }}</p>
                                                <p class="text-[10px] font-bold text-slate-400 mt-0.5 tracking-widest uppercase">ID: #{{ substr($booking->id, -4) }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-8 py-6 whitespace-nowrap">
                                        <span class="text-xs font-black text-slate-700">
                                            {{ $booking->farm->name ?? 'Luxury Estate' }}
                                        </span>
                                    </td>
                                    <td class="px-8 py-6 whitespace-nowrap">
                                        @php
                                            $status = strtolower($booking->status ?? 'pending');
                                            $badgeClasses = match($status) {
                                                'confirmed', 'approved' => 'bg-emerald-50 text-emerald-700 border-emerald-100',
                                                'pending' => 'bg-amber-50 text-amber-700 border-amber-100',
                                                'cancelled', 'rejected' => 'bg-rose-50 text-rose-700 border-rose-100',
                                                default => 'bg-slate-50 text-slate-600 border-slate-100',
                                            };
                                        @endphp
                                        <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full {{ $badgeClasses }} text-[10px] font-black uppercase tracking-widest border shadow-sm">
                                            <span class="w-1.5 h-1.5 rounded-full bg-current {{ $status == 'pending' ? 'animate-pulse' : '' }}"></span>
                                            {{ $status }}
                                        </span>
                                    </td>
                                    <td class="px-8 py-6 text-right whitespace-nowrap">
                                        <p class="text-sm font-black text-slate-900 tracking-tight">{{ \Carbon\Carbon::parse($booking->start_date)->format('M d') }} - {{ \Carbon\Carbon::parse($booking->end_date)->format('M d, Y') }}</p>
                                        <p class="text-[10px] font-bold text-slate-400 mt-0.5 tracking-widest uppercase">Shift: {{ $booking->shift ?? 'Full Day' }}</p>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    {{-- Elegant Empty State --}}
                    <div class="py-16 px-6 text-center relative overflow-hidden flex-1 flex flex-col justify-center">
                        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-64 h-64 bg-slate-50/50 rounded-full blur-3xl pointer-events-none"></div>
                        <div class="relative w-48 h-48 mx-auto mb-6 flex items-center justify-center group cursor-default">
                            <div class="relative w-24 h-24 bg-white rounded-[2rem] shadow-xl shadow-slate-200/50 border border-slate-100 flex items-center justify-center text-slate-300 transform -rotate-6 group-hover:rotate-0 group-hover:scale-110 transition-all duration-500 z-10">
                                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            </div>
                            <div class="absolute bottom-6 w-16 h-2 bg-slate-200 rounded-[100%] blur-sm group-hover:w-20 group-hover:opacity-50 transition-all duration-500"></div>
                        </div>
                        <div class="relative z-10">
                            <h3 class="text-lg font-black text-slate-900 tracking-tight mb-2 uppercase">Awaiting Reservations</h3>
                            <p class="text-xs font-bold text-slate-400 max-w-sm mx-auto leading-relaxed">Once guests begin booking your properties, their requests will appear here for you to review and approve.</p>
                        </div>
                    </div>
                @endif
            </div>

            <div class="bg-gradient-to-b from-slate-50 to-white rounded-[2rem] shadow-sm border border-slate-100 p-8 flex flex-col relative overflow-hidden">
                <div class="absolute -right-8 -top-8 w-32 h-32 bg-[#c2a265]/10 rounded-full blur-3xl"></div>
                <h3 class="text-sm font-black text-slate-900 tracking-widest uppercase mb-8 relative z-10">Platform Shortcuts</h3>

                <div class="space-y-4 flex-1 relative z-10">
                    <a href="{{ route('owner.farms.create') }}" class="group flex items-center gap-5 p-5 rounded-2xl border border-slate-200 bg-white hover:border-[#1d5c42]/50 hover:shadow-lg transition-all duration-300">
                        <div class="w-12 h-12 rounded-xl bg-[#1d5c42]/10 flex items-center justify-center text-[#1d5c42] group-hover:scale-110 group-hover:-rotate-3 transition-transform duration-300">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        </div>
                        <div>
                            <h4 class="text-sm font-black text-slate-900">Publish Listing</h4>
                            <p class="text-[10px] font-bold tracking-widest uppercase text-slate-400 mt-1">Add new property</p>
                        </div>
                    </a>

                    <a href="{{ route('owner.profile.edit') }}" class="group flex items-center gap-5 p-5 rounded-2xl border border-slate-200 bg-white hover:border-[#c2a265]/50 hover:shadow-lg transition-all duration-300">
                        <div class="w-12 h-12 rounded-xl bg-[#c2a265]/10 flex items-center justify-center text-[#c2a265] group-hover:scale-110 group-hover:rotate-3 transition-transform duration-300">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        </div>
                        <div>
                            <h4 class="text-sm font-black text-slate-900">Account Security</h4>
                            <p class="text-[10px] font-bold tracking-widest uppercase text-slate-400 mt-1">Manage settings</p>
                        </div>
                    </a>
                </div>
            </div>

        </div>
    </div>
</x-owner-layout>
