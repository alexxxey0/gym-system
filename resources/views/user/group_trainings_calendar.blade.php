@extends('layouts.' . Auth::user()->role)

@section('title', "Grupu nodarbību kalendārs")

@section('content')


    <h1 class='text-center text-3xl font-bold mt-8'>Grupu nodarbību kalendārs</h1>

    <div class='w-10/12 mx-auto mt-8 mb-16 flex flex-col gap-y-8 overflow-x-scroll'>
        @if (Auth::user()->role === 'coach' or Auth::user()->role === 'client')
            <div>
                <label class='text-lg' for="trainings_displayed">Rādīt:</label>
                <select name="trainings_displayed" id="trainings_displayed" class='rounded-md'>
                    <option value="all_trainings" selected>Visas nodarbības</option>
                    <option value="my_trainings">Manas nodarbības</option>
                </select>
            </div>
        @endif
        
        <div class='flex flex-col'>
            <div class='flex flex-row gap-x-2 items-center'>
                <div class='bg-[#a9a9a9] w-[20px] h-[20px] rounded-sm'></div>
                <span>Pabeigta nodarbība</span>
            </div>

            <div class='flex flex-row gap-x-2 items-center'>
                <div class='bg-[#50C878] w-[20px] h-[20px] rounded-sm'></div>
                <span>Aktīva nodarbība</span>
            </div>

            <div class='flex flex-row gap-x-2 items-center'>
                <div class='bg-[#007bff] w-[20px] h-[20px] rounded-sm'></div>
                <span>Ieplānota nodarbība</span>
            </div>
        </div>

        <div id='calendar' class=''></div>
    </div>

    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js'></script>
    <script>

        const events = {!! $events !!};
        const coach_id = {{ Auth::user()->coach_id ?? 0 }};

        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'timeGridWeek',
                firstDay: 1,
                events: events,
                eventTimeFormat: { // like '14:30'
                    hour: 'numeric',
                    minute: '2-digit',
                    hour12: false
                },
                dayHeaderFormat: {
                    weekday: 'long',
                },
                nowIndicator: true,
                slotMinTime: "08:00:00",
                slotMaxTime: "22:00:00",
                allDaySlot: false,
                slotLabelFormat: {
                    hour: 'numeric',
                    minute: '2-digit',
                    hour12: false
                },
                slotEventOverlap: false,
                slotDuration: "00:10:00"
            });

            calendar.render();

            const trainings_displayed_selection = document.querySelector('#trainings_displayed');

            trainings_displayed_selection.addEventListener('change', function() {
                const calendar_events = calendar.getEvents();
                if (this.value === 'my_trainings') {

                    if (coach_id != 0) {
                        // Remove trainings that are not hosted by the currently authenticated coach
                        for (let i = 0; i < calendar_events.length; i++) {
                            if (calendar_events[i].extendedProps.coach_id !== coach_id) {
                                calendar_events[i].remove();
                            }
                        }
                    } else {
                        // Remove trainings that the currently authenticated client is not signed up to
                        for (let i = 0; i < calendar_events.length; i++) {
                            if (!calendar_events[i].extendedProps.client_signed_up) {
                                calendar_events[i].remove();
                            }
                        }
                    }

                } else {

                    // Clear the calendar from all events
                    for (let i = 0; i < calendar_events.length; i++) {
                        calendar_events[i].remove();
                    }

                    // Add all the events back to the calendar
                    events.forEach((event) => {
                        calendar.addEvent(event);
                    });
                }

                calendar.render();
            });

        });
    </script>
@endsection