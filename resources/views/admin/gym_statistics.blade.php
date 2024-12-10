@extends('layouts.admin')

@section('title', 'Sporta zāļu statistika')

@section('content')
    <style>
        .my_shadow {
            box-shadow: 0 0 0 2px #007bff, 8px 8px 0 0 #007bff;
        }
    </style>

    <h1 class='mt-8 text-3xl font-bold text-center'>Sporta zāļu statistika</h1>

    <div class='w-10/12 mx-auto flex flex-col gap-y-1'>
        <label for="gym" class='text-lg font-bold'>Sporta zāle</label>
        <select name="gym" id="gym_selection" class='rounded-md w-fit'>
            <option value="total" selected>Visas zāles</option>
            @foreach($gyms as $gym)
                <option value="{{ $gym->gym_id }}">{{ $gym->name }}</option>
            @endforeach
        </select>
    </div>

    <div class='my-12 w-10/12 mx-auto grid grid-cols-6 gap-8'>
        <div class='my_shadow p-4 rounded-xl col-span-3'>
            <h2 class='text-center font-bold text-xl mb-2'>Vispārīgie rādītāji</h2>
            <ul class='text-lg'>
                <li><span class='font-bold'>Klientu skaits:</span> <span id='clients_count'></span></li>
                <li><span class='font-bold'>Treneru skaits:</span> {{ $gyms_statistics['total'][0]['coaches_count'] }}</li>
                <li><span class="font-bold">Aktīvo grupu nodarbību veidu skaits: </span><span id='active_group_trainings_count'></span></li>
            </ul>
        </div>

        <div class='my_shadow p-4 rounded-xl col-span-3'>
            <h2 class='text-center font-bold text-xl mb-2'>Grupu nodarbību apmeklējums</h2>
            <ul class='text-lg'>
                <li><span class='font-bold'>Vidējais grupu nodarbību apmeklējums: </span><span id='avg_group_training_attendance'></span>%</li>
            </ul>
        </div>


        <div class='my_shadow p-4 rounded-xl col-span-2'>
            <h2 class='text-center font-bold text-xl mb-2'>Klientu sadalījums pēc abonementa veida</h2>
            <canvas id='memberships_distribution_chart'></canvas>
        </div>

        <div class='my_shadow p-4 rounded-xl col-span-4'>
            <h2 class='text-center font-bold text-xl mb-2'>Ienākumi par periodu</h2>
            <div class='flex flex-row gap-x-4'>
                <div class='flex flex-row gap-x-2 items-center'>
                    <label for="week">Pēdējā nedēļa</label>
                    <input type="radio" name="period" id="week" class='period_radio_button' value='week'>
                </div>
                <div class='flex flex-row gap-x-2 items-center'>
                    <label for="week">Pēdējais mēnesis</label>
                    <input type="radio" name="period" id="month" class='period_radio_button' value='month'>
                </div>
                <div class='flex flex-row gap-x-2 items-center'>
                    <label for="year">Pēdējais gads</label>
                    <input type="radio" name="period" id="year" class='period_radio_button' value='year'>
                </div>
                <div class='flex flex-row gap-x-2 items-center'>
                    <label for="all">Par visu laiku</label>
                    <input type="radio" name="period" id="all" class='period_radio_button' value='all'>
                </div>
            </div>

            <canvas id='income_chart'></canvas>
            <h2 class='mt-4 text-xl text-center'><span class='font-bold'>Kopējie ienākumi par periodu: </span><span id='total_income'>0</span>€</h2>
        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        function display_chart(data, chart_element, chart_type, label) {
            var data_array = Object.keys(data).map((key) => [key, data[key]]); // Convert JS to object to array
            //console.log(data_array);

            const chart = document.getElementById(chart_element);
            new Chart(chart, {
                type: chart_type,
                data: {
                    labels: data_array.map(row => row[0]),
                    datasets: [
                        {
                            label: label,
                            data: data_array.map(row => row[1])
                        }
                    ]
                }
            });
        }

        function destroy_chart(chart_name) {
            let chartStatus = Chart.getChart(chart_name); // <canvas> id
            if (chartStatus != undefined) {
                chartStatus.destroy();
            }
        }

        const gyms_statistics = {!! $gyms_statistics_json !!};
        const gym_selection = document.querySelector('#gym_selection');

        // Display memberships distribution chart
        let memberships_distribution_data = gyms_statistics[gym_selection.value][0]['memberships_distribution'];
        display_chart(memberships_distribution_data, 'memberships_distribution_chart', 'pie', 'Klientu skaits');

        const period_radio_buttons = document.querySelectorAll('.period_radio_button');
        const payments_data = gyms_statistics[gym_selection.value][0]['payments_data'];
        const total_income_text = document.querySelector('#total_income');
        let selected_period = "";
        
        
        for (let i = 0; i < period_radio_buttons.length; i++) {
            // Display incomes chart when a period is selected
            period_radio_buttons[i].onchange = function() {
                selected_period = this.value;
                destroy_chart('income_chart');
                display_chart(gyms_statistics[gym_selection.value][0]['payments_data'][this.value].payments, 'income_chart', 'bar', 'Ienākumi, EUR');
                total_income_text.textContent = payments_data[this.value].total.toFixed(2);
            }
        }

        const clients_count = document.querySelector('#clients_count');
        const active_group_trainings_count = document.querySelector('#active_group_trainings_count');
        const avg_group_training_attendance = document.querySelector('#avg_group_training_attendance');

        clients_count.textContent = gyms_statistics[gym_selection.value][0]['clients_count'];
        active_group_trainings_count.textContent = gyms_statistics[gym_selection.value][0]['active_group_trainings_count'];
        avg_group_training_attendance.textContent = gyms_statistics[gym_selection.value][0]['avg_group_training_attendance'];

        gym_selection.addEventListener('change', function() {
            clients_count.textContent = gyms_statistics[this.value][0]['clients_count'];
            active_group_trainings_count.textContent = gyms_statistics[this.value][0]['active_group_trainings_count'];
            avg_group_training_attendance.textContent = gyms_statistics[this.value][0]['avg_group_training_attendance'];

            // Update memberships distribution chart with selected gym's data
            let memberships_distribution_data = gyms_statistics[this.value][0]['memberships_distribution'];
            destroy_chart('memberships_distribution_chart');
            display_chart(memberships_distribution_data, 'memberships_distribution_chart', 'pie', 'Klientu skaits');

            // If a period has been previously selected, update incomes chart
            if (selected_period !== "") {
                destroy_chart('income_chart');
                display_chart(gyms_statistics[gym_selection.value][0]['payments_data'][selected_period].payments, 'income_chart', 'bar', 'Ienākumi, EUR');
                total_income_text.textContent = gyms_statistics[gym_selection.value][0]['payments_data'][selected_period].total.toFixed(2);
            }

            for (let i = 0; i < period_radio_buttons.length; i++) {
                // Update incomes chart when a period button is clicked with selected gym's data
                period_radio_buttons[i].onchange = function() {
                    selected_period = this.value;
                    destroy_chart('income_chart');
                    display_chart(gyms_statistics[gym_selection.value][0]['payments_data'][this.value].payments, 'income_chart', 'bar', 'Ienākumi, EUR');
                    total_income_text.textContent = gyms_statistics[gym_selection.value][0]['payments_data'][this.value].total.toFixed(2);
                }
            }  
        });
    </script>
@endsection