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
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-black text-gray-900 tracking-tighter">
                    Interactive Booking Calendar
                </h3>
                <div class="flex gap-2">
                    <span class="px-3 py-1 bg-amber-500 text-white text-[10px] uppercase tracking-widest font-bold rounded-full shadow-sm">Pending</span>
                    <span class="px-3 py-1 bg-green-500 text-white text-[10px] uppercase tracking-widest font-bold rounded-full shadow-sm">Confirmed</span>
                </div>
            </div>

            <div id="calendar" class="w-full h-[600px] z-0 relative"></div>
        </div>
    </div>

    {{-- Modal for Accept/Reject Actions --}}
    <div id="bookingModal" class="fixed inset-0 z-50 hidden bg-black/60 backdrop-blur-sm flex items-center justify-center p-4 transition-opacity">
        <div class="bg-white rounded-3xl p-8 max-w-md w-full shadow-2xl">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-black text-gray-900" id="modalTitle">Booking Details</h3>
                <button onclick="closeModal()" class="text-gray-400 hover:text-red-500 transition"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
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
@endsection

@push('scripts')
    {{-- FullCalendar Script --}}
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js'></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');
            var events = @json($calendarEvents ?? []);

            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek'
                },
                events: events,
                eventClick: function(info) {
                    var booking = info.event;
                    var props = booking.extendedProps;

                    document.getElementById('modalTitle').innerText = booking.title;
                    document.getElementById('modalCustomer').innerText = props.customer;
                    document.getElementById('modalFarm').innerText = props.farm;
                    document.getElementById('modalNet').innerText = props.net;

                    var actionsDiv = document.getElementById('modalActions');
                    var statusP = document.getElementById('modalStatus');

                    if(props.status === 'pending') {
                        actionsDiv.classList.remove('hidden');
                        statusP.classList.add('hidden');

                        document.getElementById('approveForm').action = '/owner/bookings/' + booking.id + '/approve';
                        document.getElementById('rejectForm').action = '/owner/bookings/' + booking.id + '/reject';
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
            });

            calendar.render();
        });

        function closeModal() {
            document.getElementById('bookingModal').classList.add('hidden');
        }
    </script>
@endpush
