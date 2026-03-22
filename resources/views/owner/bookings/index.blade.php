<x-dashboard-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-black text-3xl text-gray-900 tracking-tight leading-tight">
                {{ __('Booking Management') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-8 px-4 sm:px-6 lg:px-8">

        @if (session('success'))
            <div class="mb-8 bg-green-50 border-l-4 border-green-500 p-5 rounded-2xl flex items-start shadow-sm animate-fade-in">
                <svg class="w-6 h-6 text-green-500 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <p class="text-sm text-green-700 font-black uppercase tracking-tight">{{ session('success') }}</p>
            </div>
        @endif

        @if (session('error'))
            <div class="mb-8 bg-red-50 border-l-4 border-red-500 p-5 rounded-2xl flex items-start shadow-sm animate-fade-in">
                <svg class="w-6 h-6 text-red-500 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                <p class="text-sm text-red-700 font-black uppercase tracking-tight">{{ session('error') }}</p>
            </div>
        @endif

        <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-8 border-b border-gray-50">

                <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                    <h3 class="text-xl font-black text-gray-900 tracking-tighter flex items-center">
                        <svg class="w-6 h-6 mr-3 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                        All Reservations
                    </h3>

                    <form method="GET" action="{{ route('owner.bookings.index') }}" class="flex items-center gap-3 bg-gray-50 p-2 rounded-2xl border border-gray-100">
                        <div class="flex items-center pl-3">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                        </div>
                        <select name="status" onchange="this.form.submit()" class="bg-transparent border-none text-sm font-black text-gray-700 focus:ring-0 cursor-pointer pr-8 uppercase tracking-widest">
                            <option value="all" {{ request('status') === 'all' ? 'selected' : '' }}>All Statuses</option>
                            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="confirmed" {{ request('status') === 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                            <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </form>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left whitespace-nowrap">
                    <thead class="bg-gray-50/50">
                        <tr>
                            <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest">Booking ID</th>
                            <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest">Farm / Customer</th>
                            <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest">Stay Dates</th>
                            <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest">Net Profit</th>
                            <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest">Status</th>
                            <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse ($bookings as $booking)
                            <tr class="hover:bg-gray-50/50 transition-colors group">
                                <td class="px-8 py-6">
                                    <span class="bg-gray-100 text-gray-600 text-xs font-black px-3 py-1.5 rounded-lg">#{{ $booking->id }}</span>
                                </td>
                                <td class="px-8 py-6">
                                    <div class="font-black text-green-700 text-sm mb-1">{{ $booking->farm->name }}</div>
                                    <div class="flex items-center mt-2">
                                        <div class="h-6 w-6 rounded-md bg-gray-200 flex items-center justify-center text-gray-600 font-black mr-2 text-[10px]">
                                            {{ strtoupper(substr($booking->user->name, 0, 1)) }}
                                        </div>
                                        <div class="text-xs font-bold text-gray-600">{{ $booking->user->name }}</div>
                                    </div>
                                </td>
                                <td class="px-8 py-6">
                                    <div class="font-bold text-gray-800 text-sm">{{ \Carbon\Carbon::parse($booking->check_in_date)->format('M d, Y') }}</div>
                                    <div class="text-[10px] font-black uppercase tracking-widest text-gray-400 mt-1 flex items-center">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                                        {{ \Carbon\Carbon::parse($booking->check_out_date)->format('M d, Y') }}
                                    </div>
                                </td>
                                <td class="px-8 py-6 font-black text-gray-900 text-lg">
                                    {{ number_format($booking->net_profit, 2) }} <span class="text-[10px] text-gray-400 uppercase">JOD</span>
                                </td>
                                <td class="px-8 py-6">
                                    @if($booking->status === 'pending')
                                        <span class="bg-amber-100 text-amber-700 text-[10px] font-black px-3 py-1.5 rounded-lg uppercase tracking-widest">Pending</span>
                                    @elseif($booking->status === 'confirmed')
                                        <span class="bg-green-100 text-green-700 text-[10px] font-black px-3 py-1.5 rounded-lg uppercase tracking-widest">Confirmed</span>
                                    @elseif($booking->status === 'cancelled' || $booking->status === 'rejected')
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
                                                <button onclick="return confirm('Confirm this booking?')" class="bg-green-100 hover:bg-green-500 text-green-700 hover:text-white px-4 py-2 rounded-xl flex items-center justify-center transition-all shadow-sm font-black text-xs uppercase tracking-widest">
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                                    Accept
                                                </button>
                                            </form>
                                            <form action="{{ route('owner.bookings.reject', $booking->id) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <button onclick="return confirm('Reject this booking?')" class="bg-red-100 hover:bg-red-500 text-red-700 hover:text-white px-4 py-2 rounded-xl flex items-center justify-center transition-all shadow-sm font-black text-xs uppercase tracking-widest">
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"></path></svg>
                                                    Reject
                                                </button>
                                            </form>
                                        </div>
                                    @else
                                        <span class="text-gray-300 text-[10px] font-black uppercase tracking-widest flex items-center justify-center">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                            Action Taken
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-8 py-20 text-center text-gray-500">
                                    <div class="w-24 h-24 bg-gray-50 rounded-[2rem] flex items-center justify-center mx-auto mb-6 border-4 border-white shadow-sm">
                                        <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    </div>
                                    <p class="text-xl font-black text-gray-800 tracking-tight mb-2">No bookings found</p>
                                    <p class="text-sm font-bold text-gray-400 max-w-sm mx-auto">Try adjusting your filters or wait for new customers to book your farms.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($bookings->hasPages())
                <div class="p-6 border-t border-gray-50 bg-gray-50/30">
                    {{ $bookings->links() }}
                </div>
            @endif

        </div>
    </div>
</x-dashboard-layout>
