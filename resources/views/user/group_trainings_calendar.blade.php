@extends('layouts.' . Auth::user()->role)

@section('title', "Grupu nodarbību kalendārs")

@section('content')

    <style>
        .fc-event {
            cursor: pointer;
        }

        .canceled_training_bg_sm {
            background: repeating-linear-gradient(
                45deg,
                #a9a9a9,
                #a9a9a9 5px,
                #ef4444 5px,
                #ef4444 10px
            );
        }

        .canceled_training_bg_lg {
            background: repeating-linear-gradient(
                45deg,
                #a9a9a9,
                #a9a9a9 10px,
                #ef4444 10px,
                #ef4444 20px
            );
        }
    </style>


    <h1 class='text-center text-3xl font-bold mt-8'>Grupu nodarbību kalendārs</h1>

    <div class='w-10/12 mx-auto mt-8 mb-16 flex flex-col gap-y-8 overflow-x-scroll'>
        
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

            <div class="flex flex-row gap-x-2 items-center">
                <div class='canceled_training_bg_sm w-[20px] h-[20px] rounded-sm'></div>
                <span>Atcelta nodarbība</span>
            </div>
        </div>

        @if (Auth::user()->role === 'coach' or Auth::user()->role === 'client')
            <div class='flex flex-col gap-y-1'>
                <label class='font-bold' for="trainings_displayed">Rādīt:</label>
                <select name="trainings_displayed" id="trainings_displayed" class='rounded-md w-fit'>
                    <option value="all_trainings" selected>Visas nodarbības</option>
                    <option value="my_trainings">Manas nodarbības</option>
                </select>
            </div>
        @endif

        <div class='flex flex-col gap-y-1'>
            <label for="gym" class='font-bold'>Sporta zāle</label>
            <select name="gym" id="gym_selection" class='w-fit rounded-md'>
                <option value="all">Visas zāles</option>
                @foreach($gyms as $gym)
                    <option value="{{ $gym->gym_id }}">{{ $gym->name }}</option>
                @endforeach
            </select>
        </div>

        <div id='calendar' class=''></div>
    </div>

    <div id='overlay' class='hidden fixed w-full h-full top-0 bottom-0 left-0 right-0 bg-blue-300 z-40 opacity-50'></div>

    <!-- Training info pop-up -->
    <div id='training_popup' class='hidden rounded-lg p-16 bg-white fixed top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 z-50'>
        <i class="fa-solid fa-xmark absolute top-4 right-4 text-2xl text-red-500 w-[20px] h-[20px] cursor-pointer" onclick="close_popup()"></i>
        
        <h1 class='text-center font-bold text-2xl mb-4'>Informācija par grupu nodarbību</h1>
        <h1 id='training_name' class='font-bold text-lg'></h1>
        <h2>Treneris/-e: <span id='training_coach'></span></h2>
        <h2>Sporta zāle: <span id='training_gym'></span></h2>
        <h2>Datums un laiks: <span id='training_time_and_date'></span></h2>
        <h2 id='canceled_text' class='hidden text-center text-red-500 font-bold text-2xl mt-2'>Nodarbība ir atcelta!</h2>

        <div id='training_actions' class='flex-col gap-y-2 mt-4 hidden'>
            <form action="{{ route('cancel_group_training') }}" method="POST" id='cancel_group_training_button'>
                @csrf
                <input type="hidden" name="training_id" class='training_id'>
                <input type="hidden" name="training_date" class='training_date'>
                <x-main_button type='submit' class='p-4 bg-red-500 active:bg-red-600'>Atcelt nodarbību</x-main_button>
            </form>

            <form action="{{ route('restore_group_training') }}" method="POST" id='restore_group_training_button' class='hidden'>
                @csrf
                <input type="hidden" name="training_id" class='training_id'>
                <input type="hidden" name="training_date" class='training_date'>
                <x-main_button type='submit' class='p-4 bg-[#50C878] active:bg-green-600'>Atjaunot nodarbību</x-main_button>
            </form>

            <form action="{{ route('mark_attendance_page') }}" method="POST" id='mark_attendance_button' class='hidden'>
                @csrf
                <input type="hidden" name="training_id" class='training_id'>
                <input type="hidden" name="training_date" class='training_date'>
                <x-main_button type='submit' class='p-4'>Atzīmēt nodarbības apmeklējumu</x-main_button>
            </form>
        </div>
    </div>

    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js'></script>
    <script>

        const events = {!! $events !!};
        const is_admin = "{{ Auth::user()->role === 'admin' }}";
        const coach_id = {{ Auth::user()->coach_id ?? 0 }};
        const overlay = document.querySelector('#overlay');
        const training_popup = document.querySelector('#training_popup');
        const training_name = document.querySelector('#training_name');
        const training_coach = document.querySelector('#training_coach');
        const training_gym = document.querySelector('#training_gym');
        const training_time_and_date = document.querySelector('#training_time_and_date');
        const training_actions = document.querySelector('#training_actions');
        const training_ids = document.querySelectorAll('.training_id');
        const training_dates = document.querySelectorAll('.training_date');
        const cancel_group_training_button = document.querySelector('#cancel_group_training_button');
        const canceled_text = document.querySelector('#canceled_text');
        const restore_group_training_button = document.querySelector('#restore_group_training_button');
        const mark_attendance_button = document.querySelector('#mark_attendance_button');

        function close_popup() {
            overlay.classList.add('hidden');
            training_popup.classList.add('hidden');
            training_actions.classList.remove('flex');
            training_actions.classList.add('hidden');
            cancel_group_training_button.classList.remove('hidden');
            canceled_text.classList.add('hidden');
            restore_group_training_button.classList.add('hidden');
            mark_attendance_button.classList.add('hidden');
        }

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
                slotDuration: "00:10:00",
                eventMaxStack: 2,

                eventClick: function(info) {
                    overlay.classList.remove('hidden');
                    training_popup.classList.remove('hidden');
                    training_name.textContent = info.event.title;
                    training_coach.textContent = info.event.extendedProps.coach_full_name;
                    training_gym.textContent = info.event.extendedProps.gym_name;
                    training_time_and_date.textContent = info.event.extendedProps.time_and_date;
                    for (let i = 0; i < training_ids.length; i++) {
                        training_ids[i].value = info.event.extendedProps.training_id;
                    }
                    for (let i = 0; i < training_dates.length; i++) {
                        training_dates[i].value = info.event.extendedProps.training_date;
                    }

                    if (info.event.extendedProps.canceled) {
                        canceled_text.classList.remove('hidden');
                    }

                    // Display buttons if the user has permission to manage this training (is admin or is the coach who created this training)
                    if (is_admin || coach_id == info.event.extendedProps.coach_id) {
                        training_actions.classList.remove('hidden');
                        training_actions.classList.add('flex');

                        const training_start = new Date(info.event.start);
                        const current_time = new Date();

                        // Show "Mark attendance" button only if the training has already started and has at least one client
                        if (current_time > training_start && Number(info.event.extendedProps.clients_count) > 0) {
                            mark_attendance_button.classList.remove('hidden');
                        }

                        if (current_time > training_start || info.event.extendedProps.canceled) {
                            cancel_group_training_button.classList.add('hidden');
                            restore_group_training_button.classList.add('hidden');
                        }

                        if (info.event.extendedProps.canceled) {
                            restore_group_training_button.classList.remove('hidden');
                        }
                    }
                }
            });

            calendar.render();

            const trainings_displayed_selection = document.querySelector('#trainings_displayed');
            let trainings_displayed = trainings_displayed_selection.value;
            let selected_gym_id = 'all';
            let calendar_events;

            if (trainings_displayed_selection !== null) {
                trainings_displayed_selection.addEventListener('change', function() {
                    trainings_displayed = this.value;
                    calendar_events = calendar.getEvents();

                    // Clear the calendar from all events
                    for (let i = 0; i < calendar_events.length; i++) {
                        calendar_events[i].remove();
                    }

                    // Add the necessary events back to the calendar
                    events.forEach((event) => {

                        if (event.extendedProps.gym_id == selected_gym_id || selected_gym_id === 'all') {

                            if (trainings_displayed === 'my_trainings') {
                                // For coaches, add trainings that they are hosting
                                if (coach_id != 0 && event.extendedProps.coach_id === coach_id) {
                                    calendar.addEvent(event);
                                // For clients, add trainings that they are signed up for
                                } else if (event.extendedProps.client_signed_up) {
                                    calendar.addEvent(event);
                                }
                            // If "All trainings" option is selected, add all the trainings
                            } else {
                                calendar.addEvent(event);
                            }
                        }
                    });

                    // Re-render the updated calendar
                    calendar.render();
                });
            }

            const gym_selection = document.querySelector('#gym_selection');

            gym_selection.addEventListener('change', function() {
                calendar_events = calendar.getEvents();
                selected_gym_id = this.value;

                // Clear the calendar from all events
                for (let i = 0; i < calendar_events.length; i++) {
                    calendar_events[i].remove();
                }

                // Add all the events back to the calendar
                events.forEach((event) => {
                    if (event.extendedProps.gym_id == selected_gym_id || selected_gym_id === 'all') {

                        if (trainings_displayed === 'my_trainings') {
                            // For coaches, add trainings that they are hosting
                            if (coach_id != 0 && event.extendedProps.coach_id === coach_id) {
                                calendar.addEvent(event);
                            // For clients, add trainings that they are signed up for
                            } else if (event.extendedProps.client_signed_up) {
                                calendar.addEvent(event);
                            }
                        // If "All trainings" option is selected, add all the trainings
                        } else {
                            calendar.addEvent(event);
                        }
                    }
                });

                calendar.render();
            });

        });
    </script>
@endsection