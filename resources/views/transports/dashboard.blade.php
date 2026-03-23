@extends('layouts.transport')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-12">
        <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-gray-100 flex flex-col justify-center">
            <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Gross Revenue</span>
            <p class="text-3xl font-black text-gray-900">{{ number_format($financials['gross'], 2) }} <span class="text-sm text-gray-500">JOD</span></p>
        </div>

        <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-gray-100 flex flex-col justify-center">
            <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Platform Fees</span>
            <p class="text-3xl font-black text-red-500">- {{ number_format($financials['commission'], 2) }} <span class="text-sm text-red-400">JOD</span></p>
        </div>

        <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-blue-200 flex flex-col justify-center relative overflow-hidden">
            <div class="absolute -right-4 -top-4 w-16 h-16 bg-blue-50 rounded-full"></div>
            <span class="text-[10px] font-black text-blue-500 uppercase tracking-widest mb-1 relative z-10">Net Profit</span>
            <p class="text-3xl font-black text-blue-600 relative z-10">{{ number_format($financials['net'], 2) }} <span class="text-sm text-blue-400">JOD</span></p>
        </div>

        <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-gray-100 flex flex-col justify-center">
            <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Fleet & Jobs</span>
            <div class="flex gap-4 mt-1">
                <div>
                    <p class="text-xl font-black text-gray-900">{{ $drivers->count() }}</p>
                    <p class="text-[9px] font-bold text-gray-400 uppercase">Drivers</p>
                </div>
                <div class="w-px bg-gray-200"></div>
                <div>
                    <p class="text-xl font-black text-emerald-600">{{ $activeTrips }}</p>
                    <p class="text-[9px] font-bold text-gray-400 uppercase">Active</p>
                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-50 border-l-4 border-green-500 text-green-800 p-5 rounded-r-2xl shadow-sm font-bold mb-8 animate-fade-in flex items-center gap-3">
            <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-50 border-l-4 border-red-500 text-red-800 p-5 rounded-r-2xl shadow-sm font-bold mb-8 animate-fade-in flex items-center gap-3">
            <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            {{ session('error') }}
        </div>
    @endif

    <div class="mb-12">
        <div class="flex items-center gap-3 mb-6">
            <div class="w-2 h-8 bg-emerald-400 rounded-full"></div>
            <h2 class="text-xl font-black text-gray-800 tracking-tight">Available Market Jobs</h2>
            <span class="bg-emerald-100 text-emerald-700 text-xs font-black px-3 py-1 rounded-full uppercase tracking-widest">{{ $availableJobs->count() }} NEW</span>
        </div>

        <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 overflow-hidden">
            @if($availableJobs->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full leading-normal">
                        <thead>
                            <tr>
                                <th class="px-8 py-5 border-b-2 border-gray-100 bg-gray-50 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">Job Details</th>
                                <th class="px-8 py-5 border-b-2 border-gray-100 bg-gray-50 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">Route</th>
                                <th class="px-8 py-5 border-b-2 border-gray-100 bg-gray-50 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">Date & Time</th>
                                <th class="px-8 py-5 border-b-2 border-gray-100 bg-gray-50 text-right text-[10px] font-black text-gray-400 uppercase tracking-widest">Price</th>
                                <th class="px-8 py-5 border-b-2 border-gray-100 bg-gray-50 text-right text-[10px] font-black text-gray-400 uppercase tracking-widest">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($availableJobs as $job)
                                <tr class="hover:bg-emerald-50/30 transition-colors duration-200">
                                    <td class="px-8 py-6 bg-transparent">
                                        <div class="flex items-center gap-4">
                                            <div class="flex-shrink-0 w-12 h-12 bg-emerald-50 rounded-2xl flex items-center justify-center text-emerald-600 font-black text-sm">
                                                #{{ $job->id }}
                                            </div>
                                            <div>
                                                <p class="text-gray-900 font-black text-md">{{ $job->transport_type }}</p>
                                                <p class="text-[11px] font-bold text-gray-400 mt-0.5 uppercase tracking-widest">{{ $job->passengers }} PAX</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-8 py-6 bg-transparent">
                                        <div class="flex flex-col gap-2">
                                            <div class="flex items-center gap-2 text-sm font-bold text-gray-600">
                                                <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                                {{ Str::limit($job->start_and_return_point, 25) }}
                                            </div>
                                            <div class="flex items-center gap-2 text-sm font-bold text-gray-600">
                                                <svg class="w-4 h-4 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" /></svg>
                                                {{ $job->farm->name ?? 'Farm' }}
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-8 py-6 bg-transparent">
                                        <p class="text-sm font-black text-gray-900">{{ optional($job->Farm_Arrival_Time)->format('M d, Y') }}</p>
                                        <p class="text-[11px] font-bold text-gray-400 mt-0.5 uppercase tracking-widest">{{ optional($job->Farm_Arrival_Time)->format('h:i A') }}</p>
                                    </td>
                                    <td class="px-8 py-6 bg-transparent text-right">
                                        <p class="text-lg font-black text-gray-900">{{ number_format($job->price, 2) }} <span class="text-xs text-gray-500">JOD</span></p>
                                    </td>
                                    <td class="px-8 py-6 bg-transparent text-right">
                                        <form action="{{ route('transport.dispatch.accept', $job->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="bg-emerald-500 hover:bg-emerald-600 text-white text-[11px] font-black uppercase tracking-widest py-3 px-6 rounded-xl shadow-md hover:shadow-lg transition-all transform active:scale-95">
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
                <div class="px-8 py-16 text-center">
                    <div class="inline-flex items-center justify-center w-24 h-24 rounded-full bg-gray-50 mb-4">
                        <svg class="w-12 h-12 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" /></svg>
                    </div>
                    <h3 class="text-lg font-black text-gray-800 mb-1">No Market Jobs</h3>
                    <p class="text-sm font-bold text-gray-400">Waiting for new farm requests to be broadcasted.</p>
                </div>
            @endif
        </div>
    </div>

    <div>
        <div class="flex items-center gap-3 mb-6">
            <div class="w-2 h-8 bg-blue-600 rounded-full"></div>
            <h2 class="text-xl font-black text-gray-800 tracking-tight">My Company's Dispatches</h2>
        </div>

        <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 overflow-hidden">
            @if($myJobs->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full leading-normal">
                        <thead>
                            <tr>
                                <th class="px-8 py-5 border-b-2 border-gray-100 bg-gray-50 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">Job ID</th>
                                <th class="px-8 py-5 border-b-2 border-gray-100 bg-gray-50 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">Customer / Route</th>
                                <th class="px-8 py-5 border-b-2 border-gray-100 bg-gray-50 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">Vehicle & Driver</th>
                                <th class="px-8 py-5 border-b-2 border-gray-100 bg-gray-50 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">Date & Time</th>
                                <th class="px-8 py-5 border-b-2 border-gray-100 bg-gray-50 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">Status</th>
                                <th class="px-8 py-5 border-b-2 border-gray-100 bg-gray-50 text-right text-[10px] font-black text-gray-400 uppercase tracking-widest">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($myJobs as $job)
                                <tr class="hover:bg-gray-50 transition-colors duration-200">
                                    <td class="px-8 py-6 bg-transparent">
                                        <span class="text-sm font-black text-gray-900 bg-gray-100 px-3 py-1.5 rounded-lg border border-gray-200">#{{ $job->id }}</span>
                                    </td>
                                    <td class="px-8 py-6 bg-transparent">
                                        <p class="text-sm font-black text-gray-900">{{ $job->user->name ?? 'Guest User' }}</p>
                                        <div class="flex items-center gap-1 text-[11px] font-bold text-gray-400 uppercase tracking-widest mt-1">
                                            <svg class="h-3 w-3 text-gray-400" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" /></svg>
                                            {{ Str::limit($job->start_and_return_point, 20) }}
                                        </div>
                                    </td>
                                    <td class="px-8 py-6 bg-transparent">
                                        @if($job->vehicle_id && $job->driver_id)
                                            <p class="text-sm font-black text-gray-900 flex items-center gap-1">
                                                <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
                                                {{ $job->vehicle->license_plate ?? 'N/A' }}
                                            </p>
                                            <p class="text-[11px] font-bold text-gray-400 uppercase tracking-widest mt-0.5 ml-5">{{ $job->driver->name ?? 'N/A' }}</p>
                                        @else
                                            <span class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg text-[10px] font-black uppercase tracking-widest bg-red-50 text-red-600 border border-red-100">
                                                <svg class="w-3 h-3" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" /></svg>
                                                Assignment Needed
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-8 py-6 bg-transparent">
                                        <p class="text-sm font-black text-gray-900">{{ optional($job->Farm_Arrival_Time)->format('M d, Y') }}</p>
                                        <p class="text-[11px] font-bold text-gray-400 uppercase tracking-widest mt-0.5">{{ optional($job->Farm_Arrival_Time)->format('h:i A') }}</p>
                                    </td>
                                    <td class="px-8 py-6 bg-transparent">
                                        @php
                                            $statusConfig = [
                                                'accepted' => ['bg-blue-50', 'text-blue-600', 'border-blue-100', 'bg-blue-500'],
                                                'assigned' => ['bg-indigo-50', 'text-indigo-600', 'border-indigo-100', 'bg-indigo-500'],
                                                'in_progress' => ['bg-amber-50', 'text-amber-600', 'border-amber-100', 'bg-amber-500'],
                                                'completed' => ['bg-emerald-50', 'text-emerald-600', 'border-emerald-100', 'bg-emerald-500'],
                                                'cancelled' => ['bg-red-50', 'text-red-600', 'border-red-100', 'bg-red-500'],
                                                'pending' => ['bg-gray-50', 'text-gray-600', 'border-gray-200', 'bg-gray-400'],
                                            ];
                                            $config = $statusConfig[$job->status] ?? $statusConfig['pending'];
                                        @endphp
                                        <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg text-[10px] font-black uppercase tracking-widest border {{ $config[0] }} {{ $config[1] }} {{ $config[2] }}">
                                            <span class="w-1.5 h-1.5 rounded-full {{ $config[3] }}"></span>
                                            {{ str_replace('_', ' ', $job->status) }}
                                        </span>
                                    </td>
                                    <td class="px-8 py-6 bg-transparent text-right">
                                        <a href="{{ route('transport.dispatch.edit', $job->id) }}" class="inline-flex items-center justify-center px-4 py-2 border-2 border-gray-100 rounded-xl text-xs font-black uppercase tracking-widest text-gray-600 bg-white hover:bg-blue-50 hover:text-blue-600 hover:border-blue-100 transition-all transform active:scale-95 shadow-sm">
                                            Manage
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @if($myJobs->hasPages())
                    <div class="px-8 py-5 bg-gray-50 border-t border-gray-100">
                        {{ $myJobs->links() }}
                    </div>
                @endif
            @else
                <div class="px-8 py-16 text-center">
                    <div class="inline-flex items-center justify-center w-24 h-24 rounded-full bg-gray-50 mb-4">
                        <svg class="w-12 h-12 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" /></svg>
                    </div>
                    <h3 class="text-lg font-black text-gray-800 mb-1">No Active Dispatches</h3>
                    <p class="text-sm font-bold text-gray-400">Accept jobs from the market above to build your list.</p>
                </div>
            @endif
        </div>
    </div>

</div>
@endsection
