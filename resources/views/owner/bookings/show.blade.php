<x-owner-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('owner.bookings.index') }}" class="p-2 bg-white rounded-xl border border-slate-200 text-slate-500 hover:text-emerald-600 hover:border-emerald-200 transition-all shadow-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            <div>
                <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Booking #{{ $booking->id }}</h1>
                <p class="text-sm text-slate-500">Review detailed reservation data and logistics.</p>
            </div>
        </div>
    </x-slot>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        {{-- Left Column: Core Details --}}
        <div class="lg:col-span-2 space-y-8">
            {{-- Status Card --}}
            <div class="bg-white rounded-3xl p-8 border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.04)]">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-6">
                    <div class="flex items-center gap-4">
                        <div class="w-16 h-16 rounded-2xl bg-emerald-50 flex items-center justify-center text-emerald-600">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <div>
                            <p class="text-xs font-black text-slate-400 uppercase tracking-widest mb-1">Current Status</p>
                            @php $status = strtolower($booking->status); @endphp
                            <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full text-sm font-bold
                                {{ in_array($status, ['confirmed', 'completed']) ? 'bg-emerald-50 text-emerald-700 border border-emerald-100' :
                                   ($status === 'pending' ? 'bg-amber-50 text-amber-700 border border-amber-100' : 'bg-red-50 text-red-700 border border-red-100') }}">
                                <span class="w-2 h-2 rounded-full {{ in_array($status, ['confirmed', 'completed']) ? 'bg-emerald-500' : ($status === 'pending' ? 'bg-amber-500' : 'bg-red-500') }}"></span>
                                {{ ucfirst($status) }}
                            </span>
                        </div>
                    </div>

                    <div class="flex items-center gap-3">
                        @if($status === 'pending')
                            <form action="{{ route('owner.bookings.approve', $booking->id) }}" method="POST">
                                @csrf @method('PATCH')
                                <button type="submit" class="px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-bold rounded-xl transition-all shadow-lg hover:shadow-emerald-600/20 active:scale-95">
                                    Approve Booking
                                </button>
                            </form>
                            <form action="{{ route('owner.bookings.reject', $booking->id) }}" method="POST">
                                @csrf @method('PATCH')
                                <button type="submit" class="px-6 py-3 bg-white border border-slate-200 text-slate-700 hover:bg-red-50 hover:text-red-600 hover:border-red-100 text-sm font-bold rounded-xl transition-all active:scale-95">
                                    Decline
                                </button>
                            </form>
                        @elseif($status === 'confirmed')
                            <form action="{{ route('owner.bookings.complete', $booking->id) }}" method="POST">
                                @csrf @method('PATCH')
                                <button type="submit" class="px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-bold rounded-xl transition-all shadow-lg hover:shadow-emerald-600/20 active:scale-95">
                                    Mark as Completed
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Guest & Property Details --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                {{-- Guest Card --}}
                <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm">
                    <h3 class="text-xs font-black text-slate-400 uppercase tracking-widest mb-6 border-b border-slate-50 pb-4">Guest Information</h3>
                    <div class="flex items-center gap-4 mb-6">
                        <div class="w-14 h-14 rounded-full bg-slate-900 flex items-center justify-center text-lg font-bold text-white uppercase">
                            {{ substr($booking->user->name ?? 'G', 0, 1) }}
                        </div>
                        <div>
                            <p class="font-bold text-slate-900">{{ $booking->user->name ?? 'N/A' }}</p>
                            <p class="text-sm text-slate-500">{{ $booking->user->email ?? 'N/A' }}</p>
                        </div>
                    </div>
                    <div class="p-4 bg-slate-50 rounded-2xl flex items-center justify-between">
                        <span class="text-xs font-bold text-slate-500">Phone Number</span>
                        <span class="text-sm font-black text-slate-900">{{ $booking->user->phone ?? 'Not Provided' }}</span>
                    </div>
                </div>

                {{-- Property Card --}}
                <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm">
                    <h3 class="text-xs font-black text-slate-400 uppercase tracking-widest mb-6 border-b border-slate-50 pb-4">Property Detail</h3>
                    <div class="flex items-center gap-4 mb-6">
                        <div class="w-14 h-14 rounded-2xl overflow-hidden">
                            <img src="{{ $booking->farm->images->first()->image_path ?? 'https://via.placeholder.com/150' }}" class="w-full h-full object-cover" alt="Property">
                        </div>
                        <div>
                            <p class="font-bold text-slate-900">{{ $booking->farm->name ?? 'N/A' }}</p>
                            <span class="inline-block px-2 py-0.5 bg-slate-100 text-slate-600 text-[10px] font-black rounded uppercase tracking-tighter">
                                {{ $booking->event_type ?? 'Stay' }}
                            </span>
                        </div>
                    </div>
                    <div class="p-4 bg-slate-50 rounded-2xl flex items-center justify-between">
                        <span class="text-xs font-bold text-slate-500">Location</span>
                        <span class="text-sm font-black text-slate-900">{{ $booking->farm->governorate ?? 'N/A' }}</span>
                    </div>
                </div>
            </div>

            {{-- Timing & Scheduling --}}
            <div class="bg-white rounded-3xl p-8 border border-slate-100 shadow-sm">
                <h3 class="text-xs font-black text-slate-400 uppercase tracking-widest mb-8 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Reservation Timeline
                </h3>
                <div class="flex flex-col md:flex-row gap-12">
                    <div class="flex-1 border-l-4 border-emerald-500 pl-6 py-2">
                        <p class="text-xs font-bold text-slate-400 uppercase mb-1">Check In</p>
                        <p class="text-xl font-extrabold text-slate-900">{{ \Carbon\Carbon::parse($booking->start_time)->format('M d, Y') }}</p>
                        <p class="text-sm font-bold text-emerald-600">{{ \Carbon\Carbon::parse($booking->start_time)->format('h:i A') }}</p>
                    </div>
                    <div class="flex-1 border-l-4 border-slate-200 pl-6 py-2">
                        <p class="text-xs font-bold text-slate-400 uppercase mb-1">Check Out</p>
                        <p class="text-xl font-extrabold text-slate-900">{{ \Carbon\Carbon::parse($booking->end_time)->format('M d, Y') }}</p>
                        <p class="text-sm font-bold text-slate-500">{{ \Carbon\Carbon::parse($booking->end_time)->format('h:i A') }}</p>
                    </div>
                </div>
            </div>
        </div>

{{-- Right Column: Financials & Extras --}}
        <div class="space-y-8">
            {{-- Financial Summary --}}
            <div class="bg-slate-900 rounded-3xl p-8 text-white shadow-2xl relative overflow-hidden group">
                <div class="absolute top-0 right-0 w-32 h-32 bg-emerald-500/10 rounded-full -mr-16 -mt-16 blur-2xl group-hover:bg-emerald-500/20 transition-all duration-700"></div>

                <h3 class="text-xs font-black text-emerald-400 uppercase tracking-widest mb-8">Financial Summary</h3>

                {{-- Updated Financial Breakdown --}}
                <div class="space-y-6 mb-8">
                    <div class="flex justify-between items-center text-slate-400">
                        <span class="text-sm font-bold">Gross Price</span>
                        <span class="font-black text-white">{{ number_format($booking->total_price - $booking->tax_amount, 2) }} <small class="text-[10px] uppercase">JOD</small></span>
                    </div>
                    <div class="flex justify-between items-center text-slate-400">
                        <span class="text-sm font-bold">Tax (16%)</span>
                        <span class="font-black text-slate-300">+{{ number_format($booking->tax_amount, 2) }}</span>
                    </div>
                    <div class="flex justify-between items-center text-slate-400 pt-4 border-t border-slate-800">
                        <span class="text-sm font-bold">Total Guest Paid</span>
                        <span class="font-black text-white">{{ number_format($booking->total_price, 2) }}</span>
                    </div>
                    <div class="flex justify-between items-center text-slate-400">
                        <span class="text-sm font-bold">Platform Fee</span>
                        <span class="font-black text-red-400">-{{ number_format($booking->commission_amount, 2) }}</span>
                    </div>
                    <div class="pt-6 border-t border-slate-800 flex justify-between items-center">
                        <span class="text-sm font-black text-emerald-400 uppercase tracking-wider">Your Earnings</span>
                        <span class="text-3xl font-black text-white">{{ number_format($booking->net_owner_amount, 2) }} <small class="text-xs uppercase">JOD</small></span>
                    </div>
                </div>

                <div class="p-4 bg-emerald-500/10 rounded-2xl border border-emerald-500/20 text-center">
                    <p class="text-[10px] font-black text-emerald-400 uppercase tracking-widest mb-1">Payment Status</p>
                    <p class="text-sm font-bold">{{ strtoupper($booking->payment_status ?? 'Pending') }}</p>
                </div>
            </div>

            {{-- Notes / Internal Actions --}}
            <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm">
                <h3 class="text-xs font-black text-slate-400 uppercase tracking-widest mb-6 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Helpful Tip
                </h3>
                <p class="text-sm text-slate-600 leading-relaxed font-medium">
                    Once the guest checks out, remember to mark the booking as <span class="text-slate-900 font-bold">Completed</span> to release the funds to your available balance.
                </p>
            </div>
        </div>
    </div>
</x-owner-layout>
