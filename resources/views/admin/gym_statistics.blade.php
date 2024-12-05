@extends('layouts.admin')

@section('title', 'Sporta zāles statistika')

@section('content')
    <h1 class='mt-8 text-3xl font-bold text-center'>Sporta zāles statistika</h1>

    <div class='mt-8 w-9/12 mx-auto'>
        <div class='mb-8 flex flex-row justify-around'>
            <div class='w-1/3 shadow-lg p-4 rounded-xl'>
                <h2 class='text-center font-bold text-xl mb-2'>Klientu sadalījums pēc abonementa veida</h2>
                <canvas id='memberships_distribution_chart'></canvas>
            </div>
            <div class='shadow-lg p-4 rounded-xl'>
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

                <div class='flex flex-row gap-x-2 items-center mt-4 mb-2'>
                    <label for="other">Cits periods</label>
                    <input type="radio" name="period" id="other" class='period_radio_button' value='other'>
                </div>
                <div class='flex flex-row gap-x-4'>
                    <div class='flex flex-row gap-x-2 items-center'>
                        <label for="date_from">No:</label>
                        <input type="date" name="date_from" id="date_from" disabled class='cursor-not-allowed'>
                    </div>
                    <div class='flex flex-row gap-x-2 items-center'>
                        <label for="deate_until">Līdz:</label>
                        <input type="date" name="date_until" id="date_until" disabled class='cursor-not-allowed'>
                    </div>
                </div>
                <canvas id='income_chart'></canvas>
                <h2 class='mt-4 text-xl text-center'><span class='font-bold'>Kopējie ienākumi par periodu: </span><span id='total_income'>0</span>€</h2>
            </div>
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

        // Memberships distribution chart
        const memberships_distribution_data = {!! $memberships_distribution !!};
        display_chart(memberships_distribution_data, 'memberships_distribution_chart', 'pie', 'Klientu skaits');

        // Incomes chart

        const period_radio_buttons = document.querySelectorAll('.period_radio_button');
        const date_from_input = document.querySelector('#date_from');
        const date_until_input = document.querySelector('#date_until');
        const payments_data = {!! $payments_data !!};
        const total_income_text = document.querySelector('#total_income');

        // Activate date inputs when "Other period" is selected
        for (let i = 0; i < period_radio_buttons.length; i++) {
            period_radio_buttons[i].onchange = function() {
                if (this.value === 'other') {
                    date_from_input.disabled = false;
                    date_from_input.classList.remove('cursor-not-allowed');
                    date_until_input.disabled = false;
                    date_until_input.classList.remove('cursor-not-allowed');
                } else {
                    date_from_input.disabled = true;
                    date_from_input.classList.add('cursor-not-allowed');
                    date_until_input.disabled = true;
                    date_until_input.classList.add('cursor-not-allowed');
                    destroy_chart('income_chart');
                    display_chart(payments_data[this.value].payments, 'income_chart', 'bar', 'Ienākumi, EUR');
                    total_income_text.textContent = payments_data[this.value].total.toFixed(2);
                }
            }
        }
    </script>
@endsection