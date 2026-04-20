@extends('layouts.admin')

@section('title', 'Farm Details')

@section('content')
<style>
    .fade-in-up { animation: fadeInUp 0.8s cubic-bezier(0.2, 0.8, 0.2, 1) forwards; opacity: 0; }
    @keyframes fadeInUp { 0% { opacity: 0; transform: translateY(30px); } 100% { opacity: 1; transform: translateY(0); } }
    .glass-panel { background: rgba(15, 23, 42, 0.6); backdrop-filter: blur(24px); -webkit-backdrop-filter: blur(24px); border: 1px solid rgba(255, 255, 255, 0.05); }
    .readonly-field { background: rgba(2, 6, 23, 0.4) !important; border: 1px solid rgba(51, 65, 85, 0.6) !important; color: #cbd5e1 !important; border-radius: 1rem !important; }
</style>

<div class="max-w-[96%] xl:max-w-7xl mx-auto space-y-8 animate-god-in pb-24 fade-in-up">

    <div class="relative overflow-hidden flex flex-col md:flex-row md:items-center justify-between gap-6 bg-slate-900/80 p-8 md:p-10 rounded-[3rem] border border-slate-800 backdrop-blur-2xl shadow-2xl">
        <div class="absolute -left-20 -bottom-20 w-80 h-80 bg-emerald-500/10 blur-[100px] rounded-full pointer-events-none"></div>
        <div class="relative z-10">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-slate-950/50 border border-slate-700 text-[9px] font-black uppercase tracking-widest mb-3 shadow-inner text-emerald-400">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span> Read-Only View
            </div>
            <h1 class="text-4xl md:text-5xl font-black text-white tracking-tighter mb-2">{{ $farm->name }}</h1>
            <p class="text-slate-400 font-medium flex items-center gap-3 mt-3">
                <span class="text-[10px] text-emerald-400 font-mono font-black uppercase tracking-widest bg-emerald-500/10 px-3 py-2 rounded-lg border border-emerald-500/20 shadow-[0_0_15px_rgba(16,185,129,0.15)]">ID: #{{ str_pad($farm->id, 5, '0', STR_PAD_LEFT) }}</span>
                <span class="text-[10px] font-black uppercase tracking-widest px-3 py-2 rounded-lg border shadow-inner {{ $farm->is_approved ? 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20' : 'bg-amber-500/10 text-amber-400 border-amber-500/20' }}">
                    {{ $farm->is_approved ? 'Approved' : 'Pending Approval' }}
                </span>
            </p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('admin.verifications') }}" class="px-6 py-4 bg-slate-950 hover:bg-slate-800 text-slate-400 hover:text-white font-black text-[10px] uppercase tracking-[0.2em] rounded-2xl transition-all border border-slate-800 hover:border-slate-600 flex items-center justify-center gap-3 shadow-inner active:scale-95">
                <svg class="w-5 h-5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Back to Queue
            </a>
            <a href="{{ route('admin.farms.edit', $farm->id) }}" class="px-6 py-4 bg-blue-600 hover:bg-blue-500 text-white font-black text-[10px] uppercase tracking-[0.2em] rounded-2xl transition-all shadow-[0_10px_20px_rgba(59,130,246,0.3)] flex items-center justify-center gap-3 active:scale-95">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                Edit Farm
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-12 gap-8 items-start relative">

        <div class="xl:col-span-8 space-y-8">

            <div class="bg-slate-900/60 p-8 md:p-10 rounded-[3rem] border border-slate-800 backdrop-blur-xl shadow-2xl relative overflow-hidden">
                <div class="absolute -right-20 -top-20 w-64 h-64 bg-purple-500/5 blur-[80px] rounded-full pointer-events-none"></div>

                <div class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-slate-950 border border-slate-800 text-[10px] font-black text-purple-400 uppercase tracking-widest mb-6 shadow-inner">
                    <svg class="w-4 h-4 text-purple-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    Cover Image
                </div>

                <div class="relative z-10">
                    @if($farm->main_image)
                        <div class="w-full h-80 rounded-2xl overflow-hidden border-2 border-slate-800 shadow-inner">
                            <img src="{{ asset('storage/' . $farm->main_image) }}" alt="{{ $farm->name }}" class="w-full h-full object-cover">
                        </div>
                    @else
                        <div class="w-full h-80 rounded-2xl bg-slate-950 border-2 border-slate-800 flex items-center justify-center">
                            <svg class="w-16 h-16 text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        </div>
                    @endif
                </div>
            </div>

            <div class="bg-slate-900/60 p-8 md:p-10 rounded-[3rem] border border-slate-800 backdrop-blur-xl shadow-2xl relative overflow-hidden">
                <div class="absolute -right-20 -top-20 w-64 h-64 bg-blue-500/5 blur-[80px] rounded-full pointer-events-none"></div>

                <div class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-slate-950 border border-slate-800 text-[10px] font-black text-blue-400 uppercase tracking-widest mb-8 shadow-inner">
                    <svg class="w-4 h-4 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Core Information
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 relative z-10">
                    <div class="space-y-2">
                        <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest ml-2">Property Name</label>
                        <div class="readonly-field px-5 py-4 font-bold text-sm">
                            {{ $farm->name }}
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest ml-2">Governorate</label>
                        <div class="readonly-field px-5 py-4 font-bold text-sm">
                            {{ $farm->governorate }}
                        </div>
                    </div>

                    <div class="md:col-span-2 space-y-2">
                        <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest ml-2">Location Address</label>
                        <div class="readonly-field px-5 py-4 font-medium text-sm">
                            {{ $farm->location }}
                        </div>
                    </div>

                    @if($farm->location_link)
                    <div class="md:col-span-2 space-y-2">
                        <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest ml-2">Google Maps Link</label>
                        <a href="{{ $farm->location_link }}" target="_blank" class="readonly-field px-5 py-4 font-mono text-sm text-blue-400 hover:text-blue-300 break-all block">
                            {{ $farm->location_link }}
                        </a>
                    </div>
                    @endif

                    <div class="space-y-2">
                        <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest ml-2">Latitude</label>
                        <div class="readonly-field px-5 py-4 font-mono text-sm">
                            {{ $farm->latitude ?? '—' }}
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest ml-2">Longitude</label>
                        <div class="readonly-field px-5 py-4 font-mono text-sm">
                            {{ $farm->longitude ?? '—' }}
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest ml-2">Max Capacity</label>
                        <div class="readonly-field px-5 py-4 font-bold text-sm">
                            {{ $farm->capacity }} persons
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest ml-2">Status</label>
                        <div class="readonly-field px-5 py-4 font-bold text-sm capitalize">
                            <span class="{{ $farm->status === 'active' ? 'text-emerald-400' : ($farm->status === 'maintenance' ? 'text-amber-400' : 'text-rose-400') }}">
                                {{ $farm->status }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-slate-900/60 p-8 md:p-10 rounded-[3rem] border border-slate-800 backdrop-blur-xl shadow-2xl relative overflow-hidden">
                <div class="absolute -right-20 -top-20 w-64 h-64 bg-amber-500/5 blur-[80px] rounded-full pointer-events-none"></div>

                <div class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-slate-950 border border-slate-800 text-[10px] font-black text-amber-400 uppercase tracking-widest mb-6 shadow-inner">
                    <svg class="w-4 h-4 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    Description
                </div>

                <div class="readonly-field px-6 py-5 font-medium text-sm leading-relaxed whitespace-pre-wrap">
                    {{ $farm->description }}
                </div>
            </div>

            @if($farm->images && $farm->images->count() > 0)
            <div class="bg-slate-900/60 p-8 md:p-10 rounded-[3rem] border border-slate-800 backdrop-blur-xl shadow-2xl relative overflow-hidden">
                <div class="absolute -right-20 -top-20 w-64 h-64 bg-pink-500/5 blur-[80px] rounded-full pointer-events-none"></div>

                <div class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-slate-950 border border-slate-800 text-[10px] font-black text-pink-400 uppercase tracking-widest mb-6 shadow-inner">
                    <svg class="w-4 h-4 text-pink-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    Gallery ({{ $farm->images->count() }} images)
                </div>

                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 relative z-10">
                    @foreach($farm->images as $image)
                        <div class="aspect-square rounded-xl overflow-hidden border-2 border-slate-800 shadow-inner">
                            <img src="{{ asset('storage/' . $image->image_url) }}" alt="Gallery image" class="w-full h-full object-cover">
                        </div>
                    @endforeach
                </div>
            </div>
            @endif

        </div>

        <div class="xl:col-span-4 space-y-8">

            <div class="bg-slate-900/60 p-8 rounded-[3rem] border border-slate-800 backdrop-blur-xl shadow-2xl relative overflow-hidden">
                <div class="absolute -right-20 -top-20 w-64 h-64 bg-indigo-500/5 blur-[80px] rounded-full pointer-events-none"></div>

                <div class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-slate-950 border border-slate-800 text-[10px] font-black text-indigo-400 uppercase tracking-widest mb-6 shadow-inner">
                    <svg class="w-4 h-4 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    Owner Information
                </div>

                <div class="space-y-4 relative z-10">
                    <div class="flex items-center gap-4 p-4 bg-slate-950 rounded-xl border border-slate-800">
                        <div class="w-12 h-12 rounded-xl bg-gradient-to-tr from-indigo-500 to-purple-500 flex items-center justify-center text-white font-black text-lg shadow-inner">
                            {{ substr($farm->owner->name ?? 'U', 0, 1) }}
                        </div>
                        <div>
                            <p class="text-sm font-black text-white">{{ $farm->owner->name ?? 'Unknown' }}</p>
                            <p class="text-[10px] text-slate-500 font-bold uppercase tracking-widest">Farm Owner</p>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="block text-[9px] font-black text-slate-500 uppercase tracking-widest ml-1">Email</label>
                        <div class="readonly-field px-4 py-3 font-mono text-xs">
                            {{ $farm->owner->email ?? 'N/A' }}
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="block text-[9px] font-black text-slate-500 uppercase tracking-widest ml-1">Phone</label>
                        <div class="readonly-field px-4 py-3 font-mono text-xs">
                            {{ $farm->owner->phone ?? 'N/A' }}
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-slate-900/60 p-8 rounded-[3rem] border border-slate-800 backdrop-blur-xl shadow-2xl relative overflow-hidden">
                <div class="absolute -right-20 -top-20 w-64 h-64 bg-emerald-500/5 blur-[80px] rounded-full pointer-events-none"></div>

                <div class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-slate-950 border border-slate-800 text-[10px] font-black text-emerald-400 uppercase tracking-widest mb-6 shadow-inner">
                    <svg class="w-4 h-4 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Pricing (JOD)
                </div>

                <div class="space-y-4 relative z-10">

                    <div class="flex justify-between items-center p-4 bg-slate-950 rounded-xl border border-slate-800">
                        <span class="text-[10px] font-black text-slate-500 uppercase tracking-widest">Morning Shift</span>
                        <span class="text-lg font-black text-white">{{ number_format($farm->price_per_morning_shift, 2) }}</span>
                    </div>

                    <div class="flex justify-between items-center p-4 bg-slate-950 rounded-xl border border-slate-800">
                        <span class="text-[10px] font-black text-slate-500 uppercase tracking-widest">Evening Shift</span>
                        <span class="text-lg font-black text-white">{{ number_format($farm->price_per_evening_shift, 2) }}</span>
                    </div>

                    <div class="flex justify-between items-center p-4 bg-gradient-to-r from-emerald-500/10 to-teal-500/10 rounded-xl border border-emerald-500/20">
                        <span class="text-[10px] font-black text-emerald-400 uppercase tracking-widest">Full Day</span>
                        <span class="text-2xl font-black text-emerald-400">{{ number_format($farm->price_per_full_day, 2) }}</span>
                    </div>
                </div>
            </div>

            <div class="bg-slate-900/60 p-8 rounded-[3rem] border border-slate-800 backdrop-blur-xl shadow-2xl relative overflow-hidden">
                <div class="absolute -right-20 -top-20 w-64 h-64 bg-rose-500/5 blur-[80px] rounded-full pointer-events-none"></div>

                <div class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-slate-950 border border-slate-800 text-[10px] font-black text-rose-400 uppercase tracking-widest mb-6 shadow-inner">
                    <svg class="w-4 h-4 text-rose-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2zM10 8.5a.5.5 0 11-1 0 .5.5 0 011 0zm5 5a.5.5 0 11-1 0 .5.5 0 011 0z"></path></svg>
                    Commission
                </div>

                <div class="relative z-10">
                    <div class="flex justify-between items-center p-5 bg-slate-950 rounded-xl border border-slate-800">
                        <span class="text-[10px] font-black text-slate-500 uppercase tracking-widest">Commission Rate</span>
                        <span class="text-2xl font-black text-rose-400">{{ $farm->commission_rate }}%</span>
                    </div>
                </div>
            </div>

            @if($farm->rating)
            <div class="bg-slate-900/60 p-8 rounded-[3rem] border border-slate-800 backdrop-blur-xl shadow-2xl relative overflow-hidden">
                <div class="absolute -right-20 -top-20 w-64 h-64 bg-amber-500/5 blur-[80px] rounded-full pointer-events-none"></div>

                <div class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-slate-950 border border-slate-800 text-[10px] font-black text-amber-400 uppercase tracking-widest mb-6 shadow-inner">
                    <svg class="w-4 h-4 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path></svg>
                    Rating
                </div>

                <div class="relative z-10 flex items-center gap-3 p-5 bg-slate-950 rounded-xl border border-slate-800">
                    <div class="flex text-amber-400">
                        @for($i = 1; $i <= 5; $i++)
                            @if($i <= floor($farm->rating))
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                            @else
                                <svg class="w-6 h-6 text-slate-600" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                            @endif
                        @endfor
                    </div>
                    <span class="text-2xl font-black text-white">{{ number_format($farm->rating, 1) }}</span>
                    <span class="text-[10px] text-slate-500 font-bold uppercase">/ 5.0</span>
                </div>
            </div>
            @endif

        </div>
    </div>
</div>
@endsection