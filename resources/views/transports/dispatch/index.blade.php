@extends('layouts.transport')

@section('title', 'Dispatch Board')

@section('content')
<div class="py-12 bg-slate-50 min-h-screen">
    <div class="max-w-[90rem] mx-auto sm:px-6 lg:px-8">

        <div class="mb-10">
            <h1 class="text-3xl md:text-4xl font-black text-slate-900 tracking-tight">Dispatch Board</h1>
            <p class="mt-2 text-sm font-bold text-slate-500 uppercase tracking-widest">Manage incoming requests, assign resources, and monitor active trips.</p>
        </div>

        @if (session('success'))
            <div class="mb-8 rounded-2xl bg-emerald-50 p-5 border border-emerald-100 flex items-center shadow-sm">
                <svg class="h-6 w-6 text-emerald-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
                <p class="text-sm font-black text-emerald-800 tracking-widest uppercase">{{ session('success') }}</p>
            </div>
        @endif
        @if (session('error'))
            <div class="mb-8 rounded-2xl bg-rose-50 p-5 border border-rose-100 flex items-center shadow-sm">
                <svg class="h-6 w-6 text-rose-500 mr-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" /></svg>
                <p class="text-sm font-black text-rose-800 tracking-widest uppercase">{{ session('error') }}</p>
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6 items-start">

            <div class="flex flex-col bg-slate-100/50 rounded-[2rem] p-4 border border-slate-200/60 min-h-[500px] shadow-inner">
                <div class="mb-5 px-3 flex justify-between items-center mt-2">
                    <h3 class="font-black text-slate-800 flex items-center uppercase tracking-widest text-xs">
                        <span class="w-2.5 h-2.5 rounded-full bg-amber-400 mr-2 shadow-sm"></span> Incoming
                    </h3>
                    <span class="bg-amber-100 text-amber-800 text-xs font-black px-3 py-1 rounded-lg border border-amber-200">{{ $pendingJobs->count() }}</span>
                </div>

                <div class="space-y-4 flex-1 overflow-y-auto">
                    @forelse($pendingJobs as $job)
                        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5 hover:shadow-md transition-shadow">
                            <div class="flex justify-between items-start mb-3">
                                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest bg-slate-50 px-2 py-1 rounded border border-slate-100">#TRP-{{ str_pad($job->id, 4, '0', STR_PAD_LEFT) }}</span>
                                <span class="text-[10px] font-black bg-amber-50 text-amber-600 px-2 py-1 rounded border border-amber-100">{{ optional($job->Farm_Arrival_Time)->format('H:i') ?? 'N/A' }}</span>
                            </div>
                            <h4 class="font-black text-sm text-slate-900 mb-1">{{ $job->user->name ?? 'Customer' }}</h4>
                            <p class="text-xs font-bold text-slate-500 mb-3 truncate" title="{{ $job->pickup_location ?? $job->start_and_return_point }}">From: {{ $job->pickup_location ?? $job->start_and_return_point ?? 'TBD' }}</p>

                            <div class="flex items-center justify-between mb-4 pt-3 border-t border-slate-50">
                                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">{{ $job->passengers ?? 0 }} PAX</span>
                                <span class="text-sm font-black text-cyan-600">{{ number_format($job->net_company_amount ?? 0, 2) }} JOD</span>
                            </div>

                            <form action="{{ route('transport.dispatch.accept', $job->id) }}" method="POST" class="w-full">
                                @csrf
                                <button type="submit" class="w-full block text-center bg-cyan-600 hover:bg-cyan-700 text-white text-xs font-black uppercase tracking-widest py-3 rounded-xl transition-all shadow-md shadow-cyan-600/20 active:scale-95">
                                    Accept Job
                                </button>
                            </form>
                        </div>
                    @empty
                        <div class="h-full flex flex-col items-center justify-center p-6 text-center border-2 border-dashed border-slate-200 rounded-2xl bg-white/50">
                            <p class="text-xs font-bold uppercase tracking-widest text-slate-400">No incoming requests</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <div class="flex flex-col bg-slate-100/50 rounded-[2rem] p-4 border border-slate-200/60 min-h-[500px] shadow-inner">
                <div class="mb-5 px-3 flex justify-between items-center mt-2">
                    <h3 class="font-black text-slate-800 flex items-center uppercase tracking-widest text-xs">
                        <span class="w-2.5 h-2.5 rounded-full bg-blue-400 mr-2 shadow-sm"></span> Assign
                    </h3>
                    <span class="bg-blue-100 text-blue-800 text-xs font-black px-3 py-1 rounded-lg border border-blue-200">{{ $acceptedJobs->count() }}</span>
                </div>

                <div class="space-y-4 flex-1 overflow-y-auto">
                    @forelse($acceptedJobs as $job)
                        <div class="bg-white rounded-2xl shadow-sm border border-blue-100 p-5 hover:shadow-md transition-shadow border-l-4 border-l-blue-400">
                            <div class="flex justify-between items-start mb-3">
                                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest bg-slate-50 px-2 py-1 rounded border border-slate-100">#TRP-{{ str_pad($job->id, 4, '0', STR_PAD_LEFT) }}</span>
                                <span class="text-[10px] font-black bg-blue-50 text-blue-600 px-2 py-1 rounded border border-blue-100">{{ optional($job->Farm_Arrival_Time)->format('M d, H:i') ?? 'N/A' }}</span>
                            </div>
                            <h4 class="font-black text-sm text-slate-900 mb-2">{{ $job->user->name ?? 'Customer' }}</h4>

                            <div class="text-xs font-bold text-slate-600 bg-slate-50 p-3 rounded-xl mb-4 border border-slate-100 space-y-2">
                                <div class="flex items-center"><div class="w-2 h-2 rounded-full bg-cyan-400 mr-2"></div><span class="truncate">{{ $job->pickup_location ?? $job->start_and_return_point ?? 'TBD' }}</span></div>
                                <div class="flex items-center"><div class="w-2 h-2 rounded-full bg-emerald-400 mr-2"></div><span class="truncate">Farm: {{ $job->farmBooking->farm->name ?? 'Farm' }}</span></div>
                            </div>

                            <a href="{{ route('transport.dispatch.edit', $job->id) }}" class="w-full block text-center bg-blue-600 hover:bg-blue-700 text-white text-xs font-black uppercase tracking-widest py-3 rounded-xl transition-all shadow-md shadow-blue-600/20 active:scale-95">
                                Select Driver
                            </a>
                        </div>
                    @empty
                        <div class="h-full flex flex-col items-center justify-center p-6 text-center border-2 border-dashed border-slate-200 rounded-2xl bg-white/50">
                            <p class="text-xs font-bold uppercase tracking-widest text-slate-400">All accepted jobs assigned</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <div class="flex flex-col bg-slate-100/50 rounded-[2rem] p-4 border border-slate-200/60 min-h-[500px] shadow-inner">
                <div class="mb-5 px-3 flex justify-between items-center mt-2">
                    <h3 class="font-black text-slate-800 flex items-center uppercase tracking-widest text-xs">
                        <span class="w-2.5 h-2.5 rounded-full bg-indigo-400 mr-2 shadow-sm"></span> Ready
                    </h3>
                    <span class="bg-indigo-100 text-indigo-800 text-xs font-black px-3 py-1 rounded-lg border border-indigo-200">{{ $assignedJobs->count() }}</span>
                </div>

                <div class="space-y-4 flex-1 overflow-y-auto">
                    @forelse($assignedJobs as $job)
                        <div class="bg-white rounded-2xl shadow-sm border border-indigo-100 p-5 hover:shadow-md transition-shadow border-l-4 border-l-indigo-400">
                            <div class="flex justify-between items-start mb-3">
                                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest bg-slate-50 px-2 py-1 rounded border border-slate-100">#TRP-{{ str_pad($job->id, 4, '0', STR_PAD_LEFT) }}</span>
                                <span class="text-[10px] font-black bg-indigo-50 text-indigo-700 px-2 py-1 rounded border border-indigo-100">Assigned</span>
                            </div>

                            <div class="mb-4 bg-slate-50 p-3 rounded-xl border border-slate-100">
                                <div class="flex items-center text-sm font-black text-slate-900 mb-1">
                                    <svg class="w-4 h-4 text-indigo-500 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                    {{ $job->driver->name ?? 'Driver' }}
                                </div>
                                <div class="flex items-center text-[10px] font-bold uppercase tracking-widest text-slate-500">
                                    <svg class="w-3.5 h-3.5 text-slate-400 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"></path></svg>
                                    {{ $job->vehicle->license_plate ?? 'Vehicle' }}
                                </div>
                            </div>

                            <p class="text-[10px] font-bold text-slate-400 mb-4 pt-3 border-t border-slate-50 truncate uppercase tracking-widest">Time: {{ optional($job->Farm_Arrival_Time)->format('M d, H:i') ?? 'N/A' }}</p>

                            <a href="{{ route('transport.dispatch.edit', $job->id) }}" class="w-full block text-center bg-white hover:bg-slate-50 text-slate-600 text-[10px] font-black uppercase tracking-widest py-2 rounded-xl border-2 border-slate-100 transition-colors active:scale-95">
                                Reassign
                            </a>
                        </div>
                    @empty
                        <div class="h-full flex flex-col items-center justify-center p-6 text-center border-2 border-dashed border-slate-200 rounded-2xl bg-white/50">
                            <p class="text-xs font-bold uppercase tracking-widest text-slate-400">No ready dispatches</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <div class="flex flex-col bg-slate-100/50 rounded-[2rem] p-4 border border-slate-200/60 min-h-[500px] shadow-inner">
                <div class="mb-5 px-3 flex justify-between items-center mt-2">
                    <h3 class="font-black text-slate-800 flex items-center uppercase tracking-widest text-xs">
                        <span class="w-2.5 h-2.5 rounded-full bg-emerald-400 mr-2 shadow-sm animate-pulse"></span> Active
                    </h3>
                    <span class="bg-emerald-100 text-emerald-800 text-xs font-black px-3 py-1 rounded-lg border border-emerald-200">{{ $inProgressJobs->count() }}</span>
                </div>

                <div class="space-y-4 flex-1 overflow-y-auto">
                    @forelse($inProgressJobs as $job)
                        <div class="bg-white rounded-2xl shadow-sm border border-emerald-200 p-5 relative overflow-hidden border-l-4 border-l-emerald-400">

                            <div class="flex justify-between items-start mb-4">
                                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest bg-slate-50 px-2 py-1 rounded border border-slate-100">#TRP-{{ str_pad($job->id, 4, '0', STR_PAD_LEFT) }}</span>
                                <span class="text-[10px] uppercase tracking-widest font-black text-emerald-600 bg-emerald-50 border border-emerald-100 px-2 py-1 rounded">On Route</span>
                            </div>

                            <div class="text-sm font-black text-slate-900 mb-2 flex items-center">
                                <div class="w-6 h-6 rounded-md bg-emerald-100 border border-emerald-200 flex items-center justify-center mr-2 shadow-inner">
                                    <svg class="w-3.5 h-3.5 text-emerald-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" /></svg>
                                </div>
                                {{ $job->driver->name ?? 'Driver' }}
                            </div>

                            <div class="mt-3 pt-3 border-t border-slate-50 text-[10px] font-bold uppercase tracking-widest text-slate-500 space-y-1.5">
                                <div class="truncate text-slate-700">To: {{ $job->farmBooking->farm->name ?? 'Farm' }}</div>
                                <div>Pass: {{ $job->passengers ?? 0 }} PAX</div>
                            </div>
                        </div>
                    @empty
                        <div class="h-full flex flex-col items-center justify-center p-6 text-center border-2 border-dashed border-slate-200 rounded-2xl bg-white/50">
                            <p class="text-xs font-bold uppercase tracking-widest text-slate-400">No active trips</p>
                        </div>
                    @endforelse
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
