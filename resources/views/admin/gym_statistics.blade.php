@extends('layouts.admin')

@section('title', 'Sporta zāles statistika')

@section('content')
    <h1 class='mt-8 text-3xl font-bold text-center'>Sporta zāles statistika</h1>

    <div class='mt-8'>
        <div class='mb-8'>
            <div class='w-1/3'>
                <h2 class='text-center font-bold text-xl mb-2'>Klientu sadalījums pēc abonementa veida</h2>
                <canvas id='memberships_distribution_chart'></canvas>
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

        const memberships_distribution_data = {!! $memberships_distribution !!};
        display_chart(memberships_distribution_data, 'memberships_distribution_chart', 'pie', 'Klientu skaits');
    </script>
@endsection