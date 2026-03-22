<x-dashboard-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-black text-3xl text-gray-900 tracking-tight leading-tight">
                {{ __('Dashboard Overview') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-8 px-4 sm:px-6 lg:px-8">

        @if(session('success'))
            <div class="mb-8 bg-green-50 border-l-4 border-green-500 p-5 rounded-2xl flex items-start shadow-sm animate-fade-in">
                <svg class="w-6 h-6 text-green-500 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <p class="text-sm text-green-700 font-black uppercase tracking-tight">{{ session('success') }}</p>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-8 bg-red-50 border-l-4 border-red-500 p-5 rounded-2xl flex items-start shadow-sm animate-fade-in">
                <svg class="w-6 h-6 text-red-500 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
                <p class="text-sm text-red-700 font-black uppercase tracking-tight">{{ session('error') }}</p>
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-10">
            <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 p-8 flex items-center justify-between hover:shadow-xl hover:shadow-gray-200/40 transition-all duration-500">
                <div>
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Total Farms</p>
                    <p class="text-4xl font-black text-gray-900">{{ $totalFarms }}</p>
                </div>
                <div class="w-14 h-14 bg-blue-50 text-blue-500 rounded-2xl flex items-center justify-center">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                </div>
            </div>

            <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 p-8 flex items-center justify-between hover:shadow-xl hover:shadow-gray-200/40 transition-all duration-500">
                <div>
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Active Bookings</p>
                    <p class="text-4xl font-black text-gray-900">{{ $activeBookingsCount }}</p>
                </div>
                <div class="w-14 h-14 bg-green-50 text-green-500 rounded-2xl flex items-center justify-center">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>

            <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 p-8 flex items-center justify-between hover:shadow-xl hover:shadow-gray-200/40 transition-all duration-500">
                <div>
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Pending Bookings</p>
                    <p class="text-4xl font-black text-gray-900">{{ $pendingApprovalCount }}</p>
                </div>
                <div class="w-14 h-14 bg-amber-50 text-amber-500 rounded-2xl flex items-center justify-center">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>

            <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 p-8 flex items-center justify-between hover:shadow-xl hover:shadow-gray-200/40 transition-all duration-500">
                <div>
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Total Revenue</p>
                    <p class="text-3xl font-black text-green-600 mt-1">{{ number_format($totalRevenue, 2) }} <span class="text-sm text-gray-400">JOD</span></p>
                </div>
                <div class="w-14 h-14 bg-purple-50 text-purple-500 rounded-2xl flex items-center justify-center">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-8 border-b border-gray-50 flex justify-between items-center">
                <h3 class="text-xl font-black text-gray-900 tracking-tighter flex items-center">
                    <svg class="w-6 h-6 mr-3 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                    Recent Booking Requests
                </h3>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left whitespace-nowrap">
                    <thead class="bg-gray-50/50">
                        <tr>
                            <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest">Farm Name</th>
                            <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest">Customer</th>
                            <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest">Dates</th>
                            <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest">Net Profit</th>
                            <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest">Status</th>
                            <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse ($recentBookings as $booking)
                            <tr class="hover:bg-gray-50/50 transition-colors group">
                                <td class="px-8 py-6 font-black text-green-700">{{ $booking->farm->name }}</td>
                                <td class="px-8 py-6">
                                    <div class="flex items-center">
                                        <div class="h-10 w-10 rounded-xl bg-gray-100 flex items-center justify-center text-gray-600 font-black mr-4 group-hover:bg-white transition-colors shadow-sm">
                                            {{ strtoupper(substr($booking->user->name, 0, 1)) }}
                                        </div>
                                        <span class="font-bold text-gray-800">{{ $booking->user->name }}</span>
                                    </div>
                                </td>
                                <td class="px-8 py-6">
                                    <div class="font-bold text-gray-800">{{ \Carbon\Carbon::parse($booking->check_in_date)->format('M d, Y') }}</div>
                                    <div class="text-[10px] font-black uppercase tracking-widest text-gray-400 mt-1 flex items-center">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                                        {{ \Carbon\Carbon::parse($booking->check_out_date)->format('M d, Y') }}
                                    </div>
                                </td>
                                <td class="px-8 py-6 font-black text-gray-900 text-lg">{{ number_format($booking->net_profit, 2) }} <span class="text-xs text-gray-400">JOD</span></td>
                                <td class="px-8 py-6">
                                    @if($booking->status === 'pending')
                                        <span class="bg-amber-100 text-amber-700 text-[10px] font-black px-3 py-1.5 rounded-lg uppercase tracking-widest">Pending</span>
                                    @elseif($booking->status === 'approved' || $booking->status === 'confirmed')
                                        <span class="bg-green-100 text-green-700 text-[10px] font-black px-3 py-1.5 rounded-lg uppercase tracking-widest">Confirmed</span>
                                    @elseif($booking->status === 'rejected' || $booking->status === 'cancelled')
                                        <span class="bg-red-100 text-red-700 text-[10px] font-black px-3 py-1.5 rounded-lg uppercase tracking-widest">Cancelled</span>
                                    @else
                                        <span class="bg-gray-100 text-gray-700 text-[10px] font-black px-3 py-1.5 rounded-lg uppercase tracking-widest">{{ $booking->status }}</span>
                                    @endif
                                </td>
                                <td class="px-8 py-6 text-center">
                                    @if($booking->status === 'pending')
                                        <div class="flex items-center justify-center gap-2">
                                            <form action="{{ route('owner.bookings.approve', $booking->id) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <button class="bg-green-100 hover:bg-green-500 text-green-700 hover:text-white h-10 w-10 rounded-xl flex items-center justify-center transition-all shadow-sm">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                                </button>
                                            </form>
                                            <form action="{{ route('owner.bookings.reject', $booking->id) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <button class="bg-red-100 hover:bg-red-500 text-red-700 hover:text-white h-10 w-10 rounded-xl flex items-center justify-center transition-all shadow-sm">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"></path></svg>
                                                </button>
                                            </form>
                                        </div>
                                    @else
                                        <span class="text-gray-300 text-[10px] font-black uppercase tracking-widest flex items-center justify-center">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                            Processed
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-8 py-16 text-center text-gray-500">
                                    <div class="w-20 h-20 bg-gray-50 rounded-[1.5rem] flex items-center justify-center mx-auto mb-4 border-4 border-white shadow-sm">
                                        <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    </div>
                                    <p class="text-lg font-black text-gray-700 tracking-tight">No recent bookings found.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</x-dashboard-layout>
