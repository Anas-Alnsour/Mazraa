@extends('layouts.app')

@section('title', 'Select Payment Method - Supplies')

@section('content')
<div class="bg-[#f8fafc] min-h-screen pt-24 pb-12 font-sans selection:bg-[#1d5c42] selection:text-white">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 fade-in-up">

        {{-- Header --}}
        <div class="text-center mb-10">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-[1.5rem] bg-[#1d5c42]/10 text-[#1d5c42] mb-6 border border-[#1d5c42]/20 shadow-sm">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
            </div>
            <h1 class="text-3xl md:text-4xl font-black text-gray-900 tracking-tight mb-3">Complete Your Order</h1>
            <p class="text-gray-500 font-medium">Choose your preferred payment method for Invoice <strong class="text-[#1d5c42] uppercase tracking-widest">{{ $order_id }}</strong></p>
        </div>

        {{-- Payment Methods Card --}}
        <div class="bg-white rounded-[2.5rem] p-8 md:p-10 shadow-[0_30px_60px_rgba(0,0,0,0.08)] border border-gray-100 relative overflow-hidden">

            {{-- Total Amount Display --}}
            <div class="bg-gray-50 rounded-2xl p-6 mb-8 border border-gray-200 flex justify-between items-center shadow-inner">
                <div>
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Total Amount Due</p>
                    <p class="text-3xl font-black text-gray-900">{{ number_format($totalPrice, 2) }} <span class="text-sm">JOD</span></p>
                </div>
                <div class="bg-[#1d5c42]/10 p-3 rounded-xl text-[#1d5c42] border border-[#1d5c42]/20">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                </div>
            </div>

            <p class="text-xs font-black text-gray-800 uppercase tracking-widest mb-4">Select Payment Method</p>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                {{-- Option 1: Credit Card / Visa (Stripe) --}}
                <form action="{{ route('payment.supply.checkout', $order_id) }}" method="GET" class="w-full">
                    @csrf
                    <button type="submit" class="w-full text-left group border-2 border-gray-200 hover:border-[#1d5c42] bg-white p-6 rounded-2xl transition-all duration-300 hover:shadow-md relative overflow-hidden">
                        <div class="flex items-center gap-4 mb-2">
                            <div class="bg-blue-50 p-3 rounded-lg text-blue-600">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                            </div>
                            <span class="text-lg font-black text-gray-900 group-hover:text-[#1d5c42] transition-colors">Credit Card</span>
                        </div>
                        <p class="text-xs text-gray-500 font-medium">Pay securely with Visa or Mastercard.</p>
                    </button>
                </form>

                {{-- Option 2: CliQ / E-Wallet --}}
                <form action="{{ route('payment.supply.cliq', $order_id) }}" method="GET" class="w-full">
                    @csrf
                    <button type="submit" class="w-full text-left group border-2 border-gray-200 hover:border-purple-500 bg-white p-6 rounded-2xl transition-all duration-300 hover:shadow-md relative overflow-hidden">
                        <div class="flex items-center gap-4 mb-2">
                            <div class="bg-purple-50 p-3 rounded-lg text-purple-600 font-black italic tracking-widest text-sm">
                                CliQ
                            </div>
                            <span class="text-lg font-black text-gray-900 group-hover:text-purple-600 transition-colors">CliQ / Wallet</span>
                        </div>
                        <p class="text-xs text-gray-500 font-medium">Instant transfer using your mobile wallet.</p>
                    </button>
                </form>
            </div>

            <div class="mt-8 text-center pt-6 border-t border-gray-100">
                <a href="{{ route('cart.view') }}" class="text-[10px] font-black uppercase tracking-widest text-gray-400 hover:text-red-500 transition-colors">
                    ← Cancel and return to Cart
                </a>
            </div>

        </div>
    </div>
</div>

<style>
    .fade-in-up { animation: fadeInUp 0.8s cubic-bezier(0.2, 0.8, 0.2, 1) both; }
    @keyframes fadeInUp {
        0% { opacity: 0; transform: translateY(20px); }
        100% { opacity: 1; transform: translateY(0); }
    }
</style>
@endsection
