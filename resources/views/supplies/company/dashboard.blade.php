<x-supply-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-extrabold text-gray-900 tracking-tight">Order Management Command Center</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Metrics Row -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100 flex items-center gap-4">
                    <div class="w-14 h-14 bg-indigo-50 rounded-2xl flex items-center justify-center text-indigo-600">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <div>
                        <span class="block text-sm font-bold text-gray-500 uppercase tracking-widest mb-1">Net Revenue</span>
                        <span class="block text-2xl font-black text-gray-900">{{ number_format($totalRevenue, 2) }} JOD</span>
                    </div>
                </div>
                <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100 flex items-center gap-4">
                    <div class="w-14 h-14 bg-amber-50 rounded-2xl flex items-center justify-center text-amber-600">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    </div>
                    <div>
                        <span class="block text-sm font-bold text-gray-500 uppercase tracking-widest mb-1">Active Deliveries</span>
                        <span class="block text-2xl font-black text-gray-900">{{ $activeOrdersCount }}</span>
                    </div>
                </div>
            </div>

            <!-- Grouped Orders List -->
            <div class="space-y-6">
                @forelse($groupedOrders as $invoiceId => $items)
                    @php
                        $firstItem = $items->first();
                        $invoiceTotal = $items->sum('net_company_amount');
                    @endphp

                    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">

                        <div class="px-6 py-4 bg-gray-50/50 border-b border-gray-100 flex flex-col md:flex-row justify-between md:items-center gap-4">
                            <div>
                                <div class="flex items-center gap-3">
                                    <h3 class="text-sm font-black text-gray-900 uppercase tracking-widest">Invoice {{ $invoiceId }}</h3>
                                    <span class="text-xs font-bold text-gray-500">{{ $firstItem->created_at->format('M d, Y h:i A') }}</span>
                                </div>
                                <div class="mt-2 text-xs font-bold text-gray-600 flex items-center gap-1.5">
                                    <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.243-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                    Delivery to: {{ $firstItem->booking->farm->name ?? 'Farm' }}
                                </div>
                            </div>

                            <div class="flex flex-col items-end">
                                <!-- Status Badge -->
                                @if(in_array($firstItem->status, ['pending_payment', 'pending_verification']))
                                    <span class="inline-flex items-center px-3 py-1 rounded-lg text-[10px] font-black uppercase tracking-widest bg-gray-100 text-gray-800 border border-gray-200">Awaiting Customer Payment</span>
                                @elseif($firstItem->status === 'pending')
                                    <span class="inline-flex items-center px-3 py-1 rounded-lg text-[10px] font-black uppercase tracking-widest bg-amber-100 text-amber-800 border border-amber-200">Payment Confirmed - Prepare Order</span>
                                @elseif($firstItem->status === 'delivered')
                                    <span class="inline-flex items-center px-3 py-1 rounded-lg text-[10px] font-black uppercase tracking-widest bg-emerald-100 text-emerald-800 border border-emerald-200">Delivered Successfully</span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1 rounded-lg text-[10px] font-black uppercase tracking-widest bg-blue-100 text-blue-800 border border-blue-200">{{ str_replace('_', ' ', $firstItem->status) }}</span>
                                @endif

                                <!-- Driver Info -->
                                @if($firstItem->driver_id)
                                    <div class="mt-2 text-xs font-bold text-gray-500 flex items-center gap-1.5 bg-white px-2 py-1 rounded-md border border-gray-200 shadow-sm">
                                        <svg class="w-3.5 h-3.5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
                                        Driver: {{ $firstItem->driver->name }} ({{ $firstItem->driver->phone }})
                                    </div>
                                @else
                                    <span class="mt-2 text-[10px] font-bold text-red-500 uppercase tracking-widest">No Driver Assigned Yet</span>
                                @endif
                            </div>
                        </div>

                        <div class="p-6 divide-y divide-gray-100">
                            @foreach($items as $item)
                                <div class="py-3 flex justify-between items-center">
                                    <div class="flex items-center gap-3">
                                        @if($item->supply->image)
                                            <img src="{{ asset('storage/' . $item->supply->image) }}" class="w-10 h-10 rounded-lg object-cover border border-gray-100">
                                        @endif
                                        <div>
                                            <span class="block text-sm font-bold text-gray-900">{{ $item->supply->name }}</span>
                                            <span class="block text-xs font-medium text-gray-500">Qty: {{ $item->quantity }}</span>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <span class="block text-sm font-black text-indigo-600">{{ number_format($item->net_company_amount, 2) }} JOD</span>
                                        <span class="block text-[10px] font-bold text-gray-400">Total: {{ number_format($item->total_price, 2) }} JOD</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="px-6 py-3 bg-gray-50 border-t border-gray-100 text-right">
                            <span class="text-xs font-bold text-gray-500 uppercase tracking-widest mr-2">Invoice Net Total:</span>
                            <span class="text-lg font-black text-gray-900">{{ number_format($invoiceTotal, 2) }} JOD</span>
                        </div>
                    </div>
                @empty
                    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-12 text-center">
                        <p class="text-gray-500 font-medium text-sm">No active orders assigned to your company right now.</p>
                    </div>
                @endforelse
            </div>

        </div>
    </div>
</x-supply-layout>
