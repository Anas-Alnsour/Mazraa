@extends('layouts.transport')

@section('title', 'Add New Driver')

@section('content')
<style>
    .fade-in-up { animation: fadeInUp 0.8s cubic-bezier(0.2, 0.8, 0.2, 1) forwards; opacity: 0; }
    @keyframes fadeInUp { 0% { opacity: 0; transform: translateY(30px); } 100% { opacity: 1; transform: translateY(0); } }

    .transport-input {
        background: rgba(2, 6, 23, 0.6) !important;
        border: 1px solid rgba(51, 65, 85, 0.8) !important;
        color: #f8fafc !important;
        transition: all 0.3s ease !important;
        border-radius: 1rem !important;
        box-shadow: inset 0 2px 4px rgba(0,0,0,0.3) !important;
    }
    .transport-input:focus {
        background: rgba(15, 23, 42, 1) !important;
        border-color: #0891b2 !important;
        box-shadow: 0 0 0 2px rgba(8, 145, 178, 0.2), inset 0 2px 4px rgba(0,0,0,0.3) !important;
        outline: none !important;
    }
    .transport-input::placeholder { color: #64748b !important; font-weight: 500; }

    input:-webkit-autofill, input:-webkit-autofill:hover, input:-webkit-autofill:focus, input:-webkit-autofill:active {
        -webkit-box-shadow: 0 0 0 30px #020617 inset !important;
        -webkit-text-fill-color: white !important;
        transition: background-color 5000s ease-in-out 0s;
    }

    .dark-select {
        appearance: none;
        background-color: rgba(2, 6, 23, 0.6) !important;
        border: 1px solid rgba(51, 65, 85, 0.8) !important;
        color: #fff !important;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%230891b2' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M5 7l5 5 5-5'/%3e%3c/svg%3e");
        background-position: right 1rem center;
        background-repeat: no-repeat;
        background-size: 1.5em 1.5em;
        border-radius: 1rem !important;
        transition: all 0.3s ease;
    }
    .dark-select:focus { border-color: #0891b2 !important; box-shadow: 0 0 0 2px rgba(8, 145, 178, 0.2) !important; outline: none; }
    .dark-select option { background-color: #0f172a; color: #fff; }
</style>

<div class="max-w-[96%] xl:max-w-4xl mx-auto space-y-10 pb-24 animate-god-in fade-in-up">

    {{-- 🌟 Header Section --}}
    <div class="relative overflow-hidden flex flex-col md:flex-row md:items-center justify-between gap-6 bg-slate-900/80 p-8 md:p-10 rounded-[3rem] border border-slate-800 backdrop-blur-2xl shadow-2xl">
        <div class="absolute -right-20 -top-20 w-80 h-80 bg-cyan-500/10 blur-[100px] rounded-full pointer-events-none"></div>

        <div class="relative z-10">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-slate-950/50 border border-slate-700 text-[9px] font-black uppercase tracking-widest mb-3 shadow-inner text-slate-400">
                <span class="w-1.5 h-1.5 rounded-full bg-cyan-500 animate-pulse"></span> Network Expansion
            </div>
            <h1 class="text-4xl md:text-5xl font-black text-white tracking-tighter mb-2 leading-none">Register <span class="bg-clip-text text-transparent bg-gradient-to-r from-cyan-400 to-blue-400">Operative</span></h1>
            <p class="text-slate-400 font-bold uppercase tracking-widest text-[10px] mt-3">Register a fleet member to your transport network.</p>
        </div>

        <a href="{{ route('transport.drivers.index') }}" class="relative z-10 px-6 py-4 bg-slate-950 hover:bg-slate-800 text-slate-400 hover:text-cyan-400 font-black text-[10px] uppercase tracking-[0.2em] rounded-2xl transition-all border border-slate-800 hover:border-cyan-500/30 flex items-center justify-center gap-3 shadow-inner active:scale-95 group">
            <svg class="w-5 h-5 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Return to Fleet
        </a>
    </div>

    {{-- Error Handling --}}
    @if ($errors->any())
        <div class="bg-rose-500/10 border border-rose-500/30 rounded-[2rem] p-6 shadow-inner backdrop-blur-md">
            <div class="flex items-start gap-4">
                <div class="w-10 h-10 rounded-full bg-rose-500/20 flex items-center justify-center shrink-0 border border-rose-500/30 text-rose-400">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                </div>
                <div class="flex-1">
                    <h3 class="text-xs font-black text-rose-400 uppercase tracking-widest mb-2">Registration Failed</h3>
                    <ul class="list-disc pl-5 font-bold text-sm text-slate-300 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    {{-- 🌟 Main Form --}}
    <div class="bg-slate-900/60 p-8 md:p-12 rounded-[3rem] border border-slate-800 backdrop-blur-xl shadow-2xl relative overflow-hidden">
        <form action="{{ route('transport.drivers.store') }}" method="POST" class="space-y-10 relative z-10">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                {{-- Identity Section --}}
                <div class="md:col-span-2 space-y-2">
                    <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest ml-2">Operative Full Name <span class="text-cyan-500">*</span></label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-600 group-focus-within:text-cyan-400 transition-colors">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        </div>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" required
                            placeholder="e.g. Ahmad Ali"
                            class="w-full transport-input pl-12 py-4 font-bold text-sm">
                    </div>
                </div>

                {{-- Contact Info --}}
                <div class="space-y-2">
                    <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest ml-2">Email Address <span class="text-cyan-500">*</span></label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-600 group-focus-within:text-cyan-400 transition-colors">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                        </div>
                        <input type="email" name="email" id="email" value="{{ old('email') }}" required
                            placeholder="driver@network.com"
                            class="w-full transport-input pl-12 py-4 font-bold font-mono text-sm">
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest ml-2">Phone Number <span class="text-cyan-500">*</span></label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-600 group-focus-within:text-cyan-400 transition-colors">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                        </div>
                        <input type="text" name="phone" id="phone" value="{{ old('phone') }}" required
                            placeholder="07xxxxxxxx"
                            class="w-full transport-input pl-12 py-4 font-bold font-mono tracking-widest text-sm">
                    </div>
                </div>

                {{-- Security Protocol --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 md:col-span-2 p-6 md:p-8 bg-slate-950/50 rounded-[2rem] border border-slate-800 shadow-inner">
                    <div class="md:col-span-2 inline-flex items-center gap-2 px-3 py-1.5 rounded-lg bg-[#020617] border border-slate-800 text-[9px] font-black text-rose-400 uppercase tracking-widest w-max mb-2">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                        Security Protocol
                    </div>

                    <div class="space-y-2">
                        <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest ml-2">Access Password <span class="text-rose-500">*</span></label>
                        <input type="password" name="password" id="password" required
                            class="w-full transport-input px-5 py-4 font-bold tracking-widest focus:border-rose-500 focus:ring-rose-500">
                    </div>

                    <div class="space-y-2">
                        <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest ml-2">Verify Password <span class="text-rose-500">*</span></label>
                        <input type="password" name="password_confirmation" id="password_confirmation" required
                            class="w-full transport-input px-5 py-4 font-bold tracking-widest focus:border-rose-500 focus:ring-rose-500">
                    </div>
                </div>

                {{-- Logistics Assignment --}}
                <div class="md:col-span-2 pt-6 border-t border-slate-800 space-y-2">
                    <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest ml-2 flex items-center gap-2">
                        Working Shift
                        <span class="text-[8px] bg-slate-800 text-slate-400 px-2 py-0.5 rounded uppercase tracking-widest border border-slate-700">Dispatch Routing</span>
                    </label>
                    <select name="shift" id="shift" required class="w-full dark-select px-5 py-4 font-bold text-sm">
                        <option value="morning" {{ old('shift') == 'morning' ? 'selected' : '' }}>☀️ Morning Shift (08:00 AM - 05:00 PM)</option>
                        <option value="evening" {{ old('shift') == 'evening' ? 'selected' : '' }}>🌙 Evening Shift (07:00 PM - 06:00 AM)</option>
                        <option value="full_day" {{ old('shift') == 'full_day' ? 'selected' : '' }}>🕒 Full Day Availability</option>
                    </select>
                </div>

                <div class="md:col-span-2 space-y-2">
                    <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest ml-2">Primary Operating Region (Governorate)</label>
                    <select name="governorate" id="governorate" required class="w-full dark-select px-5 py-4 font-bold text-sm">
                        <option value="" disabled selected>Select a region...</option>
                        @foreach(config('mazraa.governorates') as $gov)
                            <option value="{{ $gov }}" {{ old('governorate') == $gov ? 'selected' : '' }}>{{ $gov }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="md:col-span-2 space-y-2">
                    <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest ml-2">Assigned Vehicle <span class="text-slate-400 normal-case">(Permanent 1-to-1 Pairing)</span></label>
                    @if($availableVehicles->count() > 0)
                        <select name="transport_vehicle_id" id="transport_vehicle_id" class="w-full dark-select px-5 py-4 font-bold text-sm text-cyan-400">
                            <option value="">No vehicle assigned yet</option>
                            @foreach($availableVehicles as $vehicle)
                                <option value="{{ $vehicle->id }}" {{ old('transport_vehicle_id') == $vehicle->id ? 'selected' : '' }}>
                                    {{ $vehicle->type }} &mdash; {{ $vehicle->license_plate }} ({{ $vehicle->capacity }} seats)
                                </option>
                            @endforeach
                        </select>
                    @else
                        <div class="px-5 py-4 bg-amber-500/10 text-amber-400 rounded-2xl text-sm font-bold border border-amber-500/20">
                            All vehicles are currently assigned. <a href="{{ route('transport.vehicles.create') }}" class="underline text-amber-300">Add a new vehicle</a> first.
                        </div>
                    @endif
                    <p class="text-[9px] text-slate-500 font-bold uppercase tracking-widest mt-2 ml-1">Dispatcher will auto-assign this vehicle — no manual pickup needed.</p>
                </div>
            </div>

            {{-- Action Buttons --}}
            <div class="pt-8 mt-8 flex flex-col md:flex-row justify-end gap-5 border-t border-slate-800">
                <a href="{{ route('transport.drivers.index') }}" class="w-full md:w-auto py-4 px-12 bg-slate-950 text-slate-400 hover:text-white border border-slate-800 hover:border-slate-600 text-[11px] font-black uppercase tracking-[0.2em] rounded-2xl shadow-inner transition-all active:scale-95 flex items-center justify-center text-center">
                    Cancel Operation
                </a>
                <button type="submit" class="w-full md:w-auto py-4 px-12 bg-gradient-to-r from-cyan-600 to-blue-500 hover:to-blue-400 text-slate-950 text-[11px] font-black uppercase tracking-[0.2em] rounded-2xl shadow-[0_10px_20px_rgba(8,145,178,0.3)] transition-all active:scale-95 flex items-center justify-center gap-3">
                    Initialize Driver
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
