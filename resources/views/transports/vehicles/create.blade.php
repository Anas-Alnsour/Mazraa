@extends('layouts.transport')

@section('title', 'Add New Vehicle')

@section('content')
<div class="py-12 bg-slate-50 min-h-screen">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

        <div class="mb-8">
            <a href="{{ route('transport.vehicles.index') }}" class="inline-flex items-center text-[10px] font-black uppercase tracking-widest text-cyan-600 hover:text-cyan-800 mb-4 transition-colors bg-cyan-50 hover:bg-cyan-100 px-3 py-1.5 rounded-lg border border-cyan-100">
                <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Back to Fleet
            </a>
            <h1 class="text-3xl font-black text-slate-900 tracking-tight">Add New Vehicle</h1>
            <p class="mt-2 text-sm font-bold text-slate-500 uppercase tracking-widest">Register a new asset to your transport fleet.</p>
        </div>

        @if ($errors->any())
            <div class="bg-rose-50 border-l-4 border-rose-500 text-rose-700 px-6 py-4 rounded-r-2xl mb-8 shadow-sm">
                <ul class="list-disc pl-5 font-bold text-sm space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-white rounded-[2.5rem] shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-slate-100 overflow-hidden">
            <form action="{{ route('transport.vehicles.store') }}" method="POST" class="p-8 sm:p-10">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="md:col-span-2">
                        <label for="type" class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-2 pl-1">Vehicle Type</label>
                        <div class="relative">
                            <select name="type" id="type" required class="block w-full rounded-2xl border-slate-200 bg-slate-50 shadow-inner focus:border-cyan-500 focus:ring-4 focus:ring-cyan-500/10 sm:text-sm font-bold text-slate-900 px-5 py-4 appearance-none cursor-pointer transition-all">
                                <option value="">Select vehicle type...</option>
                                <option value="Mini Van (7 Seats)" {{ old('type') == 'Mini Van (7 Seats)' ? 'selected' : '' }}>Mini Van (7 Seats)</option>
                                <option value="Van (12 Seats)" {{ old('type') == 'Van (12 Seats)' ? 'selected' : '' }}>Van (12 Seats)</option>
                                <option value="Coaster Bus (22 Seats)" {{ old('type') == 'Coaster Bus (22 Seats)' ? 'selected' : '' }}>Coaster Bus (22 Seats)</option>
                                <option value="Large Bus (50 Seats)" {{ old('type') == 'Large Bus (50 Seats)' ? 'selected' : '' }}>Large Bus (50 Seats)</option>
                                <option value="SUV / 4x4" {{ old('type') == 'SUV / 4x4' ? 'selected' : '' }}>SUV / 4x4</option>
                                <option value="Sedan (VIP)" {{ old('type') == 'Sedan (VIP)' ? 'selected' : '' }}>Sedan (VIP)</option>
                            </select>
                            <div class="absolute right-5 top-1/2 -translate-y-1/2 pointer-events-none text-slate-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"></path></svg>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label for="license_plate" class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-2 pl-1">License Plate Number</label>
                        <input type="text" name="license_plate" id="license_plate" value="{{ old('license_plate') }}" required class="block w-full rounded-2xl border-slate-200 bg-slate-50 shadow-inner focus:border-cyan-500 focus:ring-4 focus:ring-cyan-500/10 sm:text-sm font-black text-slate-900 px-5 py-4 uppercase placeholder:normal-case transition-all" placeholder="e.g. 10-12345">
                    </div>

                    <div>
                        <label for="capacity" class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-2 pl-1">Passenger Capacity</label>
                        <div class="relative">
                            <input type="number" name="capacity" id="capacity" min="1" value="{{ old('capacity') }}" required class="block w-full rounded-2xl border-slate-200 bg-slate-50 shadow-inner focus:border-cyan-500 focus:ring-4 focus:ring-cyan-500/10 sm:text-sm font-bold text-slate-900 px-5 py-4 transition-all" placeholder="e.g. 12">
                            <div class="absolute inset-y-0 right-0 flex items-center pr-5 pointer-events-none text-slate-400 font-black text-[10px] uppercase tracking-widest">PAX</div>
                        </div>
                    </div>

                    <div class="md:col-span-2 pt-6 border-t border-slate-100">
                        <label for="driver_id" class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-2 pl-1">Assign Driver (Optional)</label>
                        <div class="relative">
                            <select name="driver_id" id="driver_id" class="block w-full rounded-2xl border-slate-200 bg-slate-50 shadow-inner focus:border-cyan-500 focus:ring-4 focus:ring-cyan-500/10 sm:text-sm font-bold text-slate-900 px-5 py-4 appearance-none cursor-pointer transition-all">
                                <option value="">-- No Driver Assigned --</option>
                                @foreach($drivers ?? [] as $driver)
                                    <option value="{{ $driver->id }}" {{ old('driver_id') == $driver->id ? 'selected' : '' }}>
                                        {{ $driver->name }} (ID: {{ $driver->id }})
                                    </option>
                                @endforeach
                            </select>
                            <div class="absolute right-5 top-1/2 -translate-y-1/2 pointer-events-none text-slate-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"></path></svg>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="pt-8 mt-8 border-t border-slate-100">
                    <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-4 pl-1">Initial Status</label>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="relative">
                            <input type="radio" name="status" id="status_available" value="available" class="peer sr-only" {{ old('status', 'available') === 'available' ? 'checked' : '' }}>
                            <label for="status_available" class="flex flex-col items-center justify-center p-6 border-2 border-slate-100 rounded-2xl cursor-pointer transition-all hover:bg-slate-50 peer-checked:border-cyan-500 peer-checked:bg-cyan-50 group">
                                <span class="w-4 h-4 rounded-full bg-emerald-500 mb-3 shadow-sm group-hover:scale-110 transition-transform"></span>
                                <span class="text-sm font-black text-slate-900 uppercase tracking-widest peer-checked:text-cyan-700">Available</span>
                            </label>
                        </div>
                        <div class="relative">
                            <input type="radio" name="status" id="status_maintenance" value="maintenance" class="peer sr-only" {{ old('status') === 'maintenance' ? 'checked' : '' }}>
                            <label for="status_maintenance" class="flex flex-col items-center justify-center p-6 border-2 border-slate-100 rounded-2xl cursor-pointer transition-all hover:bg-slate-50 peer-checked:border-cyan-500 peer-checked:bg-cyan-50 group">
                                <span class="w-4 h-4 rounded-full bg-amber-500 mb-3 shadow-sm group-hover:scale-110 transition-transform"></span>
                                <span class="text-sm font-black text-slate-900 uppercase tracking-widest peer-checked:text-cyan-700">Maintenance</span>
                            </label>
                        </div>
                        <div class="relative">
                            <input type="radio" name="status" id="status_booked" value="booked" class="peer sr-only" {{ old('status') === 'booked' ? 'checked' : '' }}>
                            <label for="status_booked" class="flex flex-col items-center justify-center p-6 border-2 border-slate-100 rounded-2xl cursor-pointer transition-all hover:bg-slate-50 peer-checked:border-cyan-500 peer-checked:bg-cyan-50 group">
                                <span class="w-4 h-4 rounded-full bg-blue-500 mb-3 shadow-sm group-hover:scale-110 transition-transform"></span>
                                <span class="text-sm font-black text-slate-900 uppercase tracking-widest peer-checked:text-cyan-700">Booked</span>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="mt-10 pt-8 border-t border-slate-100 flex flex-col sm:flex-row justify-end gap-4">
                    <a href="{{ route('transport.vehicles.index') }}" class="inline-flex justify-center items-center py-4 px-8 border-2 border-slate-100 rounded-xl shadow-sm text-xs font-black uppercase tracking-widest text-slate-500 hover:bg-slate-50 hover:text-slate-700 focus:outline-none transition-all transform active:scale-95">
                        Cancel
                    </a>
                    <button class="inline-flex justify-center items-center py-4 px-10 rounded-xl shadow-lg shadow-cyan-600/30 text-xs font-black uppercase tracking-widest text-white bg-cyan-600 hover:bg-cyan-700 focus:outline-none transition-all transform active:scale-95">
                        Save Vehicle
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
