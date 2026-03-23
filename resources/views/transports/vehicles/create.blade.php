@extends('layouts.transport')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-black text-gray-900 tracking-tight">Add New Vehicle</h1>
            <p class="text-sm font-bold text-gray-400 tracking-widest uppercase mt-1">Register an asset to your fleet</p>
        </div>
        <a href="{{ route('transport.vehicles.index') }}" class="group text-gray-500 hover:text-gray-900 font-bold flex items-center gap-2 transition-colors">
            <svg class="w-5 h-5 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Back to Fleet
        </a>
    </div>

    @if ($errors->any())
        <div class="bg-red-50 border-l-4 border-red-500 text-red-700 px-6 py-4 rounded-r-2xl mb-8 shadow-sm">
            <ul class="list-disc pl-5 font-bold text-sm space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 p-8 sm:p-12">
        <form action="{{ route('transport.vehicles.store') }}" method="POST" class="space-y-8">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="md:col-span-2">
                    <label for="type" class="block text-xs font-black text-gray-500 uppercase tracking-widest mb-2 px-1">Vehicle Type</label>
                    <select name="type" id="type" required
                        class="w-full bg-gray-50 border border-gray-200 text-gray-900 font-bold rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 focus:bg-white transition-all py-4 px-6 appearance-none cursor-pointer">
                        <option value="">Select vehicle type...</option>
                        <option value="Mini Van (7 Seats)" {{ old('type') == 'Mini Van (7 Seats)' ? 'selected' : '' }}>Mini Van (7 Seats)</option>
                        <option value="Van (12 Seats)" {{ old('type') == 'Van (12 Seats)' ? 'selected' : '' }}>Van (12 Seats)</option>
                        <option value="Coaster Bus (22 Seats)" {{ old('type') == 'Coaster Bus (22 Seats)' ? 'selected' : '' }}>Coaster Bus (22 Seats)</option>
                        <option value="Large Bus (50 Seats)" {{ old('type') == 'Large Bus (50 Seats)' ? 'selected' : '' }}>Large Bus (50 Seats)</option>
                        <option value="SUV / 4x4" {{ old('type') == 'SUV / 4x4' ? 'selected' : '' }}>SUV / 4x4</option>
                        <option value="Sedan (VIP)" {{ old('type') == 'Sedan (VIP)' ? 'selected' : '' }}>Sedan (VIP)</option>
                    </select>
                </div>

                <div>
                    <label for="license_plate" class="block text-xs font-black text-gray-500 uppercase tracking-widest mb-2 px-1">License Plate Number</label>
                    <input type="text" name="license_plate" id="license_plate" value="{{ old('license_plate') }}" required
                        class="w-full bg-gray-50 border border-gray-200 text-gray-900 font-bold uppercase rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 focus:bg-white transition-all py-4 px-6"
                        placeholder="e.g. 10-12345">
                </div>

                <div>
                    <label for="capacity" class="block text-xs font-black text-gray-500 uppercase tracking-widest mb-2 px-1">Passenger Capacity</label>
                    <div class="relative">
                        <input type="number" name="capacity" id="capacity" value="{{ old('capacity') }}" required min="1"
                            class="w-full bg-gray-50 border border-gray-200 text-gray-900 font-bold rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 focus:bg-white transition-all py-4 pl-6 pr-16"
                            placeholder="e.g. 22">
                        <div class="absolute inset-y-0 right-0 flex items-center pr-6 pointer-events-none text-gray-400 font-black text-[10px] uppercase tracking-widest">
                            PAX
                        </div>
                    </div>
                </div>
                <div class="md:col-span-2 pt-4 border-t border-gray-100 mt-2">
                    <label for="driver_id" class="block text-xs font-black text-gray-500 uppercase tracking-widest mb-2 px-1">Assign Driver</label>
                    <select name="driver_id" id="driver_id"
                        class="w-full bg-gray-50 border border-gray-200 text-gray-900 font-bold rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 focus:bg-white transition-all py-4 px-6 appearance-none cursor-pointer">
                        <option value="">-- No Driver Assigned --</option>
                        @foreach($drivers as $driver)
                            <option value="{{ $driver->id }}" {{ old('driver_id') == $driver->id ? 'selected' : '' }}>
                                {{ $driver->name }} (ID: {{ $driver->id }})
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="pt-8 border-t border-gray-100">
                <label class="block text-xs font-black text-gray-500 uppercase tracking-widest mb-4 px-1">Initial Status</label>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

                    <div class="relative">
                        <input type="radio" name="status" id="status_available" value="available" class="peer sr-only" {{ old('status', 'available') === 'available' ? 'checked' : '' }}>
                        <label for="status_available" class="flex flex-col items-center justify-center p-6 border-2 border-gray-100 rounded-2xl cursor-pointer transition-all hover:bg-gray-50 peer-checked:border-blue-500 peer-checked:bg-blue-50 group">
                            <span class="w-4 h-4 rounded-full bg-emerald-500 mb-3 shadow-sm group-hover:scale-110 transition-transform"></span>
                            <span class="text-sm font-black text-gray-900 uppercase tracking-widest peer-checked:text-blue-700">Available</span>
                        </label>
                    </div>

                    <div class="relative">
                        <input type="radio" name="status" id="status_maintenance" value="maintenance" class="peer sr-only" {{ old('status') === 'maintenance' ? 'checked' : '' }}>
                        <label for="status_maintenance" class="flex flex-col items-center justify-center p-6 border-2 border-gray-100 rounded-2xl cursor-pointer transition-all hover:bg-gray-50 peer-checked:border-blue-500 peer-checked:bg-blue-50 group">
                            <span class="w-4 h-4 rounded-full bg-amber-500 mb-3 shadow-sm group-hover:scale-110 transition-transform"></span>
                            <span class="text-sm font-black text-gray-900 uppercase tracking-widest peer-checked:text-blue-700">Maintenance</span>
                        </label>
                    </div>

                    <div class="relative">
                        <input type="radio" name="status" id="status_booked" value="booked" class="peer sr-only" {{ old('status') === 'booked' ? 'checked' : '' }}>
                        <label for="status_booked" class="flex flex-col items-center justify-center p-6 border-2 border-gray-100 rounded-2xl cursor-pointer transition-all hover:bg-gray-50 peer-checked:border-blue-500 peer-checked:bg-blue-50 group">
                            <span class="w-4 h-4 rounded-full bg-blue-500 mb-3 shadow-sm group-hover:scale-110 transition-transform"></span>
                            <span class="text-sm font-black text-gray-900 uppercase tracking-widest peer-checked:text-blue-700">Booked</span>
                        </label>
                    </div>

                </div>
            </div>

            <div class="pt-8 mt-8 border-t border-gray-100 flex flex-col md:flex-row justify-end gap-4">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-black py-4 px-10 rounded-2xl shadow-lg hover:shadow-xl transition-all transform active:scale-95 tracking-widest uppercase text-sm w-full md:w-auto">
                    Save Vehicle
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
