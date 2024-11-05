@extends('layouts.coach')

@section('title', 'Jauna grupu nodarbības veida izveidošana')

@section('content')
    <h1 class='mt-8 font-bold text-center text-3xl'>Jauna grupu nodarbības veida izveidošana</h1>

    <form action="" method="POST" class='flex flex-col gap-y-4 w-6/12 mt-12 mb-8 mx-auto'>
        @csrf

        <div class='flex flex-col'>
            <label class='text-lg' for="title">Nodarbības nosaukums</label>
            <input type="text" name='title' class='rounded-md' maxlength="50" required>
        </div>

        <div class="flex flex-col">
            <label class='text-lg' for="description">Nodarbības apraksts</label>
            <textarea name="description" cols="30" rows="5" required class='rounded-md'></textarea>
        </div>

        <div class='flex flex-col'>
            <label class='text-lg' for="image">Nodarbības ilustratīvs attēls</label>
            <input type="file" name="image" accept='image/*'>
        </div>

        <div>
            @php
                $days_eng = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
                $days_lv = ['Pirmdiena', 'Otrdiena', 'Trešdiena', 'Ceturtdiena', 'Piektdiena', 'Sestdiena', 'Svētdiena'];
            @endphp

            <h2 class='mb-2 text-lg'>Nodarbības grafiks</h2>

            @for ($i = 0; $i < count($days_lv); $i++)
                <div class='flex flex-row items-center justify-between gap-x-2 text-lg'>
                    <div class='flex flex-row items-center gap-x-4'>
                        <input type="checkbox" name="{{ $days_eng[$i] }}_checkbox" id="">
                        <label for="{{ $days_eng[$i] }}_checkbox">{{ $days_lv[$i] }}</label>
                    </div>

                    <div class='flex flex-row gap-x-4'>
                        <div class='flex flex-col'>
                            <label for="start_time_{{ $days_eng[$i] }}">Nodarbības sākums:</label>
                            <input type="time" name="start_time_{{ $days_eng[$i] }}" id="" disabled class='cursor-not-allowed rounded-md'>
                        </div>

                        <div class='flex flex-col'>
                            <label for="end_time_{{ $days_eng[$i] }}">Nodarbības beigas:</label>
                            <input type="time" name="end_time_{{ $days_eng[$i] }}" id="" disabled class='cursor-not-allowed rounded-md'>
                        </div>
                    </div>
                </div>
            @endfor
        </div>

        <div class="flex flex-col">
            <label class='text-lg' for="max_participants">Maksimālais apmeklētāju skaits (10-50)</label>
            <input class='w-fit' type="number" name="max_participants" min="10" max="50" required>
        </div>

        <x-main_button type='submit' class='mt-8 text-lg w-1/2 mx-auto p-4'>Izveidot jaunu grupu nodarbības veidu</x-main_button>
    </form>

    <script>
        // Enable time inputs when a weekday is selected and disable them when it is unselected
    </script>
@endsection