@extends('layouts.app')

@section('title', 'Edit Transport Request')

@section('content')
<div class="container mx-auto py-10">
    <h1 class="text-4xl font-bold mb-8 text-center text-blue-700">Edit Transport Request</h1>

    {{-- رسائل الخطأ --}}
    @if($errors->any())
        <div class="bg-red-100 text-red-700 p-4 rounded-lg mb-6 shadow">
            <ul class="list-disc pl-5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- رسالة نجاح --}}
    @if(session('success'))
        <div class="bg-green-100 text-green-700 p-4 rounded-lg mb-6 shadow">
            {{ session('success') }}
        </div>
    @endif

    {{-- النموذج --}}
    <form action="{{ route('transports.update', $transport->id) }}" method="POST"
          class="bg-white p-8 rounded-lg shadow-lg space-y-6"
          x-data="transportForm(@json([
              'transport_type' => old('transport_type', $transport->transport_type ?? ''),
              'passengers' => old('passengers', $transport->passengers ?? 1),
              'driver_id' => old('driver_id', $transport->driver_id ?? ''),
              'start_and_return_point' => old('start_and_return_point', $transport->start_and_return_point ?? ''),
              'farm_id' => old('farm_id', $transport->farm_id ?? ''),
              'distance' => old('distance', $transport->distance ?? 0),
              'departure_time' => old('departure_time', optional($transport->departure_time)->format('Y-m-d\TH:i') ?? ''),
              'arrival_time' => old('arrival_time', optional($transport->arrival_time)->format('Y-m-d\TH:i') ?? ''),
              'status' => old('status', $transport->status ?? 'Pending'),
              'notes' => old('notes', $transport->notes ?? '')
          ]))"
          style="margin-left:1.5rem; margin-right:1.5rem;">
        @csrf
        @method('PUT')

        {{-- Transport Type & Passengers --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block mb-2 font-semibold">Transport Type</label>
                <select name="transport_type" class="w-full border p-3 rounded-lg" x-model="transport_type" @change="setRandomDriver()">
                    <option value="" disabled>Select Transport</option>
                    <option value="Car">Car (Max 4 passengers)</option>
                    <option value="Bus">Bus (Max 20 passengers)</option>
                    <option value="Large Bus">Large Bus (Max 50 passengers)</option>
                </select>
            </div>

            <div>
                <label class="block mb-2 font-semibold">Passengers</label>
                <input type="number" name="passengers" class="w-full border p-3 rounded-lg"
                       x-model.number="passengers" :max="maxPassengers" min="1" required>
                <p class="text-gray-500 mt-1 text-sm" x-text="'Maximum passengers: ' + maxPassengers"></p>
            </div>
        </div>

        {{-- Driver --}}
        <div>
            <label class="block mb-2 font-semibold">Driver</label>
            <select name="driver_id" class="w-full border p-3 rounded-lg" x-model="driver_id">
                <option value="" disabled>Select Driver</option>
                @foreach($drivers as $driver)
                    <option value="{{ $driver->id }}">{{ $driver->name }}</option>
                @endforeach
            </select>
        </div>

        {{-- Start & Return Point + Farm + Distance --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div>
                <label class="block mb-2 font-semibold">Start & Return Point</label>
                <input type="text" name="start_and_return_point" class="w-full border p-3 rounded-lg"
                       x-model="start_and_return_point" placeholder="Starting / Return location" required>
            </div>

            <div>
                <label class="block mb-2 font-semibold">Select Farm</label>
                <select name="farm_id" class="w-full border p-3 rounded-lg" x-model="farm_id">
                    <option value="">Select Farm</option>
                    @foreach($farms as $farm)
                        {{-- نفترض أن كل $farm يحتوي على id, name, address --}}
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
                            x-on:click="calculateDistanceFromGoogle()" title="Calculate distance automatically">
                        حساب المسافة
                    </button>
                </div>

                <div class="mt-2 flex items-center gap-2">
                    <input type="checkbox" id="toggleManual" x-model="autoDistanceEnabled" :true-value="true" :false-value="false">
                    <label for="toggleManual" class="text-sm text-gray-600" x-text="autoDistanceEnabled ? 'Auto (locked)' : 'Manual (editable)'"></label>
                    {{-- ملاحظة: عند autoDistanceEnabled=true الحقل يصبح readonly --}}
                </div>

                <p class="text-gray-500 mt-1 text-sm">يمكنك الحساب التلقائي عبر Google Maps أو تعديل المسافة يدويًا.</p>
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
                <input type="datetime-local" name="departure_time" id="departure_time"
                       class="w-full border p-3 rounded-lg" x-model="departure_time" required>
            </div>
            <div>
                <label class="block mb-2 font-semibold">Farm Departure Time</label>
                <input type="datetime-local" name="arrival_time" id="arrival_time"
                       class="w-full border p-3 rounded-lg" x-model="arrival_time" required>
            </div>
        </div>

        {{-- تحقق بسيط قبل الإرسال --}}
        <div x-show="timeError" class="bg-yellow-100 text-yellow-800 p-3 rounded">
            <p x-text="timeError"></p>
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
            <textarea name="notes" class="w-full border p-3 rounded-lg" x-model="notes" placeholder="Extra notes">{{ old('notes', $transport->notes ?? '') }}</textarea>
        </div>

        <div class="text-center">
            <button type="submit" class="px-8 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-semibold transition"
                    x-on:click.prevent="submitIfValid($event)">
                Update Transport
            </button>
        </div>
    </form>

    {{-- Delete Button --}}
    <form action="{{ route('transports.destroy', $transport->id) }}" method="POST" class="mt-4 text-center"
          onsubmit="return confirm('Are you sure you want to delete this transport request?');">
        @csrf
        @method('DELETE')
        <button type="submit" class="px-6 py-2 bg-red-600 text-white rounded hover:bg-red-700">Delete Transport</button>
    </form>

    <a href="{{ route('transports.index') }}" class="inline-block mt-6 text-blue-600 hover:underline">← Back to Transport Requests</a>
</div>

{{-- Google Maps JS (Distance Matrix) --}}
{{-- ضع مفتاحك في متغير $googleMapsKey وتمريره من الكونترولر إلى ال view --}}
@if(!empty($googleMapsKey))
    <script async defer src="https://maps.googleapis.com/maps/api/js?key={{ $googleMapsKey }}&libraries=places"></script>
@else
    {{-- بدون مفتاح: زر الحساب سيعرض خطأ مساعد للمطور --}}
@endif

{{-- Alpine.js --}}
<script>
function transportForm(oldValues = {}) {
    return {
        // القيم المحمية عبر @json في أعلى الصفحة
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
        autoDistanceEnabled: false, // false = manual editable; true = auto (readonly)
        timeError: '',

        // randomDrivers: إذا أردت ملء سائق عشوائي عند تغيير نوع المركبة
        randomDrivers: @json($randomDrivers ?? []),

        get calculatedPrice() {
            const d = Number(this.distance) || 0;
            const p = Number(this.passengers) || 0;
            return (d * p * this.rate).toFixed(2);
        },

        get maxPassengers() {
            switch (this.transport_type) {
                case 'Car': return 4;
                case 'Bus': return 20;
                case 'Large Bus': return 50;
                default: return 100;
            }
        },

        setRandomDriver() {
            if (this.transport_type && this.randomDrivers[this.transport_type]) {
                this.driver_id = this.randomDrivers[this.transport_type]?.id ?? '';
            }
        },

        // الحصول على عنوان المزرعة من DOM (data-address في <option>)
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

        // استخدام Google Distance Matrix API لحساب المسافة بين نقطتين
        calculateDistanceFromGoogle() {
            this.timeError = '';
            // تأكد من وجود API
            if (typeof google === 'undefined' || !google.maps || !google.maps.DistanceMatrixService) {
                alert('Google Maps JavaScript API not loaded. Please set $googleMapsKey and enable the API.');
                return;
            }

            const origin = this.start_and_return_point;
            const destination = this.getSelectedFarmAddress();

            if (!origin || !destination) {
                alert('Please provide both a starting location and select a farm with an address to calculate distance.');
                return;
            }

            const service = new google.maps.DistanceMatrixService();
            const request = {
                origins: [origin],
                destinations: [destination],
                travelMode: google.maps.TravelMode.DRIVING,
                unitSystem: google.maps.UnitSystem.METRIC,
                avoidHighways: false,
                avoidTolls: false
            };

            service.getDistanceMatrix(request, (response, status) => {
                if (status !== 'OK') {
                    console.error('DistanceMatrix error:', status, response);
                    alert('Could not calculate distance (Distance Matrix API error). See console for details.');
                    return;
                }

                try {
                    const element = response.rows[0].elements[0];
                    if (element.status === 'OK') {
                        // المسافة بالمتر → نحول إلى كم
                        const meters = element.distance.value;
                        const km = (meters / 1000).toFixed(2);
                        this.distance = parseFloat(km);
                        this.autoDistanceEnabled = true; // نقفل الحقل للاحتفاظ بالمسافة المحسوبة
                    } else {
                        console.warn('Element status:', element.status);
                        alert('Unable to calculate route: ' + element.status);
                    }
                } catch (e) {
                    console.error(e);
                    alert('Unexpected response from Distance Matrix API.');
                }
            });
        },

        // تحقق بسيط قبل ال submit
        submitIfValid(event) {
            this.timeError = '';
            // تحقق من أن التوقيت صحيح: arrival > departure
            if (this.departure_time && this.arrival_time) {
                const dep = new Date(this.departure_time);
                const arr = new Date(this.arrival_time);
                if (arr <= dep) {
                    this.timeError = 'خطأ: وقت المغادرة يجب أن يكون قبل وقت الوصول (Arrival > Departure).';
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                    return;
                }
            }

            // تحقق من مسافة موجبة
            if (Number(this.distance) < 0) {
                alert('Distance must be a positive number.');
                return;
            }

            // كل شيء جيد — أرسل الفورم
            // بوقوف prevent في الزر نرسل الفورم يدوياً:
            event.target.closest('form').submit();
        }
    }
}
</script>
@endsection
