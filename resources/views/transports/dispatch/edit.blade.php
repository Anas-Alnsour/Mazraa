@extends('layouts.transport')

@section('content')
<div class="bg-slate-50 min-h-screen py-16">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-12">
            <div>
                <h1 class="text-4xl font-black text-slate-900 tracking-tight">Manage Dispatch #{{ $job->id }}</h1>
                <p class="text-sm font-bold text-cyan-600 mt-2 uppercase tracking-widest">Assign a fleet vehicle and driver to this request</p>
            </div>
            <a href="{{ route('transport.dispatch.index') }}" class="inline-flex items-center gap-2 bg-white border border-slate-200 hover:bg-slate-50 text-slate-900 font-black py-3.5 px-8 rounded-2xl transition-all shadow-sm transform active:scale-95 text-[10px] uppercase tracking-widest">
                <svg class="w-4 h-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                Back to Dashboard
            </a>
        </div>

        @if($errors->any())
            <div class="bg-rose-50 border-l-4 border-rose-500 text-rose-800 p-5 rounded-r-2xl shadow-sm font-bold mb-10 flex items-center gap-3">
                <svg class="w-6 h-6 text-rose-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                <ul class="list-none text-sm">
                    @foreach($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                </ul>
            </div>
        @endif

        @if(session('success'))
            <div class="bg-emerald-50 border-l-4 border-emerald-500 text-emerald-800 p-5 rounded-r-2xl shadow-sm font-bold mb-10 flex items-center gap-3">
                <svg class="w-6 h-6 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-slate-100 rounded-[2.5rem] p-8 md:p-10 mb-10">
            <h2 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-6 flex items-center gap-2 border-b border-slate-100 pb-3">
                <svg class="w-4 h-4 text-cyan-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                Trip Requirements
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                <div class="space-y-6">
                    <div>
                        <span class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Customer Info</span>
                        <p class="text-xl font-black text-slate-900">{{ $job->user->name ?? 'Guest User' }}</p>
                        <p class="text-xs font-bold text-cyan-600 mt-1 flex items-center gap-1">
                            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                            {{ $job->user->phone ?? 'No phone provided' }}
                        </p>
                    </div>

                    <div>
                        <span class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Vehicle Requested</span>
                        <p class="text-lg font-black text-slate-900">{{ $job->transport_type }}</p>
                        <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest mt-1">{{ $job->passengers_count ?? $job->passengers ?? 0 }} Passengers</p>
                    </div>

                    <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100">
                        <span class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Trip Value</span>
                        <p class="text-2xl font-black text-emerald-600">{{ number_format($job->price, 2) }} <span class="text-[10px] text-emerald-500 uppercase tracking-widest">JOD</span></p>
                        <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mt-1">Net Earnings: {{ number_format($job->net_company_amount, 2) }} JOD</p>
                    </div>
                </div>

                <div class="flex flex-col justify-center">
                    <div class="relative pl-6 border-l-2 border-dashed border-slate-200 space-y-8">
                        <div class="relative">
                            <div class="absolute -left-[31px] top-1 h-4 w-4 rounded-full bg-white border-[4px] border-cyan-500"></div>
                            <span class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Pickup Location</span>
                            <p class="text-sm font-bold text-slate-900">{{ $job->pickup_location ?? $job->start_and_return_point }}</p>
                        </div>

                        <div class="relative">
                            <div class="absolute -left-[31px] top-1 h-4 w-4 rounded-full bg-white border-[4px] border-emerald-500"></div>
                            <span class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Destination Farm</span>
                            <p class="text-sm font-bold text-slate-900">{{ $job->farmBooking->farm->name ?? 'N/A' }}</p>
                            <p class="text-[10px] font-bold text-cyan-600 uppercase tracking-widest mt-1">{{ optional($job->pickup_time ?? $job->Farm_Arrival_Time)->format('M d, Y - h:i A') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            @if(isset($job->notes) && $job->notes)
                <div class="mt-8 p-5 bg-amber-50 rounded-2xl border border-amber-100 flex gap-4">
                    <svg class="h-6 w-6 text-amber-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <div>
                        <span class="block text-[10px] font-black text-amber-600 uppercase tracking-widest mb-1">Customer Notes</span>
                        <p class="text-sm font-bold text-amber-900">"{{ $job->notes }}"</p>
                    </div>
                </div>
            @endif
        </div>

        <div class="bg-slate-900 shadow-2xl border border-slate-800 rounded-[2.5rem] p-8 md:p-10 relative overflow-hidden">
            <div class="absolute -right-10 -top-10 w-40 h-40 bg-cyan-500 rounded-full opacity-20 blur-3xl"></div>

            <h2 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-6 flex items-center gap-2 border-b border-slate-800 pb-3 relative z-10">
                <svg class="w-4 h-4 text-cyan-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" /></svg>
                Fleet Assignment
            </h2>

            <form action="{{ route('transport.dispatch.update', $job->id) }}" method="POST" class="relative z-10">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <div class="relative">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Assign Vehicle</label>
                        @if($vehicles->count() > 0)
                            <select name="vehicle_id" required
                                class="w-full px-5 py-4 bg-slate-800 border border-slate-700 rounded-2xl focus:ring-2 focus:ring-cyan-500 text-sm font-bold text-white transition-colors appearance-none cursor-pointer">
                                <option value="" disabled>Select an available vehicle...</option>
                                @foreach($vehicles as $vehicle)
                                    <option value="{{ $vehicle->id }}" {{ old('vehicle_id', $job->vehicle_id) == $vehicle->id ? 'selected' : '' }}>
                                        {{ $vehicle->license_plate ?? $vehicle->plate_number }} - {{ $vehicle->type }} ({{ $vehicle->capacity }} pax)
                                    </option>
                                @endforeach
                            </select>
                            <div class="absolute inset-y-0 right-0 top-6 flex items-center px-4 pointer-events-none text-slate-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </div>
                        @else
                            <div class="px-5 py-4 bg-rose-500/10 text-rose-400 rounded-2xl text-sm font-bold border border-rose-500/20">
                                No available vehicles. Please add vehicles to your fleet.
                            </div>
                        @endif
                    </div>

                    <div class="relative">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Assign Driver</label>
                        @if($drivers->count() > 0)
                            <select name="driver_id" required
                                class="w-full px-5 py-4 bg-slate-800 border border-slate-700 rounded-2xl focus:ring-2 focus:ring-cyan-500 text-sm font-bold text-white transition-colors appearance-none cursor-pointer">
                                <option value="" disabled>Select a driver...</option>
                                @foreach($drivers as $driver)
                                    <option value="{{ $driver->id }}" {{ old('driver_id', $job->driver_id) == $driver->id ? 'selected' : '' }}>
                                        {{ $driver->name }} ({{ $driver->phone }})
                                    </option>
                                @endforeach
                            </select>
                            <div class="absolute inset-y-0 right-0 top-6 flex items-center px-4 pointer-events-none text-slate-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </div>
                        @else
                            <div class="px-5 py-4 bg-rose-500/10 text-rose-400 rounded-2xl text-sm font-bold border border-rose-500/20">
                                No drivers found. Please add drivers to your fleet.
                            </div>
                        @endif
                    </div>
                </div>

                <div class="mb-10 relative">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Update Job Status</label>
                    <select name="status" required
                        class="w-full px-5 py-4 bg-slate-800 border border-slate-700 rounded-2xl focus:ring-2 focus:ring-cyan-500 text-sm font-bold text-white transition-colors appearance-none cursor-pointer">
                        <option value="accepted" {{ old('status', $job->status) === 'accepted' ? 'selected' : '' }}>Accepted (Pending Assignment)</option>
                        <option value="assigned" {{ old('status', $job->status) === 'assigned' ? 'selected' : '' }}>Assigned (Ready for Pickup)</option>
                        <option value="in_progress" {{ old('status', $job->status) === 'in_progress' ? 'selected' : '' }}>In Progress (On Route)</option>
                        <option value="completed" {{ old('status', $job->status) === 'completed' ? 'selected' : '' }}>Completed (Finished)</option>
                        <option value="cancelled" {{ old('status', $job->status) === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                    <div class="absolute inset-y-0 right-0 top-6 flex items-center px-4 pointer-events-none text-slate-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </div>
                </div>

                <div class="pt-6 border-t border-slate-800 flex justify-end">
                    <button type="submit" class="w-full md:w-auto bg-cyan-600 hover:bg-cyan-500 text-white font-black py-4 px-10 rounded-2xl shadow-lg shadow-cyan-600/20 transition-all transform active:scale-95 uppercase tracking-widest text-xs flex items-center justify-center gap-2" {{ !isset($vehicles) || $vehicles->count() == 0 || $drivers->count() == 0 ? 'disabled' : '' }}>
                        Save Fleet Assignment
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
                    </button>
                </div>
            </form>
        </div>

    </div>
</div>
@endsection
