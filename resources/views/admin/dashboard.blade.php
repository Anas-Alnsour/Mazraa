@extends('layouts.admin')

@section('title', 'Super Admin Dashboard')

@section('content')

<div class="py-10 bg-slate-900 min-h-screen text-slate-200">
    <div class="max-w-[95rem] mx-auto sm:px-6 lg:px-8">

        <div class="mb-8 flex flex-col md:flex-row justify-between items-start md:items-center">
            <div>
                <h1 class="text-3xl font-black text-white tracking-tight flex items-center">
                    <svg class="w-8 h-8 mr-3 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                    Mazraa.com Command Center
                </h1>
                <p class="mt-2 text-sm font-bold tracking-widest uppercase text-slate-400">System architecture and ecosystem overview</p>
            </div>

            @if($actionRequiredCount > 0)
                <a href="{{ route('admin.verifications') }}" class="mt-4 md:mt-0 inline-flex items-center bg-rose-500/10 border border-rose-500/30 rounded-xl p-3 hover:bg-rose-500/20 transition-colors group shadow-lg">
                    <div class="relative flex h-3 w-3 mr-3">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-rose-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-3 w-3 bg-rose-500"></span>
                    </div>
                    <div>
                        <div class="text-sm font-black text-rose-400 group-hover:text-rose-300 uppercase tracking-widest">Action Required</div>
                        <div class="text-xs font-bold text-rose-300/80">{{ $actionRequiredCount }} items awaiting approval</div>
                    </div>
                </a>
            @endif
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
            <div class="bg-slate-800 rounded-2xl p-6 border border-slate-700 shadow-xl relative overflow-hidden">
                <div class="absolute top-0 right-0 -mr-8 -mt-8 w-32 h-32 rounded-full bg-white/5 blur-3xl"></div>
                <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Gross Platform Volume</h3>
                <div class="text-3xl font-black text-white mb-2">{{ number_format($totalGrossVolume, 2) }} <span class="text-sm text-slate-500 font-bold uppercase tracking-widest">JOD</span></div>
                <p class="text-[10px] font-bold uppercase tracking-widest text-slate-500">Total value processed across all modules.</p>
            </div>

            <div class="bg-indigo-900/40 rounded-2xl p-6 border border-indigo-500/30 shadow-xl relative overflow-hidden">
                <div class="absolute top-0 right-0 -mr-8 -mt-8 w-32 h-32 rounded-full bg-indigo-500/20 blur-3xl"></div>
                <h3 class="text-[10px] font-black text-indigo-300 uppercase tracking-widest mb-1">Total Admin Revenue</h3>
                <div class="text-3xl font-black text-white mb-2">{{ number_format($totalCommissionEarned, 2) }} <span class="text-sm text-indigo-400 font-bold uppercase tracking-widest">JOD</span></div>
                <p class="text-[10px] font-bold uppercase tracking-widest text-indigo-200/60">Platform commissions collected.</p>
            </div>

            <a href="{{ route('admin.users.index') }}" class="block group">
                <div class="bg-slate-800 rounded-2xl p-6 border border-slate-700 shadow-xl group-hover:bg-slate-700/50 transition-all transform hover:-translate-y-1">
                    <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1 group-hover:text-indigo-400 transition-colors">Registered Users</h3>
                    <div class="text-3xl font-black text-white mb-2 flex items-center">
                        {{ number_format($totalUsers) }}
                        <span class="ml-3 text-[10px] font-black uppercase tracking-widest px-2 py-1 bg-slate-700 text-slate-300 rounded-md border border-slate-600">
                            {{ number_format($totalPartners) }} Partners
                        </span>
                    </div>
                </div>
            </a>

            <div class="bg-slate-800 rounded-2xl p-6 border border-slate-700 shadow-xl">
                <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Live Farms</h3>
                <div class="text-3xl font-black text-white mb-2">{{ number_format($activeFarmsCount) }} <span class="text-sm text-slate-500 font-bold">/ {{ $totalFarms }}</span></div>
            </div>
        </div>

        <h2 class="text-xs font-black uppercase tracking-widest text-slate-400 mb-4 pb-2 flex items-center gap-2">
            <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
            Ecosystem Live Activity
        </h2>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-10">
            <div class="bg-slate-800 rounded-2xl border border-emerald-500/20 overflow-hidden shadow-xl">
                <div class="bg-emerald-900/30 px-6 py-4 border-b border-emerald-500/20 flex items-center">
                    <div class="bg-emerald-500/20 p-2 rounded-lg mr-3 border border-emerald-500/30 shadow-inner">
                        <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                    </div>
                    <h3 class="text-sm font-black uppercase tracking-widest text-emerald-400">Farm Bookings</h3>
                </div>
                <div class="p-6">
                    <div class="mb-6">
                        <div class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1">Module Volume</div>
                        <div class="text-2xl font-black text-white">{{ number_format($farmRevenue, 2) }} <span class="text-[10px] uppercase tracking-widest text-slate-500">JOD</span></div>
                    </div>
                    <div class="space-y-3">
                        <div class="flex justify-between items-center bg-slate-900/80 p-3.5 rounded-xl border border-slate-700/50 shadow-inner">
                            <span class="text-xs font-bold text-slate-300 uppercase tracking-widest">Active / Confirmed</span>
                            <span class="bg-emerald-500/20 border border-emerald-500/30 text-emerald-400 text-xs font-black px-2.5 py-1 rounded-md">{{ $activeFarmBookings }}</span>
                        </div>
                        <div class="flex justify-between items-center bg-slate-900/80 p-3.5 rounded-xl border border-slate-700/50 shadow-inner">
                            <span class="text-xs font-bold text-slate-300 uppercase tracking-widest">Pending Actions</span>
                            <span class="bg-amber-500/20 border border-amber-500/30 text-amber-400 text-xs font-black px-2.5 py-1 rounded-md">{{ $pendingFarmBookings }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-slate-800 rounded-2xl border border-lime-500/20 overflow-hidden shadow-xl">
                <div class="bg-lime-900/30 px-6 py-4 border-b border-lime-500/20 flex items-center">
                    <div class="bg-lime-500/20 p-2 rounded-lg mr-3 border border-lime-500/30 shadow-inner">
                        <svg class="w-5 h-5 text-lime-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                    </div>
                    <h3 class="text-sm font-black uppercase tracking-widest text-lime-400">Marketplace Supplies</h3>
                </div>
                <div class="p-6">
                    <div class="mb-6">
                        <div class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1">Module Volume</div>
                        <div class="text-2xl font-black text-white">{{ number_format($supplyRevenue, 2) }} <span class="text-[10px] uppercase tracking-widest text-slate-500">JOD</span></div>
                    </div>
                    <div class="space-y-3">
                        <div class="flex justify-between items-center bg-slate-900/80 p-3.5 rounded-xl border border-slate-700/50 shadow-inner">
                            <span class="text-xs font-bold text-slate-300 uppercase tracking-widest">In Transit</span>
                            <span class="bg-lime-500/20 border border-lime-500/30 text-lime-400 text-xs font-black px-2.5 py-1 rounded-md animate-pulse">{{ $inTransitSupplies }}</span>
                        </div>
                        <div class="flex justify-between items-center bg-slate-900/80 p-3.5 rounded-xl border border-slate-700/50 shadow-inner">
                            <span class="text-xs font-bold text-slate-300 uppercase tracking-widest">Completed / Delivered</span>
                            <span class="bg-slate-700 border border-slate-600 text-slate-300 text-xs font-black px-2.5 py-1 rounded-md">{{ $completedSupplies }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-slate-800 rounded-2xl border border-cyan-500/20 overflow-hidden shadow-xl">
                <div class="bg-cyan-900/30 px-6 py-4 border-b border-cyan-500/20 flex items-center">
                    <div class="bg-cyan-500/20 p-2 rounded-lg mr-3 border border-cyan-500/30 shadow-inner">
                        <svg class="w-5 h-5 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
                    </div>
                    <h3 class="text-sm font-black uppercase tracking-widest text-cyan-400">Transport Logistics</h3>
                </div>
                <div class="p-6">
                    <div class="mb-6">
                        <div class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1">Module Volume</div>
                        <div class="text-2xl font-black text-white">{{ number_format($transportRevenue, 2) }} <span class="text-[10px] uppercase tracking-widest text-slate-500">JOD</span></div>
                    </div>
                    <div class="space-y-3">
                        <div class="flex justify-between items-center bg-slate-900/80 p-3.5 rounded-xl border border-slate-700/50 shadow-inner">
                            <span class="text-xs font-bold text-slate-300 uppercase tracking-widest">Active Trips</span>
                            <span class="bg-cyan-500/20 border border-cyan-500/30 text-cyan-400 text-xs font-black px-2.5 py-1 rounded-md animate-pulse">{{ $inProgressTransports }}</span>
                        </div>
                        <div class="flex justify-between items-center bg-slate-900/80 p-3.5 rounded-xl border border-slate-700/50 shadow-inner">
                            <span class="text-xs font-bold text-slate-300 uppercase tracking-widest">Awaiting Dispatch</span>
                            <span class="bg-amber-500/20 border border-amber-500/30 text-amber-400 text-xs font-black px-2.5 py-1 rounded-md">{{ $pendingTransports }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-slate-800 rounded-2xl p-6 border border-slate-700 shadow-xl mb-10">
            <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-6 flex items-center gap-2 border-b border-slate-700 pb-4">
                <svg class="w-4 h-4 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                Verified Farms Coverage (Jordan)
            </h3>
            <div id="farmMap" class="w-full h-[400px] rounded-xl border border-slate-700 shadow-inner z-10"></div>
        </div>

    </div>
</div>

@push('scripts')
<script src="https://unpkg.com/@googlemaps/markerclusterer/dist/index.min.js"></script>
<script src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_API_KEY') }}&libraries=marker&callback=initMap" async defer></script>
<script>
    function initMap() {
        const jordanCenter = { lat: 31.2522, lng: 36.5334 };
        const map = new google.maps.Map(document.getElementById("farmMap"), {
            zoom: 7,
            center: jordanCenter,
            disableDefaultUI: true,
            zoomControl: true,
            styles: [
                { "elementType": "geometry", "stylers": [{ "color": "#1e293b" }] },
                { "elementType": "labels.text.stroke", "stylers": [{ "color": "#1e293b" }] },
                { "elementType": "labels.text.fill", "stylers": [{ "color": "#64748b" }] },
                { "featureType": "administrative", "elementType": "geometry.stroke", "stylers": [{ "color": "#334155" }] },
                { "featureType": "landscape.natural", "elementType": "geometry", "stylers": [{ "color": "#0f172a" }] },
                { "featureType": "poi", "elementType": "geometry", "stylers": [{ "color": "#1e293b" }] },
                { "featureType": "road", "elementType": "geometry", "stylers": [{ "color": "#334155" }] },
                { "featureType": "water", "elementType": "geometry", "stylers": [{ "color": "#020617" }] }
            ]
        });

        const farms = @json($verifiedFarms ?? []);
        const markers = farms.map((farm) => {
            if (!farm.latitude || !farm.longitude) return null;
            
            const marker = new google.maps.Marker({
                position: { lat: parseFloat(farm.latitude), lng: parseFloat(farm.longitude) },
                map: map,
                title: farm.name
            });

            const infoWindow = new google.maps.InfoWindow({
                content: `
                    <div style="background:#1e293b; color:#f1f5f9; padding:10px; border-radius:8px; font-family:sans-serif;">
                        <h4 style="margin:0; font-size:14px; color:#1e293b;">${farm.name}</h4>
                        <p style="margin:5px 0 0; color:#10b981; font-size:10px; font-weight:bold; text-transform:uppercase;">Verified Farm</p>
                    </div>
                `
            });

            marker.addListener("click", () => {
                infoWindow.open(map, marker);
            });

            return marker;
        }).filter(m => m !== null);

        if (markers.length > 0) {
            new markerClusterer.MarkerClusterer({ markers, map });
        }
    }
    window.initMap = initMap;
</script>
@endpush
@endsection
