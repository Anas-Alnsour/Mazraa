<x-owner-layout>
    <x-slot name="header">
        <div>
            <h1 class="text-2xl font-extrabold text-[#020617] tracking-tight">Booking Requests</h1>
            <p class="text-sm text-gray-500 mt-1">Review and manage reservations for your properties.</p>
        </div>
    </x-slot>

    <!-- Success Message (Flash Session) -->
    @if(session('success'))
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" class="mb-6 p-4 rounded-xl bg-green-50 border border-green-200 flex items-start gap-3 shadow-sm animate-fade-in-up">
        <svg class="w-5 h-5 text-green-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        <div>
            <h3 class="text-sm font-bold text-green-800">Success</h3>
            <p class="text-sm text-green-700 mt-0.5">{{ session('success') }}</p>
        </div>
        <button @click="show = false" class="ml-auto text-green-500 hover:text-green-700">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>
    </div>
    @endif

    <div class="pb-10">
        @if(isset($bookings) && $bookings->count() > 0)

            <!-- Premium Data Table Card -->
            <div class="bg-white rounded-3xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-gray-100 overflow-hidden">
                <!-- Header / Filters (Optional placeholder for future) -->
                <div class="px-6 py-5 border-b border-gray-100 bg-gray-50/50 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <h3 class="text-base font-bold text-[#020617] flex items-center gap-2">
                        <svg class="w-5 h-5 text-[#1d5c42]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        All Reservations
                    </h3>
                    <div class="flex items-center gap-2 text-sm text-gray-500 font-medium bg-white px-3 py-1.5 rounded-lg border border-gray-200 shadow-sm">
                        Total Requests: <span class="font-bold text-[#020617]">{{ $bookings->total() ?? $bookings->count() }}</span>
                    </div>
                </div>

                <!-- Table Wrapper for Responsiveness -->
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse whitespace-nowrap">
                        <thead>
                            <tr class="border-b border-gray-100 bg-white text-xs font-bold tracking-wider text-gray-500 uppercase">
                                <th class="py-4 px-6">Guest Detail</th>
                                <th class="py-4 px-6">Property</th>
                                <th class="py-4 px-6">Stay Dates</th>
                                <th class="py-4 px-6 text-right">Total Payout</th>
                                <th class="py-4 px-6 text-center">Status</th>
                                <th class="py-4 px-6 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @foreach($bookings as $booking)
                                <tr class="hover:bg-gray-50/50 transition-colors group">

                                    <!-- Guest Detail -->
                                    <td class="py-4 px-6">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-[#c2a265] to-yellow-600 flex items-center justify-center text-sm font-bold text-white shadow-sm flex-shrink-0">
                                                {{ substr($booking->user->name ?? 'G', 0, 1) }}
                                            </div>
                                            <div>
                                                <p class="font-bold text-[#020617] text-sm">{{ $booking->user->name ?? 'Guest Name' }}</p>
                                                <p class="text-xs text-gray-500 mt-0.5">{{ $booking->user->email ?? 'Guest Contact' }}</p>
                                            </div>
                                        </div>
                                    </td>

                                    <!-- Property -->
                                    <td class="py-4 px-6">
                                        <div class="flex items-center gap-2">
                                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                                            <span class="font-semibold text-gray-700 text-sm max-w-[150px] truncate" title="{{ $booking->farm->name ?? 'Farm Name' }}">
                                                {{ $booking->farm->name ?? 'Farm Name' }}
                                            </span>
                                        </div>
                                    </td>

                                    <!-- Stay Dates -->
                                    <td class="py-4 px-6">
                                        <div class="flex flex-col">
                                            <span class="text-sm font-semibold text-[#020617]">
                                                {{ \Carbon\Carbon::parse($booking->start_date)->format('M d') }} - {{ \Carbon\Carbon::parse($booking->end_date)->format('M d, Y') }}
                                            </span>
                                            <span class="text-xs text-gray-500 mt-0.5 font-medium">
                                                {{ \Carbon\Carbon::parse($booking->start_date)->diffInDays(\Carbon\Carbon::parse($booking->end_date)) }} nights
                                            </span>
                                        </div>
                                    </td>

                                    <!-- Total Payout -->
                                    <td class="py-4 px-6 text-right">
                                        <span class="block text-base font-extrabold text-[#1d5c42]">
                                            {{ number_format($booking->total_price ?? 0, 2) }} <span class="text-xs text-gray-500 font-bold">JOD</span>
                                        </span>
                                    </td>

                                    <!-- Status -->
                                    <td class="py-4 px-6 text-center">
                                        @php
                                            $status = strtolower($booking->status);
                                        @endphp

                                        @if(in_array($status, ['approved', 'confirmed', 'completed', 'finished']))
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-bold bg-green-50 text-green-700 border border-green-200 shadow-sm">
                                                <span class="w-2 h-2 rounded-full bg-green-500"></span> {{ ucfirst($status) }}
                                            </span>
                                        @elseif($status === 'pending')
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-bold bg-yellow-50 text-yellow-700 border border-yellow-200 shadow-sm">
                                                <span class="relative flex h-2 w-2">
                                                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-yellow-400 opacity-75"></span>
                                                  <span class="relative inline-flex rounded-full h-2 w-2 bg-yellow-500"></span>
                                                </span>
                                                Action Required
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-bold bg-red-50 text-red-700 border border-red-200 shadow-sm">
                                                <span class="w-2 h-2 rounded-full bg-red-500"></span> {{ ucfirst($status) }}
                                            </span>
                                        @endif
                                    </td>

                                    <!-- Actions -->
                                    <td class="py-4 px-6 text-right">
                                        @if($status === 'pending')
                                            <div class="flex items-center justify-end gap-2">
                                                <!-- Approve Form -->
                                                <form action="{{ route('owner.bookings.approve', $booking->id) }}" method="POST">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="inline-flex items-center justify-center gap-1 px-3 py-1.5 bg-[#1d5c42] hover:bg-[#154531] text-white text-xs font-bold rounded-lg transition-colors shadow-sm" title="Approve">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                                                        Approve
                                                    </button>
                                                </form>

                                                <!-- Reject Form -->
                                                <form action="{{ route('owner.bookings.reject', $booking->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to decline this booking request?');">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="inline-flex items-center justify-center gap-1 px-3 py-1.5 bg-white hover:bg-red-50 text-red-600 border border-red-200 text-xs font-bold rounded-lg transition-colors shadow-sm" title="Reject">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                                        Decline
                                                    </button>
                                                </form>
                                            </div>
                                        @else
                                            <!-- Static Action State (Disabled/View Only) -->
                                            <span class="text-xs font-semibold text-gray-400 italic">No actions available</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Pagination Links -->
            <div class="mt-8">
                @if(method_exists($bookings, 'links'))
                    {{ $bookings->links() }}
                @endif
            </div>

        @else
            <!-- Empty State -->
            <div class="flex flex-col items-center justify-center py-20 px-4 text-center bg-white rounded-3xl border border-dashed border-gray-300 shadow-sm">
                <div class="w-24 h-24 bg-gray-50 rounded-full flex items-center justify-center mb-6 border border-gray-100 shadow-inner">
                    <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 12v.01"></path></svg>
                </div>
                <h3 class="text-2xl font-extrabold text-[#020617] mb-2">No Bookings Yet</h3>
                <p class="text-gray-500 max-w-md mx-auto mb-8">When customers book your farms, their reservation requests will appear here for you to approve or decline.</p>

                <a href="{{ route('owner.farms.index') }}" class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-[#020617] hover:bg-gray-800 text-white text-sm font-bold rounded-xl transition-all shadow-lg hover:shadow-xl hover:-translate-y-0.5">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Manage My Properties
                </a>
            </div>
        @endif
    </div>
</x-owner-layout>
