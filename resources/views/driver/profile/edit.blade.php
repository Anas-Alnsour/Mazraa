@extends('layouts.driver')

@section('title', 'Driver Identity Hub')

@section('content')
<style>
    .fade-in-up { animation: fadeInUp 0.8s cubic-bezier(0.2, 0.8, 0.2, 1) forwards; opacity: 0; }
    @keyframes fadeInUp { 0% { opacity: 0; transform: translateY(20px); } 100% { opacity: 1; transform: translateY(0); } }

    /* Custom Input Autofill Dark Mode Fix */
    .driver-input {
        background: rgba(2, 6, 23, 0.6) !important;
        border: 1px solid rgba(51, 65, 85, 0.8) !important;
        color: #f8fafc !important;
        transition: all 0.3s ease !important;
        border-radius: 1rem !important;
    }
    .driver-input:focus {
        background: rgba(15, 23, 42, 1) !important;
        box-shadow: inset 0 2px 4px rgba(0,0,0,0.3) !important;
        outline: none !important;
    }
    input:-webkit-autofill, input:-webkit-autofill:hover, input:-webkit-autofill:focus, input:-webkit-autofill:active {
        -webkit-box-shadow: 0 0 0 30px #020617 inset !important;
        -webkit-text-fill-color: white !important;
        transition: background-color 5000s ease-in-out 0s;
    }
</style>

@php
    $isSupply = Auth::user()->role === 'supply_driver';
    $themeGradient = $isSupply ? 'from-teal-600 to-emerald-500' : 'from-amber-500 to-orange-500';
    $themeGlow = $isSupply ? 'rgba(20,184,166,0.15)' : 'rgba(245,158,11,0.15)';
    $themeText = $isSupply ? 'text-teal-400' : 'text-amber-400';
    $themeBgGlow = $isSupply ? 'bg-teal-500/10' : 'bg-amber-500/10';
    $themeFocus = $isSupply ? 'focus:border-teal-500 focus:ring-1 focus:ring-teal-500' : 'focus:border-amber-500 focus:ring-1 focus:ring-amber-500';
@endphp

<div class="max-w-4xl mx-auto space-y-10 pb-20 animate-fade-in-up">

    {{-- ==========================================
         HERO SECTION (GOD MODE)
         ========================================== --}}
    <div class="relative bg-slate-900/80 rounded-[2.5rem] p-8 md:p-12 border border-slate-800 shadow-2xl overflow-hidden backdrop-blur-xl">
        <div class="absolute top-0 right-0 w-80 h-80 {{ $themeBgGlow }} rounded-full blur-[100px] -mr-32 -mt-32 pointer-events-none"></div>

        <div class="relative z-10 flex flex-col md:flex-row md:items-center gap-6">
            <div class="w-16 h-16 rounded-[1.5rem] bg-slate-950 border border-slate-800 flex items-center justify-center {{ $themeText }} shadow-[0_0_20px_{{ $themeGlow }}] shrink-0">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
            </div>
            <div>
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-slate-950/50 border border-slate-700 text-[9px] font-black uppercase tracking-widest mb-3 shadow-inner text-slate-400">
                    <span class="w-2 h-2 rounded-full {{ $isSupply ? 'bg-teal-400' : 'bg-amber-500' }} animate-pulse"></span>
                    {{ $isSupply ? 'Supply Agent' : 'Transport Commander' }}
                </div>
                <h1 class="text-3xl md:text-4xl font-black tracking-tight mb-2 text-white">Profile <span class="text-transparent bg-clip-text bg-gradient-to-r {{ $themeGradient }}">Settings</span></h1>
                <p class="text-slate-400 font-bold uppercase tracking-widest text-[10px]">Secure your identity and routing protocols.</p>
            </div>
        </div>
    </div>

    {{-- ==========================================
         SETTINGS CONTENT
         ========================================== --}}
    <div class="space-y-8">

        {{-- Profile Information Card --}}
        <div class="bg-slate-900/60 backdrop-blur-xl rounded-[2.5rem] p-8 md:p-10 border border-slate-800 shadow-xl relative overflow-hidden">
            <div class="absolute -left-20 -bottom-20 w-64 h-64 {{ $themeBgGlow }} rounded-full blur-[80px] pointer-events-none"></div>

            <div class="flex items-center gap-5 mb-10 relative z-10 border-b border-slate-800 pb-6">
                <div class="w-12 h-12 bg-slate-950 rounded-xl flex items-center justify-center border border-slate-800 shadow-inner shrink-0">
                    <svg class="w-6 h-6 {{ $themeText }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                </div>
                <div>
                    <h2 class="text-xl font-black text-white tracking-tight">Personal Details</h2>
                    <p class="text-[10px] uppercase tracking-widest text-slate-500 mt-1">Synchronize your node info</p>
                </div>
            </div>

            <form method="post" action="{{ route('profile.update') }}" class="space-y-8 relative z-10">
                @csrf
                @method('patch')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="space-y-3">
                        <label class="text-[10px] font-black text-slate-500 uppercase tracking-[0.3em] ml-2">Assigned Name</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                            class="w-full driver-input px-5 py-4 font-bold {{ $themeFocus }}">
                        @error('name') <p class="mt-2 text-[10px] font-black uppercase tracking-widest text-rose-500 ml-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="space-y-3">
                        <label class="text-[10px] font-black text-slate-500 uppercase tracking-[0.3em] ml-2">Secure Line (Phone)</label>
                        <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" required
                            class="w-full driver-input px-5 py-4 font-bold font-mono tracking-widest {{ $themeFocus }}">
                        @error('phone') <p class="mt-2 text-[10px] font-black uppercase tracking-widest text-rose-500 ml-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="md:col-span-2 space-y-3">
                        <label class="text-[10px] font-black text-slate-500 uppercase tracking-[0.3em] ml-2">Digital Protocol (Email)</label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                            class="w-full driver-input px-5 py-4 font-bold font-mono {{ $themeFocus }}">
                        @error('email') <p class="mt-2 text-[10px] font-black uppercase tracking-widest text-rose-500 ml-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="flex items-center gap-6 pt-4 border-t border-slate-800">
                    <button type="submit" class="px-8 py-4 bg-gradient-to-r {{ $themeGradient }} text-slate-950 text-[10px] font-black uppercase tracking-[0.2em] rounded-xl shadow-[0_10px_20px_{{ $themeGlow }}] active:scale-95 transition-all">
                        Commit Metadata
                    </button>
                    @if (session('status') === 'profile-updated')
                        <p x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)"
                           class="text-[10px] font-black uppercase tracking-widest {{ $themeText }} flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                            Node Synchronized
                        </p>
                    @endif
                </div>
            </form>
        </div>

        {{-- Security Card --}}
        <div class="bg-slate-900/60 backdrop-blur-xl rounded-[2.5rem] p-8 md:p-10 border border-slate-800 shadow-xl relative overflow-hidden">
            <div class="absolute -right-20 -top-20 w-64 h-64 bg-rose-500/5 rounded-full blur-[80px] pointer-events-none"></div>

            <div class="flex items-center gap-6 mb-12 relative z-10 border-b border-slate-800 pb-6">
                <div class="w-12 h-12 bg-slate-950 rounded-xl flex items-center justify-center border border-slate-800 shadow-inner shrink-0 text-rose-400">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                </div>
                <div>
                    <h2 class="text-xl font-black text-white tracking-tight">Security Update</h2>
                    <p class="text-[10px] uppercase tracking-widest text-slate-500 mt-1">Rotate your authorization keys</p>
                </div>
            </div>

            <form method="post" action="{{ route('password.update') }}" class="space-y-8 relative z-10">
                @csrf
                @method('put')

                <div class="space-y-8">
                    <div class="space-y-3">
                        <label class="text-[10px] font-black text-slate-500 uppercase tracking-[0.3em] ml-2">Current Password</label>
                        <input type="password" name="current_password" class="w-full driver-input px-5 py-4 focus:border-rose-500 focus:ring-1 focus:ring-rose-500 tracking-widest">
                        @error('current_password', 'updatePassword') <p class="text-[9px] font-black uppercase tracking-widest text-rose-500 ml-2 mt-2">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="space-y-3">
                            <label class="text-[10px] font-black text-slate-500 uppercase tracking-[0.3em] ml-2">New password</label>
                            <input type="password" name="password" class="w-full driver-input px-5 py-4 focus:border-rose-500 focus:ring-1 focus:ring-rose-500 tracking-widest">
                            @error('password', 'updatePassword') <p class="text-[9px] font-black uppercase tracking-widest text-rose-500 ml-2 mt-2">{{ $message }}</p> @enderror
                        </div>

                        <div class="space-y-3">
                            <label class="text-[10px] font-black text-slate-500 uppercase tracking-[0.3em] ml-2">Verify New Password</label>
                            <input type="password" name="password_confirmation" class="w-full driver-input px-5 py-4 focus:border-rose-500 focus:ring-1 focus:ring-rose-500 tracking-widest">
                        </div>
                    </div>
                </div>

                <div class="pt-4 border-t border-slate-800 flex items-center gap-6">
                    <button type="submit" class="px-8 py-4 bg-slate-800 hover:bg-slate-700 text-white text-[10px] font-black uppercase tracking-[0.2em] rounded-xl shadow-xl transition-all active:scale-95 border border-slate-700">
                        Rotate Password
                    </button>
                    @if (session('status') === 'password-updated')
                        <p x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)"
                           class="text-[10px] font-black uppercase tracking-widest text-rose-400 flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                            Encryption Updated
                        </p>
                    @endif
                </div>
            </form>
        </div>

    </div>
</div>
@endsection
