@extends('layouts.supply')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="flex justify-between items-center mb-10">
        <div>
            <h1 class="text-3xl font-black text-gray-900 tracking-tight">Add New Driver</h1>
            <p class="text-sm font-bold text-gray-400 tracking-widest uppercase mt-1">Register personnel to your delivery fleet</p>
        </div>
        <a href="{{ route('supplies.drivers.index') }}" class="group text-gray-500 hover:text-gray-900 font-bold flex items-center gap-2 transition-colors">
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
        <form action="{{ route('supplies.drivers.store') }}" method="POST" class="space-y-8">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="md:col-span-2">
                    <label for="name" class="block text-xs font-black text-gray-500 uppercase tracking-widest mb-2 px-1">Driver Full Name</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" required
                        placeholder="e.g. Ahmad Khaled"
                        class="w-full bg-gray-50 border border-gray-200 text-gray-900 font-bold rounded-2xl focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 focus:bg-white transition-all py-4 px-6">
                </div>

                <div>
                    <label for="email" class="block text-xs font-black text-gray-500 uppercase tracking-widest mb-2 px-1">Email Address</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" required
                        placeholder="driver@example.com"
                        class="w-full bg-gray-50 border border-gray-200 text-gray-900 font-bold rounded-2xl focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 focus:bg-white transition-all py-4 px-6">
                </div>

                <div>
                    <label for="phone" class="block text-xs font-black text-gray-500 uppercase tracking-widest mb-2 px-1">Phone Number</label>
                    <input type="text" name="phone" id="phone" value="{{ old('phone') }}" required
                        placeholder="07xxxxxxxx"
                        class="w-full bg-gray-50 border border-gray-200 text-gray-900 font-bold rounded-2xl focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 focus:bg-white transition-all py-4 px-6">
                </div>
            </div>

            <div class="pt-6 border-t border-gray-100">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                    <div class="md:col-span-2">
                        <label for="shift" class="block text-xs font-black text-gray-500 uppercase tracking-widest mb-2 px-1">Delivery Shift <span class="text-gray-400 font-medium normal-case tracking-normal">(Used for Dispatching)</span></label>
                        <div class="relative">
                            <select name="shift" id="shift" required class="block w-full rounded-2xl border-gray-200 bg-gray-50 shadow-inner focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 sm:text-sm font-bold text-gray-900 px-6 py-4 appearance-none cursor-pointer transition-all">
                                <option value="morning" {{ old('shift') == 'morning' ? 'selected' : '' }}>☀️ Morning Shift (08:00 AM - 05:00 PM)</option>
                                <option value="evening" {{ old('shift') == 'evening' ? 'selected' : '' }}>🌙 Evening Shift (07:00 PM - 06:00 AM)</option>
                                <option value="full_day" {{ old('shift') == 'full_day' ? 'selected' : '' }}>🕒 Full Day Availability</option>
                            </select>
                            <div class="absolute right-5 top-1/2 -translate-y-1/2 pointer-events-none text-gray-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"></path></svg>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <label for="password" class="block text-xs font-black text-gray-500 uppercase tracking-widest mb-2 px-1">Password</label>
                        <input type="password" name="password" id="password" required
                            class="w-full bg-gray-50 border border-gray-200 text-gray-900 font-bold rounded-2xl focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 focus:bg-white transition-all py-4 px-6">
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-xs font-black text-gray-500 uppercase tracking-widest mb-2 px-1">Confirm Password</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" required
                            class="w-full bg-gray-50 border border-gray-200 text-gray-900 font-bold rounded-2xl focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 focus:bg-white transition-all py-4 px-6">
                    </div>
                </div>
            </div>

            <div class="pt-8 border-t border-gray-100 flex justify-end gap-4 mt-8">
                <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white font-black py-4 px-10 rounded-2xl shadow-lg hover:shadow-xl transition-all transform active:scale-95 tracking-widest uppercase text-sm w-full md:w-auto">
                    Register Driver
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
