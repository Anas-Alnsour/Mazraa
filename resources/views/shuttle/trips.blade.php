@extends('layouts.driver')

@section('content')
<div class="mb-10">
    <h1 class="text-3xl font-black text-gray-900 tracking-tight">Shuttle Schedule</h1>
    <p class="text-sm font-bold text-gray-400 mt-1 uppercase tracking-widest">Manage your assigned passenger trips</p>
</div>

@if(session('success'))
    <div class="bg-green-50 border-l-4 border-green-500 text-green-800 p-5 rounded-2xl shadow-sm font-bold mb-10 flex items-center gap-3">
        <svg class="w-6 h-6 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="bg-red-50 border-l-4 border-red-500 text-red-800 p-5 rounded-2xl shadow-sm font-bold mb-10 flex items-center gap-3">
        <svg class="w-6 h-6 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
        {{ session('error') }}
    </div>
@endif

<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6 flex items-center transition-transform hover:-translate-y-1">
        <div class="p-4 rounded-2xl bg-blue-50 text-blue-600 mr-5 shadow-inner">
            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
        </div>
        <div>
            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Total Assigned</p>
            <p class="text-3xl font-black text-gray-900">{{ $totalTrips }}</p>
        </div>
    </div>

    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6 flex items-center transition-transform hover:-translate-y-1">
        <div class="p-4 rounded-2xl bg-amber-50 text-amber-500 mr-5 shadow-inner">
            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        </div>
        <div>
            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Upcoming</p>
            <p class="text-3xl font-black text-amber-600">{{ $upcomingTrips }}</p>
        </div>
    </div>

    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6 flex items-center transition-transform hover:-translate-y-1">
        <div class="p-4 rounded-2xl bg-emerald-50 text-emerald-500 mr-5 shadow-inner">
            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
        </div>
        <div>
            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Completed</p>
            <p class="text-3xl font-black text-emerald-600">{{ $completedTrips }}</p>
        </div>
    </div>
</div>

<div class="space-y-6">
    @forelse($myTrips as $trip)
        @php
            $isCompleted = in_array($trip->status, ['completed', 'finished']);
            $isInProgress = $trip->status === 'in_progress';
        @endphp

        <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 overflow-hidden {{ $isCompleted ? 'opacity-70 grayscale-[20%]' : '' }}">

            <div class="bg-gray-50/50 p-6 flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-gray-100">
                <div>
                    <div class="flex items-center gap-3 mb-1">
                        <span class="text-xl font-black text-gray-900">Trip #{{ $trip->id }}</span>

                        @if($isCompleted)
                            <span class="inline-flex items-center px-2 py-1 rounded bg-emerald-50 text-emerald-600 border border-emerald-100 text-[9px] font-black uppercase tracking-widest">
                                Completed
                            </span>
                        @elseif($isInProgress)
                            <span class="inline-flex items-center px-2 py-1 rounded bg-blue-50 text-blue-600 border border-blue-100 text-[9px] font-black uppercase tracking-widest">
                                <span class="w-1.5 h-1.5 rounded-full bg-blue-500 animate-pulse mr-1.5"></span>
                                In Progress
                            </span>
                        @else
                            <span class="inline-flex items-center px-2 py-1 rounded bg-amber-50 text-amber-600 border border-amber-100 text-[9px] font-black uppercase tracking-widest">
                                Assigned
                            </span>
                        @endif
                    </div>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">
                        Vehicle: <span class="text-gray-900">{{ $trip->vehicle->license_plate ?? 'N/A' }}</span> ({{ $trip->vehicle->type ?? '' }})
                    </p>
                </div>

                @if(!$isCompleted)
                    <div class="flex flex-col sm:flex-row gap-2">
                        @if(!$isInProgress)
                            <form action="{{ route('shuttle.update_status', $trip->id) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="in_progress">
                                <button type="submit" class="w-full md:w-auto bg-blue-600 hover:bg-blue-700 text-white font-black py-3 px-6 rounded-xl shadow-md transition-all flex items-center justify-center gap-2 uppercase tracking-widest text-[10px] transform active:scale-95">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    Start Trip
                                </button>
                            </form>
                        @endif

                        @if($isInProgress)
                            <form action="{{ route('shuttle.update_status', $trip->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to mark this trip as completed?');">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="completed">
                                <button type="submit" class="w-full md:w-auto bg-emerald-500 hover:bg-emerald-600 text-white font-black py-3 px-6 rounded-xl shadow-md transition-all flex items-center justify-center gap-2 uppercase tracking-widest text-[10px] transform active:scale-95">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    Mark Completed
                                </button>
                            </form>
                        @endif
                    </div>
                @endif
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-0 border-b border-gray-100">
                <div class="p-6 md:border-r border-gray-100 bg-white">
                    <h4 class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-4 flex items-center gap-2">
                        <svg class="w-3.5 h-3.5 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                        Trip Route
                    </h4>
                    <div class="space-y-4">
                        <div class="relative pl-4 border-l-2 border-gray-200">
                            <div class="absolute -left-[5px] top-1 w-2 h-2 rounded-full bg-blue-500"></div>
                            <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest mb-0.5">Pickup Location</p>
                            <p class="text-sm font-black text-gray-900">{{ $trip->start_and_return_point }}</p>
                        </div>
                        <div class="relative pl-4 border-l-2 border-transparent">
                            <div class="absolute -left-[5px] top-1 w-2 h-2 rounded-full bg-emerald-500"></div>
                            <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest mb-0.5">Destination Farm</p>
                            <p class="text-sm font-black text-gray-900">{{ $trip->farm->name ?? 'N/A' }}</p>
                            <p class="text-[10px] font-bold text-blue-600 mt-1">{{ optional($trip->Farm_Arrival_Time)->format('M d, Y - h:i A') }}</p>
                        </div>
                    </div>
                </div>

                <div class="p-6 bg-gray-50/50">
                    <h4 class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-4 flex items-center gap-2">
                        <svg class="w-3.5 h-3.5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                        Passenger Details
                    </h4>

                    <div class="flex items-center gap-4 mb-4">
                        <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-black text-lg">
                            {{ substr($trip->user->name ?? 'G', 0, 1) }}
                        </div>
                        <div>
                            <p class="font-black text-gray-900">{{ $trip->user->name ?? 'Guest User' }}</p>
                            <p class="text-[10px] font-bold text-gray-500 uppercase tracking-widest mt-0.5">{{ $trip->passengers }} Passengers</p>
                        </div>
                    </div>

                    <a href="tel:{{ $trip->user->phone ?? '' }}" class="inline-flex items-center gap-1.5 text-[10px] font-black uppercase tracking-widest text-blue-600 hover:text-blue-800 bg-blue-50 hover:bg-blue-100 py-2 px-4 rounded-lg w-fit transition-colors">
                        <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                        Call Customer
                    </a>

                    @if($trip->notes)
                        <div class="mt-4 p-3 bg-amber-50 rounded-xl border border-amber-100">
                            <p class="text-[9px] font-black text-amber-800 uppercase tracking-widest mb-1">Customer Note:</p>
                            <p class="text-xs font-bold text-amber-900">"{{ $trip->notes }}"</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @empty
        <div class="p-16 text-center bg-white rounded-[3rem] shadow-sm border border-gray-100">
            <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-blue-50 mb-6 border border-blue-100">
                <svg class="h-10 w-10 text-blue-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                </svg>
            </div>
            <h3 class="text-2xl font-black text-gray-900 mb-2">No Scheduled Trips</h3>
            <p class="text-xs font-bold text-gray-400 max-w-md mx-auto uppercase tracking-widest">You currently have no transport trips assigned to you. Stand by for new dispatches.</p>
        </div>
    @endforelse

    @if($myTrips->hasPages())
        <div class="mt-6 flex justify-center">
            {{ $myTrips->links() }}
        </div>
    @endif
</div>
@endsection
