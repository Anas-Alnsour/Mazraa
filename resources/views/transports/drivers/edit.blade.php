@extends('layouts.transport')

@section('title', 'Edit Driver')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-black text-slate-900 tracking-tight">Edit Driver</h1>
            <p class="text-sm font-bold text-slate-500 tracking-widest uppercase mt-1">Update <span class="bg-slate-100 text-slate-800 px-2 py-0.5 rounded-md font-mono">{{ $driver->name }}</span>'s Info</p>
        </div>
        <a href="{{ route('transport.drivers.index') }}" class="group text-slate-500 hover:text-cyan-600 font-bold flex items-center gap-2 transition-colors text-xs uppercase tracking-widest">
            <svg class="w-4 h-4 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Back to Fleet
        </a>
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

    <div class="bg-white rounded-[2.5rem] shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-slate-100 p-8 sm:p-12">
        <form action="{{ route('transport.drivers.update', $driver->id) }}" method="POST" class="space-y-8">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="md:col-span-2">
                    <label for="name" class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-2 px-1">Full Name</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $driver->name) }}" required
                        class="w-full bg-slate-50 border border-slate-200 text-slate-900 font-bold rounded-2xl focus:ring-4 focus:ring-cyan-500/10 focus:border-cyan-500 focus:bg-white transition-all py-4 px-5 shadow-inner">
                </div>

                <div>
                    <label for="email" class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-2 px-1">Email Address</label>
                    <input type="email" name="email" id="email" value="{{ old('email', $driver->email) }}" required
                        class="w-full bg-slate-50 border border-slate-200 text-slate-900 font-bold rounded-2xl focus:ring-4 focus:ring-cyan-500/10 focus:border-cyan-500 focus:bg-white transition-all py-4 px-5 shadow-inner">
                </div>

                <div>
                    <label for="phone" class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-2 px-1">Phone Number</label>
                    <input type="text" name="phone" id="phone" value="{{ old('phone', $driver->phone) }}" required
                        class="w-full bg-slate-50 border border-slate-200 text-slate-900 font-bold rounded-2xl focus:ring-4 focus:ring-cyan-500/10 focus:border-cyan-500 focus:bg-white transition-all py-4 px-5 shadow-inner">
                </div>

                <div class="md:col-span-2 pt-4 border-t border-slate-100">
                    <label for="shift" class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-2 pl-1">Working Shift <span class="text-slate-400 font-medium normal-case tracking-normal">(Used for Dispatching)</span></label>
                    <div class="relative">
                        <select name="shift" id="shift" required class="block w-full rounded-2xl border-slate-200 bg-slate-50 shadow-inner focus:border-cyan-500 focus:ring-4 focus:ring-cyan-500/10 sm:text-sm font-bold text-slate-900 px-5 py-4 appearance-none cursor-pointer transition-all">
                            <option value="morning" {{ old('shift', $driver->shift) == 'morning' ? 'selected' : '' }}>☀️ Morning Shift (08:00 AM - 05:00 PM)</option>
                            <option value="evening" {{ old('shift', $driver->shift) == 'evening' ? 'selected' : '' }}>🌙 Evening Shift (07:00 PM - 06:00 AM)</option>
                            <option value="full_day" {{ old('shift', $driver->shift) == 'full_day' ? 'selected' : '' }}>🕒 Full Day Availability</option>
                        </select>
                        <div class="absolute right-5 top-1/2 -translate-y-1/2 pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"></path></svg>
                        </div>
                    </div>
                </div>

                <div class="md:col-span-2 pt-4 border-t border-slate-100">
                    <label for="governorate" class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-2 pl-1">Primary Operating Region (Governorate)</label>
                    <div class="relative">
                        <select name="governorate" id="governorate" required class="block w-full rounded-2xl border-slate-200 bg-slate-50 shadow-inner focus:border-cyan-500 focus:ring-4 focus:ring-cyan-500/10 sm:text-sm font-bold text-slate-900 px-5 py-4 appearance-none cursor-pointer transition-all">
                            <option value="">Select a region...</option>
                            {{-- 💡 التعديل هنا: قراءة المحافظات من ملف الإعدادات --}}
                            @foreach(config('mazraa.governorates') as $gov)
                                <option value="{{ $gov }}" {{ old('governorate', $driver->governorate) == $gov ? 'selected' : '' }}>{{ $gov }}</option>
                            @endforeach
                        </select>
                        <div class="absolute right-5 top-1/2 -translate-y-1/2 pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"></path></svg>
                        </div>
                    </div>
                </div>

                {{-- Vehicle Assignment (Permanent 1-to-1 Pairing) --}}
                <div class="md:col-span-2 pt-4 border-t border-slate-100">
                    <label for="transport_vehicle_id" class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-2 pl-1">Assigned Vehicle <span class="text-slate-400 font-medium normal-case tracking-normal">(permanent 1-to-1 pairing)</span></label>
                    <div class="relative">
                        @if($availableVehicles->count() > 0)
                            <select name="transport_vehicle_id" id="transport_vehicle_id" class="block w-full rounded-2xl border-slate-200 bg-slate-50 shadow-inner focus:border-cyan-500 focus:ring-4 focus:ring-cyan-500/10 sm:text-sm font-bold text-slate-900 px-5 py-4 appearance-none cursor-pointer transition-all">
                                <option value="">No vehicle assigned</option>
                                @foreach($availableVehicles as $vehicle)
                                    <option value="{{ $vehicle->id }}" {{ old('transport_vehicle_id', $driver->transport_vehicle_id) == $vehicle->id ? 'selected' : '' }}>
                                        {{ $vehicle->type }} &mdash; {{ $vehicle->license_plate }} ({{ $vehicle->capacity }} seats)
                                    </option>
                                @endforeach
                            </select>
                        @else
                            <div class="px-5 py-4 bg-amber-50 text-amber-700 rounded-2xl text-sm font-bold border border-amber-200">
                                No vehicles available. <a href="{{ route('transport.vehicles.create') }}" class="underline text-cyan-600">Add a vehicle</a> to this fleet first.
                            </div>
                        @endif
                        <div class="absolute right-5 top-1/2 -translate-y-1/2 pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"></path></svg>
                        </div>
                    </div>
                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-2 pl-1">Dispatcher will auto-assign this vehicle — no manual pickup needed at dispatch time.</p>
                </div>
            </div>

            <div class="pt-8 mt-8 border-t border-slate-100">
                <div class="mb-6">
                    <h3 class="text-sm font-black text-slate-900 uppercase tracking-tight">Security</h3>
                    <p class="text-[10px] font-bold text-slate-400 mt-1 uppercase tracking-widest">Leave blank to keep current password</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <label for="password" class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-2 px-1">New Password</label>
                        <input type="password" name="password" id="password"
                            class="w-full bg-slate-50 border border-slate-200 text-slate-900 font-bold rounded-2xl focus:ring-4 focus:ring-cyan-500/10 focus:border-cyan-500 focus:bg-white transition-all py-4 px-5 shadow-inner"
                            placeholder="••••••••">
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-2 px-1">Confirm New Password</label>
                        <input type="password" name="password_confirmation" id="password_confirmation"
                            class="w-full bg-slate-50 border border-slate-200 text-slate-900 font-bold rounded-2xl focus:ring-4 focus:ring-cyan-500/10 focus:border-cyan-500 focus:bg-white transition-all py-4 px-5 shadow-inner"
                            placeholder="••••••••">
                    </div>
                </div>
            </div>

            <div class="pt-8 mt-4 border-t border-slate-100 flex flex-col md:flex-row justify-end gap-4">
                <a href="{{ route('transport.drivers.index') }}" class="inline-flex justify-center items-center py-4 px-8 border-2 border-slate-100 rounded-xl shadow-sm text-xs font-black uppercase tracking-widest text-slate-500 hover:bg-slate-50 hover:text-slate-700 focus:outline-none transition-all transform active:scale-95">
                    Cancel
                </a>
                <button type="submit" class="inline-flex justify-center items-center py-4 px-10 rounded-xl shadow-lg shadow-cyan-600/30 text-xs font-black uppercase tracking-widest text-white bg-cyan-600 hover:bg-cyan-700 focus:outline-none transition-all transform active:scale-95">
                    Update Driver
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
