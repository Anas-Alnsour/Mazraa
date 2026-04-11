@extends('layouts.admin')

@section('title', 'Super Admin Dashboard')

@section('content')
<style>
    .fade-in-up { animation: fadeInUp 0.8s cubic-bezier(0.2, 0.8, 0.2, 1) forwards; opacity: 0; }
    .fade-in-up-stagger { animation: fadeInUp 0.8s cubic-bezier(0.2, 0.8, 0.2, 1) both; }
    @keyframes fadeInUp { 0% { opacity: 0; transform: translateY(30px); } 100% { opacity: 1; transform: translateY(0); } }
    .stat-card { transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1); }
    .stat-card:hover { transform: translateY(-8px); z-index: 10; }
</style>

<div class="max-w-[96%] xl:max-w-7xl mx-auto py-8 space-y-10 pb-24">

    {{-- 🌟 Header Section --}}
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 bg-slate-900/80 p-10 rounded-[3rem] border border-slate-800 backdrop-blur-2xl shadow-2xl relative overflow-hidden fade-in-up">
        <div class="absolute -right-20 -top-20 w-80 h-80 bg-indigo-500/10 blur-[120px] rounded-full pointer-events-none"></div>
        <div class="relative z-10">
            <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-indigo-500/10 border border-indigo-500/30 mb-6 shadow-[0_0_15px_rgba(99,102,241,0.2)]">
                <span class="w-2 h-2 rounded-full bg-indigo-400 animate-ping absolute"></span>
                <span class="w-2 h-2 rounded-full bg-indigo-500 relative"></span>
                <span class="text-[10px] font-black tracking-widest text-indigo-400 uppercase">God Mode Active</span>
            </div>
            <h1 class="text-4xl md:text-6xl font-black text-white tracking-tighter flex items-center mb-2">
                <svg class="w-12 h-12 mr-4 text-indigo-500 drop-shadow-[0_0_20px_rgba(99,102,241,0.6)]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                Ecosystem <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-400 to-cyan-400 ml-3">Command</span>
            </h1>
            <p class="text-xs font-bold tracking-[0.2em] uppercase text-slate-400 ml-16">Global Architecture & Financial Ledger Overview</p>
        </div>

        @if($actionRequiredCount > 0)
            <div class="relative z-10">
                <a href="{{ route('admin.verifications') }}" class="inline-flex items-center bg-rose-500/10 border border-rose-500/30 rounded-[2rem] p-6 hover:bg-rose-500/20 transition-all duration-300 group shadow-[0_0_30px_rgba(244,63,94,0.15)] hover:shadow-[0_0_40px_rgba(244,63,94,0.3)] hover:-translate-y-2">
                    <div class="relative flex h-5 w-5 mr-5">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-rose-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-5 w-5 bg-rose-500 shadow-[0_0_15px_rgba(244,63,94,0.8)]"></span>
                    </div>
                    <div>
                        <div class="text-xs font-black text-rose-400 group-hover:text-rose-300 uppercase tracking-widest mb-1">Verification Queue</div>
                        <div class="text-base font-bold text-rose-200">{{ $actionRequiredCount }} modules awaiting approval</div>
                    </div>
                </a>
            </div>
        @endif
    </div>

    {{-- 🌟 Top Level KPI Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">

        {{-- Gross Volume --}}
        <div class="stat-card bg-slate-900/80 backdrop-blur-xl rounded-[2.5rem] p-8 border border-slate-800 shadow-xl relative overflow-hidden hover:border-emerald-500/40 fade-in-up-stagger" style="animation-delay: 0.1s;">
            <div class="absolute -right-10 -top-10 w-48 h-48 bg-emerald-500/10 rounded-full blur-[50px]"></div>
            <h3 class="text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] mb-3 relative z-10">Gross Platform Volume</h3>
            <div class="text-4xl font-black text-white tracking-tighter mb-4 relative z-10">{{ number_format($totalGrossVolume, 2) }} <span class="text-sm text-emerald-500 font-black uppercase tracking-widest ml-1">JOD</span></div>
            <div class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-slate-950 border border-slate-800 text-[9px] font-bold uppercase tracking-widest text-slate-400 relative z-10">
                Cumulative network liquidity
            </div>
        </div>

        {{-- Net Revenue --}}
        <div class="stat-card bg-slate-900/80 backdrop-blur-xl rounded-[2.5rem] p-8 border border-indigo-500/20 shadow-[0_0_30px_rgba(99,102,241,0.05)] relative overflow-hidden hover:border-indigo-500/50 fade-in-up-stagger" style="animation-delay: 0.2s;">
            <div class="absolute -right-10 -top-10 w-48 h-48 bg-indigo-500/20 rounded-full blur-[50px]"></div>
            <h3 class="text-[10px] font-black text-indigo-400/80 uppercase tracking-[0.2em] mb-3 relative z-10">Net Ledger Revenue</h3>
            <div class="text-4xl font-black text-white tracking-tighter mb-4 relative z-10">{{ number_format($totalCommissionEarned, 2) }} <span class="text-sm text-indigo-400 font-black uppercase tracking-widest ml-1">JOD</span></div>
            <div class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-indigo-950/30 border border-indigo-500/20 text-[9px] font-bold uppercase tracking-widest text-indigo-300 relative z-10">
                Realized platform yields
            </div>
        </div>

        {{-- Network Nodes --}}
        <a href="{{ route('admin.users.index') }}" class="block stat-card fade-in-up-stagger" style="animation-delay: 0.3s;">
            <div class="bg-slate-900/80 backdrop-blur-xl rounded-[2.5rem] p-8 border border-slate-800 shadow-xl relative overflow-hidden hover:border-cyan-500/40 h-full group">
                <div class="absolute -right-10 -bottom-10 w-48 h-48 bg-cyan-500/10 rounded-full blur-[50px] opacity-0 group-hover:opacity-100 transition-opacity duration-700"></div>
                <h3 class="text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] mb-3 relative z-10 group-hover:text-cyan-400 transition-colors">Network Nodes (Users)</h3>
                <div class="text-5xl font-black text-white tracking-tighter mb-5 relative z-10">{{ number_format($totalUsers) }}</div>
                <div class="inline-flex items-center gap-2 text-[9px] font-black uppercase tracking-widest px-3 py-1.5 bg-slate-950 text-cyan-400 rounded-lg border border-slate-800 relative z-10 shadow-inner group-hover:border-cyan-500/30 transition-colors">
                    <span class="w-1.5 h-1.5 rounded-full bg-cyan-400 animate-pulse"></span>
                    {{ number_format($totalPartners) }} Authenticated Partners
                </div>
            </div>
        </a>

        {{-- Operational Estates --}}
        <div class="stat-card bg-slate-900/80 backdrop-blur-xl rounded-[2.5rem] p-8 border border-slate-800 shadow-xl relative overflow-hidden hover:border-rose-500/30 fade-in-up-stagger" style="animation-delay: 0.4s;">
            <div class="absolute -right-10 -bottom-10 w-48 h-48 bg-rose-500/10 rounded-full blur-[50px]"></div>
            <h3 class="text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] mb-3 relative z-10">Operational Estates</h3>
            <div class="text-4xl font-black text-white tracking-tighter mb-4 relative z-10 flex items-baseline gap-2">
                {{ number_format($activeFarmsCount) }} <span class="text-xl text-slate-600 font-black">/ {{ $totalFarms }}</span>
            </div>
            <div class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-slate-950 border border-slate-800 text-[9px] font-bold uppercase tracking-widest text-slate-400 relative z-10">
                Approved active properties
            </div>
        </div>
    </div>

    {{-- 🌟 Micro-Service Telemetry --}}
    <div class="fade-in-up" style="animation-delay: 0.5s;">
        <h2 class="text-[11px] font-black uppercase tracking-[0.3em] text-slate-500 mb-8 pb-4 border-b border-slate-800 flex items-center gap-3 ml-2">
            <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
            Micro-Service Live Telemetry
        </h2>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            {{-- Farm Module --}}
            <div class="bg-slate-900/50 backdrop-blur-2xl rounded-[3rem] border border-emerald-500/10 overflow-hidden shadow-2xl hover:border-emerald-500/30 transition-all duration-500 group">
                <div class="bg-emerald-950/20 px-8 py-6 border-b border-emerald-500/10 flex items-center">
                    <div class="bg-emerald-500/10 p-3 rounded-xl mr-4 border border-emerald-500/20 shadow-[0_0_15px_rgba(16,185,129,0.1)] group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6 text-emerald-400 drop-shadow-[0_0_5px_rgba(16,185,129,0.8)]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                    </div>
                    <h3 class="text-xs font-black uppercase tracking-[0.2em] text-emerald-400">Core: Farm Booking</h3>
                </div>
                <div class="p-8 relative">
                    <div class="absolute -right-20 -bottom-20 w-64 h-64 bg-emerald-500/5 rounded-full blur-[80px] pointer-events-none"></div>
                    <div class="mb-10 relative z-10">
                        <div class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-2">Module Gross Liquidity</div>
                        <div class="text-4xl font-black text-white tracking-tighter">{{ number_format($farmRevenue, 2) }} <span class="text-[10px] font-bold uppercase tracking-widest text-emerald-500/50 ml-1">JOD</span></div>
                    </div>
                    <div class="space-y-3 relative z-10">
                        <div class="flex justify-between items-center bg-slate-950/80 p-5 rounded-[1.5rem] border border-slate-800 shadow-inner hover:border-emerald-500/30 transition-colors">
                            <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Confirmed Pipeline</span>
                            <span class="bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-xs font-black px-4 py-2 rounded-xl shadow-[0_0_15px_rgba(16,185,129,0.15)]">{{ $activeFarmBookings }}</span>
                        </div>
                        <div class="flex justify-between items-center bg-slate-950/80 p-5 rounded-[1.5rem] border border-slate-800 shadow-inner hover:border-amber-500/30 transition-colors">
                            <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Pending Actions</span>
                            <span class="bg-amber-500/10 border border-amber-500/20 text-amber-400 text-xs font-black px-4 py-2 rounded-xl">{{ $pendingFarmBookings }}</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Supply Module --}}
            <div class="bg-slate-900/50 backdrop-blur-2xl rounded-[3rem] border border-lime-500/10 overflow-hidden shadow-2xl hover:border-lime-500/30 transition-all duration-500 group">
                <div class="bg-lime-950/20 px-8 py-6 border-b border-lime-500/10 flex items-center">
                    <div class="bg-lime-500/10 p-3 rounded-xl mr-4 border border-lime-500/20 shadow-[0_0_15px_rgba(132,204,22,0.1)] group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6 text-lime-400 drop-shadow-[0_0_5px_rgba(132,204,22,0.8)]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                    </div>
                    <h3 class="text-xs font-black uppercase tracking-[0.2em] text-lime-400">Micro: E-Commerce</h3>
                </div>
                <div class="p-8 relative">
                    <div class="absolute -right-20 -bottom-20 w-64 h-64 bg-lime-500/5 rounded-full blur-[80px] pointer-events-none"></div>
                    <div class="mb-10 relative z-10">
                        <div class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-2">Marketplace Volume</div>
                        <div class="text-4xl font-black text-white tracking-tighter">{{ number_format($supplyRevenue, 2) }} <span class="text-[10px] font-bold uppercase tracking-widest text-lime-500/50 ml-1">JOD</span></div>
                    </div>
                    <div class="space-y-3 relative z-10">
                        <div class="flex justify-between items-center bg-slate-950/80 p-5 rounded-[1.5rem] border border-slate-800 shadow-inner hover:border-lime-500/30 transition-colors">
                            <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">In Transit (Active)</span>
                            <span class="bg-lime-500/10 border border-lime-500/20 text-lime-400 text-xs font-black px-4 py-2 rounded-xl shadow-[0_0_15px_rgba(132,204,22,0.2)] animate-pulse">{{ $inTransitSupplies }}</span>
                        </div>
                        <div class="flex justify-between items-center bg-slate-950/80 p-5 rounded-[1.5rem] border border-slate-800 shadow-inner hover:border-slate-600 transition-colors">
                            <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Successfully Fulfilled</span>
                            <span class="bg-slate-800 border border-slate-700 text-slate-300 text-xs font-black px-4 py-2 rounded-xl">{{ $completedSupplies }}</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Transport Module --}}
            <div class="bg-slate-900/50 backdrop-blur-2xl rounded-[3rem] border border-cyan-500/10 overflow-hidden shadow-2xl hover:border-cyan-500/30 transition-all duration-500 group">
                <div class="bg-cyan-950/20 px-8 py-6 border-b border-cyan-500/10 flex items-center">
                    <div class="bg-cyan-500/10 p-3 rounded-xl mr-4 border border-cyan-500/20 shadow-[0_0_15px_rgba(6,182,212,0.1)] group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6 text-cyan-400 drop-shadow-[0_0_5px_rgba(6,182,212,0.8)]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
                    </div>
                    <h3 class="text-xs font-black uppercase tracking-[0.2em] text-cyan-400">Micro: Logistics</h3>
                </div>
                <div class="p-8 relative">
                    <div class="absolute -right-20 -bottom-20 w-64 h-64 bg-cyan-500/5 rounded-full blur-[80px] pointer-events-none"></div>
                    <div class="mb-10 relative z-10">
                        <div class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-2">Dispatch Liquidity</div>
                        <div class="text-4xl font-black text-white tracking-tighter">{{ number_format($transportRevenue, 2) }} <span class="text-[10px] font-bold uppercase tracking-widest text-cyan-500/50 ml-1">JOD</span></div>
                    </div>
                    <div class="space-y-3 relative z-10">
                        <div class="flex justify-between items-center bg-slate-950/80 p-5 rounded-[1.5rem] border border-slate-800 shadow-inner hover:border-cyan-500/30 transition-colors">
                            <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Active Shuttles</span>
                            <span class="bg-cyan-500/10 border border-cyan-500/20 text-cyan-400 text-xs font-black px-4 py-2 rounded-xl shadow-[0_0_15px_rgba(6,182,212,0.2)] animate-pulse">{{ $inProgressTransports }}</span>
                        </div>
                        <div class="flex justify-between items-center bg-slate-950/80 p-5 rounded-[1.5rem] border border-slate-800 shadow-inner hover:border-amber-500/30 transition-colors">
                            <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Awaiting Driver</span>
                            <span class="bg-amber-500/10 border border-amber-500/20 text-amber-400 text-xs font-black px-4 py-2 rounded-xl">{{ $pendingTransports }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- 🌟 Geo-Spatial Coverage (Map) --}}
    <div class="fade-in-up" style="animation-delay: 0.6s;">
        <div class="bg-slate-900/80 backdrop-blur-2xl rounded-[3rem] p-10 border border-slate-800 shadow-2xl relative overflow-hidden group hover:border-emerald-500/30 transition-colors duration-700">
            <div class="absolute -left-32 -bottom-32 w-[500px] h-[500px] bg-emerald-500/5 rounded-full blur-[100px] pointer-events-none"></div>

            <h3 class="text-sm font-black text-emerald-400 uppercase tracking-[0.3em] mb-8 flex items-center gap-3 border-b border-slate-800 pb-5 relative z-10">
                <svg class="w-6 h-6 drop-shadow-[0_0_10px_rgba(16,185,129,0.8)]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                Geo-Spatial Coverage Map (Jordan)
            </h3>

            <div class="relative z-10 rounded-[2rem] overflow-hidden border border-slate-800 shadow-[0_0_40px_rgba(0,0,0,0.5)]">
                <div id="farmMap" class="w-full h-[600px] z-10 mix-blend-luminosity filter contrast-125"></div>
                <div class="absolute inset-0 border-[6px] border-slate-950/40 rounded-[2rem] pointer-events-none"></div>
            </div>
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
            backgroundColor: '#020617',
            styles: [
                { "elementType": "geometry", "stylers": [{ "color": "#0f172a" }] },
                { "elementType": "labels.text.stroke", "stylers": [{ "color": "#020617" }] },
                { "elementType": "labels.text.fill", "stylers": [{ "color": "#64748b" }] },
                { "featureType": "administrative", "elementType": "geometry.stroke", "stylers": [{ "color": "#334155" }] },
                { "featureType": "landscape.natural", "elementType": "geometry", "stylers": [{ "color": "#020617" }] },
                { "featureType": "poi", "elementType": "geometry", "stylers": [{ "color": "#0f172a" }] },
                { "featureType": "road", "elementType": "geometry", "stylers": [{ "color": "#1e293b" }] },
                { "featureType": "water", "elementType": "geometry", "stylers": [{ "color": "#000000" }] }
            ]
        });

        const farms = @json($verifiedFarms ?? []);
        const markers = farms.map((farm) => {
            if (!farm.latitude || !farm.longitude) return null;

            const marker = new google.maps.Marker({
                position: { lat: parseFloat(farm.latitude), lng: parseFloat(farm.longitude) },
                map: map,
                title: farm.name,
                icon: {
                    path: google.maps.SymbolPath.CIRCLE,
                    fillColor: '#10b981',
                    fillOpacity: 1,
                    strokeWeight: 0,
                    scale: 7
                }
            });

            const infoWindow = new google.maps.InfoWindow({
                content: `
                    <div style="background:#0f172a; color:#f8fafc; padding:20px; border-radius:16px; font-family:sans-serif; border: 1px solid #1e293b; box-shadow: 0 10px 25px rgba(0,0,0,0.5);">
                        <h4 style="margin:0 0 10px 0; font-size:16px; font-weight:900; letter-spacing:-0.5px;">${farm.name}</h4>
                        <div style="display:inline-block; padding:6px 10px; background:rgba(16,185,129,0.15); border:1px solid rgba(16,185,129,0.3); border-radius:8px;">
                            <p style="margin:0; color:#10b981; font-size:10px; font-weight:900; text-transform:uppercase; letter-spacing:1.5px;">Active Node</p>
                        </div>
                    </div>
                `
            });

            marker.addListener("click", () => { infoWindow.open(map, marker); });
            return marker;
        }).filter(m => m !== null);

        if (markers.length > 0) {
            new markerClusterer.MarkerClusterer({
                markers,
                map,
                renderer: {
                    render: function({ count, position }) {
                        return new google.maps.Marker({
                            position,
                            label: { text: String(count), color: '#ffffff', fontSize: '13px', fontWeight: '900' },
                            icon: { path: google.maps.SymbolPath.CIRCLE, fillColor: '#6366f1', fillOpacity: 0.9, strokeColor: '#818cf8', strokeWeight: 3, scale: 20 }
                        });
                    }
                }
            });
        }
    }
    window.initMap = initMap;
</script>
@endpush
@endsection
