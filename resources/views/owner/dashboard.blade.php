@extends('layouts.owner')

@section('title', 'Overview & Financials')

@section('content')
    {{-- FullCalendar Core CSS --}}
    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css' rel='stylesheet' />

    <div class="max-w-7xl mx-auto pb-12">

        @if(session('success'))
            <div class="mb-8 bg-green-50 border-l-4 border-green-500 p-5 rounded-r-xl shadow-sm">
                <p class="text-sm text-green-700 font-bold tracking-tight">{{ session('success') }}</p>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-8 bg-red-50 border-l-4 border-red-500 p-5 rounded-r-xl shadow-sm">
                <p class="text-sm text-red-700 font-bold tracking-tight">{{ session('error') }}</p>
            </div>
        @endif

        {{-- 1. Detailed Financial Reports --}}
        <h3 class="text-xl font-black text-gray-800 mb-4 tracking-tight">Financial Reports <span class="text-sm font-bold text-gray-400">(Confirmed Bookings)</span></h3>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-10">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 transition-transform hover:-translate-y-1">
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Gross Revenue</p>
                <p class="text-3xl font-black text-gray-900">{{ number_format($financials['gross'] ?? 0, 2) }} <span class="text-sm text-gray-400">JOD</span></p>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 transition-transform hover:-translate-y-1">
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Platform Commission</p>
                <p class="text-3xl font-black text-red-500">- {{ number_format($financials['commission'] ?? 0, 2) }} <span class="text-sm text-gray-400">JOD</span></p>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 transition-transform hover:-translate-y-1">
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Taxes (VAT)</p>
                <p class="text-3xl font-black text-red-500">- {{ number_format($financials['taxes'] ?? 0, 2) }} <span class="text-sm text-gray-400">JOD</span></p>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-green-500 p-6 bg-green-50 transition-transform hover:-translate-y-1">
                <p class="text-[10px] font-black text-green-600 uppercase tracking-widest mb-1">Net Profit</p>
                <p class="text-3xl font-black text-green-700">{{ number_format($financials['net'] ?? 0, 2) }} <span class="text-sm text-green-500">JOD</span></p>
            </div>
        </div>

        {{-- 2. Interactive Booking Management Calendar --}}
        <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 overflow-hidden p-8">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
                <div>
                    <h3 class="text-xl font-black text-gray-900 tracking-tighter">
                        Interactive Booking & Blocking Calendar
                    </h3>
                    <p class="text-xs text-gray-500 mt-1">Click on an empty date to block/unblock a shift. Click on an event to view details.</p>
                </div>
                <div class="flex gap-2 flex-wrap">
                    <span class="px-3 py-1 bg-amber-500 text-white text-[10px] uppercase tracking-widest font-bold rounded-full shadow-sm">Pending</span>
                    <span class="px-3 py-1 bg-[#10b981] text-white text-[10px] uppercase tracking-widest font-bold rounded-full shadow-sm">Confirmed</span>
                    <span class="px-3 py-1 bg-[#111827] text-white text-[10px] uppercase tracking-widest font-bold rounded-full shadow-sm">Blocked</span>
                </div>
            </div>

            <div id="calendar" class="w-full h-[600px] z-0 relative"></div>
        </div>
    </div>

    {{-- Modal for View Booking Details --}}
    <div id="bookingModal" class="fixed inset-0 z-50 hidden bg-black/60 backdrop-blur-sm flex items-center justify-center p-4 transition-opacity">
        <div class="bg-white rounded-3xl p-8 max-w-md w-full shadow-2xl">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-black text-gray-900" id="modalTitle">Booking Details</h3>
                <button onclick="closeModal('bookingModal')" class="text-gray-400 hover:text-red-500 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <div class="space-y-4 mb-8 bg-gray-50 p-4 rounded-2xl border border-gray-100">
                <div>
                    <p class="text-[10px] text-gray-500 uppercase tracking-widest font-black">Customer</p>
                    <p id="modalCustomer" class="text-gray-900 font-bold text-sm"></p>
                </div>
                <div>
                    <p class="text-[10px] text-gray-500 uppercase tracking-widest font-black">Farm</p>
                    <p id="modalFarm" class="text-gray-900 font-bold text-sm"></p>
                </div>
                <div class="pt-2 border-t border-gray-200">
                    <p class="text-[10px] text-gray-500 uppercase tracking-widest font-black">Net Payout</p>
                    <p class="text-[#1d5c42] font-black text-xl"><span id="modalNet"></span> JOD</p>
                </div>
            </div>

            <div id="modalActions" class="flex gap-4 hidden">
                <form id="approveForm" method="POST" class="flex-1">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="w-full bg-[#1d5c42] hover:bg-[#154230] text-white font-black uppercase tracking-widest text-xs py-4 rounded-xl shadow-lg transition-transform active:scale-95">Accept</button>
                </form>
                <form id="rejectForm" method="POST" class="flex-1">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="w-full bg-red-50 border border-red-200 hover:bg-red-500 hover:text-white text-red-600 font-black uppercase tracking-widest text-xs py-4 rounded-xl transition-all active:scale-95">Reject</button>
                </form>
            </div>
            <p id="modalStatus" class="text-center font-black uppercase tracking-widest text-xs py-4 rounded-xl hidden"></p>
        </div>
    </div>

    {{-- Modal for Blocking Dates/Shifts --}}
    <div id="blockModal" class="fixed inset-0 z-50 hidden bg-black/60 backdrop-blur-sm flex items-center justify-center p-4 transition-opacity">
        <div class="bg-white rounded-3xl p-8 max-w-md w-full shadow-2xl">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-black text-gray-900">Manage Availability</h3>
                <button onclick="closeModal('blockModal')" class="text-gray-400 hover:text-red-500 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <p class="text-sm text-gray-500 mb-6">Select a farm and shift to block or unblock for <span id="blockDateDisplay" class="font-bold text-gray-900"></span>.</p>

            <form id="blockForm" onsubmit="submitBlock(event)">
                <input type="hidden" id="blockDateInput" name="date">

                <div class="space-y-4 mb-8">
                    <div>
                        <label class="block text-[10px] text-gray-500 uppercase tracking-widest font-black mb-2">Select Farm</label>
                        <select id="blockFarmId" name="farm_id" class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-[#1d5c42] focus:border-[#1d5c42] block p-3 font-medium" required>
                            @if(isset($farms) && $farms->count() > 0)
                                @foreach($farms as $farm)
                                    <option value="{{ $farm->id }}">{{ $farm->name }}</option>
                                @endforeach
                            @else
                                <option value="">No farms available</option>
                            @endif
                        </select>
                    </div>

                    <div>
                        <label class="block text-[10px] text-gray-500 uppercase tracking-widest font-black mb-2">Select Shift</label>
                        <select id="blockShift" name="shift" class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-[#1d5c42] focus:border-[#1d5c42] block p-3 font-medium" required>
                            <option value="full_day">Full Day</option>
                            <option value="morning">Morning (08:00 - 16:00)</option>
                            <option value="evening">Evening (18:00 - 02:00)</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-[10px] text-gray-500 uppercase tracking-widest font-black mb-2">Reason (Optional)</label>
                        <input type="text" id="blockReason" name="reason" class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-[#1d5c42] focus:border-[#1d5c42] block p-3 font-medium" placeholder="e.g. Maintenance, Offline booking">
                    </div>
                </div>

                <button type="submit" id="blockSubmitBtn" class="w-full bg-[#111827] hover:bg-black text-white font-black uppercase tracking-widest text-xs py-4 rounded-xl shadow-lg transition-transform active:scale-95">
                    Toggle Block Status
                </button>
            </form>

            <div id="blockMessage" class="mt-4 text-center text-sm font-bold hidden"></div>
        </div>
    </div>
@endsection

@push('scripts')
    {{-- FullCalendar Script --}}
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js'></script>
    <script>
        let calendar;

        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');
            var events = @json($calendarEvents ?? []);

            calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek'
                },
                events: events,
                selectable: true,
                select: function(info) {
                    // When a user clicks on an empty date/time slot
                    openBlockModal(info.startStr);
                },
                eventClick: function(info) {
                    var eventObj = info.event;
                    var props = eventObj.extendedProps;

                    if (props.type === 'blocked') {
                        // If it's a blocked event, open the block modal to allow unblocking
                        openBlockModal(props.date, props.farm_id, props.shift, props.reason);
                    } else if (props.type === 'booking') {
                        // If it's a booking, show booking details
                        document.getElementById('modalTitle').innerText = eventObj.title;
                        document.getElementById('modalCustomer').innerText = props.customer;
                        document.getElementById('modalFarm').innerText = props.farm;
                        document.getElementById('modalNet').innerText = props.net;

                        var actionsDiv = document.getElementById('modalActions');
                        var statusP = document.getElementById('modalStatus');

                        // Extract ID from string like 'booking_123'
                        var bookingId = eventObj.id.replace('booking_', '');

                        if(props.status === 'pending') {
                            actionsDiv.classList.remove('hidden');
                            statusP.classList.add('hidden');

                            document.getElementById('approveForm').action = '/owner/bookings/' + bookingId + '/approve';
                            document.getElementById('rejectForm').action = '/owner/bookings/' + bookingId + '/reject';
                        } else {
                            actionsDiv.classList.add('hidden');
                            statusP.classList.remove('hidden');
                            statusP.innerText = props.status;
                            if(props.status === 'confirmed') {
                                statusP.className = "text-center font-black uppercase tracking-widest text-xs py-4 rounded-xl bg-green-50 text-[#1d5c42] border border-green-200";
                            } else {
                                statusP.className = "text-center font-black uppercase tracking-widest text-xs py-4 rounded-xl bg-gray-50 text-gray-500 border border-gray-200";
                            }
                        }

                        document.getElementById('bookingModal').classList.remove('hidden');
                    }
                }
            });

            calendar.render();
        });

        function closeModal(modalId) {
            document.getElementById(modalId).classList.add('hidden');
            // Reset messages if closing block modal
            if(modalId === 'blockModal') {
                document.getElementById('blockMessage').classList.add('hidden');
            }
        }

        function openBlockModal(dateStr, farmId = '', shift = 'full_day', reason = '') {
            // Strip time if present for dayGridMonth view clicks
            let dateOnly = dateStr.split('T')[0];

            document.getElementById('blockDateDisplay').innerText = dateOnly;
            document.getElementById('blockDateInput').value = dateOnly;

            if(farmId) document.getElementById('blockFarmId').value = farmId;
            document.getElementById('blockShift').value = shift;
            document.getElementById('blockReason').value = reason;

            document.getElementById('blockMessage').classList.add('hidden');
            document.getElementById('blockModal').classList.remove('hidden');
        }

        async function submitBlock(e) {
            e.preventDefault();

            const btn = document.getElementById('blockSubmitBtn');
            const msgDiv = document.getElementById('blockMessage');

            btn.disabled = true;
            btn.innerText = 'Processing...';
            msgDiv.classList.add('hidden');

            const formData = new FormData(e.target);
            const data = {
                farm_id: formData.get('farm_id'),
                date: formData.get('date'),
                shift: formData.get('shift'),
                reason: formData.get('reason'),
            };

            try {
                const response = await fetch('{{ route('owner.dashboard.toggle_block') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(data)
                });

                const result = await response.json();

                msgDiv.classList.remove('hidden');

                if (response.ok && result.success) {
                    msgDiv.className = "mt-4 text-center text-sm font-bold text-green-600";
                    msgDiv.innerText = result.message;

                    // Reload the page after a short delay to refresh calendar data
                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                } else {
                    msgDiv.className = "mt-4 text-center text-sm font-bold text-red-600";
                    msgDiv.innerText = result.message || 'An error occurred.';
                    btn.disabled = false;
                    btn.innerText = 'Toggle Block Status';
                }
            } catch (error) {
                msgDiv.classList.remove('hidden');
                msgDiv.className = "mt-4 text-center text-sm font-bold text-red-600";
                msgDiv.innerText = 'Network error occurred. Please try again.';
                btn.disabled = false;
                btn.innerText = 'Toggle Block Status';
            }
        }
    </script>
@endpush
