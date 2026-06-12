<div>
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Booking Calendar</h1>
        <div class="flex gap-2 text-xs font-medium">
            <span class="flex items-center gap-1"><span class="w-3 h-3 rounded-full bg-yellow-500"></span> Pending</span>
            <span class="flex items-center gap-1 ml-2"><span class="w-3 h-3 rounded-full bg-green-500"></span> Confirmed</span>
            <span class="flex items-center gap-1 ml-2"><span class="w-3 h-3 rounded-full bg-purple-500"></span> Checked-In</span>
            <span class="flex items-center gap-1 ml-2"><span class="w-3 h-3 rounded-full bg-red-500"></span> Cancelled</span>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <div id="calendar" wire:ignore></div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');
        var events = @json($events);
        
        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay'
            },
            events: events,
            eventClick: function(info) {
                // Could open a modal with reservation details here
            }
        });
        
        calendar.render();
    });
</script>
@endpush
