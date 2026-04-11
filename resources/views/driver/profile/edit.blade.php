@extends('layouts.driver')

@section('content')
<div class="max-w-4xl mx-auto space-y-8 animate-fade-in-up">

    {{-- ==========================================
         HERO SECTION
         ========================================== --}}
    <div class="relative bg-gradient-to-r from-slate-800 to-slate-900 rounded-[2.5rem] p-8 md:p-12 text-white shadow-2xl border border-slate-700 overflow-hidden">
        <div class="absolute top-0 right-0 w-64 h-64 bg-white/5 rounded-full blur-3xl -mr-32 -mt-32 pointer-events-none"></div>

        <div class="relative z-10">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/5 border border-white/10 text-[10px] font-black uppercase tracking-widest mb-6 backdrop-blur-md">
                <span class="w-2 h-2 rounded-full {{ Auth::user()->role === 'supply_driver' ? 'bg-teal-400' : 'bg-amber-500' }} animate-pulse"></span>
                Driver Account
            </div>
            <h1 class="text-3xl md:text-5xl font-black tracking-tight mb-4">Profile <span class="text-slate-400">Settings</span></h1>
            <p class="text-slate-400 font-medium max-w-md leading-relaxed">Keep your driver profile updated and secure for smooth operations on the platform.</p>
        </div>
    </div>

    {{-- ==========================================
         SETTINGS CONTENT
         ========================================== --}}
    <div class="space-y-8">

        {{-- Profile Information Card --}}
        <div class="bg-slate-800/50 backdrop-blur-xl rounded-[2.5rem] p-8 md:p-12 border border-slate-700 shadow-xl">
            <div class="flex items-center gap-6 mb-12">
                <div class="w-14 h-14 bg-slate-700 rounded-2xl flex items-center justify-center border border-slate-600 shadow-inner">
                    <svg class="w-7 h-7 {{ Auth::user()->role === 'supply_driver' ? 'text-teal-400' : 'text-amber-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                </div>
                <div>
                    <h2 class="text-2xl font-black text-white tracking-tight">Personal Details</h2>
                    <p class="text-sm text-slate-400 font-medium">Update your name, email, and contact phone number.</p>
                </div>
            </div>

            <form method="post" action="{{ route('profile.update') }}" class="space-y-8">
                @csrf
                @method('patch')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest ml-4">Full Name</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                            class="w-full bg-slate-900/50 border-slate-700 text-white rounded-2xl px-6 py-4 focus:ring-4 {{ Auth::user()->role === 'supply_driver' ? 'focus:ring-teal-500/10 focus:border-teal-500' : 'focus:ring-amber-500/10 focus:border-amber-500' }} transition-all font-bold">
                    </div>

                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest ml-4">Phone Number</label>
                        <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" required
                            class="w-full bg-slate-900/50 border-slate-700 text-white rounded-2xl px-6 py-4 focus:ring-4 {{ Auth::user()->role === 'supply_driver' ? 'focus:ring-teal-500/10 focus:border-teal-500' : 'focus:ring-amber-500/10 focus:border-amber-500' }} transition-all font-bold">
                    </div>

                    <div class="md:col-span-2 space-y-2">
                        <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest ml-4">Email Address</label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                            class="w-full bg-slate-900/50 border-slate-700 text-white rounded-2xl px-6 py-4 focus:ring-4 {{ Auth::user()->role === 'supply_driver' ? 'focus:ring-teal-500/10 focus:border-teal-500' : 'focus:ring-amber-500/10 focus:border-amber-500' }} transition-all font-bold">
                    </div>
                </div>

                <div class="flex items-center gap-4 pt-4">
                    <button type="submit" class="px-8 py-4 {{ Auth::user()->role === 'supply_driver' ? 'bg-teal-600 hover:bg-teal-500 shadow-teal-900/20' : 'bg-amber-600 hover:bg-amber-500 shadow-amber-900/20' }} text-white text-xs font-black uppercase tracking-widest rounded-2xl transition-all shadow-lg active:scale-95">
                        Save Changes
                    </button>
                    @if (session('status') === 'profile-updated')
                        <p x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 2000)" class="text-sm font-bold text-emerald-400">Settings Saved ✓</p>
                    @endif
                </div>
            </form>
        </div>

        {{-- Security Card --}}
        <div class="bg-slate-800/50 backdrop-blur-xl rounded-[2.5rem] p-8 md:p-12 border border-slate-700 shadow-xl">
            <div class="flex items-center gap-6 mb-12">
                <div class="w-14 h-14 bg-slate-700 rounded-2xl flex items-center justify-center border border-slate-600 shadow-inner">
                    <svg class="w-7 h-7 text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                </div>
                <div>
                    <h2 class="text-2xl font-black text-white tracking-tight">Security Update</h2>
                    <p class="text-sm text-slate-400 font-medium">Protect your driver account with a strong, private password.</p>
                </div>
            </div>

            <form method="post" action="{{ route('password.update') }}" class="space-y-8">
                @csrf
                @method('put')

                <div class="space-y-8">
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest ml-4">Current Password</label>
                        <input type="password" name="current_password"
                            class="w-full bg-slate-900/50 border-slate-700 text-white rounded-2xl px-6 py-4 focus:ring-4 focus:ring-slate-500/10 focus:border-slate-500 transition-all font-bold">
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest ml-4">New Password</label>
                            <input type="password" name="password"
                                class="w-full bg-slate-900/50 border-slate-700 text-white rounded-2xl px-6 py-4 focus:ring-4 focus:ring-slate-500/10 focus:border-slate-500 transition-all font-bold">
                        </div>

                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest ml-4">Confirm New Password</label>
                            <input type="password" name="password_confirmation"
                                class="w-full bg-slate-900/50 border-slate-700 text-white rounded-2xl px-6 py-4 focus:ring-4 focus:ring-slate-500/10 focus:border-slate-500 transition-all font-bold">
                        </div>
                    </div>
                </div>

                <div class="pt-4">
                    <button type="submit" class="px-8 py-4 bg-slate-700 hover:bg-slate-600 text-white text-xs font-black uppercase tracking-widest rounded-2xl transition-all shadow-lg active:scale-95">
                        Update Security Credentials
                    </button>
                    @if (session('status') === 'password-updated')
                        <p x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 2000)" class="text-sm font-bold text-emerald-400 mt-4">Password Updated ✓</p>
                    @endif
                </div>
            </form>
        </div>

    </div>
</div>

<style>
    @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
    .animate-fade-in-up { animation: fadeInUp 0.6s ease-out forwards; }
</style>
@endsection
