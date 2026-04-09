@extends('layouts.admin')

@section('title', 'Super Admin Dashboard')

@section('content')

<div class="py-10 bg-slate-950 min-h-screen text-slate-200">
    <div class="max-w-[95rem] mx-auto sm:px-6 lg:px-8">

        <div class="mb-10 flex flex-col md:flex-row justify-between items-start md:items-center">
            <div>
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-indigo-500/10 border border-indigo-500/20 mb-4 backdrop-blur-md">
                    <span class="flex h-2 w-2 relative">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-indigo-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-indigo-500"></span>
                    </span>
                    <span class="text-[10px] font-black tracking-widest text-indigo-400 uppercase">God Mode Active</span>
                </div>
                <h1 class="text-4xl font-black text-white tracking-tight flex items-center">
                    <svg class="w-10 h-10 mr-4 text-indigo-500 drop-shadow-[0_0_15px_rgba(99,102,241,0.5)]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                    Ecosystem Command Center
                </h1>
                <p class="mt-2 text-xs font-bold tracking-[0.2em] uppercase text-slate-500">Global Architecture & Financial Ledger Overview</p>
            </div>

            @if($actionRequiredCount > 0)
                <a href="{{ route('admin.verifications') }}" class="mt-6 md:mt-0 inline-flex items-center bg-rose-500/10 border border-rose-500/20 rounded-2xl p-4 hover:bg-rose-500/20 transition-all duration-300 group shadow-[0_0_30px_rgba(244,63,94,0.15)] hover:shadow-[0_0_40px_rgba(244,63,94,0.25)] hover:-translate-y-1">
                    <div class="relative flex h-4 w-4 mr-4">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-rose-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-4 w-4 bg-rose-500 shadow-[0_0_10px_rgba(244,63,94,0.8)]"></span>
                    </div>
                    <div>
                        <div class="text-xs font-black text-rose-400 group-hover:text-rose-300 uppercase tracking-widest">Verification Queue</div>
                        <div class="text-sm font-bold text-rose-300/80">{{ $actionRequiredCount }} modules awaiting approval</div>
                    </div>
                </a>
            @endif
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">

            {{-- Dark Stat 1: Gross Volume --}}
            <div class="bg-slate-900/80 backdrop-blur-xl rounded-[2rem] p-8 border border-slate-800 shadow-2xl relative overflow-hidden group hover:border-emerald-500/30 transition-all duration-500">
                <div class="absolute -right-10 -top-10 w-40 h-40 bg-emerald-500/10 rounded-full blur-3xl opacity-50 group-hover:opacity-100 transition-opacity duration-700"></div>
                <h3 class="text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] mb-2 relative z-10">Gross Platform Volume</h3>
                <div class="text-4xl font-black text-white tracking-tighter mb-3 relative z-10">{{ number_format($totalGrossVolume, 2) }} <span class="text-sm text-emerald-500 font-black uppercase tracking-widest">JOD</span></div>
                <p class="text-[9px] font-bold uppercase tracking-widest text-slate-600 relative z-10">Cumulative network liquidity processed</p>
            </div>

            {{-- Dark Stat 2: Net Revenue --}}
            <div class="bg-slate-900/80 backdrop-blur-xl rounded-[2rem] p-8 border border-indigo-500/20 shadow-[0_0_30px_rgba(99,102,241,0.05)] relative overflow-hidden group hover:border-indigo-500/40 transition-all duration-500">
                <div class="absolute -right-10 -top-10 w-40 h-40 bg-indigo-500/20 rounded-full blur-3xl opacity-50 group-hover:opacity-100 transition-opacity duration-700"></div>
                <h3 class="text-[10px] font-black text-indigo-400/80 uppercase tracking-[0.2em] mb-2 relative z-10">Net Ledger Revenue</h3>
                <div class="text-4xl font-black text-white tracking-tighter mb-3 relative z-10">{{ number_format($totalCommissionEarned, 2) }} <span class="text-sm text-indigo-400 font-black uppercase tracking-widest">JOD</span></div>
                <p class="text-[9px] font-bold uppercase tracking-widest text-indigo-400/50 relative z-10">Realized platform commission yields</p>
            </div>

            {{-- Dark Stat 3: User Graph --}}
            <a href="{{ route('admin.users.index') }}" class="block group">
                <div class="bg-slate-900/80 backdrop-blur-xl rounded-[2rem] p-8 border border-slate-800 shadow-2xl relative overflow-hidden hover:bg-slate-800/80 hover:border-cyan-500/30 transition-all duration-500 transform hover:-translate-y-1">
                    <div class="absolute -right-10 -bottom-10 w-40 h-40 bg-cyan-500/10 rounded-full blur-3xl opacity-0 group-hover:opacity-100 transition-opacity duration-700"></div>
                    <h3 class="text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] mb-2 relative z-10 group-hover:text-cyan-400 transition-colors">Network Nodes (Users)</h3>
                    <div class="text-4xl font-black text-white tracking-tighter mb-4 flex items-center relative z-10">
                        {{ number_format($totalUsers) }}
                    </div>
                    <div class="inline-flex items-center gap-2 text-[9px] font-black uppercase tracking-widest px-3 py-1.5 bg-slate-800 text-cyan-400 rounded-lg border border-slate-700 relative z-10 shadow-inner">
                        <span class="w-1.5 h-1.5 rounded-full bg-cyan-400 animate-pulse"></span>
                        {{ number_format($totalPartners) }} Authenticated Partners
                    </div>
                </div>
            </a>

            {{-- Dark Stat 4: Live Farms --}}
            <div class="bg-slate-900/80 backdrop-blur-xl rounded-[2rem] p-8 border border-slate-800 shadow-2xl relative overflow-hidden group hover:border-rose-500/20 transition-all duration-500">
                <div class="absolute -right-10 -bottom-10 w-40 h-40 bg-rose-500/10 rounded-full blur-3xl opacity-50 group-hover:opacity-100 transition-opacity duration-700"></div>
                <h3 class="text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] mb-2 relative z-10">Operational Estates</h3>
                <div class="text-4xl font-black text-white tracking-tighter mb-3 relative z-10">{{ number_format($activeFarmsCount) }} <span class="text-lg text-slate-600 font-black">/ {{ $totalFarms }}</span></div>
                <p class="text-[9px] font-bold uppercase tracking-widest text-slate-600 relative z-10">Approved and active properties</p>
            </div>
        </div>

        <h2 class="text-xs font-black uppercase tracking-[0.2em] text-slate-500 mb-6 pb-4 border-b border-slate-800/50 flex items-center gap-3">
            <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
            Micro-Service Live Telemetry
        </h2>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-12">

            {{-- Farm Module --}}
            <div class="bg-slate-900/50 backdrop-blur-xl rounded-[2.5rem] border border-emerald-500/10 overflow-hidden shadow-2xl hover:border-emerald-500/30 transition-all duration-500">
                <div class="bg-emerald-950/20 px-8 py-6 border-b border-emerald-500/10 flex items-center">
                    <div class="bg-emerald-500/10 p-3 rounded-xl mr-4 border border-emerald-500/20 shadow-[0_0_15px_rgba(16,185,129,0.1)]">
                        <svg class="w-6 h-6 text-emerald-400 drop-shadow-[0_0_5px_rgba(16,185,129,0.8)]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                    </div>
                    <h3 class="text-sm font-black uppercase tracking-[0.2em] text-emerald-400">Core: Farm Booking</h3>
                </div>
                <div class="p-8 relative overflow-hidden">
                    <div class="absolute -right-20 -bottom-20 w-64 h-64 bg-emerald-500/5 rounded-full blur-3xl pointer-events-none"></div>
                    <div class="mb-8 relative z-10">
                        <div class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1.5">Module Gross Liquidity</div>
                        <div class="text-3xl font-black text-white tracking-tighter">{{ number_format($farmRevenue, 2) }} <span class="text-xs uppercase tracking-widest text-emerald-500/50">JOD</span></div>
                    </div>
                    <div class="space-y-4 relative z-10">
                        <div class="flex justify-between items-center bg-slate-950/50 p-4 rounded-2xl border border-slate-800 shadow-inner group">
                            <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest group-hover:text-emerald-400 transition-colors">Confirmed Pipeline</span>
                            <span class="bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-xs font-black px-3 py-1.5 rounded-lg shadow-[0_0_10px_rgba(16,185,129,0.1)]">{{ $activeFarmBookings }}</span>
                        </div>
                        <div class="flex justify-between items-center bg-slate-950/50 p-4 rounded-2xl border border-slate-800 shadow-inner group">
                            <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest group-hover:text-amber-400 transition-colors">Pending Actions</span>
                            <span class="bg-amber-500/10 border border-amber-500/20 text-amber-400 text-xs font-black px-3 py-1.5 rounded-lg">{{ $pendingFarmBookings }}</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Supply Module --}}
            <div class="bg-slate-900/50 backdrop-blur-xl rounded-[2.5rem] border border-lime-500/10 overflow-hidden shadow-2xl hover:border-lime-500/30 transition-all duration-500">
                <div class="bg-lime-950/20 px-8 py-6 border-b border-lime-500/10 flex items-center">
                    <div class="bg-lime-500/10 p-3 rounded-xl mr-4 border border-lime-500/20 shadow-[0_0_15px_rgba(132,204,22,0.1)]">
                        <svg class="w-6 h-6 text-lime-400 drop-shadow-[0_0_5px_rgba(132,204,22,0.8)]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                    </div>
                    <h3 class="text-sm font-black uppercase tracking-[0.2em] text-lime-400">Micro: E-Commerce</h3>
                </div>
                <div class="p-8 relative overflow-hidden">
                    <div class="absolute -right-20 -bottom-20 w-64 h-64 bg-lime-500/5 rounded-full blur-3xl pointer-events-none"></div>
                    <div class="mb-8 relative z-10">
                        <div class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1.5">Marketplace Volume</div>
                        <div class="text-3xl font-black text-white tracking-tighter">{{ number_format($supplyRevenue, 2) }} <span class="text-xs uppercase tracking-widest text-lime-500/50">JOD</span></div>
                    </div>
                    <div class="space-y-4 relative z-10">
                        <div class="flex justify-between items-center bg-slate-950/50 p-4 rounded-2xl border border-slate-800 shadow-inner group">
                            <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest group-hover:text-lime-400 transition-colors">In Transit</span>
                            <span class="bg-lime-500/10 border border-lime-500/20 text-lime-400 text-xs font-black px-3 py-1.5 rounded-lg shadow-[0_0_10px_rgba(132,204,22,0.2)] animate-pulse">{{ $inTransitSupplies }}</span>
                        </div>
                        <div class="flex justify-between items-center bg-slate-950/50 p-4 rounded-2xl border border-slate-800 shadow-inner group">
                            <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest group-hover:text-slate-300 transition-colors">Successfully Fulfilled</span>
                            <span class="bg-slate-800 border border-slate-700 text-slate-300 text-xs font-black px-3 py-1.5 rounded-lg">{{ $completedSupplies }}</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Transport Module --}}
            <div class="bg-slate-900/50 backdrop-blur-xl rounded-[2.5rem] border border-cyan-500/10 overflow-hidden shadow-2xl hover:border-cyan-500/30 transition-all duration-500">
                <div class="bg-cyan-950/20 px-8 py-6 border-b border-cyan-500/10 flex items-center">
                    <div class="bg-cyan-500/10 p-3 rounded-xl mr-4 border border-cyan-500/20 shadow-[0_0_15px_rgba(6,182,212,0.1)]">
                        <svg class="w-6 h-6 text-cyan-400 drop-shadow-[0_0_5px_rgba(6,182,212,0.8)]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
                    </div>
                    <h3 class="text-sm font-black uppercase tracking-[0.2em] text-cyan-400">Micro: Logistics</h3>
                </div>
                <div class="p-8 relative overflow-hidden">
                    <div class="absolute -right-20 -bottom-20 w-64 h-64 bg-cyan-500/5 rounded-full blur-3xl pointer-events-none"></div>
                    <div class="mb-8 relative z-10">
                        <div class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1.5">Dispatch Liquidity</div>
                        <div class="text-3xl font-black text-white tracking-tighter">{{ number_format($transportRevenue, 2) }} <span class="text-xs uppercase tracking-widest text-cyan-500/50">JOD</span></div>
                    </div>
                    <div class="space-y-4 relative z-10">
                        <div class="flex justify-between items-center bg-slate-950/50 p-4 rounded-2xl border border-slate-800 shadow-inner group">
                            <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest group-hover:text-cyan-400 transition-colors">Active Shuttles</span>
                            <span class="bg-cyan-500/10 border border-cyan-500/20 text-cyan-400 text-xs font-black px-3 py-1.5 rounded-lg shadow-[0_0_10px_rgba(6,182,212,0.2)] animate-pulse">{{ $inProgressTransports }}</span>
                        </div>
                        <div class="flex justify-between items-center bg-slate-950/50 p-4 rounded-2xl border border-slate-800 shadow-inner group">
                            <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest group-hover:text-amber-400 transition-colors">Awaiting Driver</span>
                            <span class="bg-amber-500/10 border border-amber-500/20 text-amber-400 text-xs font-black px-3 py-1.5 rounded-lg">{{ $pendingTransports }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-slate-900/80 backdrop-blur-xl rounded-[2.5rem] p-8 border border-slate-800 shadow-2xl mb-10 relative overflow-hidden group hover:border-emerald-500/20 transition-colors duration-500">
            <div class="absolute -left-32 -bottom-32 w-96 h-96 bg-emerald-500/5 rounded-full blur-3xl pointer-events-none"></div>

            <h3 class="text-xs font-black text-emerald-400 uppercase tracking-[0.2em] mb-8 flex items-center gap-3 border-b border-slate-800/50 pb-4 relative z-10">
                <svg class="w-5 h-5 drop-shadow-[0_0_8px_rgba(16,185,129,0.8)]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                Geo-Spatial Coverage (Hashemite Kingdom of Jordan)
            </h3>

            <div class="relative z-10 rounded-2xl overflow-hidden border border-slate-700/80 shadow-[0_0_30px_rgba(0,0,0,0.5)]">
                <div id="farmMap" class="w-full h-[500px] z-10 mix-blend-luminosity"></div>
                <div class="absolute inset-0 border-4 border-slate-900/40 rounded-2xl pointer-events-none"></div>
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
                    scale: 6
                }
            });

            const infoWindow = new google.maps.InfoWindow({
                content: `
                    <div style="background:#0f172a; color:#f8fafc; padding:15px; border-radius:12px; font-family:sans-serif; border: 1px solid #1e293b;">
                        <h4 style="margin:0 0 8px 0; font-size:16px; font-weight:900;">${farm.name}</h4>
                        <div style="display:inline-block; padding:4px 8px; background:rgba(16,185,129,0.1); border:1px solid rgba(16,185,129,0.2); border-radius:4px;">
                            <p style="margin:0; color:#10b981; font-size:10px; font-weight:900; text-transform:uppercase; letter-spacing:1px;">Active Node</p>
                        </div>
                    </div>
                `
            });

            marker.addListener("click", () => {
                infoWindow.open(map, marker);
            });

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
                            label: {
                                text: String(count),
                                color: '#ffffff',
                                fontSize: '12px',
                                fontWeight: 'bold'
                            },
                            icon: {
                                path: google.maps.SymbolPath.CIRCLE,
                                fillColor: '#6366f1',
                                fillOpacity: 0.8,
                                strokeColor: '#818cf8',
                                strokeWeight: 2,
                                scale: 18
                            }
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
