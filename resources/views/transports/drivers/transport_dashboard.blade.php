@extends('layouts.driver')

@section('content')
<div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Transport Dispatch Dashboard</h1>
        <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">View and manage your assigned round-trips.</p>
    </div>

    @if($trips->isEmpty())
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 text-center">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">No active trips</h3>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">You currently have no assigned trips in your region.</p>
        </div>
    @else
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Trip List Sidebar -->
            <div class="lg:col-span-1 space-y-4">
                @foreach($trips as $trip)
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-5 border-l-4 {{ $trip->status == 'in_progress' ? 'border-blue-500' : 'border-gray-300' }}">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Trip #{{ $trip->id }}</h3>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $trip->status == 'completed' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                    {{ ucfirst(str_replace('_', ' ', $trip->status)) }}
                                </span>
                            </div>
                        </div>

                        <div class="space-y-2 text-sm text-gray-600 dark:text-gray-300">
                            <p><strong class="text-gray-900 dark:text-white">Customer:</strong> {{ $trip->user->name ?? 'N/A' }}</p>
                            <p><strong class="text-gray-900 dark:text-white">Phone:</strong> {{ $trip->user->phone ?? 'N/A' }}</p>
                            <div class="mt-4 bg-gray-50 dark:bg-gray-700 p-3 rounded-md">
                                <p class="font-medium text-gray-900 dark:text-white mb-1">Schedule (Round-Trip):</p>
                                <p><strong>Pickup (Origin):</strong> {{ $trip->origin_governorate }}<br> <span class="text-xs text-gray-500 dark:text-gray-400">{{ $trip->scheduled_at ? $trip->scheduled_at->format('M d, Y h:i A') : 'N/A' }}</span></p>
                                <p class="mt-2"><strong>Destination:</strong> {{ $trip->destination_governorate }} (Farm)</p>
                                <p class="mt-2"><strong>Return:</strong> From Farm back to {{ $trip->origin_governorate }}<br> <span class="text-xs text-gray-500 dark:text-gray-400">{{ $trip->return_scheduled_at ? $trip->return_scheduled_at->format('M d, Y h:i A') : 'N/A' }}</span></p>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="mt-5 flex flex-col space-y-2">
                            <form action="{{ route('transport.driver.update_status', $trip->id) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <select name="status" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white mb-2">
                                    <option value="pending" {{ $trip->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="accepted" {{ $trip->status == 'accepted' ? 'selected' : '' }}>Accepted</option>
                                    <option value="assigned" {{ $trip->status == 'assigned' ? 'selected' : '' }}>Assigned</option>
                                    <option value="in_progress" {{ $trip->status == 'in_progress' ? 'selected' : '' }}>In Progress (Driving)</option>
                                    <option value="completed" {{ $trip->status == 'completed' ? 'selected' : '' }}>Completed</option>
                                </select>
                                <button class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 mb-2">
                                    Update Status
                                </button>
                            </form>
                            <button onclick="showRoute('{{ $trip->origin_governorate }}', '{{ $trip->destination_governorate }}')" class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                View Route on Map
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Map View -->
            <div class="lg:col-span-2">
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden h-[600px] relative">
                    <div id="transportMap" class="w-full h-full z-0"></div>
                </div>
            </div>
        </div>
    @endif
</div>

<!-- Leaflet CSS & JS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize Map centered on Jordan
        const map = L.map('transportMap').setView([31.2400, 36.5140], 7);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        let currentRoutingControl = null;

        // Approximate coordinates for Jordanian Governorates for demo purposes
        const governorateCoords = {
            'Amman': [31.9454, 35.9284],
            'Irbid': [32.5514, 35.8515],
            'Zarqa': [32.0645, 36.0858],
            'Mafraq': [32.3423, 36.2045],
            'Balqa': [32.0400, 35.7300],
            'Karak': [31.1828, 35.7031],
            'Jerash': [32.2763, 35.8943],
            'Madaba': [31.7196, 35.7946],
            'Ma\'an': [30.1945, 35.7342],
            'Ajloun': [32.3326, 35.7517],
            'Aqaba': [29.5319, 35.0061],
            'Tafilah': [30.8359, 35.6133]
        };

        window.showRoute = function(origin, destination) {
            // Remove existing markers/routes
            map.eachLayer(function (layer) {
                if (layer instanceof L.Marker || layer instanceof L.Polyline) {
                    map.removeLayer(layer);
                }
            });

            const startCoords = governorateCoords[origin];
            const endCoords = governorateCoords[destination];

            if (startCoords && endCoords) {
                // Add Markers
                const startMarker = L.marker(startCoords).addTo(map)
                    .bindPopup(`<b>Origin:</b> ${origin}`).openPopup();
                const endMarker = L.marker(endCoords).addTo(map)
                    .bindPopup(`<b>Destination (Farm):</b> ${destination}`);

                // Draw a simple polyline to represent the route
                const latlngs = [startCoords, endCoords];
                const polyline = L.polyline(latlngs, {color: 'blue', weight: 4, opacity: 0.7}).addTo(map);

                // Zoom the map to the polyline
                map.fitBounds(polyline.getBounds(), {padding: [50, 50]});
            } else {
                alert('Coordinates not found for these locations.');
            }
        };
    });
</script>
@endsection
