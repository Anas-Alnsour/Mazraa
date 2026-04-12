@extends('layouts.transport')

@section('title', 'Deploy New Vehicle')

@section('content')
<style>
    .fade-in-up { animation: fadeInUp 0.8s cubic-bezier(0.2, 0.8, 0.2, 1) forwards; opacity: 0; }
    @keyframes fadeInUp { 0% { opacity: 0; transform: translateY(30px); } 100% { opacity: 1; transform: translateY(0); } }

    /* God Mode Inputs */
    .transport-input {
        background: rgba(2, 6, 23, 0.6) !important;
        border: 1px solid rgba(51, 65, 85, 0.8) !important;
        color: #f8fafc !important;
        transition: all 0.3s ease !important;
        border-radius: 1.25rem !important;
        box-shadow: inset 0 2px 4px rgba(0,0,0,0.3) !important;
    }
    .transport-input:focus {
        background: rgba(15, 23, 42, 1) !important;
        border-color: #06b6d4 !important;
        box-shadow: 0 0 0 2px rgba(6, 182, 212, 0.2), inset 0 2px 4px rgba(0,0,0,0.3) !important;
        outline: none !important;
    }
    .transport-input::placeholder { color: #475569 !important; font-weight: 500; font-size: 11px; text-transform: uppercase; letter-spacing: 0.1em; }

    /* Custom Input Autofill Dark Mode Fix */
    input:-webkit-autofill, input:-webkit-autofill:hover, input:-webkit-autofill:focus, input:-webkit-autofill:active {
        -webkit-box-shadow: 0 0 0 30px #020617 inset !important;
        -webkit-text-fill-color: white !important;
        transition: background-color 5000s ease-in-out 0s;
    }

    /* God Mode Select */
    .dark-select {
        appearance: none;
        background-color: rgba(2, 6, 23, 0.6) !important;
        border: 1px solid rgba(51, 65, 85, 0.8) !important;
        color: #fff !important;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%2306b6d4' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M5 7l5 5 5-5'/%3e%3c/svg%3e");
        background-position: right 1.25rem center;
        background-repeat: no-repeat;
        background-size: 1.5em 1.5em;
        border-radius: 1.25rem !important;
        transition: all 0.3s ease;
    }
    .dark-select:focus { border-color: #06b6d4 !important; box-shadow: 0 0 0 2px rgba(6, 182, 212, 0.2) !important; outline: none; }
    .dark-select option { background-color: #0f172a; color: #fff; }

    /* Radio Buttons */
    .status-radio:checked + label { border-color: #06b6d4; background-color: rgba(6, 182, 212, 0.1); }
</style>

<div class="max-w-[96%] xl:max-w-5xl mx-auto space-y-10 pb-24 animate-god-in fade-in-up">

    {{-- 🌟 Header Section --}}
    <div class="relative overflow-hidden flex flex-col md:flex-row md:items-center justify-between gap-6 bg-slate-900/80 p-8 md:p-10 rounded-[3rem] border border-slate-800 backdrop-blur-2xl shadow-2xl">
        <div class="absolute -right-20 -top-20 w-80 h-80 bg-cyan-500/10 blur-[100px] rounded-full pointer-events-none"></div>
        <div class="absolute -left-20 -bottom-20 w-64 h-64 bg-blue-500/10 blur-[80px] rounded-full pointer-events-none"></div>

        <div class="relative z-10">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-slate-950/50 border border-slate-700 text-[9px] font-black uppercase tracking-widest mb-3 shadow-inner text-slate-400">
                <span class="w-1.5 h-1.5 rounded-full bg-cyan-500 animate-pulse"></span> Fleet Expansion
            </div>
            <h1 class="text-4xl md:text-5xl font-black text-white tracking-tighter mb-2 leading-none">Deploy New <span class="bg-clip-text text-transparent bg-gradient-to-r from-cyan-400 to-blue-400">Vehicle</span></h1>
            <p class="text-slate-400 font-bold uppercase tracking-widest text-[10px] mt-3">Register a new asset to your transport network.</p>
        </div>

        <a href="{{ route('transport.vehicles.index') }}" class="relative z-10 px-6 py-4 bg-slate-950 hover:bg-slate-800 text-slate-400 hover:text-cyan-400 font-black text-[10px] uppercase tracking-[0.2em] rounded-2xl transition-all border border-slate-800 hover:border-cyan-500/30 flex items-center justify-center gap-3 shadow-inner active:scale-95 group">
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
                        @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    {{-- 🌟 Main Form --}}
    <div class="bg-slate-900/60 p-8 md:p-12 rounded-[3rem] border border-slate-800 backdrop-blur-xl shadow-2xl relative overflow-hidden">
        <form action="{{ route('transport.vehicles.store') }}" method="POST" class="space-y-10 relative z-10">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                {{-- Vehicle Type --}}
                <div class="md:col-span-2 space-y-2">
                    <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest ml-2">Vehicle Type <span class="text-cyan-500">*</span></label>
                    <select name="type" id="type" required class="w-full dark-select px-6 py-5 font-bold text-sm">
                        <option value="" disabled selected>Select vehicle classification...</option>
                        <option value="Mini Van (7 Seats)" {{ old('type') == 'Mini Van (7 Seats)' ? 'selected' : '' }}>Mini Van (7 Seats)</option>
                        <option value="Van (12 Seats)" {{ old('type') == 'Van (12 Seats)' ? 'selected' : '' }}>Van (12 Seats)</option>
                        <option value="Coaster Bus (22 Seats)" {{ old('type') == 'Coaster Bus (22 Seats)' ? 'selected' : '' }}>Coaster Bus (22 Seats)</option>
                        <option value="Large Bus (50 Seats)" {{ old('type') == 'Large Bus (50 Seats)' ? 'selected' : '' }}>Large Bus (50 Seats)</option>
                        <option value="SUV / 4x4" {{ old('type') == 'SUV / 4x4' ? 'selected' : '' }}>SUV / 4x4</option>
                        <option value="Sedan (VIP)" {{ old('type') == 'Sedan (VIP)' ? 'selected' : '' }}>Sedan (VIP)</option>
                    </select>
                </div>

                {{-- License Plate --}}
                <div class="space-y-2">
                    <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest ml-2">License Plate Number <span class="text-cyan-500">*</span></label>
                    <input type="text" name="license_plate" id="license_plate" value="{{ old('license_plate') }}" required
                        placeholder="e.g. 10-12345"
                        class="w-full transport-input px-6 py-5 font-black uppercase font-mono tracking-widest text-sm placeholder:normal-case">
                </div>

                {{-- Capacity --}}
                <div class="space-y-2">
                    <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest ml-2">Passenger Capacity <span class="text-cyan-500">*</span></label>
                    <div class="relative">
                        <input type="number" name="capacity" id="capacity" min="1" value="{{ old('capacity') }}" required
                            placeholder="e.g. 12"
                            class="w-full transport-input pl-6 pr-14 py-5 font-bold text-sm">
                        <div class="absolute inset-y-0 right-0 flex items-center pr-6 pointer-events-none text-slate-500 font-black text-[10px] uppercase tracking-widest">PAX</div>
                    </div>
                </div>

                {{-- Driver Assignment --}}
                <div class="md:col-span-2 pt-6 border-t border-slate-800 space-y-2">
                    <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest ml-2 flex items-center gap-2">Assign Driver (Optional)</label>
                    <select name="driver_id" id="driver_id" class="w-full dark-select px-6 py-5 font-bold text-sm">
                        <option value="">-- No Driver Assigned Yet --</option>
                        @foreach($drivers ?? [] as $driver)
                            <option value="{{ $driver->id }}" {{ old('driver_id') == $driver->id ? 'selected' : '' }}>
                                {{ $driver->name }} (ID: {{ $driver->id }})
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- 🌟 Status Matrix --}}
            <div class="pt-8 mt-8 border-t border-slate-800">
                <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-4 ml-2">Initial Status Matrix</label>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="relative">
                        <input type="radio" name="status" id="status_available" value="available" class="status-radio sr-only" {{ old('status', 'available') === 'available' ? 'checked' : '' }}>
                        <label for="status_available" class="flex flex-col items-center justify-center p-6 border border-slate-700 bg-slate-950 rounded-[1.5rem] cursor-pointer transition-all hover:border-emerald-500/50 group shadow-inner">
                            <span class="w-3 h-3 rounded-full bg-emerald-500 mb-3 shadow-[0_0_10px_#10b981] group-hover:scale-125 transition-transform"></span>
                            <span class="text-[11px] font-black text-slate-400 uppercase tracking-widest group-hover:text-white">Available</span>
                        </label>
                    </div>
                    <div class="relative">
                        <input type="radio" name="status" id="status_maintenance" value="maintenance" class="status-radio sr-only" {{ old('status') === 'maintenance' ? 'checked' : '' }}>
                        <label for="status_maintenance" class="flex flex-col items-center justify-center p-6 border border-slate-700 bg-slate-950 rounded-[1.5rem] cursor-pointer transition-all hover:border-amber-500/50 group shadow-inner">
                            <span class="w-3 h-3 rounded-full bg-amber-500 mb-3 shadow-[0_0_10px_#f59e0b] group-hover:scale-125 transition-transform"></span>
                            <span class="text-[11px] font-black text-slate-400 uppercase tracking-widest group-hover:text-white">Maintenance</span>
                        </label>
                    </div>
                    <div class="relative">
                        <input type="radio" name="status" id="status_booked" value="booked" class="status-radio sr-only" {{ old('status') === 'booked' ? 'checked' : '' }}>
                        <label for="status_booked" class="flex flex-col items-center justify-center p-6 border border-slate-700 bg-slate-950 rounded-[1.5rem] cursor-pointer transition-all hover:border-blue-500/50 group shadow-inner">
                            <span class="w-3 h-3 rounded-full bg-blue-500 mb-3 shadow-[0_0_10px_#3b82f6] group-hover:scale-125 transition-transform"></span>
                            <span class="text-[11px] font-black text-slate-400 uppercase tracking-widest group-hover:text-white">Booked</span>
                        </label>
                    </div>
                </div>
            </div>

            {{-- Action Buttons --}}
            <div class="pt-8 mt-8 flex flex-col md:flex-row justify-end gap-5 border-t border-slate-800">
                <a href="{{ route('transport.vehicles.index') }}" class="w-full md:w-auto py-5 px-12 bg-slate-950 text-slate-400 hover:text-white border border-slate-800 hover:border-slate-600 text-[11px] font-black uppercase tracking-[0.2em] rounded-2xl shadow-inner transition-all active:scale-95 flex items-center justify-center text-center">
                    Cancel Operation
                </a>
                <button type="submit" class="w-full md:w-auto py-5 px-12 bg-gradient-to-r from-cyan-600 to-blue-500 hover:to-blue-400 text-slate-950 text-[11px] font-black uppercase tracking-[0.2em] rounded-2xl shadow-[0_10px_20px_rgba(6,182,212,0.3)] transition-all active:scale-95 flex items-center justify-center gap-3 border border-cyan-400/50">
                    Initialize Vehicle
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
