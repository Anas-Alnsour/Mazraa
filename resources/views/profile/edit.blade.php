@extends('layouts.app')

@section('title', 'Account Settings')

@section('content')

<style>
    .settings-card {
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.5);
    }
    input:focus {
        border-color: #1d5c42 !important;
        box-shadow: 0 0 0 4px rgba(29, 92, 66, 0.1) !important;
    }
</style>

<div class="bg-[#f8fafc] min-h-screen pb-24 font-sans selection:bg-[#1d5c42] selection:text-white">

    {{-- ==========================================
         1. SETTINGS HEADER
         ========================================== --}}
    <div class="relative w-full h-[35vh] min-h-[300px] flex items-center justify-center bg-[#020617] overflow-hidden">
        <img src="{{ asset('backgrounds/home.JPG') }}" alt="Settings Background"
             class="absolute inset-0 w-full h-full object-cover opacity-20 animate-[pulse_20s_ease-in-out_infinite]">

        <div class="absolute inset-0 bg-gradient-to-b from-[#020617]/95 via-[#020617]/60 to-[#f8fafc]"></div>

        <div class="relative z-10 text-center px-4 max-w-4xl mx-auto">
            <h1 class="text-4xl md:text-6xl font-black text-white tracking-tighter mb-4">
                Account <span class="text-[#c2a265]">Settings</span>
            </h1>
            <p class="text-base text-gray-400 font-medium max-w-xl mx-auto">
                Update your personal information and keep your account secure.
            </p>
        </div>
    </div>

    {{-- ==========================================
         2. SETTINGS CONTENT
         ========================================== --}}
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 relative z-30 -mt-16">

        <div class="space-y-8">

            {{-- Update Profile Information --}}
            <div class="settings-card rounded-[2.5rem] p-8 md:p-12 shadow-[0_10px_40px_rgba(0,0,0,0.03)]">
                <div class="flex items-center gap-4 mb-10">
                    <div class="w-12 h-12 bg-green-50 text-[#1d5c42] rounded-2xl flex items-center justify-center shadow-sm">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    </div>
                    <div>
                        <h2 class="text-2xl font-black text-gray-900 tracking-tight">Profile Information</h2>
                        <p class="text-sm text-gray-500 font-medium">Update your account's profile information and email address.</p>
                    </div>
                </div>

                @include('profile.partials.update-profile-information-form')
            </div>

            {{-- Update Password --}}
            <div class="settings-card rounded-[2.5rem] p-8 md:p-12 shadow-[0_10px_40px_rgba(0,0,0,0.03)]">
                <div class="flex items-center gap-4 mb-10">
                    <div class="w-12 h-12 bg-amber-50 text-amber-600 rounded-2xl flex items-center justify-center shadow-sm">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                    </div>
                    <div>
                        <h2 class="text-2xl font-black text-gray-900 tracking-tight">Security Update</h2>
                        <p class="text-sm text-gray-500 font-medium">Ensure your account is using a long, random password to stay secure.</p>
                    </div>
                </div>

                @include('profile.partials.update-password-form')
            </div>

            {{-- Delete Account --}}
            <div class="bg-red-50/50 border border-red-100 rounded-[2.5rem] p-8 md:p-12 transition-all hover:bg-red-50">
                <div class="flex items-center gap-4 mb-10">
                    <div class="w-12 h-12 bg-white text-red-500 rounded-2xl flex items-center justify-center shadow-sm border border-red-100">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                    </div>
                    <div>
                        <h2 class="text-2xl font-black text-red-600 tracking-tight">Danger Zone</h2>
                        <p class="text-sm text-red-500/70 font-medium">Once your account is deleted, all of its resources and data will be permanently deleted.</p>
                    </div>
                </div>

                @include('profile.partials.delete-user-form')
            </div>

        </div>
    </div>
</div>
@endsection
