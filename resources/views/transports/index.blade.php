@extends('layouts.app')

@section('title', 'My Transport Bookings')

@section('content')
<div class="bg-gray-50 min-h-screen py-16">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-12">
            <div>
                <h1 class="text-4xl font-black text-gray-900 tracking-tight">My Transport Bookings</h1>
                <p class="text-sm font-bold text-gray-400 mt-2 uppercase tracking-widest">Track and manage your farm shuttle requests</p>
            </div>
            {{-- Removed the standalone 'Book Shuttle' button --}}
        </div>

        @if(session('success'))
            <div class="bg-green-50 border-l-4 border-green-500 text-green-800 p-5 rounded-r-2xl shadow-sm font-bold mb-10 flex items-center gap-3">
                <svg class="w-6 h-6 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                {{ session('success') }}
            </div>
        @endif

        @if($transports->count() > 0)
            <div class="space-y-6">
                @foreach($transports as $transport)
                    @php
                        $statusConfig = [
                            'pending' => ['bg-amber-50', 'text-amber-600', 'border-amber-100', 'animate-pulse bg-amber-500'],
                            'accepted' => ['bg-blue-50', 'text-blue-600', 'border-blue-100', 'bg-blue-500'],
                            'assigned' => ['bg-indigo-50', 'text-indigo-600', 'border-indigo-100', 'bg-indigo-500'],
                            'in_progress' => ['bg-purple-50', 'text-purple-600', 'border-purple-100', 'bg-purple-500'],
                            'completed' => ['bg-emerald-50', 'text-emerald-600', 'border-emerald-100', 'bg-emerald-500'],
                            'cancelled' => ['bg-red-50', 'text-red-600', 'border-red-100', 'bg-red-500'],
                        ];
                        $config = $statusConfig[$transport->status] ?? ['bg-gray-50', 'text-gray-600', 'border-gray-200', 'bg-gray-400'];
                    @endphp

                    <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 p-6 lg:p-8 flex flex-col lg:flex-row gap-8 transition-transform hover:-translate-y-1">

                        <div class="lg:w-1/4 flex flex-col justify-between border-b lg:border-b-0 lg:border-r border-gray-100 pb-6 lg:pb-0 lg:pr-8">
                            <div>
                                <div class="flex items-center justify-between mb-4">
                                    <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-[9px] font-black uppercase tracking-widest border {{ $config[0] }} {{ $config[1] }} {{ $config[2] }}">
                                        <span class="w-1.5 h-1.5 rounded-full mr-2 {{ $config[3] }}"></span>
                                        {{ str_replace('_', ' ', $transport->status) }}
                                    </span>

                                    <a href="{{ route('transports.show', $transport->id) }}" class="text-[9px] font-black text-gray-400 hover:text-blue-600 uppercase tracking-widest flex items-center gap-1 transition-colors">
                                        Details
                                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" /></svg>
                                    </a>
                                </div>
                                <h3 class="text-xl font-black text-gray-900">{{ $transport->transport_type }}</h3>
                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mt-1">{{ $transport->passengers }} Passengers</p>
                            </div>

                            <div class="mt-6">
                                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Est. Price</p>
                                <p class="text-2xl font-black text-blue-600">{{ number_format($transport->price, 2) }} <span class="text-xs text-blue-400">JOD</span></p>
                            </div>
                        </div>

                        <div class="lg:w-2/4 flex flex-col justify-center">
                            <div class="relative pl-6 border-l-2 border-dashed border-gray-200 space-y-6">
                                <div class="relative">
                                    <div class="absolute -left-[31px] top-1 w-4 h-4 rounded-full bg-white border-[4px] border-blue-500"></div>
                                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Pickup Location</p>
                                    <p class="text-sm font-bold text-gray-900">{{ $transport->start_and_return_point }}</p>
                                </div>
                                <div class="relative">
                                    <div class="absolute -left-[31px] top-1 w-4 h-4 rounded-full bg-white border-[4px] border-emerald-500"></div>
                                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Destination Farm</p>
                                    <p class="text-sm font-bold text-gray-900">{{ $transport->farm->name ?? 'Farm' }}</p>
                                    <p class="text-xs font-bold text-gray-400 mt-1">Arrival: <span class="text-gray-600">{{ optional($transport->Farm_Arrival_Time)->format('M d, h:i A') }}</span></p>
                                </div>
                            </div>
                        </div>

                        <div class="lg:w-1/4 flex flex-col justify-between items-start lg:items-end">
                            <div class="w-full bg-gray-50 rounded-2xl p-4 border border-gray-100 mb-4 lg:mb-0">
                                <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-2">Assigned Fleet</p>
                                @if($transport->company)
                                    <p class="text-sm font-black text-gray-900 truncate">{{ $transport->company->name }}</p>
                                    @if($transport->vehicle && $transport->driver)
                                        <p class="text-xs font-bold text-gray-500 mt-1">Driver: <span class="text-gray-900">{{ $transport->driver->name }}</span></p>
                                        <p class="text-xs font-bold text-gray-500">Plate: <span class="text-gray-900">{{ $transport->vehicle->license_plate }}</span></p>
                                    @else
                                        <p class="text-[10px] font-bold text-amber-600 mt-1 uppercase tracking-widest flex items-center gap-1">
                                            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                            Assigning Driver...
                                        </p>
                                    @endif
                                @else
                                    <p class="text-[10px] font-bold text-gray-400 italic">Awaiting partner acceptance</p>
                                @endif
                            </div>

                            @if($transport->status === 'pending')
                                <div class="flex gap-2 w-full lg:w-auto">
                                    {{-- Optional: Keep Edit button if you still want users to edit transport details ONLY, or remove it if Edit is only via Farm Booking --}}
                                    <a href="{{ route('transports.edit', $transport->id) }}" class="flex-1 lg:flex-none text-[10px] font-black uppercase tracking-widest text-blue-600 hover:text-white bg-blue-50 hover:bg-blue-600 px-5 py-3 rounded-xl transition-all border border-blue-100 hover:border-blue-600 text-center active:scale-95">
                                        Edit
                                    </a>

                                    <form action="{{ route('transports.destroy', $transport->id) }}" method="POST" class="flex-1 lg:flex-none" onsubmit="return confirm('Are you sure you want to cancel this transport request?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="w-full text-[10px] font-black uppercase tracking-widest text-red-500 hover:text-white bg-red-50 hover:bg-red-500 px-5 py-3 rounded-xl transition-all border border-red-100 hover:border-red-500 active:scale-95">
                                            Cancel
                                        </button>
                                    </form>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            @if($transports->hasPages())
                <div class="mt-12 flex justify-center">
                    {{ $transports->links() }}
                </div>
            @endif
        @else
            <div class="p-24 text-center bg-white rounded-[3rem] shadow-sm border border-gray-100">
                <div class="inline-flex items-center justify-center w-24 h-24 rounded-full bg-blue-50 mb-8 border border-blue-100">
                    <svg class="h-10 w-10 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" /></svg>
                </div>
                <h3 class="text-2xl font-black text-gray-900 mb-2">No Transport Bookings Yet</h3>
                <p class="text-sm font-bold text-gray-400 max-w-md mx-auto uppercase tracking-widest mb-8">Transport is now booked directly alongside your farm stays.</p>
                <a href="{{ route('farms.public.index') }}" class="inline-block bg-[#1d5c42] hover:bg-[#154230] text-white font-black py-4 px-8 rounded-2xl shadow-lg transition-all transform active:scale-95 text-xs uppercase tracking-widest">
                    Browse Farms
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
