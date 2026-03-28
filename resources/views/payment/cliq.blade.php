@extends('layouts.app')

@section('title', 'CliQ Payment')

@section('content')
<div class="bg-[#f8fafc] min-h-screen pt-24 pb-12 font-sans flex items-center justify-center">
    <div class="max-w-md w-full px-4 fade-in-up">

        {{-- CliQ Payment Card --}}
        <div class="bg-white rounded-[2rem] shadow-2xl border border-purple-100 overflow-hidden relative">

            {{-- Header --}}
            <div class="bg-gradient-to-r from-purple-700 to-purple-900 p-8 text-center relative">
                <div class="absolute top-0 left-0 w-full h-full bg-white/5 pattern-dots"></div>
                <div class="relative z-10 flex flex-col items-center">
                    <div class="bg-white text-purple-800 font-black italic tracking-widest text-2xl px-4 py-2 rounded-xl mb-4 shadow-lg">
                        CliQ
                    </div>
                    <p class="text-purple-100 text-sm font-medium">Secure E-Wallet Payment</p>
                </div>
            </div>

            {{-- Body --}}
            <div class="p-8">
                <div class="flex justify-between items-center mb-6 pb-6 border-b border-gray-100">
                    <span class="text-gray-500 font-bold text-sm">Amount to Pay</span>
                    <span class="text-3xl font-black text-gray-900">{{ number_format($booking->total_price, 2) }} <span class="text-sm">JOD</span></span>
                </div>

                <div class="bg-purple-50 border border-purple-100 p-4 rounded-xl mb-6">
                    <p class="text-xs text-purple-800 font-bold mb-1">Transfer Instructions:</p>
                    <p class="text-[11px] text-purple-600">Please ensure you transfer the exact amount to our official CliQ Alias: <strong class="bg-white px-2 py-0.5 rounded text-purple-900">MAZRAA</strong></p>
                </div>

                <form action="{{ route('payment.confirm.cliq', $booking->id) }}" method="POST">
                    @csrf

                    <div class="mb-6">
                        <label class="block text-xs font-black text-gray-600 uppercase tracking-widest mb-2">Your CliQ Alias / Mobile Number</label>
                        <div class="relative">
                            <input type="text" name="cliq_alias" required placeholder="e.g. 0791234567 or YOUR_NAME"
                                class="w-full bg-gray-50 border border-gray-200 rounded-xl py-4 px-5 text-sm font-bold text-gray-900 focus:ring-4 focus:ring-purple-500/20 focus:border-purple-500 transition-all outline-none">
                            <div class="absolute inset-y-0 right-0 flex items-center px-4 text-purple-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                            </div>
                        </div>
                        <p class="text-[10px] text-gray-400 mt-2 font-medium">We need this to verify your transfer.</p>
                    </div>

<button type="submit" class="w-full bg-[#7e22ce] hover:bg-[#581c87] text-white font-black uppercase tracking-widest text-sm py-4 rounded-xl shadow-[0_10px_20px_rgba(126,34,206,0.3)] transition-all transform hover:-translate-y-0.5 active:translate-y-0 flex justify-center items-center gap-2">
    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
    Confirm Payment
</button>
                </form>

                <div class="mt-6 text-center">
                    <a href="{{ route('payment.select', $booking->id) }}" class="text-xs font-bold text-gray-400 hover:text-gray-600 transition-colors">
                        ← Back to Payment Methods
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .fade-in-up { animation: fadeInUp 0.6s cubic-bezier(0.2, 0.8, 0.2, 1) both; }
    @keyframes fadeInUp {
        0% { opacity: 0; transform: translateY(20px); }
        100% { opacity: 1; transform: translateY(0); }
    }
    .pattern-dots { background-image: radial-gradient(currentColor 1px, transparent 1px); background-size: 20px 20px; }
</style>
@endsection
