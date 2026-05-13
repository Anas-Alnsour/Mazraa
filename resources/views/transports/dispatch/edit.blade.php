@extends('layouts.transport')

@section('title', 'Manage Dispatch')

@section('content')
<style>
    .fade-in-up { animation: fadeInUp 0.8s cubic-bezier(0.2, 0.8, 0.2, 1) forwards; opacity: 0; }
    @keyframes fadeInUp { 0% { opacity: 0; transform: translateY(30px); } 100% { opacity: 1; transform: translateY(0); } }

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
</style>

<div class="max-w-[96%] xl:max-w-5xl mx-auto space-y-10 pb-24 animate-god-in fade-in-up">

    {{-- 🌟 Header Section --}}
    <div class="relative overflow-hidden flex flex-col md:flex-row md:items-center justify-between gap-6 bg-slate-900/80 p-8 md:p-10 rounded-[3rem] border border-slate-800 backdrop-blur-2xl shadow-2xl">
        <div class="absolute -right-20 -top-20 w-80 h-80 bg-cyan-500/10 blur-[100px] rounded-full pointer-events-none"></div>
        <div class="absolute -left-20 -bottom-20 w-64 h-64 bg-blue-500/10 blur-[80px] rounded-full pointer-events-none"></div>

        <div class="relative z-10">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-slate-950/50 border border-slate-700 text-[9px] font-black uppercase tracking-widest mb-3 shadow-inner text-slate-400">
                <span class="w-1.5 h-1.5 rounded-full bg-cyan-500 animate-pulse"></span> Dispatch Node
            </div>
            <h1 class="text-4xl md:text-5xl font-black text-white tracking-tighter mb-2 leading-none">Manage <span class="bg-clip-text text-transparent bg-gradient-to-r from-cyan-400 to-blue-400">Dispatch</span></h1>
            <p class="text-slate-400 font-bold uppercase tracking-widest text-[10px] mt-3">Assign a driver to request <span class="text-cyan-400 font-mono">#{{ $job->id }}</span></p>
        </div>

        {{-- 💡 FIX: route name updated to transport.dashboard --}}
        <a href="{{ route('transport.dashboard') }}" class="relative z-10 px-6 py-4 bg-slate-950 hover:bg-slate-800 text-slate-400 hover:text-cyan-400 font-black text-[10px] uppercase tracking-[0.2em] rounded-2xl transition-all border border-slate-800 hover:border-cyan-500/30 flex items-center justify-center gap-3 shadow-inner active:scale-95 group">
            <svg class="w-5 h-5 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Return to Dashboard
        </a>
    </div>

    {{-- Error & Success Notifications --}}
    @if($errors->any())
        <div class="bg-rose-500/10 border border-rose-500/30 rounded-[2rem] p-6 shadow-inner backdrop-blur-md">
            <div class="flex items-start gap-4">
                <div class="w-10 h-10 rounded-full bg-rose-500/20 flex items-center justify-center shrink-0 border border-rose-500/30 text-rose-400">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                </div>
                <div class="flex-1">
                    <h3 class="text-xs font-black text-rose-400 uppercase tracking-widest mb-2">Assignment Interrupted</h3>
                    <ul class="list-disc pl-5 font-bold text-sm text-slate-300 space-y-1">
                        @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    @if(session('success'))
        <div class="bg-emerald-500/10 border border-emerald-500/30 rounded-[2rem] p-5 shadow-inner backdrop-blur-md flex items-center gap-4">
            <div class="w-10 h-10 rounded-full bg-emerald-500/20 flex items-center justify-center shrink-0 border border-emerald-500/30 text-emerald-400">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
            </div>
            <p class="text-[11px] font-black text-emerald-400 uppercase tracking-widest">{{ session('success') }}</p>
        </div>
    @endif

    {{-- 🌟 Main Requirements Card --}}
    <div class="bg-slate-900/60 p-8 md:p-12 rounded-[3rem] border border-slate-800 backdrop-blur-xl shadow-2xl relative overflow-hidden">
        <h2 class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-8 flex items-center gap-2 border-b border-slate-800 pb-4">
            <svg class="w-4 h-4 text-cyan-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            Trip Requirements
        </h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-10 relative z-10">

            {{-- Left Side: Customer & Value Info --}}
            <div class="space-y-8">
                <div>
                    <span class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-2">Customer Info</span>
                    <p class="text-xl font-black text-white">{{ $job->user->name ?? 'Guest User' }}</p>
                    <p class="text-xs font-bold text-cyan-400 mt-1 flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                        {{ $job->user->phone ?? 'No phone provided' }}
                    </p>
                </div>

                <div>
                    <span class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-2">Vehicle Requested</span>
                    <p class="text-lg font-black text-white">{{ $job->transport_type }}</p>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1.5">{{ $job->passengers_count ?? $job->passengers ?? 0 }} Passengers</p>
                </div>

                <div class="bg-[#020617] p-6 rounded-[2rem] border border-cyan-500/20 shadow-inner">
                    <span class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-2">Trip Value</span>
                    <p class="text-3xl font-black text-emerald-400">{{ number_format($job->price, 2) }} <span class="text-[10px] text-emerald-500 uppercase tracking-widest">JOD</span></p>
                    <p class="text-[9px] font-bold text-slate-500 uppercase tracking-widest mt-2 border-t border-slate-800 pt-2">Net Earnings: <span class="text-white">{{ number_format($job->net_company_amount, 2) }} JOD</span></p>
                </div>
            </div>

            {{-- Right Side: Route Map --}}
            <div class="flex flex-col justify-center">
                <div class="relative pl-8 border-l-2 border-dashed border-slate-700 space-y-10">
                    <div class="relative">
                        <div class="absolute -left-[39px] top-1 h-4 w-4 rounded-full bg-[#020617] border-[4px] border-cyan-500 shadow-[0_0_10px_rgba(6,182,212,0.5)]"></div>
                        <span class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1.5">Pickup Location</span>
                        <p class="text-sm font-bold text-white">{{ $job->pickup_location ?? $job->start_and_return_point }}</p>
                    </div>

                    <div class="relative">
                        <div class="absolute -left-[39px] top-1 h-4 w-4 rounded-full bg-[#020617] border-[4px] border-blue-500 shadow-[0_0_10px_rgba(59,130,246,0.5)]"></div>
                        <span class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1.5">Destination Farm</span>
                        <p class="text-sm font-bold text-white">{{ $job->farmBooking->farm->name ?? 'N/A' }}</p>
                        <p class="text-[10px] font-bold text-blue-400 uppercase tracking-widest mt-2 font-mono bg-slate-950 inline-block px-3 py-1.5 rounded-lg border border-slate-800 shadow-inner">{{ optional($job->pickup_time ?? $job->Farm_Arrival_Time)->format('M d, Y - h:i A') }}</p>
                    </div>
                </div>
            </div>
        </div>

        @if(isset($job->notes) && $job->notes)
            <div class="mt-10 p-6 bg-amber-500/10 rounded-[2rem] border border-amber-500/30 flex gap-4 shadow-inner">
                <svg class="h-6 w-6 text-amber-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <div>
                    <span class="block text-[10px] font-black text-amber-500 uppercase tracking-widest mb-1.5">Customer Notes</span>
                    <p class="text-sm font-bold text-slate-300 leading-relaxed">"{{ $job->notes }}"</p>
                </div>
            </div>
        @endif
    </div>

    {{-- 🌟 Fleet Assignment Card --}}
    <div class="bg-slate-900/60 shadow-2xl border border-slate-800 rounded-[3rem] p-8 md:p-12 relative overflow-hidden backdrop-blur-xl">
        <div class="absolute -right-10 -top-10 w-40 h-40 bg-cyan-500/10 rounded-full blur-[60px] pointer-events-none"></div>

        <h2 class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-8 flex items-center gap-2 border-b border-slate-800 pb-4 relative z-10">
            <svg class="w-4 h-4 text-cyan-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" /></svg>
            Fleet Assignment Matrix
        </h2>

        <form action="{{ route('transport.dispatch.update', $job->id) }}" method="POST" class="relative z-10">
            @csrf
            @method('PUT')

            <div class="mb-10">
    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3 ml-2">
        Assign Driver 
        <span class="text-slate-500 font-medium normal-case tracking-normal">
            &mdash; (Trip Passengers: {{ $job->passengers }} seats required)
        </span>
    </label>
    
    @if($drivers->count() > 0)
        <div class="relative">
            {{-- إضافة ID للـ select لاستخدامه في الجافاسكريبت --}}
            <select name="driver_id" id="driver_select" required class="w-full dark-select px-6 py-5 font-bold text-sm">
                <option value="" disabled selected>Select a driver from the roster...</option>
                @foreach($drivers as $driver)
                    @php
                        $vehicle = $driver->transportVehicle;
                        // إضافة السعة للعنوان
                        $vehicleLabel = $vehicle 
                            ? " — {$vehicle->type} ({$vehicle->license_plate}) [Capacity: {$vehicle->capacity} Seats]"
                            : ' — ⚠️ No Vehicle Linked';
                        
                        // تعطيل السائق إذا كانت سيارته أصغر من العدد المطلوب (اختياري كحماية إضافية في الواجهة)
                        $isDisabled = ($vehicle && $vehicle->capacity < $job->passengers) ? 'disabled' : '';
                    @endphp
                    
                    <option value="{{ $driver->id }}" 
                            data-vehicle-id="{{ $vehicle?->id }}" 
                            {{ old('driver_id', $job->driver_id) == $driver->id ? 'selected' : '' }}
                            {{ $isDisabled }}>
                        {{ $driver->name }}{{ $vehicleLabel }} 
                        @if($isDisabled) (Insufficient Capacity) @endif
                    </option>
                @endforeach
            </select>

            {{-- حقل مخفي لإرسال vehicle_id كما يتطلب الـ Controller --}}
            <input type="hidden" name="vehicle_id" id="vehicle_id_input" value="{{ old('vehicle_id', $job->vehicle_id) }}">
        </div>

        {{-- جافاسكريبت لتحديث الـ vehicle_id تلقائياً عند تغيير السائق --}}
       <script>
    document.addEventListener('DOMContentLoaded', function() {
        const driverSelect = document.getElementById('driver_select');
        const vehicleInput = document.getElementById('vehicle_id_input');

        function updateVehicleId() {
            const selectedOption = driverSelect.options[driverSelect.selectedIndex];
            if (selectedOption) {
                const vehicleId = selectedOption.getAttribute('data-vehicle-id');
                vehicleInput.value = vehicleId || ''; // إذا لم يوجد مركبة نضع قيمة فارغة
            }
        }

        // 1. تشغيل الدالة فور تحميل الصفحة (عشان الـ Old Input أو الـ Selected)
        updateVehicleId();

        // 2. تشغيل الدالة عند كل تغيير في الاختيار
        driverSelect.addEventListener('change', updateVehicleId);
    });
</script>
    @else
        <div class="px-6 py-5 bg-rose-500/10 text-rose-400 rounded-[1.5rem] text-xs font-bold border border-rose-500/30 shadow-inner text-center">
            No active drivers found. Please <a href="{{ route('transport.drivers.create') }}" class="underline hover:text-white">add drivers</a> to your fleet first.
        </div>
    @endif
</div>

            <div class="mb-10">
                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3 ml-2">Update Job Status</label>
                <div class="relative">
                    <select name="status" required class="w-full dark-select px-6 py-5 font-bold text-sm">
                        <option value="accepted" {{ old('status', $job->status) === 'accepted' ? 'selected' : '' }}>Accepted (Pending Assignment)</option>
                        <option value="assigned" {{ old('status', $job->status) === 'assigned' ? 'selected' : '' }}>Assigned (Ready for Pickup)</option>
                        <option value="in_progress" {{ old('status', $job->status) === 'in_progress' ? 'selected' : '' }}>In Progress (On Route)</option>
                        <option value="completed" {{ old('status', $job->status) === 'completed' ? 'selected' : '' }}>Completed (Finished)</option>
                        <option value="cancelled" {{ old('status', $job->status) === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>
            </div>

            <div class="pt-8 border-t border-slate-800 flex flex-col sm:flex-row justify-end gap-4">
                {{-- 💡 FIX: route name updated here too --}}
                <a href="{{ route('transport.dashboard') }}" class="w-full sm:w-auto px-10 py-5 bg-slate-950 text-slate-400 hover:text-white font-black text-[11px] uppercase tracking-[0.2em] rounded-2xl transition-all border border-slate-800 hover:border-slate-600 shadow-inner text-center active:scale-95">
                    Cancel
                </a>
                <button type="submit" class="w-full sm:w-auto bg-gradient-to-r from-cyan-600 to-blue-500 hover:to-blue-400 text-white font-black py-5 px-12 rounded-2xl shadow-[0_10px_25px_rgba(6,182,212,0.3)] transition-all transform active:scale-95 uppercase tracking-widest text-[11px] flex items-center justify-center gap-3 border border-cyan-400/50" {{ $drivers->count() == 0 ? 'disabled' : '' }}>
                    Save Dispatch Configuration
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
