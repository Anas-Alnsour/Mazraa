<x-app-layout>
    <x-slot name="header">
        <h2 class="font-black text-2xl text-[#1d5c42] leading-tight tracking-tighter">
            {{ __('Manage Bookings') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-[#f9f8f4] min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            @if(session('success'))
                <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded-r-xl shadow-sm animate-fade-in mb-6">
                    <p class="text-green-800 font-bold">{{ session('success') }}</p>
                </div>
            @endif

            @forelse ($farms as $farm)
                <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 overflow-hidden">

                    <div class="p-6 bg-gray-50/50 border-b border-gray-100 flex flex-col md:flex-row justify-between items-center gap-4">
                        <div class="flex items-center gap-4">
                            <div class="w-16 h-16 rounded-xl bg-gray-200 overflow-hidden flex-shrink-0">
                                <img src="{{ $farm->main_image ? asset('storage/' . $farm->main_image) : 'https://via.placeholder.com/150' }}" class="w-full h-full object-cover">
                            </div>
                            <div>
                                <h3 class="text-xl font-black text-gray-900">{{ $farm->name }}</h3>
                                <p class="text-xs font-bold text-gray-500 mt-1 flex items-center">
                                    <span class="text-[#1d5c42] mr-2">{{ number_format($farm->price_per_night, 0) }} JOD / Shift</span>
                                    | <span class="ml-2">{{ $farm->location }}</span>
                                </p>
                            </div>
                        </div>
                        <div>
                            @if($farm->is_approved)
                                <span class="px-4 py-2 text-[10px] font-black uppercase tracking-widest rounded-xl bg-green-100 text-green-800 border border-green-200">
                                    Verified & Public
                                </span>
                            @else
                                <span class="px-4 py-2 text-[10px] font-black uppercase tracking-widest rounded-xl bg-amber-100 text-amber-800 border border-amber-200">
                                    Pending Approval
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="overflow-x-auto p-4">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="border-b border-gray-100">
                                    <th class="py-4 px-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Shift Time</th>
                                    <th class="py-4 px-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Event & Guest</th>
                                    <th class="py-4 px-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Revenue</th>
                                    <th class="py-4 px-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Status</th>
                                    <th class="py-4 px-4 text-[10px] font-black text-gray-400 uppercase tracking-widest text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                @forelse($farm->bookings as $booking)
                                    <tr class="hover:bg-gray-50/50 transition-colors">
                                        <td class="py-4 px-4">
                                            <div class="text-sm font-bold text-gray-900">{{ \Carbon\Carbon::parse($booking->start_time)->format('M d, Y') }}</div>
                                            <div class="text-xs font-bold text-gray-500 mt-0.5">
                                                {{ \Carbon\Carbon::parse($booking->start_time)->format('h:i A') }} - {{ \Carbon\Carbon::parse($booking->end_time)->format('h:i A') }}
                                            </div>
                                        </td>
                                        <td class="py-4 px-4">
                                            <div class="text-sm font-bold text-gray-900">{{ $booking->event_type ?? 'Standard' }}</div>
                                            <div class="text-xs font-bold text-gray-500 mt-0.5">{{ $booking->user->name ?? 'Guest User' }}</div>
                                        </td>
                                        <td class="py-4 px-4">
                                            <div class="text-sm font-black text-[#1d5c42]">{{ number_format($booking->total_price, 0) }} JOD</div>
                                            <div class="text-[10px] font-bold text-gray-400 uppercase">Profit: {{ number_format($booking->net_profit, 0) }} JOD</div>
                                        </td>
                                        <td class="py-4 px-4">
                                            @if($booking->status == 'pending')
                                                <span class="px-3 py-1 text-[10px] font-black uppercase tracking-widest rounded-lg bg-yellow-100 text-yellow-800">Pending</span>
                                            @elseif($booking->status == 'confirmed')
                                                <span class="px-3 py-1 text-[10px] font-black uppercase tracking-widest rounded-lg bg-green-100 text-green-800">Confirmed</span>
                                            @else
                                                <span class="px-3 py-1 text-[10px] font-black uppercase tracking-widest rounded-lg bg-red-100 text-red-800">Rejected</span>
                                            @endif
                                        </td>
                                        <td class="py-4 px-4 text-right">
                                            @if($booking->status == 'pending')
                                                <div class="flex justify-end gap-2">
                                                    <form action="{{ route('farm_owner.bookings.updateStatus', $booking->id) }}" method="POST">
                                                        @csrf @method('PATCH')
                                                        <input type="hidden" name="status" value="confirmed">
                                                        <button type="submit" class="bg-green-50 hover:bg-green-100 text-green-700 font-bold text-xs px-3 py-2 rounded-lg transition-colors border border-green-200">Accept</button>
                                                    </form>
                                                    <form action="{{ route('farm_owner.bookings.updateStatus', $booking->id) }}" method="POST">
                                                        @csrf @method('PATCH')
                                                        <input type="hidden" name="status" value="rejected">
                                                        <button type="submit" class="bg-red-50 hover:bg-red-100 text-red-700 font-bold text-xs px-3 py-2 rounded-lg transition-colors border border-red-200">Reject</button>
                                                    </form>
                                                </div>
                                            @else
                                                <span class="text-xs font-bold text-gray-400">Actioned</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="py-12 text-center">
                                            <div class="text-gray-400 mb-2 text-2xl">📭</div>
                                            <p class="text-sm font-bold text-gray-500">No bookings yet for this property.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 p-16 text-center">
                    <div class="text-gray-300 mb-4 text-5xl">🚜</div>
                    <h3 class="text-xl font-black text-gray-900 mb-2">No farms listed</h3>
                    <p class="text-sm font-bold text-gray-500 mb-6">You haven't added any properties to your portfolio yet.</p>
                    <a href="{{ route('owner.farms.create') }}" class="inline-flex items-center px-6 py-3 bg-[#b46146] hover:bg-[#964f38] text-white rounded-xl font-black text-xs uppercase tracking-widest shadow-lg transition-transform active:scale-95">
                        List Your First Farm
                    </a>
                </div>
            @endforelse

        </div>
    </div>
</x-app-layout>
