{{-- 💡 THE FIX: Changed from layouts.app to layouts.transport to get the Sidebar UI --}}
@extends('layouts.transport')

@section('title', 'Fleet Dashboard')

@section('content')
<div class="px-6 py-8 md:px-10">

    {{-- Header Section --}}
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-10">
        <div>
            <h1 class="text-3xl md:text-4xl font-black text-slate-900 tracking-tight">Fleet Operations</h1>
            <p class="text-xs font-bold text-slate-500 mt-2 uppercase tracking-widest">Welcome back, {{ explode(' ', Auth::user()->name)[0] }}. Here is your fleet status.</p>
        </div>

        <div class="flex gap-4 shrink-0">
            <a href="{{ route('transport.vehicles.index') }}" class="inline-flex items-center gap-2 bg-cyan-600 hover:bg-cyan-700 text-white font-black py-3 px-6 rounded-xl transition-all shadow-lg shadow-cyan-600/30 border border-cyan-700 transform active:scale-95 text-[11px] uppercase tracking-widest">
                <svg class="w-4 h-4 text-cyan-100" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" /></svg>
                Manage Fleet
            </a>
        </div>
    </div>

    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="bg-cyan-50 border border-cyan-200 text-cyan-800 px-6 py-4 rounded-xl shadow-sm font-bold mb-8 flex items-center gap-3">
            <div class="bg-cyan-500 rounded-full p-1"><svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" /></svg></div>
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="bg-rose-50 border border-rose-200 text-rose-800 px-6 py-4 rounded-xl shadow-sm font-bold mb-8 flex items-center gap-3">
            <div class="bg-rose-500 rounded-full p-1"><svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12" /></svg></div>
            {{ session('error') }}
        </div>
    @endif

    {{-- Financial Overview --}}
    <h2 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4">Financial Overview (Completed Trips)</h2>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
        {{-- Gross Revenue --}}
        <div class="bg-white rounded-3xl p-6 shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-slate-100 flex items-center gap-5 hover:shadow-md transition-shadow">
            <div class="w-14 h-14 rounded-2xl bg-slate-50 flex items-center justify-center text-slate-400 shadow-inner border border-slate-100 shrink-0">
                <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Gross Revenue</p>
                <p class="text-2xl font-black text-slate-900">{{ number_format($financials['gross'] ?? 0, 2) }} <span class="text-[10px] text-slate-400">JOD</span></p>
            </div>
        </div>

        {{-- Platform Fees --}}
        <div class="bg-white rounded-3xl p-6 shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-slate-100 flex items-center gap-5 hover:shadow-md transition-shadow">
            <div class="w-14 h-14 rounded-2xl bg-rose-50 flex items-center justify-center text-rose-500 shadow-inner border border-rose-100 shrink-0">
                <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"/></svg>
            </div>
            <div>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Platform Fees (10%)</p>
                <p class="text-2xl font-black text-rose-600">-{{ number_format($financials['commission'] ?? 0, 2) }} <span class="text-[10px] text-rose-400">JOD</span></p>
            </div>
        </div>

        {{-- Net Earnings --}}
        <div class="bg-gradient-to-br from-cyan-600 to-blue-700 rounded-3xl p-6 shadow-[0_10px_25px_rgba(8,145,178,0.3)] flex items-center gap-5 relative overflow-hidden transform hover:-translate-y-1 transition-all">
            <div class="absolute -right-6 -bottom-6 opacity-10">
                <svg class="h-32 w-32 text-white" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M12 7a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0V8.414l-4.293 4.293a1 1 0 01-1.414 0L8 10.414l-4.293 4.293a1 1 0 01-1.414-1.414l5-5a1 1 0 011.414 0L11 10.586 14.586 7H12z" clip-rule="evenodd"/></svg>
            </div>
            <div class="w-14 h-14 rounded-2xl bg-white/20 backdrop-blur-md border border-white/20 flex items-center justify-center text-white shrink-0 relative z-10">
                <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div class="relative z-10">
                <p class="text-[10px] font-black text-cyan-100 uppercase tracking-widest mb-1">Net Earnings</p>
                <p class="text-2xl font-black text-white">{{ number_format($financials['net'] ?? 0, 2) }} <span class="text-[10px] text-cyan-200">JOD</span></p>
            </div>
        </div>
    </div>

    {{-- Operations & Fleet Status --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-10">
        {{-- Trip Operations --}}
        <div class="bg-white rounded-[2rem] shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-slate-100 p-6 md:p-8">
            <h2 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-5">Trip Operations</h2>
            <div class="grid grid-cols-2 gap-4">
                <div class="bg-slate-50 rounded-2xl p-5 border border-slate-100 flex flex-col justify-center">
                    <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-1">Total Trips</p>
                    <p class="text-3xl font-black text-slate-900">{{ $totalTrips ?? 0 }}</p>
                </div>
                <div class="bg-amber-50 rounded-2xl p-5 border border-amber-100 flex flex-col justify-center">
                    <p class="text-[10px] font-bold text-amber-600 uppercase tracking-widest flex items-center gap-1.5 mb-1">
                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span> Active
                    </p>
                    <p class="text-3xl font-black text-amber-700">{{ $activeTrips ?? 0 }}</p>
                </div>
                <div class="col-span-2 bg-indigo-50 rounded-2xl p-5 border border-indigo-100 flex justify-between items-end">
                    <div>
                        <p class="text-[10px] font-bold text-indigo-700 uppercase tracking-widest mb-0.5">Completed</p>
                        <p class="text-[9px] font-bold text-indigo-500 uppercase tracking-widest">Successfully delivered</p>
                    </div>
                    <p class="text-4xl font-black text-indigo-800">{{ $completedTrips ?? 0 }}</p>
                </div>
            </div>
        </div>

        {{-- Fleet Status --}}
        <div class="bg-white rounded-[2rem] shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-slate-100 p-6 md:p-8 flex flex-col">
            <h2 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-5">Fleet Status</h2>

            <div class="flex-1 flex flex-col justify-center space-y-5">
                {{-- Vehicles --}}
                <div class="flex items-center justify-between bg-slate-50 p-4 rounded-2xl border border-slate-100">
                    <div class="flex items-center gap-4">
                        <div class="h-12 w-12 rounded-xl bg-cyan-50 border border-cyan-100 text-cyan-600 flex items-center justify-center shrink-0">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                        </div>
                        <div>
                            <p class="text-xs font-black text-slate-900 uppercase tracking-widest">Vehicles</p>
                            <p class="text-[9px] font-bold text-cyan-500 uppercase tracking-widest mt-0.5">{{ $availableVehicles ?? 0 }} available</p>
                        </div>
                    </div>
                    <span class="text-2xl font-black text-slate-900">{{ $totalVehicles ?? 0 }}</span>
                </div>

                {{-- Drivers --}}
                <div class="flex items-center justify-between bg-slate-50 p-4 rounded-2xl border border-slate-100">
                    <div class="flex items-center gap-4">
                        <div class="h-12 w-12 rounded-xl bg-blue-50 border border-blue-100 text-blue-600 flex items-center justify-center shrink-0">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        </div>
                        <div>
                            <p class="text-xs font-black text-slate-900 uppercase tracking-widest">Drivers</p>
                            <p class="text-[9px] font-bold text-blue-500 uppercase tracking-widest mt-0.5">Ready for assignment</p>
                        </div>
                    </div>
                    <span class="text-2xl font-black text-slate-900">{{ $totalDrivers ?? 0 }}</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Available Market Jobs --}}
    <div class="mb-10">
        <div class="flex items-center gap-3 mb-5">
            <div class="w-1.5 h-6 bg-cyan-500 rounded-full"></div>
            <h2 class="text-lg font-black text-slate-900 tracking-tight">Available Market Jobs</h2>
            @if(isset($availableJobs) && $availableJobs->count() > 0)
                <span class="bg-cyan-100 text-cyan-700 text-[9px] font-black px-2.5 py-1 rounded-md uppercase tracking-widest">{{ $availableJobs->count() }} NEW</span>
            @endif
        </div>

        <div class="bg-white rounded-3xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-slate-100 overflow-hidden">
            @if(isset($availableJobs) && $availableJobs->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full text-left whitespace-nowrap">
                        <thead>
                            <tr>
                                <th class="px-6 py-4 border-b border-slate-100 bg-slate-50 text-[9px] font-black text-slate-400 uppercase tracking-widest">Job Details</th>
                                <th class="px-6 py-4 border-b border-slate-100 bg-slate-50 text-[9px] font-black text-slate-400 uppercase tracking-widest">Route</th>
                                <th class="px-6 py-4 border-b border-slate-100 bg-slate-50 text-[9px] font-black text-slate-400 uppercase tracking-widest">Date & Time</th>
                                <th class="px-6 py-4 border-b border-slate-100 bg-slate-50 text-right text-[9px] font-black text-slate-400 uppercase tracking-widest">Est. Revenue</th>
                                <th class="px-6 py-4 border-b border-slate-100 bg-slate-50 text-right text-[9px] font-black text-slate-400 uppercase tracking-widest">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @foreach($availableJobs as $job)
                                <tr class="hover:bg-cyan-50/30 transition-colors duration-200">
                                    <td class="px-6 py-5">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 bg-slate-50 rounded-xl flex items-center justify-center text-slate-600 font-black text-xs border border-slate-100 shrink-0">
                                                #{{ $job->id }}
                                            </div>
                                            <div>
                                                <p class="text-sm font-black text-slate-900">{{ $job->transport_type ?? 'Transport' }}</p>
                                                <p class="text-[10px] font-bold text-slate-400 mt-0.5 uppercase tracking-widest">{{ $job->passengers ?? 0 }} PAX</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-5">
                                        <div class="flex flex-col gap-1.5">
                                            <div class="flex items-center gap-2 text-xs font-bold text-slate-500">
                                                <svg class="w-3.5 h-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                                {{ Str::limit($job->pickup_location ?? $job->start_and_return_point ?? 'TBD', 25) }}
                                            </div>
                                            <div class="flex items-center gap-2 text-xs font-bold text-slate-900">
                                                <svg class="w-3.5 h-3.5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" /></svg>
                                                {{ $job->farmBooking->farm->name ?? $job->farm->name ?? 'Farm' }}
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-5">
                                        <p class="text-xs font-black text-slate-900">{{ optional($job->pickup_time ?? $job->Farm_Arrival_Time)->format('M d, Y') ?? 'N/A' }}</p>
                                        <p class="text-[9px] font-bold text-slate-400 mt-1 uppercase tracking-widest">{{ optional($job->pickup_time ?? $job->Farm_Arrival_Time)->format('h:i A') ?? 'N/A' }}</p>
                                    </td>
                                    <td class="px-6 py-5 text-right">
                                        <p class="text-base font-black text-slate-900">{{ number_format($job->net_company_amount ?? 0, 2) }} <span class="text-[9px] text-slate-400">JOD</span></p>
                                    </td>
                                    <td class="px-6 py-5 text-right">
                                        <form action="{{ route('transport.dispatch.accept', $job->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="bg-cyan-600 hover:bg-cyan-700 text-white text-[10px] font-black uppercase tracking-widest py-2.5 px-5 rounded-xl shadow-sm transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-cyan-500">
                                                Accept Job
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="px-6 py-12 text-center flex flex-col items-center">
                    <div class="w-16 h-16 rounded-2xl bg-slate-50 flex items-center justify-center mb-4 border border-slate-100 text-slate-400">
                        <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" /></svg>
                    </div>
                    <h3 class="text-sm font-black text-slate-900 mb-1">No Market Jobs</h3>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Waiting for new farm requests to be broadcasted.</p>
                </div>
            @endif
        </div>
    </div>

    {{-- My Company's Dispatches --}}
    <div>
        <div class="flex items-center gap-3 mb-5">
            <div class="w-1.5 h-6 bg-blue-500 rounded-full"></div>
            <h2 class="text-lg font-black text-slate-900 tracking-tight">My Company's Dispatches</h2>
        </div>

        <div class="bg-white rounded-[2rem] shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-slate-100 overflow-hidden">
            @if(isset($myJobs) && $myJobs->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full text-left whitespace-nowrap">
                        <thead>
                            <tr>
                                <th class="px-6 py-4 border-b border-slate-100 bg-slate-50 text-[9px] font-black text-slate-400 uppercase tracking-widest">ID</th>
                                <th class="px-6 py-4 border-b border-slate-100 bg-slate-50 text-[9px] font-black text-slate-400 uppercase tracking-widest">Customer / Route</th>
                                <th class="px-6 py-4 border-b border-slate-100 bg-slate-50 text-[9px] font-black text-slate-400 uppercase tracking-widest">Fleet Details</th>
                                <th class="px-6 py-4 border-b border-slate-100 bg-slate-50 text-[9px] font-black text-slate-400 uppercase tracking-widest">Timing</th>
                                <th class="px-6 py-4 border-b border-slate-100 bg-slate-50 text-[9px] font-black text-slate-400 uppercase tracking-widest">Status</th>
                                <th class="px-6 py-4 border-b border-slate-100 bg-slate-50 text-right text-[9px] font-black text-slate-400 uppercase tracking-widest">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @foreach($myJobs as $job)
                                <tr class="hover:bg-slate-50/50 transition-colors duration-200">
                                    <td class="px-6 py-5">
                                        <span class="text-[10px] font-black text-slate-600 bg-slate-100 px-2.5 py-1 rounded-md border border-slate-200">#{{ $job->id }}</span>
                                    </td>
                                    <td class="px-6 py-5">
                                        <p class="text-xs font-black text-slate-900 mb-1">{{ $job->user->name ?? 'Guest User' }}</p>
                                        <div class="flex items-center gap-1.5 text-[9px] font-bold text-slate-500 uppercase tracking-widest">
                                            <svg class="h-3 w-3 text-slate-400" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" /></svg>
                                            {{ Str::limit($job->pickup_location ?? $job->start_and_return_point ?? 'TBD', 20) }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-5">
                                        @if($job->vehicle_id && $job->driver_id)
                                            <div class="flex items-center gap-3">
                                                <div class="w-8 h-8 rounded-lg bg-blue-50 flex items-center justify-center text-blue-600 border border-blue-100 shrink-0">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
                                                </div>
                                                <div>
                                                    <p class="text-xs font-black text-slate-900">{{ $job->vehicle->plate_number ?? $job->vehicle->license_plate ?? 'N/A' }}</p>
                                                    <p class="text-[9px] font-bold text-slate-500 uppercase tracking-widest mt-0.5">{{ $job->driver->name ?? 'N/A' }}</p>
                                                </div>
                                            </div>
                                        @else
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-[9px] font-black uppercase tracking-widest bg-rose-50 text-rose-600 border border-rose-100">
                                                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                                Needs Assignment
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-5">
                                        <p class="text-xs font-black text-slate-900">{{ optional($job->pickup_time ?? $job->Farm_Arrival_Time)->format('M d, Y') ?? 'N/A' }}</p>
                                        <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mt-1">{{ optional($job->pickup_time ?? $job->Farm_Arrival_Time)->format('h:i A') ?? 'N/A' }}</p>
                                    </td>
                                    <td class="px-6 py-5">
                                        @php
                                            $statusConfig = [
                                                'accepted' => ['bg-blue-50', 'text-blue-600', 'border-blue-100', 'bg-blue-500'],
                                                'assigned' => ['bg-indigo-50', 'text-indigo-600', 'border-indigo-100', 'bg-indigo-500'],
                                                'in_progress' => ['bg-amber-50', 'text-amber-600', 'border-amber-100', 'bg-amber-500'],
                                                'in_way' => ['bg-cyan-50', 'text-cyan-600', 'border-cyan-100', 'bg-cyan-500'],
                                                'completed' => ['bg-emerald-50', 'text-emerald-600', 'border-emerald-100', 'bg-emerald-500'],
                                                'cancelled' => ['bg-rose-50', 'text-rose-600', 'border-rose-100', 'bg-rose-500'],
                                                'pending' => ['bg-slate-50', 'text-slate-600', 'border-slate-200', 'bg-slate-400'],
                                            ];
                                            $config = $statusConfig[$job->status] ?? $statusConfig['pending'];
                                        @endphp
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-[9px] font-black uppercase tracking-widest border {{ $config[0] }} {{ $config[1] }} {{ $config[2] }}">
                                            <span class="w-1.5 h-1.5 rounded-full {{ $config[3] }}"></span>
                                            {{ str_replace('_', ' ', $job->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-5 text-right">
                                        <a href="{{ route('transport.dispatch.edit', $job->id) }}" class="inline-flex items-center justify-center px-4 py-2 border border-slate-200 rounded-lg text-[10px] font-black uppercase tracking-widest text-slate-600 bg-white hover:bg-slate-50 hover:text-slate-900 transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-slate-900">
                                            Manage
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @if($myJobs->hasPages())
                    <div class="px-6 py-4 bg-slate-50 border-t border-slate-100">
                        {{ $myJobs->links() }}
                    </div>
                @endif
            @else
                <div class="px-6 py-12 text-center flex flex-col items-center">
                    <div class="w-16 h-16 rounded-2xl bg-slate-50 flex items-center justify-center mb-4 border border-slate-100 text-slate-400">
                        <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" /></svg>
                    </div>
                    <h3 class="text-sm font-black text-slate-900 mb-1">No Active Dispatches</h3>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Accept jobs from the market above to build your list.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
