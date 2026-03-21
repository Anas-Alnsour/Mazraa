<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Super Admin Dashboard - Mazraa</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        #farmMap { height: 400px; border-radius: 0.5rem; z-index: 10; }
    </style>
</head>
<body class="bg-gray-50 text-gray-800 font-sans antialiased">

<div class="flex h-screen overflow-hidden">

    <aside class="w-64 bg-slate-900 text-white flex-col hidden md:flex">
        <div class="p-6 border-b border-slate-700">
            <h2 class="text-2xl font-bold text-emerald-400"><i class="fa-solid fa-leaf mr-2"></i>Mazraa Admin</h2>
        </div>
        <nav class="flex-1 p-4 space-y-2">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center p-3 bg-emerald-600 rounded-lg text-white font-medium transition-colors">
                <i class="fa-solid fa-chart-pie w-6"></i> <span class="ml-3">Dashboard</span>
            </a>
            <a href="{{ route('admin.verifications') }}" class="flex items-center p-3 hover:bg-slate-800 rounded-lg text-slate-300 font-medium transition-colors">
                <i class="fa-solid fa-clipboard-check w-6"></i> <span class="ml-3">Verifications</span>
            </a>
            <a href="{{ route('admin.system') }}" class="flex items-center p-3 hover:bg-slate-800 rounded-lg text-slate-300 font-medium transition-colors">
                <i class="fa-solid fa-cogs w-6"></i> <span class="ml-3">System Settings</span>
            </a>
        </nav>
        <div class="p-4 border-t border-slate-700">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full flex items-center p-3 hover:bg-red-600 rounded-lg text-slate-300 hover:text-white font-medium transition-colors">
                    <i class="fa-solid fa-sign-out-alt w-6"></i> <span class="ml-3">Logout</span>
                </button>
            </form>
        </div>
    </aside>

    <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50">

        <header class="bg-white shadow-sm px-6 py-4 flex justify-between items-center">
            <h1 class="text-2xl font-bold text-gray-800">Financial Hub & Overview</h1>

            <form method="GET" action="{{ route('admin.dashboard') }}" class="flex items-center space-x-2">
                <label for="timeframe" class="text-sm text-gray-600 font-medium">Timeframe:</label>
                <select name="timeframe" id="timeframe" onchange="this.form.submit()" class="border-gray-300 rounded-md text-sm focus:ring-emerald-500 focus:border-emerald-500 py-1.5 pl-3 pr-8">
                    <option value="week" {{ $timeframe === 'week' ? 'selected' : '' }}>Past Week</option>
                    <option value="month" {{ $timeframe === 'month' ? 'selected' : '' }}>Past Month</option>
                    <option value="year" {{ $timeframe === 'year' ? 'selected' : '' }}>Past Year</option>
                </select>
            </form>
        </header>

        <div class="p-6 space-y-6">

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex items-center space-x-4">
                    <div class="p-3 bg-blue-100 text-blue-600 rounded-lg">
                        <i class="fa-solid fa-wallet text-2xl"></i>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Total Gross Processing</p>
                        <h3 class="text-2xl font-bold text-gray-800">JOD {{ number_format($financials['total_income'], 2) }}</h3>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex items-center space-x-4">
                    <div class="p-3 bg-emerald-100 text-emerald-600 rounded-lg">
                        <i class="fa-solid fa-coins text-2xl"></i>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Total Commissions</p>
                        <h3 class="text-2xl font-bold text-gray-800">JOD {{ number_format($financials['total_commissions'], 2) }}</h3>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex items-center space-x-4">
                    <div class="p-3 bg-amber-100 text-amber-600 rounded-lg">
                        <i class="fa-solid fa-house-chimney text-2xl"></i>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Farm Commissions</p>
                        <h3 class="text-2xl font-bold text-gray-800">JOD {{ number_format($financials['farm_commissions'], 2) }}</h3>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex items-center space-x-4">
                    <div class="p-3 bg-red-100 text-red-600 rounded-lg">
                        <i class="fa-solid fa-file-invoice-dollar text-2xl"></i>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Estimated Taxes (16%)</p>
                        <h3 class="text-2xl font-bold text-gray-800">JOD {{ number_format($financials['taxes'], 2) }}</h3>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <div class="p-3 bg-indigo-100 text-indigo-600 rounded-lg">
                            <i class="fa-solid fa-truck text-xl"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Transport Commissions</p>
                            <p class="text-lg font-bold text-gray-800">JOD {{ number_format($financials['transport_commissions'], 2) }}</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <div class="p-3 bg-purple-100 text-purple-600 rounded-lg">
                            <i class="fa-solid fa-box-open text-xl"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Supply Commissions</p>
                            <p class="text-lg font-bold text-gray-800">JOD {{ number_format($financials['supply_commissions'], 2) }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4"><i class="fa-solid fa-map-location-dot mr-2 text-emerald-500"></i>Verified Farms Coverage (Jordan)</h3>
                <div id="farmMap" class="w-full border border-gray-200 shadow-inner"></div>
            </div>

        </div>
    </main>
</div>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize the map centered on Amman, Jordan
        var map = L.map('farmMap').setView([31.9522, 35.9334], 7);

        // Add OpenStreetMap tiles
        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
        }).addTo(map);

        // Parse verified farms from PHP
        var farms = @json($verifiedFarms);

        // Add markers for each farm
        farms.forEach(function(farm) {
            // Check if lat/lng are present and valid
            if(farm.latitude && farm.longitude) {
                var marker = L.marker([parseFloat(farm.latitude), parseFloat(farm.longitude)]).addTo(map);
                marker.bindPopup("<b>" + farm.name + "</b><br>ID: " + farm.id);
            }
        });
    });
</script>

</body>
</html>
