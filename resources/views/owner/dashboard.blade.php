<x-dashboard-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-black text-3xl text-gray-900 tracking-tight">
                {{ __('Welcome back, ') }} <span class="text-green-600">{{ Auth::user()->name }}</span>
            </h2>
            <div class="flex gap-3">
    <a href="{{ route('owner.farms.create') }}" class="bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-6 rounded-2xl shadow-lg shadow-green-200 transition-all flex items-center gap-2 transform active:scale-95">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path>
        </svg>
        Add New Farm
    </a>
</div>
        </div>
    </x-slot>

    <div class="py-8 px-4 sm:px-6 lg:px-8">

        @if(session('success'))
            <div class="mb-6 p-4 bg-green-100 border-l-4 border-green-500 text-green-700 rounded-xl font-bold shadow-sm animate-bounce">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="mb-6 p-4 bg-red-100 border-l-4 border-red-500 text-red-700 rounded-xl font-bold shadow-sm">
                {{ session('error') }}
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-10">

            <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 overflow-hidden hover:shadow-xl hover:shadow-gray-200/50 transition-all duration-300">
                <div class="p-8 flex items-center">
                    <div class="p-4 rounded-2xl bg-green-50 text-green-600 mr-6 shadow-sm">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="mb-1 text-xs font-black text-gray-400 uppercase tracking-widest">Total Farms</p>
                        <h3 class="text-4xl font-black text-gray-900 tracking-tighter">{{ $totalFarms }}</h3>
                        <p class="text-[10px] text-green-600 mt-1 font-bold bg-green-50 px-2 py-0.5 rounded-full inline-block">Active Portfolio</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 overflow-hidden hover:shadow-xl hover:shadow-gray-200/50 transition-all duration-300">
                <div class="p-8 flex items-center">
                    <div class="p-4 rounded-2xl bg-blue-50 text-blue-600 mr-6 shadow-sm">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="mb-1 text-xs font-black text-gray-400 uppercase tracking-widest">Active Bookings</p>
                        <h3 class="text-4xl font-black text-gray-900 tracking-tighter">{{ $activeBookingsCount }}</h3>
                        <p class="text-[10px] text-amber-600 mt-1 font-bold bg-amber-50 px-2 py-0.5 rounded-full inline-block">{{ $pendingApprovalCount }} pending approval</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 overflow-hidden hover:shadow-xl hover:shadow-gray-200/50 transition-all duration-300">
                <div class="p-8 flex items-center">
                    <div class="p-4 rounded-2xl bg-orange-50 text-orange-600 mr-6 shadow-sm">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="mb-1 text-xs font-black text-gray-400 uppercase tracking-widest">Net Revenue</p>
                        <h3 class="text-4xl font-black text-gray-900 tracking-tighter">JOD {{ number_format($totalRevenue, 2) }}</h3>
                        <p class="text-[10px] text-orange-600 mt-1 font-bold bg-orange-50 px-2 py-0.5 rounded-full inline-block">Total Earnings</p>
                    </div>
                </div>
            </div>

        </div>

        <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-8 py-6 border-b border-gray-50 flex justify-between items-center">
                <h3 class="text-xl font-black text-gray-800 uppercase tracking-tighter">Recent Bookings Activity</h3>
                <a href="#" class="text-sm font-black text-green-600 hover:text-green-800 transition-colors uppercase tracking-widest">View All &rarr;</a>
            </div>

            <div class="p-0">
                @if(isset($recentBookings) && $recentBookings->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-gray-50/50 border-b border-gray-100 text-[10px] uppercase text-gray-400 tracking-[0.2em]">
                                    <th class="px-8 py-5 font-black">Guest / Customer</th>
                                    <th class="px-8 py-5 font-black">Farm Property</th>
                                    <th class="px-8 py-5 font-black">Check-in Date</th>
                                    <th class="px-8 py-5 font-black">Net Profit</th>
                                    <th class="px-8 py-5 font-black text-center">Status</th>
                                    <th class="px-8 py-5 font-black text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="text-sm text-gray-700 divide-y divide-gray-50">
                                @foreach($recentBookings as $booking)
                                    <tr class="hover:bg-gray-50/30 transition-all group">

                                        <td class="px-8 py-5">
                                            <div class="flex items-center gap-4">
                                                <div class="w-10 h-10 rounded-2xl bg-gray-100 flex items-center justify-center text-gray-600 font-black text-sm shadow-sm group-hover:bg-white transition-colors">
                                                    {{ substr($booking->user->name ?? 'G', 0, 1) }}
                                                </div>
                                                <div>
                                                    <p class="font-bold text-gray-900">{{ $booking->user->name ?? 'Unknown Guest' }}</p>
                                                    <p class="text-xs text-gray-400 font-medium">{{ $booking->user->phone ?? 'No Phone' }}</p>
                                                </div>
                                            </div>
                                        </td>

                                        <td class="px-8 py-5 font-bold text-gray-700">
                                            {{ $booking->farm->name ?? 'N/A' }}
                                        </td>

                                        <td class="px-8 py-5">
                                            <p class="text-gray-900 font-bold">{{ \Carbon\Carbon::parse($booking->start_time)->format('M d, Y') }}</p>
                                            <p class="text-[10px] text-gray-400 font-black uppercase tracking-tighter">{{ \Carbon\Carbon::parse($booking->start_time)->format('h:i A') }}</p>
                                        </td>

                                        <td class="px-8 py-5 font-black text-gray-900">
                                            JOD {{ number_format($booking->net_profit ?? 0, 2) }}
                                        </td>

                                        <td class="px-8 py-5 text-center">
                                            @if($booking->status == 'confirmed')
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest bg-green-100 text-green-700 border border-green-200 shadow-sm">
                                                    Confirmed
                                                </span>
                                            @elseif($booking->status == 'cancelled')
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest bg-red-100 text-red-700 border border-red-200 shadow-sm">
                                                    Cancelled
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest bg-amber-100 text-amber-700 border border-amber-200 shadow-sm animate-pulse">
                                                    Pending
                                                </span>
                                            @endif
                                        </td>

                                        <td class="px-8 py-5 text-right">
                                            <div class="flex justify-end gap-2">
                                                @if($booking->status == 'pending')
                                                    <form action="{{ route('owner.bookings.approve', $booking->id) }}" method="POST">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-green-700 transition-all shadow-md active:scale-95">
                                                            Approve
                                                        </button>
                                                    </form>

                                                    <form action="{{ route('owner.bookings.reject', $booking->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to reject this booking?')">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit" class="bg-white text-red-500 border border-red-100 px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-red-50 transition-all active:scale-95">
                                                            Reject
                                                        </button>
                                                    </form>
                                                @else
                                                    <span class="text-[10px] font-black text-gray-300 uppercase tracking-widest py-2 px-4 border border-gray-100 rounded-xl italic">
                                                        No Actions
                                                    </span>
                                                @endif
                                            </div>
                                        </td>

                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="py-24 flex flex-col items-center justify-center text-center">
                        <div class="w-32 h-32 bg-gray-50 rounded-[2.5rem] flex items-center justify-center mb-6 shadow-inner">
                            <svg class="w-12 h-12 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <h4 class="text-2xl font-black text-gray-900 mb-2 tracking-tighter uppercase">No recent bookings</h4>
                        <p class="text-sm text-gray-400 max-w-sm mb-8 font-medium">Your activity stream is quiet for now. Once customers start booking, their details will appear here.</p>
                        <a href="{{ route('owner.farms.create') }}" class="text-green-600 font-black uppercase tracking-widest text-xs hover:text-green-800 transition-all">Boost your visibility &rarr;</a>
                    </div>
                @endif
            </div>
        </div>

    </div>
</x-dashboard-layout>
