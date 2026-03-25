@extends('layouts.admin')

@section('content')
<div class="max-w-7xl mx-auto">

    <div class="mb-12 flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div>
            <h1 class="text-4xl font-black text-slate-900 tracking-tight">Farm Verification Queue</h1>
            <p class="text-sm font-bold text-slate-400 mt-2 uppercase tracking-widest">Review and approve new farm listings before they go public</p>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-emerald-50 border-l-4 border-emerald-500 text-emerald-800 p-5 rounded-2xl shadow-sm font-bold mb-10 flex items-center gap-3">
            <svg class="w-6 h-6 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-50 border-l-4 border-red-500 text-red-800 p-5 rounded-2xl shadow-sm font-bold mb-10 flex items-center gap-3">
            <svg class="w-6 h-6 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
            {{ session('error') }}
        </div>
    @endif

    <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden">
        @if($pendingFarms->count() > 0)
            <div class="overflow-x-auto p-4">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr>
                            <th class="px-6 py-5 text-[9px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100">Farm Details</th>
                            <th class="px-6 py-5 text-[9px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100">Owner Info</th>
                            <th class="px-6 py-5 text-[9px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100">Location / Price</th>
                            <th class="px-6 py-5 text-[9px] font-black text-slate-400 uppercase tracking-widest text-right border-b border-slate-100">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @foreach($pendingFarms as $farm)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-6 py-6">
                                    <div class="flex items-center gap-5">
                                        <div class="h-16 w-16 rounded-2xl bg-slate-100 overflow-hidden flex-shrink-0 border border-slate-200">
                                            @if($farm->images && $farm->images->count() > 0)
                                                <img src="{{ asset('storage/' . $farm->images->first()->image_path) }}" alt="Farm Image" class="h-full w-full object-cover">
                                            @else
                                                <div class="h-full w-full flex items-center justify-center text-slate-300">
                                                    <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                                </div>
                                            @endif
                                        </div>
                                        <div>
                                            <div class="flex items-center gap-2 mb-1">
                                                <span class="text-[10px] font-black text-slate-500 bg-slate-100 px-2 py-0.5 rounded-lg">#{{ $farm->id }}</span>
                                                <a href="{{ route('farms.show', $farm->id) }}" target="_blank" class="text-base font-black text-slate-900 hover:text-emerald-600 transition-colors flex items-center gap-1.5">
                                                    {{ $farm->name }}
                                                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" /></svg>
                                                </a>
                                            </div>
                                            <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">Submitted: {{ $farm->created_at->format('M d, Y') }}</p>
                                        </div>
                                    </div>
                                </td>

                                <td class="px-6 py-6">
                                    <div class="flex items-center gap-3">
                                        <div class="h-10 w-10 rounded-full bg-indigo-50 flex items-center justify-center text-indigo-600 font-black text-sm border border-indigo-100">
                                            {{ strtoupper(substr($farm->owner->name ?? '?', 0, 1)) }}
                                        </div>
                                        <div>
                                            <p class="text-sm font-black text-slate-900">{{ $farm->owner->name ?? 'Unknown Owner' }}</p>
                                            <p class="text-[10px] font-bold text-indigo-600 mt-0.5 uppercase tracking-widest">{{ $farm->owner->phone ?? 'No Phone' }}</p>
                                        </div>
                                    </div>
                                </td>

                                <td class="px-6 py-6">
                                    <p class="text-sm font-bold text-slate-700">{{ $farm->location ?? 'Location Not Set' }}</p>
                                    @if($farm->latitude && $farm->longitude)
                                        <p class="text-[9px] font-bold text-slate-400 mt-1 uppercase tracking-widest">Lat: {{ Str::limit($farm->latitude, 6) }}, Lng: {{ Str::limit($farm->longitude, 6) }}</p>
                                    @endif
                                    <p class="text-[11px] font-black text-emerald-600 mt-1 uppercase tracking-widest">{{ number_format($farm->price_per_night, 2) }} JOD/night</p>
                                </td>

                                <td class="px-6 py-6 text-right">
                                    <form action="{{ route('admin.verifications.handle', $farm->id) }}" method="POST" class="inline-flex gap-2">
                                        @csrf
                                        <button type="submit" name="action" value="reject" class="px-5 py-3 bg-white border border-slate-200 text-red-500 hover:text-white hover:bg-red-500 hover:border-red-500 text-[10px] font-black uppercase tracking-widest rounded-xl transition-all transform active:scale-95 flex items-center gap-1.5" onclick="return confirm('Are you sure you want to REJECT and completely delete this farm?');">
                                            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12" /></svg>
                                            Reject
                                        </button>
                                        <button type="submit" name="action" value="approve" class="px-5 py-3 bg-slate-900 hover:bg-emerald-500 border border-slate-900 hover:border-emerald-500 text-white text-[10px] font-black uppercase tracking-widest rounded-xl shadow-lg shadow-slate-900/20 hover:shadow-emerald-500/20 transition-all transform active:scale-95 flex items-center gap-1.5">
                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                            Approve
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="p-24 text-center bg-white rounded-[2.5rem]">
                <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-emerald-50 mb-6 border border-emerald-100">
                    <svg class="h-10 w-10 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                </div>
                <h3 class="text-2xl font-black text-slate-900 mb-2">Queue is Empty</h3>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">There are no pending farm listings waiting for verification.</p>
            </div>
        @endif
    </div>
</div>
@endsection
