@extends('layouts.driver')

@section('title', 'Driver Dashboard')

@section('content')
<div class="max-w-7xl mx-auto space-y-8 pb-12 animate-fade-in-up">

    {{-- ==========================================
         HERO SECTION (TRANSPORT THEME: AMBER/SLATE)
         ========================================== --}}
    <div class="relative bg-gradient-to-br from-[#1e293b] via-[#0f172a] to-[#020617] rounded-[3rem] p-10 md:p-14 text-white shadow-[0_30px_60px_-15px_rgba(0,0,0,0.5)] overflow-hidden border border-slate-700/50 mb-10">
        <div class="absolute top-0 right-0 w-96 h-96 bg-amber-500/5 rounded-full blur-[100px] -mr-32 -mt-32 pointer-events-none"></div>
        <div class="absolute bottom-0 left-1/4 w-64 h-64 bg-cyan-500/5 rounded-full blur-[80px] pointer-events-none"></div>
        
        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/5 border border-white/10 text-[10px] font-black uppercase tracking-widest mb-4 backdrop-blur-md">
                    <span class="w-2 h-2 rounded-full bg-amber-500 animate-ping"></span>
                    Fleet Commander
                </div>
                <h1 class="text-3xl md:text-5xl font-black tracking-tight mb-2">Trip Management</h1>
                <p class="text-slate-400 font-medium max-w-md leading-relaxed">Oversee your assigned routes, navigate to pickups, and ensure timely drops at destination farms.</p>
            </div>

            <div class="bg-slate-900/50 backdrop-blur-xl border border-white/5 p-6 rounded-3xl text-center min-w-[160px] shadow-2xl transform hover:scale-105 transition-transform">
                <p class="text-[10px] uppercase tracking-widest text-slate-500 font-black mb-1">Upcoming Jobs</p>
                <p class="text-5xl font-black text-white tracking-tighter">{{ $assignedTrips->count() }}</p>
            </div>
        </div>
    </div>

        @if($activeTrip)
            <div class="mb-10">
                <div class="flex items-center justify-between mb-4 pl-1">
                    <h2 class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Current Active Trip</h2>
                    <span class="relative flex h-3 w-3">
                      <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-cyan-400 opacity-75"></span>
                      <span class="relative inline-flex rounded-full h-3 w-3 bg-cyan-500"></span>
                    </span>
                </div>

                <div class="bg-slate-800 rounded-[2rem] shadow-2xl border border-slate-700 overflow-hidden relative">
                    <div class="absolute top-0 left-0 right-0 h-1.5 bg-gradient-to-r from-cyan-500 to-emerald-400"></div>

                    <div class="p-6">
                        <div class="flex justify-between items-start mb-6 border-b border-slate-700/50 pb-5">
                            <div>
                                <span class="bg-cyan-500/10 text-cyan-400 text-[10px] font-black px-3 py-1 rounded-lg uppercase tracking-widest border border-cyan-500/20 inline-block mb-3">On Route</span>
                                <h3 class="text-2xl font-black text-white">{{ $activeTrip->user->name ?? 'Guest User' }}</h3>
                                <div class="flex items-center text-slate-400 text-xs font-bold mt-2">
                                    <svg class="w-4 h-4 mr-1.5 text-cyan-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                                    <a href="tel:{{ $activeTrip->user->phone ?? '' }}" class="text-slate-300 hover:text-white transition-colors">{{ $activeTrip->user->phone ?? 'No Phone' }}</a>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="text-xs font-black text-slate-500 uppercase tracking-widest mb-1">Trip ID</div>
                                <div class="text-xl font-black text-white bg-slate-900 px-3 py-1.5 rounded-xl border border-slate-700">#{{ $activeTrip->id }}</div>
                            </div>
                        </div>

                        <div class="space-y-6">
                            <div class="relative pl-8">
                                <div class="absolute left-[13px] top-2 bottom-4 w-0.5 bg-slate-700 border-l border-dashed border-slate-600"></div>

                                <div class="relative mb-8">
                                    <div class="absolute -left-8 top-1 w-5 h-5 rounded-full bg-slate-800 border-[4px] border-cyan-500 z-10 shadow-[0_0_10px_rgba(6,182,212,0.5)]"></div>
                                    <p class="text-[10px] font-black text-cyan-500 uppercase tracking-widest mb-1">Pickup Location</p>
                                    <p class="text-base text-white font-bold">{{ $activeTrip->pickup_location ?? $activeTrip->start_and_return_point ?? 'TBD' }}</p>

                                    <a href="https://www.google.com/maps/search/?api=1&query={{ urlencode($activeTrip->pickup_location ?? $activeTrip->start_and_return_point ?? '') }}" target="_blank" class="inline-flex items-center justify-center mt-3 text-[10px] font-black uppercase tracking-widest text-cyan-400 bg-cyan-500/10 px-4 py-2 rounded-lg border border-cyan-500/30 hover:bg-cyan-500/20 transition-colors active:scale-95">
                                        <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                        Navigate to Pickup
                                    </a>
                                </div>

                                <div class="relative">
                                    <div class="absolute -left-8 top-1 w-5 h-5 rounded-full bg-slate-800 border-[4px] border-emerald-500 z-10 flex items-center justify-center shadow-[0_0_10px_rgba(16,185,129,0.5)]"></div>
                                    <p class="text-[10px] font-black text-emerald-500 uppercase tracking-widest mb-1">Drop-off Farm</p>
                                    <p class="text-base text-white font-bold">{{ $activeTrip->farmBooking->farm->name ?? 'Farm' }}</p>

                                    <a href="https://www.google.com/maps/search/?api=1&query={{ urlencode($activeTrip->farmBooking->farm->name ?? '') }}" target="_blank" class="inline-flex items-center justify-center mt-3 text-[10px] font-black uppercase tracking-widest text-emerald-400 bg-emerald-500/10 px-4 py-2 rounded-lg border border-emerald-500/30 hover:bg-emerald-500/20 transition-colors active:scale-95">
                                        <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                        Navigate to Farm
                                    </a>
                                </div>
                            </div>
                        </div>

                        @if($activeTrip->notes)
                            <div class="mt-6 bg-amber-500/10 border border-amber-500/20 rounded-xl p-4">
                                <span class="block text-[9px] font-black text-amber-500 uppercase tracking-widest mb-1">Customer Notes</span>
                                <p class="text-xs font-bold text-amber-100">"{{ $activeTrip->notes }}"</p>
                            </div>
                        @endif

                    </div>

                    <div class="p-5 bg-slate-900 border-t border-slate-800">
                        <form action="{{ route('transport.driver.update_status', $activeTrip->id) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="status" value="completed">
                            <button onclick="return confirm('Confirm drop-off and complete trip?');" class="w-full flex items-center justify-center px-4 py-5 border border-transparent text-sm font-black tracking-widest rounded-2xl text-slate-900 bg-emerald-500 hover:bg-emerald-400 focus:outline-none transition-all shadow-[0_0_20px_rgba(16,185,129,0.4)] active:scale-95 uppercase">
                                <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                Slide / Tap to Complete
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @endif

        <div class="mb-10">
            <h2 class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-4 pl-1 flex items-center gap-2">
                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                Assigned Trips ({{ $assignedTrips->count() }})
            </h2>

            <div class="space-y-5">
                @forelse($assignedTrips as $trip)
                    <div class="bg-slate-800 rounded-2xl border border-slate-700 overflow-hidden shadow-lg {{ $activeTrip ? 'opacity-60 grayscale-[30%]' : '' }}">
                        <div class="p-5">
                            <div class="flex justify-between items-start mb-4">
                                <div class="text-xs font-black text-slate-400 bg-slate-900 px-2 py-1 rounded border border-slate-700 uppercase tracking-widest">#TRP-{{ str_pad($trip->id, 4, '0', STR_PAD_LEFT) }}</div>
                                <span class="bg-blue-500/10 text-blue-400 text-[9px] font-black px-2.5 py-1 rounded border border-blue-500/20 uppercase tracking-widest">Ready</span>
                            </div>

                            <div class="grid grid-cols-2 gap-y-4 gap-x-2 mb-6">
                                <div>
                                    <span class="block text-[9px] font-black text-slate-500 uppercase tracking-widest mb-1">Time</span>
                                    <span class="text-sm font-bold text-white">{{ optional($trip->Farm_Arrival_Time)->format('M d, H:i') ?? 'N/A' }}</span>
                                </div>
                                <div>
                                    <span class="block text-[9px] font-black text-slate-500 uppercase tracking-widest mb-1">Customer</span>
                                    <span class="text-sm font-bold text-white">{{ $trip->user->name ?? 'N/A' }}</span>
                                </div>
                                <div class="col-span-2 bg-slate-900/50 p-3 rounded-xl border border-slate-700/50 mt-1">
                                    <span class="block text-[9px] font-black text-slate-500 uppercase tracking-widest mb-1.5">Route</span>
                                    <div class="flex items-center text-xs font-bold text-slate-300">
                                        <span class="w-1.5 h-1.5 rounded-full bg-cyan-500 mr-2 shrink-0"></span>
                                        <span class="truncate">{{ $trip->pickup_location ?? $trip->start_and_return_point }}</span>
                                    </div>
                                    <div class="w-0.5 h-2 bg-slate-700 ml-[2.5px] my-0.5"></div>
                                    <div class="flex items-center text-xs font-bold text-slate-300">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-2 shrink-0"></span>
                                        <span class="truncate">{{ $trip->farmBooking->farm->name ?? 'Farm' }}</span>
                                    </div>
                                </div>
                            </div>

                            @if(!$activeTrip)
                                <form action="{{ route('transport.driver.update_status', $trip->id) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="in_progress">
                                    <button class="w-full flex justify-center items-center py-4 px-4 border border-cyan-500/30 rounded-xl shadow-lg shadow-cyan-600/10 text-xs font-black tracking-widest uppercase text-white bg-cyan-600 hover:bg-cyan-500 focus:outline-none transition-all active:scale-95">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        Start This Trip
                                    </button>
                                </form>
                            @else
                                <div class="w-full text-center py-3 bg-slate-900/50 text-[10px] font-black uppercase tracking-widest text-slate-500 rounded-xl border border-slate-700 border-dashed">
                                    Finish active trip to start
                                </div>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="bg-slate-800/30 rounded-[2rem] border border-slate-700/50 border-dashed p-10 text-center">
                        <div class="w-16 h-16 bg-slate-800 rounded-full flex items-center justify-center mx-auto mb-4 border border-slate-700">
                            <svg class="w-8 h-8 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                        </div>
                        <p class="text-sm text-slate-300 font-bold">No assigned trips.</p>
                        <p class="text-[10px] uppercase tracking-widest font-bold text-slate-500 mt-2">Wait for dispatch to assign a new job.</p>
                    </div>
                @endforelse
            </div>
        </div>

    </div>
</div>
@endsection
