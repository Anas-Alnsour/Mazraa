@extends('layouts.admin')

@section('content')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>

<div class="max-w-7xl mx-auto">

    <div class="flex flex-col md:flex-row md:items-center justify-between mb-10 gap-6">
        <div>
            <h1 class="text-4xl font-black text-slate-900 tracking-tight">System Overview</h1>
            <p class="text-sm font-bold text-slate-400 mt-2 uppercase tracking-widest">Real-time metrics for Mazraa.com Platform</p>
        </div>

        <form action="{{ route('admin.dashboard') }}" method="GET" class="flex items-center gap-2">
            <select name="timeframe" onchange="this.form.submit()" class="bg-white border border-slate-200 text-slate-700 font-black rounded-2xl px-5 py-3.5 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition-all cursor-pointer shadow-sm text-xs uppercase tracking-widest">
                <option value="all" {{ $timeframe === 'all' ? 'selected' : '' }}>All Time</option>
                <option value="year" {{ $timeframe === 'year' ? 'selected' : '' }}>Past Year</option>
                <option value="month" {{ $timeframe === 'month' ? 'selected' : '' }}>Past Month</option>
                <option value="week" {{ $timeframe === 'week' ? 'selected' : '' }}>Past Week</option>
            </select>
        </form>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-10">
        <div class="bg-white rounded-[2.5rem] p-10 shadow-sm border border-slate-100 transition-transform hover:-translate-y-1">
            <div class="flex items-center gap-4 mb-6">
                <div class="h-14 w-14 rounded-2xl bg-slate-50 text-slate-500 flex items-center justify-center border border-slate-100 shadow-inner">
                    <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                </div>
                <div>
                    <h3 class="text-xs font-black text-slate-400 uppercase tracking-widest">Platform GMV</h3>
                    <p class="text-[9px] font-bold text-slate-400 mt-1 uppercase">Gross Merchandise Volume</p>
                </div>
            </div>
            <p class="text-5xl font-black text-slate-900">{{ number_format($financials['gross_volume'], 2) }} <span class="text-lg text-slate-400">JOD</span></p>
        </div>

        <div class="bg-slate-900 rounded-[2.5rem] p-10 shadow-2xl shadow-slate-900/20 border border-slate-800 relative overflow-hidden transition-transform hover:-translate-y-1">
            <div class="absolute -right-10 -bottom-10 opacity-10">
                <svg class="h-56 w-56 text-emerald-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M12 7a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0V8.414l-4.293 4.293a1 1 0 01-1.414 0L8 10.414l-4.293 4.293a1 1 0 01-1.414-1.414l5-5a1 1 0 011.414 0L11 10.586 14.586 7H12z" clip-rule="evenodd"/></svg>
            </div>
            <div class="relative z-10">
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center gap-4">
                        <div class="h-14 w-14 rounded-2xl bg-emerald-500/20 text-emerald-400 border border-emerald-500/30 flex items-center justify-center backdrop-blur-sm">
                            <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <div>
                            <h3 class="text-xs font-black text-emerald-400 uppercase tracking-widest">Net Revenue</h3>
                            <p class="text-[9px] font-bold text-slate-400 mt-1 uppercase">10% Platform Commission</p>
                        </div>
                    </div>
                    <div class="bg-red-500/20 border border-red-500/30 text-red-400 px-3 py-1.5 rounded-lg text-[9px] font-black uppercase tracking-widest backdrop-blur-sm">
                        16% TAX: {{ number_format($financials['taxes'], 2) }}
                    </div>
                </div>
                <p class="text-5xl font-black text-white">{{ number_format($financials['net_revenue'], 2) }} <span class="text-lg text-emerald-400">JOD</span></p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-10">
        <div class="lg:col-span-2 bg-white rounded-[2.5rem] p-8 md:p-10 shadow-sm border border-slate-100 flex flex-col justify-center">
            <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-8 border-b border-slate-100 pb-4">Revenue Sources</h3>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                <div class="p-6 bg-slate-50 rounded-3xl border border-slate-100">
                    <div class="h-10 w-10 bg-white rounded-xl flex items-center justify-center text-slate-500 mb-4 shadow-sm border border-slate-100">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" /></svg>
                    </div>
                    <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1">Farm Bookings</p>
                    <p class="text-2xl font-black text-slate-900">{{ number_format($financials['farm_rev'], 2) }} <span class="text-[10px] text-slate-400">JOD</span></p>
                </div>
                <div class="p-6 bg-slate-50 rounded-3xl border border-slate-100">
                    <div class="h-10 w-10 bg-white rounded-xl flex items-center justify-center text-slate-500 mb-4 shadow-sm border border-slate-100">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg>
                    </div>
                    <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1">Supply Logistics</p>
                    <p class="text-2xl font-black text-slate-900">{{ number_format($financials['supply_rev'], 2) }} <span class="text-[10px] text-slate-400">JOD</span></p>
                </div>
                <div class="p-6 bg-slate-50 rounded-3xl border border-slate-100">
                    <div class="h-10 w-10 bg-white rounded-xl flex items-center justify-center text-slate-500 mb-4 shadow-sm border border-slate-100">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" /></svg>
                    </div>
                    <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1">Shuttle Transport</p>
                    <p class="text-2xl font-black text-slate-900">{{ number_format($financials['transport_rev'], 2) }} <span class="text-[10px] text-slate-400">JOD</span></p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-[2.5rem] p-8 md:p-10 shadow-sm border border-slate-100 flex flex-col justify-center">
            <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-8 border-b border-slate-100 pb-4">System Health</h3>
            <ul class="space-y-4">
                <li class="flex items-center justify-between p-4 bg-slate-50 rounded-2xl border border-slate-100">
                    <span class="text-[10px] font-black text-slate-600 uppercase tracking-widest">Total Users</span>
                    <span class="text-lg font-black text-slate-900">{{ $systemStats['total_users'] }}</span>
                </li>
                <li class="flex items-center justify-between p-4 bg-slate-50 rounded-2xl border border-slate-100">
                    <span class="text-[10px] font-black text-slate-600 uppercase tracking-widest">Total Farms</span>
                    <span class="text-lg font-black text-slate-900">{{ $systemStats['total_farms'] }}</span>
                </li>
                <li class="flex items-center justify-between p-4 bg-emerald-50 text-emerald-700 rounded-2xl border border-emerald-100">
                    <span class="text-[10px] font-black uppercase tracking-widest">Verified Farms</span>
                    <span class="text-lg font-black">{{ $systemStats['approved_farms'] }}</span>
                </li>
                <li class="flex items-center justify-between p-4 {{ $systemStats['pending_farms'] > 0 ? 'bg-red-50 text-red-700 border border-red-100' : 'bg-slate-50 text-slate-600' }} rounded-2xl transition-colors">
                    <span class="text-[10px] font-black uppercase tracking-widest flex items-center gap-2">
                        @if($systemStats['pending_farms'] > 0) <span class="w-1.5 h-1.5 rounded-full bg-red-500 animate-pulse"></span> @endif
                        Pending Approval
                    </span>
                    <span class="text-lg font-black">{{ $systemStats['pending_farms'] }}</span>
                </li>
            </ul>
        </div>
    </div>

    <div class="bg-white rounded-[2.5rem] p-8 md:p-10 shadow-sm border border-slate-100 mb-10">
        <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-6 flex items-center gap-2 border-b border-slate-100 pb-4">
            <svg class="w-4 h-4 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            Verified Farms Coverage (Jordan)
        </h3>
        <div id="farmMap" class="w-full h-[450px] rounded-3xl border border-slate-200 shadow-inner z-10"></div>
    </div>

</div>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Center Map on Jordan
        var map = L.map('farmMap').setView([31.9522, 35.9334], 7);

        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
        }).addTo(map);

        // Fetch verified farms from controller
        var farms = @json($verifiedFarms);

        farms.forEach(function(farm) {
            if(farm.latitude && farm.longitude) {
                var marker = L.marker([parseFloat(farm.latitude), parseFloat(farm.longitude)]).addTo(map);
                marker.bindPopup("<div class='text-center'><b class='font-black text-slate-800 text-sm'>" + farm.name + "</b><br><span class='text-[10px] font-bold text-slate-400 uppercase tracking-widest'>ID: " + farm.id + "</span></div>");
            }
        });
    });
</script>
@endsection
