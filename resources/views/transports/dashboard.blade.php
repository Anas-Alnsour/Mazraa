@extends('layouts.transport')

@section('title', 'Fleet Operations & Dispatch')

@section('content')
    <div class="max-w-7xl mx-auto space-y-8">

        @if(session('success'))
            <div class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 rounded-r-xl shadow-sm mb-6">
                <p class="font-bold text-sm tracking-tight">{{ session('success') }}</p>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded-r-xl shadow-sm mb-6">
                <p class="font-bold text-sm tracking-tight">{{ session('error') }}</p>
            </div>
        @endif

        {{-- 1. Financial Reports & Fleet Stats --}}
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="bg-white overflow-hidden shadow-sm rounded-2xl border border-gray-100 p-6 transition-transform hover:-translate-y-1">
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Gross Revenue</p>
                <p class="text-3xl font-black text-gray-900">{{ number_format($financials['gross'], 2) }} <span class="text-sm text-gray-400">JOD</span></p>
            </div>

            <div class="bg-white overflow-hidden shadow-sm rounded-2xl border border-gray-100 p-6 transition-transform hover:-translate-y-1">
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Platform Fees</p>
                <p class="text-3xl font-black text-red-500">- {{ number_format($financials['commission'], 2) }} <span class="text-sm text-gray-400">JOD</span></p>
            </div>

            <div class="bg-white overflow-hidden shadow-sm rounded-2xl border border-indigo-200 bg-indigo-50 p-6 transition-transform hover:-translate-y-1">
                <p class="text-[10px] font-black text-[#312e81] uppercase tracking-widest mb-1">Net Profit</p>
                <p class="text-3xl font-black text-[#3730a3]">{{ number_format($financials['net'], 2) }} <span class="text-sm text-indigo-400">JOD</span></p>
            </div>

            <div class="bg-white overflow-hidden shadow-sm rounded-2xl border border-gray-100 p-6 transition-transform hover:-translate-y-1">
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Fleet Drivers</p>
                <p class="text-3xl font-black text-gray-900">{{ $drivers->count() }}</p>
            </div>
        </div>

        {{-- 2. Trip Scheduling & Dispatching --}}
        <div class="bg-white overflow-hidden shadow-sm rounded-[2rem] border border-gray-100 p-8">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-black text-gray-800 tracking-tight">Trip Scheduling & Dispatch</h3>
                <a href="#" class="px-5 py-2.5 bg-[#312e81] hover:bg-[#3730a3] text-white text-[10px] uppercase tracking-widest font-black rounded-xl transition-all shadow-md active:scale-95">
                    + Register Driver
                </a>
            </div>

            @if($recentTrips->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full text-left whitespace-nowrap">
                        <thead class="bg-gray-50 border-b border-gray-100 rounded-t-xl">
                            <tr>
                                <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Trip ID</th>
                                <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Route</th>
                                <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Schedule</th>
                                <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Net Value</th>
                                <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Status</th>
                                <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Assigned Driver</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @foreach($recentTrips as $trip)
                                <tr class="hover:bg-gray-50/50 transition-colors">
                                    <td class="px-6 py-4 font-black text-gray-900">#{{ substr($trip->id, 0, 8) }}</td>
                                    <td class="px-6 py-4">
                                        <span class="text-sm font-bold text-gray-700">{{ $trip->user->name ?? 'Customer' }}</span><br>
                                        <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">{{ $trip->start_and_return_point ?? 'Pickup' }} &rarr; {{ $trip->farm->name ?? 'Farm' }}</span>
                                    </td>
                                    <td class="px-6 py-4 text-sm font-bold text-gray-600">
                                        {{ \Carbon\Carbon::parse($trip->Farm_Arrival_Time ?? now())->format('M d, Y') }}<br>
                                        <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">{{ \Carbon\Carbon::parse($trip->Farm_Arrival_Time ?? now())->format('h:i A') }}</span>
                                    </td>
                                    <td class="px-6 py-4 text-sm font-black text-[#312e81]">
                                        {{ number_format($trip->net_company_amount ?? 0, 2) }} JOD
                                    </td>
                                    <td class="px-6 py-4">
                                        @if(in_array($trip->status, ['completed', 'finished', 'delivered']))
                                            <span class="bg-green-50 text-green-600 text-[10px] font-black px-3 py-1.5 rounded-md border border-green-200 uppercase tracking-widest">Completed</span>
                                        @elseif(in_array($trip->status, ['assigned', 'in_progress', 'started']))
                                            <span class="bg-indigo-50 text-[#312e81] text-[10px] font-black px-3 py-1.5 rounded-md border border-indigo-200 uppercase tracking-widest">Dispatched</span>
                                        @else
                                            <span class="bg-amber-50 text-amber-600 text-[10px] font-black px-3 py-1.5 rounded-md border border-amber-200 uppercase tracking-widest">Pending</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($trip->driver_id)
                                            <div class="flex items-center space-x-3">
                                                <div class="w-8 h-8 rounded-full bg-indigo-100 text-[#312e81] flex items-center justify-center text-xs font-black border border-indigo-200">
                                                    {{ strtoupper(substr($trip->driver->name, 0, 1)) }}
                                                </div>
                                                <span class="text-sm font-bold text-gray-700">{{ $trip->driver->name }}</span>
                                            </div>
                                        @else
                                            {{-- Dispatching Form --}}
                                            <form method="POST" action="{{ route('transport.assign_driver', $trip->id) }}" class="flex items-center space-x-2">
                                                @csrf
                                                @method('PATCH')
                                                <select name="driver_id" required class="text-xs border-gray-200 rounded-lg focus:ring-[#312e81] focus:border-[#312e81] font-bold text-gray-600 py-2 pl-3 pr-8 shadow-sm">
                                                    <option value="">Select Driver...</option>
                                                    @foreach($drivers as $driver)
                                                        <option value="{{ $driver->id }}">{{ $driver->name }}</option>
                                                    @endforeach
                                                </select>
                                                <button type="submit" class="bg-[#312e81] text-white hover:bg-[#3730a3] px-4 py-2 rounded-lg text-[10px] uppercase tracking-widest font-black transition-transform active:scale-95 shadow-sm">
                                                    Assign
                                                </button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-12">
                    <svg class="w-16 h-16 text-gray-200 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <p class="text-sm font-black uppercase tracking-widest text-gray-400">No trips awaiting scheduling.</p>
                </div>
            @endif
        </div>

    </div>
@endsection
