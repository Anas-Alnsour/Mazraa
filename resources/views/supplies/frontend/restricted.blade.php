@extends('layouts.app')

@section('title', 'Marketplace Access Restricted')

@section('content')
<style>
    .fade-in-up { animation: fadeInUp 0.8s cubic-bezier(0.2, 0.8, 0.2, 1) forwards; opacity: 0; }
    @keyframes fadeInUp { 0% { opacity: 0; transform: translateY(30px); } 100% { opacity: 1; transform: translateY(0); } }
</style>

<div class="min-h-screen bg-[#020617] flex items-center justify-center py-20 px-4 relative overflow-hidden selection:bg-rose-500/30 selection:text-rose-200">

    <div class="absolute top-1/4 left-1/4 w-96 h-96 bg-rose-500/10 rounded-full blur-[120px] pointer-events-none"></div>
    <div class="absolute bottom-1/4 right-1/4 w-96 h-96 bg-indigo-500/10 rounded-full blur-[120px] pointer-events-none"></div>

    <div class="max-w-2xl w-full relative z-10 fade-in-up">
        <div class="bg-slate-900/80 backdrop-blur-2xl border border-slate-800/80 rounded-[3rem] p-10 md:p-16 shadow-[0_0_50px_rgba(0,0,0,0.5)] text-center relative overflow-hidden group hover:border-rose-500/30 transition-colors duration-700">

            <div class="inline-flex items-center justify-center w-24 h-24 rounded-[2rem] bg-rose-500/10 border border-rose-500/20 text-rose-500 mb-8 shadow-inner group-hover:scale-110 transition-transform duration-500">
                <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8V7a4 4 0 00-8 0v4h8z"></path></svg>
            </div>

            @if(isset($no_company) && $no_company)
                <h1 class="text-3xl md:text-4xl font-black text-white tracking-tighter mb-4">Region Not Serviced</h1>
                <p class="text-slate-400 font-medium leading-relaxed mb-10 max-w-lg mx-auto">
                    Your booked farm is located in <span class="text-indigo-400 font-bold">{{ $governorate }}</span>. We currently do not have an active supply branch in this region. We are expanding rapidly!
                </p>
            @else
                <h1 class="text-3xl md:text-4xl font-black text-white tracking-tighter mb-4">Access <span class="text-transparent bg-clip-text bg-gradient-to-r from-rose-400 to-orange-400">Restricted</span></h1>
                <p class="text-slate-400 font-medium leading-relaxed mb-10 max-w-lg mx-auto">
                    The exclusive Mazraa Marketplace is reserved for guests with active farm reservations. Book a luxury escape first to unlock local supplies, premium meats, and delivery directly to your farm.
                </p>
            @endif

            <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                <a href="{{ route('explore') }}" class="w-full sm:w-auto px-8 py-4 bg-gradient-to-r from-indigo-600 to-cyan-600 hover:from-indigo-500 hover:to-cyan-500 text-white font-black text-xs uppercase tracking-widest rounded-2xl transition-all shadow-[0_0_20px_rgba(99,102,241,0.3)] active:scale-95">
                    Explore Farms
                </a>
                <a href="{{ route('dashboard') }}" class="w-full sm:w-auto px-8 py-4 bg-slate-950 text-slate-400 hover:text-white font-black text-xs uppercase tracking-widest rounded-2xl transition-all border border-slate-800 hover:border-slate-600 active:scale-95">
                    My Account
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
