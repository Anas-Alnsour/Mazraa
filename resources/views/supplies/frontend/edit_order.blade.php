<x-app-layout>
    <x-slot name="header">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center gap-4">
                <a href="{{ route('supplies.my_orders') }}" class="p-2 text-gray-400 hover:bg-gray-100 hover:text-gray-900 rounded-full transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                </a>
                <div>
                    <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Modify Order Item</h1>
                    <p class="text-sm font-bold text-amber-600 mt-1">Changes are only allowed within 10 minutes of ordering.</p>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="bg-gray-50 min-h-screen py-12 fade-in-up">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">

            @if(session('error'))
                <div class="mb-8 bg-red-50 border border-red-200 text-red-800 rounded-2xl p-4 flex items-center gap-3 shadow-sm">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span class="font-bold text-sm">{{ session('error') }}</span>
                </div>
            @endif

            <form action="{{ route('supplies.update_order', $order->id) }}" method="POST"
                  x-data="{
                      qty: {{ $order->quantity }},
                      price: {{ $order->supply->price }},
                      maxStock: {{ $order->supply->stock + $order->quantity }},
                      increment() { if(this.qty < this.maxStock) this.qty++; },
                      decrement() { if(this.qty > 1) this.qty--; }
                  }"
                  class="bg-white rounded-[3rem] shadow-[0_20px_50px_rgba(0,0,0,0.03)] border border-gray-100 overflow-hidden">
                @csrf
                @method('PUT')

                <div class="p-10">
                    <div class="flex flex-col sm:flex-row gap-8 items-start sm:items-center">

                        <div class="flex items-center gap-6 flex-1">
                            @if($order->supply->image)
                                <img src="{{ asset('storage/' . $order->supply->image) }}" alt="{{ $order->supply->name }}" class="w-24 h-24 rounded-2xl object-cover border border-gray-100 shadow-sm">
                            @else
                                <div class="w-24 h-24 rounded-2xl bg-gray-100 flex items-center justify-center text-gray-400">
                                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                </div>
                            @endif
                            <div>
                                <span class="block text-[10px] font-black text-[#1d5c42] uppercase tracking-widest mb-1">{{ $order->supply->category }}</span>
                                <h3 class="text-2xl font-black text-gray-900">{{ $order->supply->name }}</h3>
                                <p class="text-sm font-bold text-gray-400 mt-1">{{ number_format($order->supply->price, 2) }} JOD per unit</p>
                            </div>
                        </div>

                        <div>
                            <label class="block text-[10px] font-bold text-gray-500 mb-3 uppercase tracking-widest">Adjust Quantity</label>
                            <div class="flex items-center gap-4 bg-gray-50 p-2 rounded-2xl border border-gray-200 w-max shadow-inner">
                                <button type="button" @click="decrement" class="w-12 h-12 flex items-center justify-center rounded-xl bg-white text-gray-600 shadow-sm hover:bg-gray-100 font-black transition-colors">-</button>
                                <input type="number" name="quantity" x-model="qty" readonly class="w-14 text-center bg-transparent border-none focus:ring-0 text-xl font-black text-gray-900 p-0 pointer-events-none">
                                <button type="button" @click="increment" class="w-12 h-12 flex items-center justify-center rounded-xl bg-white text-gray-600 shadow-sm hover:bg-gray-100 font-black transition-colors">+</button>
                            </div>
                            <p class="text-[10px] font-medium text-gray-400 mt-3 text-center">Max available: <span x-text="maxStock" class="font-bold text-gray-700"></span></p>
                        </div>
                    </div>

                    <div class="mt-10 bg-[#1d5c42]/5 rounded-3xl p-8 border border-[#1d5c42]/10 flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
                        <div>
                            <span class="block text-xs font-black text-[#1d5c42] uppercase tracking-widest mb-1">New Total</span>
                            <span class="text-4xl font-black text-[#1d5c42]" x-text="(qty * price).toFixed(2) + ' JOD'"></span>
                        </div>
                        <p class="text-[10px] text-gray-500 font-bold max-w-xs leading-relaxed uppercase tracking-widest">If the new total is lower, you will be refunded the difference automatically.</p>
                    </div>
                </div>

                <div class="px-10 py-6 bg-gray-50 border-t border-gray-100 flex flex-col sm:flex-row gap-4">
                    <a href="{{ route('supplies.my_orders') }}" class="flex-1 text-center px-6 py-4 rounded-2xl border border-gray-200 bg-white text-gray-700 font-black text-[10px] sm:text-xs uppercase tracking-widest hover:bg-gray-50 transition-colors transform active:scale-95 shadow-sm">Cancel</a>
                    <button type="submit" class="flex-1 px-6 py-4 rounded-2xl bg-[#1d5c42] text-white font-black text-[10px] sm:text-xs tracking-widest uppercase transition-all hover:bg-[#154531] shadow-lg shadow-[#1d5c42]/30 transform active:scale-95">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
    <style>
        .fade-in-up { animation: fadeInUp 0.8s cubic-bezier(0.2, 0.8, 0.2, 1) both; }
        @keyframes fadeInUp {
            0% { opacity: 0; transform: translateY(20px); }
            100% { opacity: 1; transform: translateY(0); }
        }
    </style>
</x-app-layout>
