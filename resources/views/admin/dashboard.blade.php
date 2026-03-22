<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                {{ __('Super Admin Dashboard') }}
            </h2>

            {{-- Time Filter --}}
            <form method="GET" action="{{ route('admin.dashboard') }}" class="flex items-center space-x-2">
                <select name="timeframe" onchange="this.form.submit()" class="border-gray-300 rounded-lg shadow-sm focus:ring-green-500 focus:border-green-500 text-sm font-bold">
                    <option value="week" {{ $timeframe === 'week' ? 'selected' : '' }}>Past Week</option>
                    <option value="month" {{ $timeframe === 'month' ? 'selected' : '' }}>Past Month</option>
                    <option value="year" {{ $timeframe === 'year' ? 'selected' : '' }}>Past Year</option>
                    <option value="all" {{ $timeframe === 'all' ? 'selected' : '' }}>All Time</option>
                </select>
            </form>
        </div>
    </x-slot>

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            {{-- Notifications --}}
            @if(session('success'))
                <div class="bg-green-50 border-l-4 border-green-500 text-green-700 px-4 py-3 rounded-r-xl shadow-sm mb-6 font-bold" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            {{-- 1. Financial Hub --}}
            <div>
                <h3 class="text-xl font-black text-gray-800 mb-4 tracking-tight">Financial Hub</h3>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6">

                    <div class="bg-white overflow-hidden shadow-sm rounded-2xl border border-gray-100 hover:shadow-md transition">
                        <div class="p-6">
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">Total Platform Gross</p>
                            <p class="text-3xl font-black text-gray-900">{{ number_format($financials['total_income'], 0) }} JOD</p>
                        </div>
                    </div>

                    <div class="bg-white overflow-hidden shadow-sm rounded-2xl border border-gray-100 hover:shadow-md transition">
                        <div class="p-6">
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">Total Commissions (Net)</p>
                            <p class="text-3xl font-black text-[#1d5c42]">{{ number_format($financials['total_commissions'], 0) }} JOD</p>
                        </div>
                    </div>

                    <div class="bg-white overflow-hidden shadow-sm rounded-2xl border border-gray-100 hover:shadow-md transition">
                        <div class="p-6">
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">Taxes Collected</p>
                            <p class="text-3xl font-black text-red-600">{{ number_format($financials['taxes'], 0) }} JOD</p>
                        </div>
                    </div>

                    <div class="bg-white overflow-hidden shadow-sm rounded-2xl border border-gray-100 hover:shadow-md transition">
                        <div class="p-6">
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">Total Users</p>
                            <p class="text-3xl font-black text-blue-600">{{ number_format($totalUsers) }}</p>
                        </div>
                    </div>

                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

                {{-- 2. Pending Verifications --}}
                <div class="bg-white overflow-hidden shadow-sm rounded-2xl p-6 border border-gray-100">
                    <h3 class="text-xl font-black text-gray-800 mb-4 flex items-center justify-between tracking-tight">
                        Pending Farm Verifications
                        @if($pendingFarms->count() > 0)
                            <span class="bg-red-50 text-red-600 border border-red-200 text-xs px-3 py-1 font-bold rounded-full animate-pulse">{{ $pendingFarms->count() }} New</span>
                        @endif
                    </h3>

                    @if($pendingFarms->count() > 0)
                        <div class="space-y-4">
                            @foreach($pendingFarms as $farm)
                                <div class="border border-gray-100 rounded-xl p-4 flex justify-between items-center bg-gray-50/50 hover:bg-white transition shadow-sm">
                                    <div>
                                        <p class="font-black text-gray-900 text-lg">{{ $farm->name }}</p>
                                        <p class="text-xs font-bold text-gray-500 mt-1">Owner: <span class="text-[#1d5c42]">{{ $farm->owner->name ?? 'Unknown' }}</span> | {{ $farm->location }}</p>
                                    </div>
                                    <form method="POST" action="{{ route('admin.verifications.handle', $farm->id) }}" class="flex space-x-2">
                                        @csrf
                                        <button type="submit" name="action" value="approve" class="px-4 py-2 bg-[#1d5c42] hover:bg-[#154230] text-white text-[10px] uppercase tracking-widest font-black rounded-lg transition-colors shadow-sm">Approve</button>
                                        <button type="submit" name="action" value="reject" class="px-4 py-2 bg-red-50 hover:bg-red-100 border border-red-200 text-red-600 text-[10px] uppercase tracking-widest font-black rounded-lg transition-colors">Reject</button>
                                    </form>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-12 text-gray-400">
                            <svg class="w-16 h-16 mx-auto mb-4 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <p class="font-bold">No pending verifications. All caught up!</p>
                        </div>
                    @endif
                </div>

                {{-- 3. Central Maps --}}
                <div class="bg-white overflow-hidden shadow-sm rounded-2xl p-6 border border-gray-100">
                    <h3 class="text-xl font-black text-gray-800 mb-4 tracking-tight">Central Farm Map</h3>
                    <div id="admin-map" class="w-full h-[400px] rounded-xl border border-gray-200 z-0 overflow-hidden shadow-inner"></div>
                </div>

            </div>

        </div>
    </div>

    {{-- Leaflet.js Scripts for Map --}}
    @push('scripts')
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
        <script>
            document.addEventListener("DOMContentLoaded", function () {
                var map = L.map('admin-map').setView([31.9522, 35.9334], 7); // Default to Jordan approx.

                L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
                    attribution: '&copy; OpenStreetMap contributors'
                }).addTo(map);

                var farms = @json($verifiedFarms);
                var bounds = [];

                farms.forEach(function(farm) {
                    if(farm.latitude && farm.longitude) {
                        var marker = L.marker([farm.latitude, farm.longitude]).addTo(map)
                            .bindPopup('<b class="text-[#1d5c42]">' + farm.name + '</b><br><span class="text-xs text-gray-500">' + farm.location + '</span>');
                        bounds.push([farm.latitude, farm.longitude]);
                    }
                });

                if (bounds.length > 0) {
                    map.fitBounds(bounds);
                }
            });
        </script>
    @endpush
</x-app-layout>
