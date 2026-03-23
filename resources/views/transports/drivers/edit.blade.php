@extends('layouts.transport')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-black text-gray-900 tracking-tight">Edit Driver</h1>
            <p class="text-sm font-bold text-gray-400 tracking-widest uppercase mt-1">Update {{ $driver->name }}'s Info</p>
        </div>
        <a href="{{ route('transport.drivers.index') }}" class="group text-gray-500 hover:text-gray-900 font-bold flex items-center gap-2 transition-colors">
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
        <form action="{{ route('transport.drivers.update', $driver->id) }}" method="POST" class="space-y-8">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="md:col-span-2">
                    <label for="name" class="block text-xs font-black text-gray-500 uppercase tracking-widest mb-2 px-1">Full Name</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $driver->name) }}" required
                        class="w-full bg-gray-50 border border-gray-200 text-gray-900 font-bold rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 focus:bg-white transition-all py-4 px-6">
                </div>

                <div>
                    <label for="email" class="block text-xs font-black text-gray-500 uppercase tracking-widest mb-2 px-1">Email Address</label>
                    <input type="email" name="email" id="email" value="{{ old('email', $driver->email) }}" required
                        class="w-full bg-gray-50 border border-gray-200 text-gray-900 font-bold rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 focus:bg-white transition-all py-4 px-6">
                </div>

                <div>
                    <label for="phone" class="block text-xs font-black text-gray-500 uppercase tracking-widest mb-2 px-1">Phone Number</label>
                    <input type="text" name="phone" id="phone" value="{{ old('phone', $driver->phone) }}" required
                        class="w-full bg-gray-50 border border-gray-200 text-gray-900 font-bold rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 focus:bg-white transition-all py-4 px-6">
                </div>
            </div>

            <div class="pt-8 mt-8 border-t border-gray-100">
                <div class="mb-6">
                    <h3 class="text-lg font-black text-gray-900 uppercase tracking-tight">Security</h3>
                    <p class="text-xs font-bold text-gray-400 mt-1 uppercase tracking-widest">Leave blank to keep current password</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <label for="password" class="block text-xs font-black text-gray-500 uppercase tracking-widest mb-2 px-1">New Password</label>
                        <input type="password" name="password" id="password"
                            class="w-full bg-gray-50 border border-gray-200 text-gray-900 font-bold rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 focus:bg-white transition-all py-4 px-6"
                            placeholder="••••••••">
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-xs font-black text-gray-500 uppercase tracking-widest mb-2 px-1">Confirm New Password</label>
                        <input type="password" name="password_confirmation" id="password_confirmation"
                            class="w-full bg-gray-50 border border-gray-200 text-gray-900 font-bold rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 focus:bg-white transition-all py-4 px-6"
                            placeholder="••••••••">
                    </div>
                </div>
            </div>

            <div class="pt-8 mt-8 border-t border-gray-100 flex flex-col md:flex-row justify-end gap-4">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-black py-4 px-10 rounded-2xl shadow-lg hover:shadow-xl transition-all transform active:scale-95 tracking-widest uppercase text-sm w-full md:w-auto">
                    Update Driver
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
