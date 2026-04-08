<x-app-layout>
    <x-slot name="header">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">My Supply Orders</h1>
            <p class="text-sm text-gray-500 mt-1">Track your deliveries and manage recent orders.</p>
        </div>
    </x-slot>

    <style>
        /* Smooth Fade In Stagger */
        .fade-in-up { animation: fadeInUp 0.8s cubic-bezier(0.2, 0.8, 0.2, 1) both; }
        .fade-in-up-stagger { animation: fadeInUp 0.6s cubic-bezier(0.2, 0.8, 0.2, 1) both; }
        @keyframes fadeInUp {
            0% { opacity: 0; transform: translateY(20px); }
            100% { opacity: 1; transform: translateY(0); }
        }
    </style>

    <div class="bg-gray-50 min-h-screen py-12 fade-in-up" x-data="{
    reviewModalOpen: false,
    reviewableId: null,
    reviewableType: 'supply',
    rating: 0,
    hoverRating: 0,
    comment: '',
    itemName: '',
    openReviewModal(id, name) {
        if (!id) return;
        this.reviewableId = id;
        this.itemName = name;
        this.rating = 0;
        this.hoverRating = 0;
        this.comment = '';
        this.reviewModalOpen = true;
    },
    closeReviewModal() {
        this.reviewModalOpen = false;
    }
}">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- 🌟 Flash Messages --}}
            @if(session('success'))
                <div class="mb-8 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl p-4 flex items-center gap-3 shadow-sm fade-in-up">
                    <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span class="font-bold text-sm">{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-8 bg-red-50 border border-red-200 text-red-800 rounded-2xl p-4 flex items-center gap-3 shadow-sm fade-in-up">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span class="font-bold text-sm">{{ session('error') }}</span>
                </div>
            @endif

            @if($groupedOrders->isEmpty())
                {{-- 🌟 Empty State --}}
                <div class="bg-white rounded-[3rem] shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-gray-100 p-12 text-center fade-in-up delay-100">
                    <div class="w-24 h-24 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-6 border border-gray-100 shadow-inner">
                        <svg class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900 mb-2">No active orders found</h2>
                    <p class="text-gray-500 font-medium mb-8">You haven't placed any supply orders yet.</p>
                    <a href="{{ route('supplies.market') }}" class="inline-flex px-8 py-3.5 bg-[#1d5c42] hover:bg-[#154531] text-white text-sm font-black uppercase tracking-widest rounded-xl transition-all shadow-lg shadow-[#1d5c42]/20">
                        Browse Marketplace
                    </a>
                </div>
            @else
                <div class="space-y-10">
                    @foreach($groupedOrders as $index => $invoiceItems)
                        @php
                            $firstItem = $invoiceItems->first();
                            $invoiceId = $firstItem->order_id ?? $firstItem->id;
                            $invoiceStatus = $firstItem->status;
                            $invoiceTotal = $invoiceItems->sum('total_price');
                            $orderDate = $firstItem->created_at;

                            // 💡 الـ Business Logic للتتبع
                            $isPreparing = in_array($invoiceStatus, ['pending', 'processing', 'accepted', 'ready', 'waiting_driver', 'in_way', 'delivered']);
                            $isWaitingDriver = in_array($invoiceStatus, ['accepted', 'ready', 'waiting_driver', 'in_way', 'delivered']);
                            $isInWay = in_array($invoiceStatus, ['in_way', 'delivered']);
                            $isDelivered = in_array($invoiceStatus, ['delivered']);

                            $steps = [
                                ['title' => 'Preparing', 'sub' => 'يتم التجهيز بالمتجر', 'active' => $isPreparing],
                                ['title' => 'Waiting Driver', 'sub' => 'بانتظار السائق', 'active' => $isWaitingDriver],
                                ['title' => 'On the Way', 'sub' => 'في الطريق للمزرعة', 'active' => $isInWay],
                                ['title' => 'Delivered', 'sub' => 'وصل الطلب', 'active' => $isDelivered],
                            ];

                            $activeCount = count(array_filter(array_column($steps, 'active')));
                            $progressPercentage = $activeCount > 0 ? (($activeCount - 1) / (count($steps) - 1)) * 100 : 0;
                        @endphp

                        <div class="bg-white rounded-[3rem] shadow-[0_20px_50px_rgba(0,0,0,0.03)] border border-gray-100 overflow-hidden relative fade-in-up-stagger" style="animation-delay: {{ (float)$index * 0.1 }}s;">

                            {{-- 📦 Invoice Header & Tracker --}}
                            <div class="bg-gray-50/50 p-8 lg:p-10 flex flex-col lg:flex-row lg:items-center justify-between gap-10 border-b border-gray-100">
                                <div>
                                    <div class="flex items-center gap-4 mb-3">
                                        <span class="text-3xl font-black text-gray-900 tracking-tighter">{{ $invoiceId }}</span>
                                        <span class="text-[10px] font-black text-[#1d5c42] bg-[#1d5c42]/10 border border-[#1d5c42]/20 px-3 py-1.5 rounded-lg uppercase tracking-widest">{{ $invoiceItems->count() }} items</span>
                                    </div>
                                    <p class="text-xs font-bold text-gray-400 uppercase tracking-widest flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                        {{ $orderDate->format('M d, Y - h:i A') }}
                                    </p>
                                    <p class="text-xs font-medium text-gray-500 mt-2">Delivering to: <span class="font-bold text-gray-700">{{ $firstItem->booking->farm->name ?? 'Unknown Farm' }}</span></p>
                                </div>

                                <div class="w-full lg:w-3/5">
                                    @if(in_array($invoiceStatus, ['cancelled', 'rejected', 'failed']))
                                        <div class="w-full text-center py-4 relative z-10 bg-red-50 backdrop-blur-sm rounded-2xl border border-red-100">
                                            <span class="inline-flex items-center gap-2 text-[11px] font-black uppercase tracking-widest text-red-600">
                                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
                                                Order Cancelled
                                            </span>
                                        </div>
                                    @elseif($invoiceStatus === 'pending_payment' || $invoiceStatus === 'pending_verification')
                                        <div class="w-full text-center py-4 relative z-10 bg-amber-50 backdrop-blur-sm rounded-2xl border border-amber-100">
                                            <span class="inline-flex items-center gap-2 text-[11px] font-black uppercase tracking-widest text-amber-600">
                                                <div class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></div>
                                                Awaiting Payment Verification
                                            </span>
                                        </div>
                                    @else
                                        <div class="relative flex items-center justify-between pt-6">
                                            {{-- Background Line --}}
                                            <div class="absolute left-0 top-10 w-full h-1.5 bg-gray-200 rounded-full z-0"></div>
                                            {{-- Progress Line (Green) --}}
                                            <div class="absolute left-0 top-10 h-1.5 bg-[#1d5c42] rounded-full z-0 transition-all duration-1000 ease-out" style="width: {{ $progressPercentage }}%;"></div>

                                            @foreach($steps as $step)
                                                <div class="relative z-10 flex flex-col items-center gap-3">
                                                    <div class="h-10 w-10 rounded-full flex items-center justify-center border-4 transition-all duration-500 bg-white {{ $step['active'] ? 'border-[#1d5c42] text-[#1d5c42] shadow-[0_0_15px_rgba(29,92,66,0.3)]' : 'border-gray-200 text-transparent' }}">
                                                        <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                                    </div>
                                                    <div class="text-center bg-gray-50/80 px-2 rounded backdrop-blur-sm">
                                                        <span class="block text-[9px] md:text-[10px] font-black uppercase tracking-widest {{ $step['active'] ? 'text-gray-900' : 'text-gray-400' }}">{{ $step['title'] }}</span>
                                                        <span class="block text-[8px] font-bold text-[#c2a265] mt-0.5">{{ $step['sub'] }}</span>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            </div>

                            {{-- 🚚 Driver Info Section --}}
                            @if(in_array($invoiceStatus, ['waiting_driver', 'in_way', 'delivered']) && $firstItem->driver)
                                <div class="bg-[#1d5c42]/5 border-b border-[#1d5c42]/10 px-10 py-6 flex flex-col sm:flex-row sm:items-center justify-between gap-5">
                                    <div class="flex items-center gap-5">
                                        <div class="h-14 w-14 rounded-2xl bg-white shadow-sm flex items-center justify-center text-[#1d5c42] border border-[#1d5c42]/20">
                                            <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" /></svg>
                                        </div>
                                        <div>
                                            <p class="text-[10px] font-black text-gray-500 uppercase tracking-widest mb-1">Driver Assigned</p>
                                            <p class="text-base font-black text-gray-900">{{ $firstItem->driver->name }}</p>
                                        </div>
                                    </div>
                                    @if($firstItem->driver->phone)
                                        <a href="tel:{{ $firstItem->driver->phone }}" class="inline-flex items-center gap-2 bg-white border border-gray-200 text-gray-700 hover:text-[#1d5c42] hover:border-[#1d5c42]/50 px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all shadow-sm active:scale-95">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" /></svg>
                                            Call Driver
                                        </a>
                                    @endif
                                </div>
                            @endif

                            {{-- 🛒 Products List --}}
                            <div class="p-8 lg:p-10">
                                <ul class="space-y-6">
                                    @foreach($invoiceItems as $item)
                                        <li class="flex flex-col sm:flex-row sm:items-center justify-between gap-6 p-4 rounded-2xl hover:bg-gray-50/50 transition-colors border border-transparent hover:border-gray-100">
                                            <div class="flex items-center gap-6">
                                                <div class="h-20 w-20 rounded-2xl bg-gray-50 border border-gray-100 overflow-hidden flex-shrink-0 shadow-sm relative group">
                                                    @if($item->supply && $item->supply->image)
                                                        <img src="{{ Storage::url($item->supply->image) }}" class="h-full w-full object-cover transform group-hover:scale-110 transition-transform duration-500">
                                                    @else
                                                        <div class="h-full w-full flex items-center justify-center text-gray-300">
                                                            <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div>
                                                    @if($item->supply && $item->supply->category)
                                                        <span class="text-[8px] font-black text-[#c2a265] uppercase tracking-widest block mb-1">{{ $item->supply->category }}</span>
                                                    @endif
                                                    <h4 class="text-lg font-black text-gray-900">{{ $item->supply->name ?? 'Product Unavailable' }}</h4>
                                                    <p class="text-[10px] font-bold text-gray-400 mt-1 uppercase tracking-widest flex items-center gap-2">
                                                        <span>Qty: <strong class="text-gray-700">{{ $item->quantity }}</strong></span>
                                                        <span class="text-gray-300">|</span>
                                                        <span class="flex items-center gap-1">
                                                            <svg class="w-3 h-3 text-[#c2a265]" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                                            By {{ $item->supply->company->name ?? 'Vendor' }}
                                                        </span>
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="flex items-center justify-between w-full sm:w-auto sm:justify-end gap-6 ml-20 sm:ml-0 mt-4 sm:mt-0">
                                                <div class="text-left sm:text-right flex flex-col sm:items-end">
                                                    <span class="text-2xl font-black text-[#1d5c42] tracking-tighter">{{ number_format($item->total_price, 2) }}</span>
                                                    <span class="text-[9px] text-gray-400 uppercase tracking-widest font-black">JOD Total</span>
                                                </div>

                                                @if($invoiceStatus === 'pending' && $item->canBeModifiedOrCancelled())
                                                    <div class="flex items-center gap-2 border-l border-gray-100 pl-6">
                                                        <a href="{{ route('supplies.edit_order', $item->id) }}" class="p-2 bg-gray-50 hover:bg-gray-100 text-gray-600 rounded-lg border border-gray-200 transition-colors" title="Edit Quantity">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                                        </a>
                                                        <form action="{{ route('supplies.destroy_order', $item->id) }}" method="POST" onsubmit="return confirm('Cancel this specific item?');">
                                                            @csrf @method('DELETE')
                                                            <button type="submit" class="p-2 bg-red-50 hover:bg-red-100 text-red-600 rounded-lg border border-red-200 transition-colors" title="Cancel Item">
                                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                            </button>
                                                        </form>
                                                    </div>
                                                @elseif($invoiceStatus === 'delivered')
                                                    <div class="flex items-center gap-2 border-l border-gray-100 pl-6">
                                                        <button type="button" @click="openReviewModal({{ $item->supply->id ?? 'null' }}, '{{ addslashes($item->supply->name ?? 'Product') }}')"
                                                            class="bg-emerald-50 text-emerald-600 hover:bg-emerald-100 hover:border-emerald-300 border border-emerald-100 px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest transition-colors flex items-center gap-1.5 shadow-sm active:scale-95">
                                                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                                            Rate Item
                                                        </button>
                                                    </div>
                                                @endif
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>

                                <div class="mt-8 pt-8 border-t border-gray-100 flex flex-col sm:flex-row justify-between items-center gap-4 bg-gray-50/50 p-6 rounded-3xl">
                                    @if($invoiceStatus === 'pending_payment')
                                        <a href="{{ route('payment.select_supply', $invoiceId) }}" class="px-6 py-2.5 bg-gradient-to-r from-[#1d5c42] to-[#154531] text-white text-xs font-black uppercase tracking-widest rounded-xl hover:shadow-lg hover:-translate-y-0.5 transition-all">
                                            Resume Payment
                                        </a>
                                    @else
                                        <span class="text-[10px] font-black text-gray-500 uppercase tracking-widest">Grand Invoice Total</span>
                                    @endif
                                    <span class="text-3xl font-black text-[#1d5c42] tracking-tighter">{{ number_format($invoiceTotal, 2) }} <span class="text-xs font-bold text-gray-400 uppercase tracking-widest ml-1">JOD</span></span>
                                </div>
                            </div>

                            {{-- 💡 10 Minute Cancellation Logic Enforced Frontend (For whole invoice) --}}
                            @if(!in_array($invoiceStatus, ['cancelled', 'delivered', 'pending_payment', 'pending_verification']))
                                <div class="px-8 py-5 bg-gray-50 border-t border-gray-100 flex justify-end items-center gap-4">
                                    @if($firstItem->canBeModifiedOrCancelled())
                                        <span class="text-[10px] font-black text-amber-500 flex items-center gap-2 uppercase tracking-widest">
                                            <svg class="h-4 w-4 animate-pulse" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            {{ 10 - now()->diffInMinutes($orderDate) }} min left to modify
                                        </span>
                                    @else
                                        <span class="text-[10px] font-black text-gray-400 flex items-center gap-2 uppercase tracking-widest">
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                            </svg>
                                            Modification window closed
                                        </span>
                                    @endif
                                </div>
                            @endif

                        </div>
                    @endforeach
                </div>
            @endif

        </div>

        {{-- 💡 Write a Review Modal (AlpineJS) --}}
        <div x-show="reviewModalOpen" style="display: none;" class="fixed inset-0 z-[100] overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="reviewModalOpen" x-transition.opacity.duration.300ms class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" @click="closeReviewModal"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div x-show="reviewModalOpen"
                     x-transition:enter="ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave="ease-in duration-200"
                     x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     class="inline-block align-bottom bg-white rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md w-full border border-gray-100">

                    <form action="{{ route('reviews.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="reviewable_id" x-model="reviewableId">
                        <input type="hidden" name="reviewable_type" x-model="reviewableType">
                        <input type="hidden" name="rating" x-model="rating">

                        <div class="px-6 py-5 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                            <h3 class="text-lg font-black text-gray-900 flex items-center gap-2">
                                <svg class="w-5 h-5 text-[#1d5c42]" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                Rate this Item
                            </h3>
                            <button type="button" @click="closeReviewModal" class="text-gray-400 hover:text-gray-600 bg-white rounded-full p-1 shadow-sm border border-gray-200 focus:outline-none transition-colors">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                        </div>

                        <div class="p-6 space-y-6">
                            <div class="text-center">
                                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">How did you like</p>
                                <h4 class="text-xl font-bold text-gray-900" x-text="itemName"></h4>
                            </div>

                            {{-- Dynamic Stars --}}
                            <div class="flex justify-center items-center gap-1 cursor-pointer" @mouseleave="hoverRating = 0">
                                <template x-for="star in 5">
                                    <svg
                                        @click="rating = star"
                                        @mouseenter="hoverRating = star"
                                        :class="{'text-amber-400': (hoverRating || rating) >= star, 'text-gray-200': (hoverRating || rating) < star}"
                                        class="h-10 w-10 fill-current transition-colors transform hover:scale-110 drop-shadow-sm"
                                        viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                </template>
                            </div>
                            <p class="text-xs text-center font-bold" :class="rating > 0 ? 'text-[#1d5c42]' : 'text-transparent'" x-text="rating + ' out of 5 Stars'"></p>

                            <div>
                                <label class="block text-xs font-black text-gray-700 uppercase tracking-widest mb-2">Comment (Optional)</label>
                                <textarea name="comment" x-model="comment" rows="3" placeholder="Tell us what you loved..."
                                    class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-[#1d5c42] focus:border-[#1d5c42] transition-colors resize-none text-sm shadow-inner"></textarea>
                            </div>
                        </div>

                        <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex gap-3">
                            <button type="submit" :disabled="rating === 0" :class="rating === 0 ? 'opacity-50 cursor-not-allowed' : 'hover:bg-[#154230] shadow-lg shadow-[#1d5c42]/30 active:scale-95'" class="w-full px-6 py-3.5 rounded-xl bg-[#1d5c42] text-white font-black text-xs tracking-widest uppercase transition-all transform focus:outline-none">
                                Submit Review
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
