import './bootstrap';
import Alpine from 'alpinejs';

window.Alpine = Alpine;

// الكود الحالي للـ gallery
document.addEventListener('alpine:init', () => {
    Alpine.data('gallery', ({ images }) => ({
        images,
        current: 0,
        isOpen: false,

        open(index) {
            this.current = index
            this.isOpen = true
        },

        close() {
            this.isOpen = false
        },

        next() {
            this.current = (this.current + 1) % this.images.length
        },

        prev() {
            this.current =
                (this.current - 1 + this.images.length) % this.images.length
        },
    }))
})

Alpine.start();

document.addEventListener('DOMContentLoaded', () => {
    const eventType = document.getElementById('eventType');
    const dateInput = document.getElementById('booking_date');
    const timeGrid = document.getElementById('timeGrid');
    const timeSection = document.getElementById('timeSection');
    const startInput = document.getElementById('start_time');

    // عناصر ملخص الحجز
    const bookingSummary = document.getElementById('bookingSummary');
    const summaryType = document.getElementById('summaryType');
    const summaryDate = document.getElementById('summaryDate');
    const summaryTime = document.getElementById('summaryTime');

    if (!eventType || !dateInput) return;

    let duration = 0;

    // عند اختيار المناسبة
    eventType.addEventListener('change', () => {
        timeGrid.innerHTML = '';
        timeSection.classList.add('hidden');
        startInput.value = '';
        bookingSummary.classList.add('hidden');

        const type = eventType.value;
        if (!type) return;

        duration = window.bookingData.durations[type];
    });

    // عند اختيار التاريخ
    dateInput.addEventListener('input', () => {
        const selectedDate = dateInput.value;
        timeGrid.innerHTML = '';
        timeSection.classList.add('hidden');
        startInput.value = '';
        bookingSummary.classList.add('hidden');

        if (!selectedDate || duration === 0) return;

        const booked = window.bookingData.bookings
            .filter(b => b.date === selectedDate)
            .map(b => b.hour);

        showAvailableTimes(booked, duration, selectedDate);
    });

    function showAvailableTimes(booked, duration, date) {
        timeSection.classList.remove('hidden');

        for (let h = 8; h <= 20; h++) {
            let valid = true;
            for (let i = 0; i < duration; i++) {
                if (booked.includes(h + i)) {
                    valid = false;
                    break;
                }
            }
            if (!valid) continue;

            const btn = document.createElement('button');
            btn.type = 'button'; // مهم جداً لمنع الفورم من الإرسال
            btn.textContent = `${h}:00 - ${h + duration}:00`;
            btn.className = 'bg-green-200 p-2 rounded';

            const endInput = document.getElementById('end_time');

            btn.onclick = () => {
                const startTime = `${date} ${h}:00`;
                const endTime = `${date} ${h + duration}:00`;

                // تخزين القيم للفورم
                startInput.value = startTime;
                endInput.value = endTime;

                // عرض ملخص الحجز
                bookingSummary.classList.remove('hidden');
                summaryType.textContent = eventType.value;
                summaryDate.textContent = date;
                summaryTime.textContent = `${h}:00 - ${h + duration}:00`;
            };


            timeGrid.appendChild(btn);

        }
    }
});
