<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Pending Financial Verifications (CliQ)') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-12">

            @if(session('success'))
                <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl p-4 flex items-center gap-3">
                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span class="font-bold text-sm">{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-50 border border-red-200 text-red-800 rounded-xl p-4 flex items-center gap-3">
                    <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span class="font-bold text-sm">{{ session('error') }}</span>
                </div>
            @endif

            <!-- 1. Farm Bookings Verifications -->
            <div>
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                        <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                        Farm Bookings Awaiting Verification
                        <span class="bg-indigo-100 text-indigo-800 text-xs font-black px-2 py-0.5 rounded-full">{{ $farmBookings->count() }}</span>
                    </h3>
                </div>

                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-gray-50 border-b border-gray-100">
                                    <th class="px-6 py-4 text-xs font-black text-gray-500 uppercase tracking-widest">Customer & ID</th>
                                    <th class="px-6 py-4 text-xs font-black text-gray-500 uppercase tracking-widest">Farm Details</th>
                                    <th class="px-6 py-4 text-xs font-black text-gray-500 uppercase tracking-widest">Amount Required</th>
                                    <th class="px-6 py-4 text-xs font-black text-gray-500 uppercase tracking-widest">Date Submitted</th>
                                    <th class="px-6 py-4 text-xs font-black text-gray-500 uppercase tracking-widest text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @forelse($farmBookings as $booking)
                                    <tr class="hover:bg-gray-50/50 transition-colors">
                                        <td class="px-6 py-4">
                                            <span class="block text-sm font-bold text-gray-900">{{ $booking->user->name ?? 'Guest' }}</span>
                                            <span class="block text-xs font-medium text-gray-500">{{ $booking->user->email ?? 'No email' }}</span>
                                            <span class="block mt-1 text-[10px] font-mono font-bold text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded w-max">#FB-{{ $booking->id }}</span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="block text-sm font-bold text-gray-900">{{ $booking->farm->name }}</span>
                                            <span class="block text-xs font-medium text-gray-500">{{ \Carbon\Carbon::parse($booking->start_time)->format('M d, Y') }} ({{ ucfirst(str_replace('_', ' ', $booking->event_type)) }})</span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="text-sm font-black text-[#7e22ce]">{{ number_format($booking->total_price, 3) }} JOD</span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="text-xs font-bold text-gray-600">{{ $booking->updated_at->diffForHumans() }}</span>
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <div class="flex items-center justify-end gap-2">
                                                <form action="{{ route('admin.verifications.handle', ['id' => $booking->id, 'type' => 'farm_booking']) }}" method="POST" class="inline-block">
                                                    @csrf
                                                    <input type="hidden" name="action" value="approve">
                                                    <button type="submit" class="px-3 py-1.5 rounded-lg bg-emerald-50 text-emerald-700 hover:bg-emerald-600 hover:text-white transition-colors border border-emerald-200 text-xs font-black uppercase tracking-wider" onclick="return confirm('Confirm receipt of funds?');">
                                                        Approve
                                                    </button>
                                                </form>
                                                <form action="{{ route('admin.verifications.handle', ['id' => $booking->id, 'type' => 'farm_booking']) }}" method="POST" class="inline-block">
                                                    @csrf
                                                    <input type="hidden" name="action" value="reject">
                                                    <button type="submit" class="px-3 py-1.5 rounded-lg bg-red-50 text-red-700 hover:bg-red-600 hover:text-white transition-colors border border-red-200 text-xs font-black uppercase tracking-wider" onclick="return confirm('Are you sure you want to REJECT and CANCEL this booking?');">
                                                        Reject
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-8 text-center text-sm font-medium text-gray-500 italic">No farm bookings awaiting verification.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- 2. Supply Orders Verifications -->
            <div>
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                        <svg class="w-5 h-5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                        Supply Orders Awaiting Verification
                        <span class="bg-rose-100 text-rose-800 text-xs font-black px-2 py-0.5 rounded-full">{{ $supplyOrders->count() }}</span>
                    </h3>
                </div>

                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-gray-50 border-b border-gray-100">
                                    <th class="px-6 py-4 text-xs font-black text-gray-500 uppercase tracking-widest">Customer & Invoice</th>
                                    <th class="px-6 py-4 text-xs font-black text-gray-500 uppercase tracking-widest">Supply Item</th>
                                    <th class="px-6 py-4 text-xs font-black text-gray-500 uppercase tracking-widest">Amount Required</th>
                                    <th class="px-6 py-4 text-xs font-black text-gray-500 uppercase tracking-widest">Date Submitted</th>
                                    <th class="px-6 py-4 text-xs font-black text-gray-500 uppercase tracking-widest text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @forelse($supplyOrders as $order)
                                    <tr class="hover:bg-gray-50/50 transition-colors">
                                        <td class="px-6 py-4">
                                            <span class="block text-sm font-bold text-gray-900">{{ $order->user->name ?? 'Guest' }}</span>
                                            <span class="block mt-1 text-[10px] font-mono font-bold text-rose-600 bg-rose-50 px-2 py-0.5 rounded w-max">{{ $order->order_id }}</span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="block text-sm font-bold text-gray-900">{{ $order->supply->name ?? 'Deleted Item' }}</span>
                                            <span class="block text-xs font-medium text-gray-500">Qty: {{ $order->quantity }}</span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="text-sm font-black text-[#7e22ce]">{{ number_format($order->total_price, 3) }} JOD</span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="text-xs font-bold text-gray-600">{{ $order->updated_at->diffForHumans() }}</span>
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <div class="flex items-center justify-end gap-2">
                                                <form action="{{ route('admin.verifications.handle', ['id' => $order->id, 'type' => 'supply_order']) }}" method="POST" class="inline-block">
                                                    @csrf
                                                    <input type="hidden" name="action" value="approve">
                                                    <button type="submit" class="px-3 py-1.5 rounded-lg bg-emerald-50 text-emerald-700 hover:bg-emerald-600 hover:text-white transition-colors border border-emerald-200 text-xs font-black uppercase tracking-wider" onclick="return confirm('Confirm receipt of funds?');">
                                                        Approve
                                                    </button>
                                                </form>
                                                <form action="{{ route('admin.verifications.handle', ['id' => $order->id, 'type' => 'supply_order']) }}" method="POST" class="inline-block">
                                                    @csrf
                                                    <input type="hidden" name="action" value="reject">
                                                    <button type="submit" class="px-3 py-1.5 rounded-lg bg-red-50 text-red-700 hover:bg-red-600 hover:text-white transition-colors border border-red-200 text-xs font-black uppercase tracking-wider" onclick="return confirm('Are you sure you want to REJECT and CANCEL this order?');">
                                                        Reject
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-8 text-center text-sm font-medium text-gray-500 italic">No supply orders awaiting verification.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-admin-layout>
