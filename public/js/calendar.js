// resources/js/calendar-page.js
console.log('calendar-page.js loaded');

import { Calendar } from '@fullcalendar/core';
import dayGridPlugin from '@fullcalendar/daygrid';
import timeGridPlugin from '@fullcalendar/timegrid';
import interactionPlugin from '@fullcalendar/interaction';

document.addEventListener('DOMContentLoaded', function () {
    const calendarEl = document.getElementById('calendar');
    if (!calendarEl) return;

    // Laravel will define this globally in Blade
    const eventsData = window.eventsData || [];
console.log( window.eventsData);

    // Default filters
    const activeFilters = new Set(['attendance', 'leaves', 'holidays']);
    const filterButtons = document.querySelectorAll('.filter-btn');
    const toggleAllBtn = document.getElementById('toggleAllBtn');

    const calendar = new Calendar(calendarEl, {
        plugins: [dayGridPlugin, timeGridPlugin, interactionPlugin],
        initialView: 'dayGridMonth',
        height: 'auto',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },
        events: eventsData.filter(e => activeFilters.has(e.type)),
        eventClick: (info) => {
    const event = info.event;
    const startDate = event.start;
    const endDate = event.end;

    // Title
    document.getElementById('modalTitle').textContent = event.title;

    // Date
    document.getElementById('modalDate').textContent =
        `Date: ${startDate.toLocaleDateString()}${endDate ? ' - ' + endDate.toLocaleDateString() : ''}`;

    // Type badge
    const typeBadge = document.getElementById('modalTypeBadge');
    typeBadge.textContent = event.extendedProps.type.toUpperCase();
    typeBadge.className = `inline-block px-3 py-1 text-sm font-semibold rounded-full mb-3 text-white ${
        event.extendedProps.type === 'attendance' ? 'bg-green-600' :
        event.extendedProps.type === 'leaves' ? 'bg-yellow-600' :
        'bg-purple-600'
    }`;

    // User
    const userName = event.extendedProps.user || 'Unknown';
    document.getElementById('modalUserName').textContent = userName;

    // User initials
    const initials = userName.split(' ').map(n => n[0]).join('').toUpperCase();
    document.getElementById('modalUserInitials').textContent = initials;

    // Location
    document.getElementById('modalLocation').textContent = event.extendedProps.location || '';

    // Info / remarks
    document.getElementById('modalInfo').textContent = event.extendedProps.info || '';

    // Show modal
    document.getElementById('eventModal').classList.remove('hidden');
}
,
        eventDidMount: (info) => {
            info.el.setAttribute('title', info.event.title);
        }
    });

    calendar.render();

    function updateCalendar() {
        calendar.getEvents().forEach(e => e.remove());
        const filteredEvents = eventsData.filter(e => activeFilters.has(e.type));
        calendar.addEventSource(filteredEvents);
    }

    // Filter buttons
    filterButtons.forEach(btn => {
        btn.addEventListener('click', () => {
            const type = btn.getAttribute('data-type');
            if (activeFilters.has(type)) {
                activeFilters.delete(type);
                btn.classList.remove('ring-2', 'ring-offset-2', 'ring-indigo-500');
            } else {
                activeFilters.add(type);
                btn.classList.add('ring-2', 'ring-offset-2', 'ring-indigo-500');
            }
            toggleAllBtn.textContent = activeFilters.size === 3 ? 'Deselect All' : 'Select All';
            updateCalendar();
        });
    });

    // Toggle all
    toggleAllBtn?.addEventListener('click', () => {
        const allTypes = ['attendance', 'leaves', 'holidays'];
        if (activeFilters.size === allTypes.length) {
            activeFilters.clear();
            toggleAllBtn.textContent = 'Select All';
            filterButtons.forEach(btn =>
                btn.classList.remove('ring-2', 'ring-offset-2', 'ring-indigo-500'));
        } else {
            allTypes.forEach(type => activeFilters.add(type));
            toggleAllBtn.textContent = 'Deselect All';
            filterButtons.forEach(btn =>
                btn.classList.add('ring-2', 'ring-offset-2', 'ring-indigo-500'));
        }
        updateCalendar();
    });

    // Close modal
    document.getElementById('closeModal')?.addEventListener('click', () => {
        document.getElementById('eventModal')?.classList.add('hidden');
    });

    // Highlight default filters
    filterButtons.forEach(btn =>
        btn.classList.add('ring-2', 'ring-offset-2', 'ring-indigo-500'));
});
