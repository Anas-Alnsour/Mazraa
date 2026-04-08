@extends('layouts.app')

@section('content')
<div class="min-h-[70vh] flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 bg-slate-900 overflow-hidden relative">
    {{-- Background Glow --}}
    <div class="absolute top-0 left-0 w-96 h-96 bg-emerald-600/10 rounded-full blur-[120px] -translate-x-1/2 -translate-y-1/2"></div>
    <div class="absolute bottom-0 right-0 w-96 h-96 bg-indigo-600/10 rounded-full blur-[120px] translate-x-1/2 translate-y-1/2"></div>

    <div class="max-w-md w-full space-y-8 relative z-10">
        <div class="text-center">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-emerald-500/10 border border-emerald-500/20 mb-6 shadow-xl">
                <svg class="w-8 h-8 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <h2 class="text-3xl font-black text-white tracking-tight">Market <span class="text-emerald-400">CliQ</span> Payment</h2>
            <p class="mt-2 text-slate-400 font-medium">Complete your supply order via CliQ transfer.</p>
        </div>

        <div class="bg-slate-800/50 backdrop-blur-xl rounded-3xl border border-slate-700/50 shadow-2xl overflow-hidden p-8">
            {{-- Order Summary --}}
            <div class="flex justify-between items-center mb-8 pb-6 border-b border-slate-700/50">
                <div>
                    <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1">Invoice ID</p>
                    <p class="text-white font-bold">#{{ $order_id }}</p>
                </div>
                <div class="text-right">
                    <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1">Total Amount</p>
                    <p class="text-2xl font-black text-emerald-400">{{ number_format($totalPrice, 2) }} <span class="text-xs">JOD</span></p>
                </div>
            </div>

            {{-- Instructions --}}
            <div class="bg-emerald-500/5 border border-emerald-500/10 rounded-2xl p-6 mb-8">
                <div class="flex items-start gap-4">
                    <div class="bg-emerald-500/20 p-2 rounded-lg">
                        <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs font-black text-emerald-400 uppercase tracking-widest mb-2">Instructions</p>
                        <p class="text-sm text-slate-300 leading-relaxed">
                            Please transfer the total amount above to the following CliQ Alias:
                        </p>
                        <div class="mt-3 flex items-center justify-between bg-slate-900/50 px-4 py-3 rounded-xl border border-slate-700/50 group cursor-pointer" onclick="navigator.clipboard.writeText('MazraaPay')">
                            <span class="text-lg font-black text-white tracking-wider">MazraaPay</span>
                            <svg class="w-4 h-4 text-slate-500 group-hover:text-emerald-400 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Confirmation Form --}}
            <form action="{{ route('payment.supply.confirm_cliq', $order_id) }}" method="POST" class="space-y-6">
                @csrf
                <div>
                    <label for="cliq_alias" class="block text-xs font-black text-slate-500 uppercase tracking-widest mb-2 px-1">Your CliQ Alias / Phone</label>
                    <input type="text" name="cliq_alias" id="cliq_alias" required
                        class="w-full bg-slate-900/50 border border-slate-700 rounded-2xl px-5 py-4 text-white placeholder-slate-600 focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-500 outline-none transition-all shadow-inner"
                        placeholder="e.g. 079XXXXXXX">
                    <p class="mt-2 text-[10px] text-slate-500 italic px-1">We'll use this to verify your transfer.</p>
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-500 text-white font-black py-4 px-6 rounded-2xl shadow-xl shadow-emerald-900/20 transform transition-all active:scale-95 group flex items-center justify-center gap-2">
                        <span>Confirm Payment</span>
                        <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </button>
                    <p class="text-center mt-4">
                        <a href="{{ route('cart.view') }}" class="text-xs font-bold text-slate-500 hover:text-slate-300 transition-colors">Cancel Payment</a>
                    </p>
                </div>
            </form>
        </div>

        {{-- Footer Note --}}
        <p class="text-center text-slate-500 text-[10px] font-bold uppercase tracking-[0.2em]">Secure Transaction • 256-bit SSL</p>
    </div>
</div>
@endsection
