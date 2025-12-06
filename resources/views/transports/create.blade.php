@extends('layouts.app')

@section('title', 'Add Transport Request')

@section('content')
<div class="container mx-auto py-10">
    <h1 class="text-4xl font-bold mb-8 text-center text-green-700">Add Transport Request</h1>

    @if($errors->any())
        <div class="bg-red-100 text-red-700 p-4 rounded-lg mb-6 shadow">
            <ul class="list-disc pl-5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('transports.store') }}" method="POST"
          class="bg-white p-8 rounded-lg shadow-lg space-y-6"
          x-data='transportForm(@json([
              "transport_type" => old("transport_type"),
              "passengers" => old("passengers", 1),
              "driver_id" => old("driver_id"),
              "start_and_return_point" => old("start_and_return_point", ""),
              "farm_id" => old("farm_id"),
              "distance" => old("distance", 0),
              "departure_time" => old("departure_time"),
              "arrival_time" => old("arrival_time"),
              "status" => old("status", "Pending"),
              "notes" => old("notes")
          ]))'>
        @csrf

        {{-- Transport Type & Passengers --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block mb-2 font-semibold">Transport Type</label>
                <select name="transport_type" class="w-full border p-3 rounded-lg" x-model="transport_type">
                    <option value="" disabled>Select Transport</option>
                    <option value="Car">Car (Max 4 passengers)</option>
                    <option value="Bus">Bus (Max 20 passengers)</option>
                    <option value="Large Bus">Large Bus (Max 50 passengers)</option>
                </select>
            </div>

            <div>
                <label class="block mb-2 font-semibold">Passengers</label>
                <input type="number" name="passengers" class="w-full border p-3 rounded-lg"
                       x-model.number="passengers" :max="maxPassengers" min="1">
                <p class="text-gray-500 mt-1 text-sm" x-text="'Maximum passengers: ' + maxPassengers"></p>
            </div>
        </div>

        {{-- Driver --}}
        <div>
            <label class="block mb-2 font-semibold">Driver</label>
            <select name="driver_id" class="w-full border p-3 rounded-lg" x-model="driver_id">
                <option value="" disabled>Select Driver</option>
                @foreach($Drivers as $driver)
                    <option value="{{ $driver->id }}">{{ $driver->name }}</option>
                @endforeach
            </select>
        </div>

        {{-- Start & Return Point + Farm + Distance --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div>
                <label class="block mb-2 font-semibold">Start & Return Point</label>
                <input type="text" name="start_and_return_point" class="w-full border p-3 rounded-lg"
                       x-model="start_and_return_point" placeholder="Starting location">
            </div>

            <div>
                <label class="block mb-2 font-semibold">Select Farm</label>
                <select name="farm_id" class="w-full border p-3 rounded-lg" x-model="farm_id">
                    <option value="">Select Farm</option>
                    @foreach($farms as $farm)
                        <option value="{{ $farm->id }}" data-address="{{ $farm->address ?? '' }}">
                            {{ $farm->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block mb-2 font-semibold">Distance (km)</label>
                <div class="flex gap-2">
                    <input type="number" step="0.1" name="distance" class="w-full border p-3 rounded-lg"
                           x-model.number="distance" min="0" :readonly="autoDistanceEnabled">
                    <button type="button" class="px-3 py-2 bg-indigo-600 text-white rounded"
                            x-on:click="calculateDistanceFromGoogle()">حساب المسافة</button>
                </div>
                <div class="mt-2 flex items-center gap-2">
                    <input type="checkbox" id="toggleManual" x-model="autoDistanceEnabled" :true-value="true" :false-value="false">
                    <label for="toggleManual" class="text-sm text-gray-600" x-text="autoDistanceEnabled ? 'Auto (locked)' : 'Manual (editable)'"></label>
                </div>
            </div>
        </div>

        {{-- Price --}}
        <div>
            <label class="block mb-2 font-semibold">Price ($)</label>
            <input type="number" name="price" class="w-full border p-3 rounded-lg bg-gray-100"
                   :value="calculatedPrice" readonly>
            <p class="text-gray-500 text-sm mt-1">Distance × Passengers × Rate ($2/km)</p>
        </div>

        {{-- Arrival & Departure --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block mb-2 font-semibold">Farm Arrival Time</label>
                <input type="datetime-local" name="departure_time" class="w-full border p-3 rounded-lg" x-model="departure_time">
            </div>
            <div>
                <label class="block mb-2 font-semibold">Farm Departure Time</label>
                <input type="datetime-local" name="arrival_time" class="w-full border p-3 rounded-lg" x-model="arrival_time">
            </div>
        </div>

        {{-- Status --}}
        <div>
            <label class="block mb-2 font-semibold">Status</label>
            <select name="status" class="w-full border p-3 rounded-lg" x-model="status">
                <option value="Pending">Pending</option>
                <option value="In Transit">In Transit</option>
                <option value="Delivered">Delivered</option>
            </select>
        </div>

        {{-- Notes --}}
        <div>
            <label class="block mb-2 font-semibold">Notes</label>
            <textarea name="notes" class="w-full border p-3 rounded-lg" x-model="notes" placeholder="Extra notes">{{ old('notes') }}</textarea>
        </div>

        <div class="text-center">
            <button type="submit" class="px-8 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 font-semibold transition">
                Create Transport
            </button>
        </div>
    </form>
</div>

{{-- Google Maps JS --}}
@if(!empty($googleMapsKey))
<script async defer src="https://maps.googleapis.com/maps/api/js?key={{ $googleMapsKey }}&libraries=places"></script>
@endif

<script>
function transportForm(oldValues = {}) {
    return {
        transport_type: oldValues.transport_type ?? '',
        passengers: Number(oldValues.passengers ?? 1),
        driver_id: oldValues.driver_id ?? '',
        start_and_return_point: oldValues.start_and_return_point ?? '',
        farm_id: oldValues.farm_id ?? '',
        distance: Number(oldValues.distance ?? 0),
        departure_time: oldValues.departure_time ?? '',
        arrival_time: oldValues.arrival_time ?? '',
        status: oldValues.status ?? 'Pending',
        notes: oldValues.notes ?? '',
        rate: 2,
        autoDistanceEnabled: false,

        get calculatedPrice() {
            const d = Number(this.distance) || 0;
            const p = Number(this.passengers) || 0;
            return (d * p * this.rate).toFixed(2);
        },

        get maxPassengers() {
            switch(this.transport_type) {
                case 'Car': return 4;
                case 'Bus': return 20;
                case 'Large Bus': return 50;
                default: return 100;
            }
        },

        getSelectedFarmAddress() {
            try {
                const sel = document.querySelector('select[name="farm_id"]');
                if (!sel) return '';
                const opt = sel.options[sel.selectedIndex];
                return opt ? (opt.dataset.address || '') : '';
            } catch (e) {
                return '';
            }
        },

        calculateDistanceFromGoogle() {
            if (typeof google === 'undefined' || !google.maps || !google.maps.DistanceMatrixService) {
                alert('Google Maps JS API not loaded.');
                return;
            }

            const origin = this.start_and_return_point;
            const destination = this.getSelectedFarmAddress();

            if (!origin || !destination) {
                alert('Please provide start location and select a farm.');
                return;
            }

            const service = new google.maps.DistanceMatrixService();
            service.getDistanceMatrix({
                origins: [origin],
                destinations: [destination],
                travelMode: google.maps.TravelMode.DRIVING,
                unitSystem: google.maps.UnitSystem.METRIC
            }, (response, status) => {
                if (status !== 'OK') { alert('Distance API error'); return; }
                const element = response.rows[0].elements[0];
                if (element.status === 'OK') {
                    this.distance = (element.distance.value / 1000).toFixed(2);
                    this.autoDistanceEnabled = true;
                } else {
                    alert('Unable to calculate distance: ' + element.status);
                }
            });
        }
    }
}
</script>
@endsection
