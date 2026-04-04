<x-owner-layout>
    <x-slot name="header">
        Dashboard Overview
    </x-slot>

    <div class="space-y-8 pb-10">

        <!-- Welcome Hero Section (Dark / Glassmorphic) -->
        <div class="relative overflow-hidden bg-[#020617] rounded-3xl shadow-2xl shadow-gray-900/20 border border-gray-800 isolate">
            <!-- Decorative Background Gradients -->
            <div class="absolute inset-0 z-0 opacity-40 mix-blend-screen" style="background-image: radial-gradient(circle at 80% 0%, #1d5c42 0%, transparent 50%), radial-gradient(circle at 10% 100%, #c2a265 0%, transparent 40%);"></div>
            <div class="absolute top-0 right-0 w-64 h-64 bg-gradient-to-br from-[#1d5c42]/30 to-transparent rounded-full blur-3xl -mr-20 -mt-20"></div>

            <div class="relative z-10 px-6 py-10 sm:px-10 sm:py-14 flex flex-col md:flex-row items-center justify-between gap-8">
                <div class="max-w-xl text-center md:text-left">
                    <h2 class="text-3xl sm:text-4xl font-extrabold text-white tracking-tight mb-2">
                        Welcome back, <span class="text-transparent bg-clip-text bg-gradient-to-r from-[#c2a265] to-yellow-500">{{ Auth::user()->name ?? 'Partner' }}</span>
                    </h2>
                    <p class="text-gray-400 text-sm sm:text-base font-medium leading-relaxed max-w-lg">
                        Here's what's happening with your properties today. Manage your bookings, track your earnings, and grow your business.
                    </p>
                    <div class="mt-6 flex flex-wrap gap-4 justify-center md:justify-start">
                        <a href="{{ route('owner.farms.create') ?? '#' }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-[#c2a265] hover:bg-[#b09053] text-[#020617] text-sm font-bold rounded-xl transition-all shadow-lg shadow-[#c2a265]/20 hover:shadow-xl hover:-translate-y-0.5">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                            List New Farm
                        </a>
                        <a href="{{ route('owner.bookings.index') ?? '#' }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-gray-800/80 hover:bg-gray-700 text-white border border-gray-700 text-sm font-bold rounded-xl backdrop-blur-sm transition-all shadow-lg hover:shadow-xl hover:-translate-y-0.5">
                            View Schedule
                        </a>
                    </div>
                </div>

                <!-- Quick Status Badge -->
                <div class="hidden lg:flex flex-col items-end gap-3">
                    <div class="flex items-center gap-3 px-4 py-3 bg-white/5 backdrop-blur-md rounded-2xl border border-white/10 shadow-inner">
                        <div class="relative flex h-4 w-4">
                          <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-[#1d5c42] opacity-75"></span>
                          <span class="relative inline-flex rounded-full h-4 w-4 bg-[#1d5c42]"></span>
                        </div>
                        <span class="text-white font-semibold tracking-wide text-sm">System Online</span>
                    </div>
                    <p class="text-xs text-gray-500 font-medium">{{ now()->format('l, F j, Y') }}</p>
                </div>
            </div>
        </div>

        <!-- Stats Bento Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

            <!-- Card 1: Total Earnings -->
            <div class="bg-white rounded-3xl p-6 shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-gray-100 relative overflow-hidden group hover:border-[#c2a265]/50 transition-colors duration-300">
                <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                    <svg class="w-24 h-24 text-[#c2a265]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <div class="relative z-10">
                    <div class="w-12 h-12 rounded-2xl bg-[#c2a265]/10 flex items-center justify-center mb-4 border border-[#c2a265]/20">
                        <svg class="w-6 h-6 text-[#c2a265]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <p class="text-sm font-bold text-gray-500 uppercase tracking-wider mb-1">Total Earnings</p>
                    <div class="flex items-end gap-3">
                        <h3 class="text-3xl font-extrabold text-[#020617] tracking-tight">{{ $totalEarnings ?? '0.00' }} <span class="text-lg text-gray-400 font-semibold">JOD</span></h3>
                    </div>
                </div>
            </div>

            <!-- Card 2: Active Bookings -->
            <div class="bg-white rounded-3xl p-6 shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-gray-100 relative overflow-hidden group hover:border-[#1d5c42]/50 transition-colors duration-300">
                <div class="absolute top-0 right-0 p-4 opacity-5 group-hover:opacity-10 transition-opacity">
                    <svg class="w-24 h-24 text-[#1d5c42]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                </div>
                <div class="relative z-10">
                    <div class="w-12 h-12 rounded-2xl bg-[#1d5c42]/10 flex items-center justify-center mb-4 border border-[#1d5c42]/20">
                        <svg class="w-6 h-6 text-[#1d5c42]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    </div>
                    <p class="text-sm font-bold text-gray-500 uppercase tracking-wider mb-1">Active Bookings</p>
                    <div class="flex items-end gap-3">
                        <h3 class="text-3xl font-extrabold text-[#020617] tracking-tight">{{ $activeBookingsCount ?? '0' }}</h3>
                        <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-bold bg-[#1d5c42]/10 text-[#1d5c42] mb-1.5">
                            Pending Action
                        </span>
                    </div>
                </div>
            </div>

            <!-- Card 3: Total Farms -->
            <div class="bg-white rounded-3xl p-6 shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-gray-100 relative overflow-hidden group hover:border-blue-500/50 transition-colors duration-300">
                <div class="absolute top-0 right-0 p-4 opacity-5 group-hover:opacity-10 transition-opacity">
                    <svg class="w-24 h-24 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                </div>
                <div class="relative z-10">
                    <div class="w-12 h-12 rounded-2xl bg-blue-500/10 flex items-center justify-center mb-4 border border-blue-500/20">
                        <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                    </div>
                    <p class="text-sm font-bold text-gray-500 uppercase tracking-wider mb-1">Total Properties</p>
                    <div class="flex items-end gap-3">
                        <h3 class="text-3xl font-extrabold text-[#020617] tracking-tight">{{ $totalFarms ?? '0' }}</h3>
                    </div>
                </div>
            </div>

        </div>

        <!-- Recent Activity & Quick Links Section -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <!-- Recent Bookings Table (Takes up 2 columns on large screens) -->
            <div class="lg:col-span-2 bg-white rounded-3xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-gray-100 p-6">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h3 class="text-lg font-bold text-[#020617]">Recent Bookings</h3>
                        <p class="text-sm text-gray-500 mt-1">Latest booking requests needing your attention.</p>
                    </div>
                    <a href="{{ route('owner.bookings.index') ?? '#' }}" class="text-sm font-bold text-[#1d5c42] hover:text-[#154531] transition-colors flex items-center gap-1">
                        View All
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </a>
                </div>

                <!-- Empty State Placeholder (Assuming no data passed yet) -->
                @if(isset($recentBookings) && $recentBookings->count() > 0)
                    <!-- Here goes the table if data exists -->
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="border-b border-gray-100 text-xs font-semibold tracking-wide text-gray-500 uppercase">
                                    <th class="py-3 px-4">Guest</th>
                                    <th class="py-3 px-4">Farm</th>
                                    <th class="py-3 px-4">Dates</th>
                                    <th class="py-3 px-4">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                @foreach($recentBookings as $booking)
                                    <tr class="hover:bg-gray-50/50 transition-colors">
                                        <td class="py-3 px-4">
                                            <div class="flex items-center gap-3">
                                                <div class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center text-xs font-bold text-gray-600">
                                                    {{ substr($booking->user->name ?? 'G', 0, 1) }}
                                                </div>
                                                <span class="font-semibold text-sm text-[#020617]">{{ $booking->user->name ?? 'Guest Name' }}</span>
                                            </div>
                                        </td>
                                        <td class="py-3 px-4 text-sm font-medium text-gray-600">{{ $booking->farm->name ?? 'Farm Name' }}</td>
                                        <td class="py-3 px-4 text-sm text-gray-500">{{ \Carbon\Carbon::parse($booking->start_date)->format('M d') }} - {{ \Carbon\Carbon::parse($booking->end_date)->format('M d, Y') }}</td>
                                        <td class="py-3 px-4">
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold bg-yellow-100 text-yellow-800 border border-yellow-200">
                                                Pending
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="flex flex-col items-center justify-center py-12 text-center">
                        <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mb-4 border border-gray-100">
                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        </div>
                        <h4 class="text-base font-bold text-[#020617] mb-1">No recent bookings</h4>
                        <p class="text-sm text-gray-500 max-w-sm">When guests book your properties, their requests will appear here for you to review.</p>
                    </div>
                @endif
            </div>

            <!-- Helpful Resources / Quick Actions -->
            <div class="bg-gradient-to-b from-gray-50 to-white rounded-3xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-gray-100 p-6 flex flex-col">
                <h3 class="text-lg font-bold text-[#020617] mb-6">Quick Actions</h3>

                <div class="space-y-4 flex-1">
                    <a href="{{ route('owner.farms.create') ?? '#' }}" class="group flex items-center gap-4 p-4 rounded-2xl border border-gray-200 bg-white hover:border-[#1d5c42]/50 hover:shadow-md transition-all">
                        <div class="w-10 h-10 rounded-xl bg-[#1d5c42]/10 flex items-center justify-center text-[#1d5c42] group-hover:scale-110 transition-transform">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        </div>
                        <div>
                            <h4 class="text-sm font-bold text-[#020617]">Add New Farm</h4>
                            <p class="text-xs text-gray-500 mt-0.5">List a new property for rent.</p>
                        </div>
                    </a>

                    <a href="{{ route('owner.profile.edit') ?? '#' }}" class="group flex items-center gap-4 p-4 rounded-2xl border border-gray-200 bg-white hover:border-[#c2a265]/50 hover:shadow-md transition-all">
                        <div class="w-10 h-10 rounded-xl bg-[#c2a265]/10 flex items-center justify-center text-[#c2a265] group-hover:scale-110 transition-transform">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        </div>
<div>
    <h4 class="text-sm font-bold text-[#020617]">Account Settings</h4>
    <p class="text-xs text-gray-500 mt-0.5">Manage your profile and security.</p>
</div>
                    </a>
                </div>
            </div>

        </div>
    </div>
</x-owner-layout>
