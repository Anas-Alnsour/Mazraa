<x-dashboard-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                {{ __('Welcome back, ') }} <span class="text-green-600">{{ Auth::user()->name }}</span>
            </h2>
            <div class="flex gap-3">
                <button class="bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded-lg shadow-sm transition-colors text-sm flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Add New Farm
                </button>
            </div>
        </div>
    </x-slot>

    <div class="py-8 px-4 sm:px-6 lg:px-8">

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">

            <!-- Total Farms -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden transform hover:-translate-y-1 transition-transform duration-300">
                <div class="p-6 flex items-center">
                    <div class="p-4 rounded-full bg-green-50 text-green-600 mr-5 shadow-inner">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="mb-1 text-sm font-semibold text-gray-500 uppercase tracking-wide">Total Farms</p>
                        <h3 class="text-3xl font-extrabold text-gray-800">{{ $totalFarms ?? 0 }}</h3>
                        <p class="text-xs text-green-600 mt-1 font-medium">+1 this month</p>
                    </div>
                </div>
            </div>

            <!-- Active Bookings -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden transform hover:-translate-y-1 transition-transform duration-300">
                <div class="p-6 flex items-center">
                    <div class="p-4 rounded-full bg-blue-50 text-blue-600 mr-5 shadow-inner">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="mb-1 text-sm font-semibold text-gray-500 uppercase tracking-wide">Active Bookings</p>
                        <h3 class="text-3xl font-extrabold text-gray-800">{{ $totalBookings ?? 0 }}</h3>
                        <p class="text-xs text-blue-600 mt-1 font-medium">5 pending approval</p>
                    </div>
                </div>
            </div>

            <!-- Total Revenue -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden transform hover:-translate-y-1 transition-transform duration-300">
                <div class="p-6 flex items-center">
                    <div class="p-4 rounded-full bg-orange-50 text-orange-600 mr-5 shadow-inner">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="mb-1 text-sm font-semibold text-gray-500 uppercase tracking-wide">Net Revenue</p>
                        <h3 class="text-3xl font-extrabold text-gray-800">${{ number_format($totalRevenue ?? 0, 2) }}</h3>
                        <p class="text-xs text-orange-600 mt-1 font-medium">+14% from last month</p>
                    </div>
                </div>
            </div>

        </div>

        <!-- Recent Bookings Table -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
                <h3 class="text-lg font-bold text-gray-800">Recent Bookings Activity</h3>
                <a href="#" class="text-sm font-medium text-green-600 hover:text-green-800 transition-colors">View All &rarr;</a>
            </div>

            <div class="p-0">
                @if(isset($recentBookings) && $recentBookings->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-white border-b border-gray-100 text-xs uppercase text-gray-500 tracking-wider">
                                    <th class="px-6 py-4 font-semibold">Guest / Customer</th>
                                    <th class="px-6 py-4 font-semibold">Farm Property</th>
                                    <th class="px-6 py-4 font-semibold">Check-in Date</th>
                                    <th class="px-6 py-4 font-semibold">Net Profit</th>
                                    <th class="px-6 py-4 font-semibold text-center">Status</th>
                                    <th class="px-6 py-4 font-semibold text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="text-sm text-gray-700">
                                @foreach($recentBookings as $booking)
                                    <tr class="border-b border-gray-50 hover:bg-gray-50 transition-colors">

                                        <!-- Guest -->
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-3">
                                                <div class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center text-gray-600 font-bold text-xs">
                                                    {{ substr($booking->user->name ?? 'G', 0, 1) }}
                                                </div>
                                                <div>
                                                    <p class="font-medium text-gray-900">{{ $booking->user->name ?? 'Unknown Guest' }}</p>
                                                    <p class="text-xs text-gray-500">{{ $booking->user->phone ?? 'No Phone' }}</p>
                                                </div>
                                            </div>
                                        </td>

                                        <!-- Farm -->
                                        <td class="px-6 py-4 font-medium text-gray-800">
                                            {{ $booking->farm->name ?? 'N/A' }}
                                        </td>

                                        <!-- Date -->
                                        <td class="px-6 py-4">
                                            <p class="text-gray-900">{{ \Carbon\Carbon::parse($booking->start_time)->format('M d, Y') }}</p>
                                            <p class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($booking->start_time)->format('h:i A') }}</p>
                                        </td>

                                        <!-- Profit -->
                                        <td class="px-6 py-4 font-semibold text-gray-900">
                                            ${{ number_format($booking->net_profit ?? 0, 2) }}
                                        </td>

                                        <!-- Status Badge -->
                                        <td class="px-6 py-4 text-center">
                                            @if($booking->status == 'confirmed')
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 border border-green-200">
                                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                                                    Confirmed
                                                </span>
                                            @elseif($booking->status == 'cancelled')
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 border border-red-200">
                                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg>
                                                    Cancelled
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 border border-yellow-200">
                                                    <svg class="w-3 h-3 mr-1 animate-pulse" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path></svg>
                                                    Pending
                                                </span>
                                            @endif
                                        </td>

                                        <!-- Actions -->
                                        <td class="px-6 py-4 text-right">
                                            <button class="text-indigo-600 hover:text-indigo-900 text-sm font-medium mr-3">Manage</button>
                                        </td>

                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <!-- Empty State Placeholder -->
                    <div class="py-12 flex flex-col items-center justify-center text-center">
                        <div class="w-24 h-24 bg-gray-50 rounded-full flex items-center justify-center mb-4">
                            <svg class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        </div>
                        <h4 class="text-lg font-medium text-gray-900 mb-1">No recent bookings</h4>
                        <p class="text-sm text-gray-500 max-w-sm mb-6">You don't have any bookings yet. When a customer books one of your farms, it will appear here.</p>
                        <button class="text-green-600 font-medium hover:underline text-sm">Learn how to boost your visibility</button>
                    </div>
                @endif
            </div>
        </div>

    </div>
</x-dashboard-layout>
