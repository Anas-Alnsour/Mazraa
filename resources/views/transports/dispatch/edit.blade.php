@extends('layouts.transport')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-black text-gray-900 tracking-tight">Dispatch #{{ $dispatch->id }}</h1>
            <p class="text-sm font-bold text-gray-400 tracking-widest uppercase mt-1">Assign Fleet to Job</p>
        </div>
        <a href="{{ route('transport.dashboard') }}" class="group text-gray-500 hover:text-gray-900 font-bold flex items-center gap-2 transition-colors">
            <svg class="w-5 h-5 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Back to Board
        </a>
    </div>

    @if($errors->any())
        <div class="bg-red-50 border-l-4 border-red-500 text-red-700 px-6 py-4 rounded-r-2xl mb-8 shadow-sm">
            <ul class="list-disc pl-5 font-bold text-sm space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white shadow-sm border border-gray-100 rounded-[2.5rem] p-8 sm:p-12 mb-8 relative overflow-hidden">
        <div class="absolute -top-16 -right-16 w-64 h-64 bg-gray-50 rounded-full opacity-50 pointer-events-none"></div>

        <h2 class="text-sm font-black text-gray-400 uppercase tracking-widest mb-8 border-b border-gray-100 pb-4">Trip Specifications</h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-12 relative z-10">
            <div class="space-y-8">
                <div>
                    <span class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Customer Profile</span>
                    <p class="text-lg font-black text-gray-900">{{ $dispatch->user->name ?? 'Guest User' }}</p>
                    <p class="text-sm font-bold text-gray-500">{{ $dispatch->user->phone ?? 'No phone provided' }}</p>
                </div>

                <div>
                    <span class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Asset Requirement</span>
                    <p class="text-lg font-black text-gray-900">{{ $dispatch->transport_type }}</p>
                    <p class="text-sm font-bold text-blue-600 bg-blue-50 inline-block px-3 py-1 rounded-lg mt-1">{{ $dispatch->passengers }} PAX Capacity</p>
                </div>

                <div>
                    <span class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Financial Value</span>
                    <p class="text-3xl font-black text-emerald-600">{{ number_format($dispatch->price, 2) }} <span class="text-sm text-emerald-500 uppercase">JOD</span></p>
                </div>
            </div>

            <div class="relative">
                <div class="absolute left-[11px] top-3 bottom-8 w-1 bg-gray-100 rounded-full"></div>

                <div class="mb-10 relative pl-10">
                    <div class="absolute left-0 top-1 h-6 w-6 rounded-full bg-white border-4 border-gray-300 shadow-sm"></div>
                    <span class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Pickup Location</span>
                    <p class="text-md font-black text-gray-900 leading-tight">{{ $dispatch->start_and_return_point }}</p>
                </div>

                <div class="relative pl-10">
                    <div class="absolute left-0 top-1 h-6 w-6 rounded-full bg-blue-500 border-4 border-blue-100 shadow-sm animate-pulse"></div>
                    <span class="block text-[10px] font-black text-blue-500 uppercase tracking-widest mb-1">Destination Farm</span>
                    <p class="text-md font-black text-gray-900 leading-tight">{{ $dispatch->farm->name ?? 'N/A' }}</p>
                    <p class="text-xs font-bold text-gray-500 mt-2 bg-gray-50 inline-block px-3 py-1.5 rounded-lg border border-gray-200">
                        {{ optional($dispatch->Farm_Arrival_Time)->format('M d, Y - h:i A') }}
                    </p>
                </div>
            </div>
        </div>

        @if($dispatch->notes)
            <div class="mt-8 p-6 bg-amber-50 rounded-2xl border border-amber-100 relative z-10">
                <span class="block text-[10px] font-black text-amber-600 uppercase tracking-widest mb-2 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    Customer Notes
                </span>
                <p class="text-sm font-bold text-amber-900 italic">"{{ $dispatch->notes }}"</p>
            </div>
        @endif
    </div>

    <div class="bg-white shadow-sm border border-gray-100 rounded-[2.5rem] p-8 sm:p-12">
        <h2 class="text-sm font-black text-gray-400 uppercase tracking-widest mb-8 border-b border-gray-100 pb-4">Fleet Assignment</h2>

        <form action="{{ route('transport.dispatch.update', $dispatch->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="space-y-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <label for="vehicle_id" class="block text-xs font-black text-gray-500 uppercase tracking-widest mb-2 px-1">Assign Vehicle</label>
                        @if($vehicles->count() > 0)
                            <select name="vehicle_id" id="vehicle_id" required
                                class="w-full bg-gray-50 border border-gray-200 text-gray-900 font-bold rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 focus:bg-white transition-all py-4 px-6 appearance-none cursor-pointer">
                                <option value="">Select an available vehicle...</option>
                                @foreach($vehicles as $vehicle)
                                    <option value="{{ $vehicle->id }}" {{ old('vehicle_id', $dispatch->vehicle_id) == $vehicle->id ? 'selected' : '' }}>
                                        [{{ $vehicle->license_plate }}] {{ $vehicle->type }} ({{ $vehicle->capacity }} PAX)
                                    </option>
                                @endforeach
                            </select>
                        @else
                            <div class="px-6 py-4 bg-red-50 text-red-700 rounded-2xl text-xs font-black uppercase tracking-widest border border-red-100">
                                No available vehicles. Please update fleet status.
                            </div>
                        @endif
                    </div>

                    <div>
                        <label for="driver_id" class="block text-xs font-black text-gray-500 uppercase tracking-widest mb-2 px-1">Assign Driver</label>
                        @if($drivers->count() > 0)
                            <select name="driver_id" id="driver_id" required
                                class="w-full bg-gray-50 border border-gray-200 text-gray-900 font-bold rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 focus:bg-white transition-all py-4 px-6 appearance-none cursor-pointer">
                                <option value="">Select a driver...</option>
                                @foreach($drivers as $driver)
                                    <option value="{{ $driver->id }}" {{ old('driver_id', $dispatch->driver_id) == $driver->id ? 'selected' : '' }}>
                                        {{ $driver->name }} - {{ $driver->phone }}
                                    </option>
                                @endforeach
                            </select>
                        @else
                            <div class="px-6 py-4 bg-red-50 text-red-700 rounded-2xl text-xs font-black uppercase tracking-widest border border-red-100">
                                No drivers found. Add drivers to fleet.
                            </div>
                        @endif
                    </div>
                </div>

                <div class="pt-4">
                    <label for="status" class="block text-xs font-black text-gray-500 uppercase tracking-widest mb-2 px-1">Job Status</label>
                    <select name="status" id="status" required
                        class="w-full bg-gray-50 border border-gray-200 text-gray-900 font-bold rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 focus:bg-white transition-all py-4 px-6 appearance-none cursor-pointer">
                        <option value="accepted" {{ old('status', $dispatch->status) === 'accepted' ? 'selected' : '' }}>Accepted (Pending Assignment)</option>
                        <option value="assigned" {{ old('status', $dispatch->status) === 'assigned' ? 'selected' : '' }}>Assigned (Ready for Pickup)</option>
                        <option value="in_progress" {{ old('status', $dispatch->status) === 'in_progress' ? 'selected' : '' }}>In Progress (On Route)</option>
                        <option value="completed" {{ old('status', $dispatch->status) === 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="cancelled" {{ old('status', $dispatch->status) === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>
            </div>

            <div class="mt-10 pt-8 border-t border-gray-100 flex flex-col md:flex-row justify-end gap-4">
                <a href="{{ route('transport.dispatch.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-800 font-black py-4 px-10 rounded-2xl transition-all transform active:scale-95 tracking-widest uppercase text-sm w-full md:w-auto text-center border border-gray-200">
                    Cancel
                </a>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-black py-4 px-10 rounded-2xl shadow-lg hover:shadow-xl transition-all transform active:scale-95 tracking-widest uppercase text-sm w-full md:w-auto" {{ $vehicles->count() == 0 || $drivers->count() == 0 ? 'disabled' : '' }}>
                    Lock Assignment
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
