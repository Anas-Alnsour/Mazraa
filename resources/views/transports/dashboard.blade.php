@extends('layouts.transport')

@section('title', 'Fleet Command')

@section('content')
<style>
    .fade-in-up { animation: fadeInUp 0.8s cubic-bezier(0.2, 0.8, 0.2, 1) forwards; opacity: 0; }
    .fade-in-up-stagger { animation: fadeInUp 0.8s cubic-bezier(0.2, 0.8, 0.2, 1) both; }
    @keyframes fadeInUp { 0% { opacity: 0; transform: translateY(20px); } 100% { opacity: 1; transform: translateY(0); } }

    .table-scroll::-webkit-scrollbar { height: 8px; width: 6px; }
    .table-scroll::-webkit-scrollbar-track { background: rgba(15, 23, 42, 0.5); border-radius: 8px; }
    .table-scroll::-webkit-scrollbar-thumb { background: rgba(51, 65, 85, 0.8); border-radius: 8px; }
    .table-scroll::-webkit-scrollbar-thumb:hover { background: #06b6d4; }
</style>

<div class="max-w-[96%] xl:max-w-7xl mx-auto space-y-10 pb-24 animate-god-in">

    {{-- 🌟 1. Floating Toast Notifications --}}
    <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 6000)" class="fixed top-24 right-5 z-[150] flex flex-col gap-4 pointer-events-none">
        @if(session('success'))
            <div x-show="show" x-transition:enter="transition ease-out duration-300 transform" x-transition:enter-start="opacity-0 translate-x-10" x-transition:enter-end="opacity-100 translate-x-0" x-transition:leave="transition ease-in duration-300 transform" x-transition:leave-start="opacity-100 translate-x-0" x-transition:leave-end="opacity-0 translate-x-10" class="pointer-events-auto bg-slate-900/95 backdrop-blur-2xl border border-cyan-500/40 rounded-2xl shadow-[0_20px_50px_rgba(6,182,212,0.2)] p-5 flex items-start gap-4 w-96 relative overflow-hidden" x-cloak>
                <div class="absolute bottom-0 left-0 h-1 bg-cyan-500 w-full animate-[progress-shrink_6s_linear_forwards]"></div>
                <div class="bg-cyan-500/20 p-2.5 rounded-xl text-cyan-400 shrink-0 border border-cyan-500/30 shadow-inner"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg></div>
                <div class="flex-1 mt-0.5"><h4 class="font-black text-white text-[11px] uppercase tracking-[0.2em]">Execution Success</h4><p class="text-slate-400 text-xs mt-1.5 font-medium leading-relaxed">{{ session('success') }}</p></div>
                <button @click="show = false" class="text-slate-600 hover:text-white transition-colors p-1"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg></button>
            </div>
        @endif

        @if(session('error'))
            <div x-show="show" x-transition:enter="transition ease-out duration-300 transform" x-transition:enter-start="opacity-0 translate-x-10" x-transition:enter-end="opacity-100 translate-x-0" x-transition:leave="transition ease-in duration-300 transform" x-transition:leave-start="opacity-100 translate-x-0" x-transition:leave-end="opacity-0 translate-x-10" class="pointer-events-auto bg-slate-900/95 backdrop-blur-2xl border border-rose-500/40 rounded-2xl shadow-[0_20px_50px_rgba(244,63,94,0.2)] p-5 flex items-start gap-4 w-96 relative overflow-hidden" x-cloak>
                <div class="absolute bottom-0 left-0 h-1 bg-rose-500 w-full animate-[progress-shrink_6s_linear_forwards]"></div>
                <div class="bg-rose-500/20 p-2.5 rounded-xl text-rose-400 shrink-0 border border-rose-500/30 shadow-inner"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg></div>
                <div class="flex-1 mt-0.5"><h4 class="font-black text-white text-[11px] uppercase tracking-[0.2em]">System Alert</h4><p class="text-slate-400 text-xs mt-1.5 font-medium leading-relaxed">{{ session('error') }}</p></div>
                <button @click="show = false" class="text-slate-600 hover:text-white transition-colors p-1"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg></button>
            </div>
        @endif
    </div>
    <style>@keyframes progress-shrink { from { width: 100%; } to { width: 0%; } }</style>

    {{-- 🌟 2. Header Section (Ultra-Modern) --}}
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 bg-slate-900/80 p-8 md:p-12 rounded-[3rem] border border-slate-800 shadow-2xl relative overflow-hidden backdrop-blur-2xl transition-all hover:border-cyan-500/30 fade-in-up">
        <div class="absolute -right-20 -top-20 w-80 h-80 bg-cyan-500/10 blur-[120px] rounded-full pointer-events-none"></div>

        <div class="relative z-10 flex flex-col md:flex-row items-center gap-6 text-center md:text-left">
            <div class="w-16 h-16 rounded-[1.5rem] bg-cyan-500/10 border border-cyan-500/20 flex items-center justify-center text-cyan-400 shadow-[0_0_20px_rgba(6,182,212,0.2)] shrink-0">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"></path></svg>
            </div>
            <div>
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-slate-950/50 border border-slate-700 text-[9px] font-black text-slate-400 uppercase tracking-widest mb-3 shadow-inner mx-auto md:mx-0">
                    <span class="w-1.5 h-1.5 rounded-full bg-cyan-500 animate-pulse"></span> Transport Node
                </div>
                <h1 class="text-4xl md:text-5xl font-black text-white tracking-tighter mb-1">Fleet <span class="text-transparent bg-clip-text bg-gradient-to-r from-cyan-400 to-blue-400">Operations</span></h1>
                <p class="text-slate-400 text-xs font-bold uppercase tracking-[0.2em] mt-2">Manage routing, vehicles, and global dispatch.</p>
            </div>
        </div>

        <div class="relative z-10 w-full md:w-auto mt-6 md:mt-0 flex justify-center md:justify-end gap-3">
            <a href="{{ route('transport.vehicles.index') }}" class="w-full md:w-auto px-8 py-5 bg-gradient-to-tr from-cyan-600 to-cyan-400 hover:to-cyan-300 text-slate-950 font-black text-[11px] uppercase tracking-[0.2em] rounded-2xl shadow-[0_10px_30px_rgba(6,182,212,0.3)] transform hover:-translate-y-1 active:scale-95 transition-all flex items-center justify-center gap-3">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 6v6m0 0v6m0-6h6m-6 0H6" /></svg>
                Manage Fleet
            </a>
        </div>
    </div>

    {{-- 🌟 3. Financial Overview (God Mode Grid) --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

        {{-- Gross Revenue --}}
        <div class="bg-slate-900/80 backdrop-blur-xl rounded-[2.5rem] p-8 border border-slate-800 shadow-xl relative overflow-hidden hover:border-cyan-500/40 transition-all duration-500 group fade-in-up-stagger" style="animation-delay: 0.1s;">
            <div class="absolute -right-10 -top-10 w-32 h-32 bg-cyan-500/10 rounded-full blur-[50px] group-hover:bg-cyan-500/20 transition-all duration-500"></div>
            <div class="flex items-center gap-4 mb-6 relative z-10">
                <div class="w-12 h-12 bg-slate-950 rounded-xl flex items-center justify-center text-cyan-400 border border-slate-800 shadow-inner group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest">Gross Revenue</p>
            </div>
            <div class="relative z-10 flex items-baseline gap-2">
                <p class="text-4xl font-black text-white tracking-tighter">{{ number_format($financials['gross'] ?? 0, 2) }}</p>
                <span class="text-xs font-bold text-cyan-500 uppercase tracking-widest">JOD</span>
            </div>
        </div>

        {{-- Platform Fees --}}
        <div class="bg-slate-900/80 backdrop-blur-xl rounded-[2.5rem] p-8 border border-slate-800 shadow-xl relative overflow-hidden hover:border-rose-500/40 transition-all duration-500 group fade-in-up-stagger" style="animation-delay: 0.2s;">
            <div class="absolute -right-10 -top-10 w-32 h-32 bg-rose-500/10 rounded-full blur-[50px] group-hover:bg-rose-500/20 transition-all duration-500"></div>
            <div class="flex items-center gap-4 mb-6 relative z-10">
                <div class="w-12 h-12 bg-slate-950 rounded-xl flex items-center justify-center text-rose-500 border border-slate-800 shadow-inner group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"/></svg>
                </div>
                <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest">Platform Tax (10%)</p>
            </div>
            <div class="relative z-10 flex items-baseline gap-2">
                <p class="text-4xl font-black text-rose-400 tracking-tighter">-{{ number_format($financials['commission'] ?? 0, 2) }}</p>
                <span class="text-xs font-bold text-rose-500 uppercase tracking-widest">JOD</span>
            </div>
        </div>

        {{-- Net Earnings --}}
        <div class="bg-slate-900/80 backdrop-blur-xl rounded-[2.5rem] p-8 border border-indigo-500/30 shadow-[0_0_30px_rgba(99,102,241,0.1)] relative overflow-hidden hover:border-indigo-400/50 transition-all duration-500 group fade-in-up-stagger" style="animation-delay: 0.3s;">
            <div class="absolute -left-10 -bottom-10 w-40 h-40 bg-indigo-500/20 rounded-full blur-[60px] group-hover:bg-indigo-500/30 transition-all duration-500"></div>
            <div class="flex items-center gap-4 mb-6 relative z-10">
                <div class="w-12 h-12 bg-indigo-500/10 rounded-xl flex items-center justify-center text-indigo-400 border border-indigo-500/30 shadow-inner group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <p class="text-[10px] font-black text-indigo-400 uppercase tracking-widest">Net Earnings</p>
            </div>
            <div class="relative z-10 flex items-baseline gap-2">
                <p class="text-4xl font-black text-white tracking-tighter">{{ number_format($financials['net'] ?? 0, 2) }}</p>
                <span class="text-xs font-bold text-indigo-400 uppercase tracking-widest">JOD</span>
            </div>
        </div>
    </div>

    {{-- 🌟 4. Operations & Fleet Status --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 fade-in-up" style="animation-delay: 0.4s;">

        {{-- Trip Operations --}}
        <div class="bg-slate-900/60 rounded-[3rem] shadow-2xl border border-slate-800 p-8 md:p-10 backdrop-blur-2xl flex flex-col h-full relative overflow-hidden">
            <div class="absolute -right-10 -top-10 w-48 h-48 bg-blue-500/5 rounded-full blur-[80px] pointer-events-none"></div>
            <h2 class="text-xs font-black text-slate-500 uppercase tracking-[0.2em] mb-6 border-b border-slate-800 pb-4 relative z-10">Trip Matrix</h2>

            <div class="grid grid-cols-2 gap-4 flex-1 relative z-10">
                <div class="bg-slate-950 rounded-2xl p-6 border border-slate-800 flex flex-col justify-center shadow-inner hover:border-slate-700 transition-colors">
                    <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-1">Total Trips</p>
                    <p class="text-3xl font-black text-white">{{ $totalTrips ?? 0 }}</p>
                </div>
                <div class="bg-amber-500/10 rounded-2xl p-6 border border-amber-500/20 flex flex-col justify-center shadow-inner hover:border-amber-500/40 transition-colors">
                    <p class="text-[10px] font-bold text-amber-500 uppercase tracking-widest flex items-center gap-1.5 mb-1">
                        <span class="w-1.5 h-1.5 rounded-full bg-amber-400 animate-pulse"></span> Active
                    </p>
                    <p class="text-3xl font-black text-amber-400">{{ $activeTrips ?? 0 }}</p>
                </div>
                <div class="col-span-2 bg-indigo-500/10 rounded-2xl p-6 border border-indigo-500/20 flex justify-between items-end shadow-inner hover:border-indigo-500/40 transition-colors">
                    <div>
                        <p class="text-[10px] font-bold text-indigo-400 uppercase tracking-widest mb-1">Completed</p>
                        <p class="text-[9px] font-black text-slate-500 uppercase tracking-widest">Successfully delivered</p>
                    </div>
                    <p class="text-5xl font-black text-indigo-400">{{ $completedTrips ?? 0 }}</p>
                </div>
            </div>
        </div>

        {{-- Fleet Status --}}
        <div class="bg-slate-900/60 rounded-[3rem] shadow-2xl border border-slate-800 p-8 md:p-10 backdrop-blur-2xl flex flex-col h-full relative overflow-hidden">
            <div class="absolute -right-10 -top-10 w-48 h-48 bg-cyan-500/5 rounded-full blur-[80px] pointer-events-none"></div>
            <h2 class="text-xs font-black text-slate-500 uppercase tracking-[0.2em] mb-6 border-b border-slate-800 pb-4 relative z-10">Fleet Status</h2>

            <div class="flex-1 flex flex-col justify-center space-y-5 relative z-10">
                <div class="flex items-center justify-between bg-slate-950 p-6 rounded-2xl border border-slate-800 shadow-inner hover:border-cyan-500/30 transition-colors group">
                    <div class="flex items-center gap-5">
                        <div class="h-12 w-12 rounded-xl bg-cyan-500/10 border border-cyan-500/20 text-cyan-400 flex items-center justify-center shrink-0 group-hover:scale-110 transition-transform">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                        </div>
                        <div>
                            <p class="text-sm font-black text-white uppercase tracking-widest mb-1">Vehicles</p>
                            <p class="text-[9px] font-bold text-cyan-500 uppercase tracking-widest">{{ $availableVehicles ?? 0 }} available</p>
                        </div>
                    </div>
                    <span class="text-3xl font-black text-white">{{ $totalVehicles ?? 0 }}</span>
                </div>

                <div class="flex items-center justify-between bg-slate-950 p-6 rounded-2xl border border-slate-800 shadow-inner hover:border-blue-500/30 transition-colors group">
                    <div class="flex items-center gap-5">
                        <div class="h-12 w-12 rounded-xl bg-blue-500/10 border border-blue-500/20 text-blue-400 flex items-center justify-center shrink-0 group-hover:scale-110 transition-transform">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        </div>
                        <div>
                            <p class="text-sm font-black text-white uppercase tracking-widest mb-1">Drivers</p>
                            <p class="text-[9px] font-bold text-blue-500 uppercase tracking-widest">Ready for assignment</p>
                        </div>
                    </div>
                    <span class="text-3xl font-black text-white">{{ $totalDrivers ?? 0 }}</span>
                </div>
            </div>
        </div>
    </div>

    {{-- 🌟 5. Available Market Jobs --}}
    <div class="fade-in-up" style="animation-delay: 0.5s;">
        <div class="flex items-center gap-3 mb-6">
            <div class="w-1.5 h-6 bg-cyan-500 rounded-full shadow-[0_0_10px_rgba(6,182,212,0.5)]"></div>
            <h2 class="text-2xl font-black text-white tracking-tight">Available Market Jobs</h2>
            @if(isset($availableJobs) && $availableJobs->count() > 0)
                <span class="bg-cyan-500/20 text-cyan-400 border border-cyan-500/30 text-[9px] font-black px-3 py-1.5 rounded-lg uppercase tracking-widest shadow-inner ml-2 animate-pulse">{{ $availableJobs->count() }} NEW</span>
            @endif
        </div>

        <div class="bg-slate-900/60 rounded-[3rem] shadow-2xl border border-slate-800 overflow-hidden backdrop-blur-2xl">
            @if(isset($availableJobs) && $availableJobs->count() > 0)
                <div class="overflow-x-auto table-scroll bg-slate-900/20">
                    <table class="w-full text-left border-collapse min-w-[900px]">
                        <thead class="bg-slate-950/80 border-b border-slate-800">
                            <tr>
                                <th class="px-8 py-7 text-[9px] font-black text-slate-500 uppercase tracking-widest whitespace-nowrap">Job Identity</th>
                                <th class="px-8 py-7 text-[9px] font-black text-slate-500 uppercase tracking-widest whitespace-nowrap">Route Vector</th>
                                <th class="px-8 py-7 text-[9px] font-black text-slate-500 uppercase tracking-widest whitespace-nowrap">Time Node</th>
                                <th class="px-8 py-7 text-[9px] font-black text-slate-500 uppercase tracking-widest text-right whitespace-nowrap">Est. Revenue</th>
                                <th class="px-8 py-7 text-[9px] font-black text-slate-500 uppercase tracking-widest text-right whitespace-nowrap">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-800/40">
                            @foreach($availableJobs as $job)
                                <tr class="hover:bg-white/5 transition-colors duration-200 group">
                                    <td class="px-8 py-6 whitespace-nowrap">
                                        <div class="flex items-center gap-4">
                                            <div class="w-12 h-12 bg-slate-950 rounded-[1rem] flex items-center justify-center text-slate-400 font-black text-xs border border-slate-700 shadow-inner group-hover:border-cyan-500/50 group-hover:text-cyan-400 transition-colors shrink-0">
                                                #{{ $job->id }}
                                            </div>
                                            <div>
                                                <p class="text-sm font-black text-white">{{ $job->transport_type ?? 'Transport' }}</p>
                                                <p class="text-[9px] font-bold text-slate-500 mt-1 uppercase tracking-widest">{{ $job->passengers ?? 0 }} PAX</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-8 py-6 whitespace-nowrap">
                                        <div class="flex flex-col gap-2">
                                            <div class="flex items-center gap-2 text-xs font-bold text-slate-400">
                                                <svg class="w-4 h-4 text-slate-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                                <span class="truncate max-w-[200px]">{{ $job->pickup_location ?? $job->start_and_return_point ?? 'TBD' }}</span>
                                            </div>
                                            <div class="flex items-center gap-2 text-xs font-bold text-white">
                                                <svg class="w-4 h-4 text-indigo-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" /></svg>
                                                <span class="truncate max-w-[200px]">{{ $job->farmBooking->farm->name ?? $job->farm->name ?? 'Farm' }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-8 py-6 whitespace-nowrap">
                                        <p class="text-xs font-black text-slate-200">{{ optional($job->pickup_time ?? $job->Farm_Arrival_Time)->format('M d, Y') ?? 'N/A' }}</p>
                                        <p class="text-[9px] font-bold text-slate-500 mt-1.5 uppercase tracking-widest font-mono bg-slate-950 inline-block px-2 py-1 rounded border border-slate-800">{{ optional($job->pickup_time ?? $job->Farm_Arrival_Time)->format('h:i A') ?? 'N/A' }}</p>
                                    </td>
                                    <td class="px-8 py-6 text-right whitespace-nowrap">
                                        <p class="text-xl font-black text-emerald-400 tracking-tighter">{{ number_format($job->net_company_amount ?? 0, 2) }} <span class="text-[10px] text-slate-500 font-bold uppercase tracking-widest ml-0.5">JOD</span></p>
                                    </td>
                                    <td class="px-8 py-6 text-right whitespace-nowrap">
                                        <form action="{{ route('transport.dispatch.accept', $job->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="bg-cyan-600 hover:bg-cyan-500 text-slate-950 text-[10px] font-black uppercase tracking-[0.2em] py-3.5 px-6 rounded-xl shadow-[0_0_15px_rgba(6,182,212,0.3)] transition-all active:scale-95">
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
                <div class="py-32 text-center flex flex-col items-center">
                    <div class="w-24 h-24 rounded-[2rem] bg-slate-950 flex items-center justify-center mb-6 border border-slate-800 shadow-inner">
                        <svg class="w-12 h-12 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                    </div>
                    <h3 class="text-xl font-black text-white mb-2 tracking-tight">Market Empty</h3>
                    <p class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-500">Waiting for new farm requests to be broadcasted to the network.</p>
                </div>
            @endif
        </div>
    </div>

    {{-- 🌟 6. My Company's Dispatches --}}
    <div class="fade-in-up" style="animation-delay: 0.6s;">
        <div class="flex items-center gap-3 mb-6 mt-12">
            <div class="w-1.5 h-6 bg-indigo-500 rounded-full shadow-[0_0_10px_rgba(99,102,241,0.5)]"></div>
            <h2 class="text-2xl font-black text-white tracking-tight">My Dispatches</h2>
        </div>

        <div class="bg-slate-900/60 rounded-[3rem] shadow-2xl border border-slate-800 overflow-hidden backdrop-blur-2xl">
            @if(isset($myJobs) && $myJobs->count() > 0)
                <div class="overflow-x-auto table-scroll bg-slate-900/20">
                    <table class="w-full text-left whitespace-nowrap border-collapse min-w-[1000px]">
                        <thead class="bg-slate-950/80 border-b border-slate-800">
                            <tr>
                                <th class="px-10 py-7 border-b border-slate-100 bg-slate-50 text-[9px] font-black text-slate-500 uppercase tracking-widest">Node ID</th>
                                <th class="px-10 py-7 border-b border-slate-100 bg-slate-50 text-[9px] font-black text-slate-500 uppercase tracking-widest">Client & Route</th>
                                <th class="px-10 py-7 border-b border-slate-100 bg-slate-50 text-[9px] font-black text-slate-500 uppercase tracking-widest">Fleet Assignment</th>
                                <th class="px-10 py-7 border-b border-slate-100 bg-slate-50 text-[9px] font-black text-slate-500 uppercase tracking-widest">Temporal Log</th>
                                <th class="px-10 py-7 border-b border-slate-100 bg-slate-50 text-[9px] font-black text-slate-500 uppercase tracking-widest text-center">Status Matrix</th>
                                <th class="px-10 py-7 border-b border-slate-100 bg-slate-50 text-right text-[9px] font-black text-slate-500 uppercase tracking-widest">Action Gateway</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-800/40">
                            @foreach($myJobs as $job)
                                <tr class="hover:bg-white/5 transition-colors duration-200 group">
                                    <td class="px-10 py-6 whitespace-nowrap">
                                        <span class="text-[10px] font-black font-mono text-indigo-400 bg-slate-950 px-3 py-1.5 rounded-lg border border-slate-800 shadow-inner group-hover:border-indigo-500/50 transition-colors">#{{ substr($job->id, 0, 8) }}</span>
                                    </td>
                                    <td class="px-10 py-6 whitespace-nowrap">
                                        <p class="text-sm font-black text-white mb-1.5 truncate max-w-[200px]">{{ $job->user->name ?? 'Guest User' }}</p>
                                        <div class="flex items-center gap-1.5 text-[9px] font-bold text-slate-500 uppercase tracking-widest">
                                            <svg class="h-3 w-3 text-slate-600" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" /></svg>
                                            {{ Str::limit($job->pickup_location ?? $job->start_and_return_point ?? 'TBD', 25) }}
                                        </div>
                                    </td>
                                    <td class="px-10 py-6 whitespace-nowrap">
                                        @if($job->vehicle_id && $job->driver_id)
                                            <div class="flex items-center gap-4">
                                                <div class="w-10 h-10 rounded-xl bg-blue-500/10 flex items-center justify-center text-blue-400 border border-blue-500/20 shrink-0">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
                                                </div>
                                                <div>
                                                    <p class="text-sm font-black text-white">{{ $job->vehicle->plate_number ?? $job->vehicle->license_plate ?? 'N/A' }}</p>
                                                    <p class="text-[9px] font-bold text-slate-500 uppercase tracking-widest mt-0.5">{{ $job->driver->name ?? 'N/A' }}</p>
                                                </div>
                                            </div>
                                        @else
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-[9px] font-black uppercase tracking-widest bg-rose-500/10 text-rose-400 border border-rose-500/20">
                                                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                                Needs Assignment
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-10 py-6 whitespace-nowrap">
                                        <p class="text-xs font-black text-slate-200">{{ optional($job->pickup_time ?? $job->Farm_Arrival_Time)->format('M d, Y') ?? 'N/A' }}</p>
                                        <p class="text-[9px] font-bold text-slate-500 uppercase tracking-widest mt-1.5 font-mono">{{ optional($job->pickup_time ?? $job->Farm_Arrival_Time)->format('h:i A') ?? 'N/A' }}</p>
                                    </td>
                                    <td class="px-10 py-6 whitespace-nowrap text-center">
                                        @php
                                            $statusConfig = [
                                                'accepted' => ['bg-blue-500/10', 'text-blue-400', 'border-blue-500/30', 'bg-blue-400'],
                                                'assigned' => ['bg-indigo-500/10', 'text-indigo-400', 'border-indigo-500/30', 'bg-indigo-400'],
                                                'in_progress' => ['bg-amber-500/10', 'text-amber-400', 'border-amber-500/30', 'bg-amber-400'],
                                                'in_way' => ['bg-cyan-500/10', 'text-cyan-400', 'border-cyan-500/30', 'bg-cyan-400'],
                                                'completed' => ['bg-emerald-500/10', 'text-emerald-400', 'border-emerald-500/30', 'bg-emerald-400'],
                                                'cancelled' => ['bg-rose-500/10', 'text-rose-400', 'border-rose-500/30', 'bg-rose-400'],
                                                'pending' => ['bg-slate-800', 'text-slate-400', 'border-slate-700', 'bg-slate-500'],
                                            ];
                                            $config = $statusConfig[$job->status] ?? $statusConfig['pending'];
                                        @endphp
                                        <span class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-[9px] font-black uppercase tracking-widest border {{ $config[0] }} {{ $config[1] }} {{ $config[2] }}">
                                            <span class="w-1.5 h-1.5 rounded-full {{ $config[3] }} shadow-[0_0_5px_currentColor]"></span>
                                            {{ str_replace('_', ' ', $job->status) }}
                                        </span>
                                    </td>
                                    <td class="px-10 py-6 whitespace-nowrap text-right">
                                        <a href="{{ route('transport.dispatch.edit', $job->id) }}" class="inline-flex items-center justify-center p-3.5 bg-slate-950 border border-slate-700 rounded-xl text-slate-400 hover:text-white hover:border-indigo-500 transition-colors shadow-inner active:scale-95 opacity-0 group-hover:opacity-100 transform translate-x-4 group-hover:translate-x-0" title="Manage Protocol">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @if(method_exists($myJobs, 'hasPages') && $myJobs->hasPages())
                    <div class="px-10 py-10 border-t border-slate-800 bg-slate-950/40 flex justify-center custom-pagination">
                        {{ $myJobs->links() }}
                    </div>
                @endif
            @else
                <div class="py-32 text-center flex flex-col items-center">
                    <div class="w-24 h-24 rounded-[2rem] bg-slate-950 flex items-center justify-center mb-6 border border-slate-800 shadow-inner">
                        <svg class="w-12 h-12 text-slate-700" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" /></svg>
                    </div>
                    <h3 class="text-2xl font-black text-white mb-2 tracking-tight">No Active Dispatches</h3>
                    <p class="text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] max-w-sm">Accept jobs from the market above to build your operational list.</p>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
    /* Pagination Overrides */
    .custom-pagination nav { @apply flex items-center gap-2; }
    .custom-pagination .page-link { @apply bg-slate-900 border-none text-slate-400 text-[11px] font-black px-5 py-3 rounded-xl transition-all hover:bg-indigo-600 hover:text-white shadow-lg; }
    .custom-pagination .active .page-link { @apply bg-indigo-600 text-white shadow-[0_0_20px_rgba(99,102,241,0.4)]; }
    .custom-pagination .disabled .page-link { @apply bg-transparent opacity-20 text-slate-700 cursor-not-allowed; }
</style>
@endsection
