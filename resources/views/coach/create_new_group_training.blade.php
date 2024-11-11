@extends('layouts.' . Auth::user()->role)

@section('title', 'Jauna grupu nodarbības veida izveidošana')

@section('content')
    <h1 class='mt-8 font-bold text-center text-3xl'>Jauna grupu nodarbības veida izveidošana</h1>

    <form action="{{ route('create_new_group_training') }}" method="POST" class='flex flex-col gap-y-4 w-6/12 mt-12 mb-8 mx-auto' enctype="multipart/form-data">
        @csrf

        <div class='flex flex-col'>
            <label class='text-lg' for="title">Nodarbības nosaukums</label>
            <input type="text" name='title' class='rounded-md' maxlength="50" required value="{{ old('title') }}">
            @if ($errors->has('title'))
                <div class='bg-[#f54242] text-white rounded-md p-2 mt-2'>{{ $errors->first('title') }}</div>
            @endif
        </div>

        <div class="flex flex-col">
            <label class='text-lg' for="description">Nodarbības apraksts</label>
            <textarea name="description" cols="30" rows="5" required class='rounded-md'>{{ old('description') }}</textarea>
            @if ($errors->has('description'))
                <div class='bg-[#f54242] text-white rounded-md p-2 mt-2'>{{ $errors->first('description') }}</div>
            @endif
        </div>

        <div class='flex flex-col'>
            <label class='text-lg' for="image">Nodarbības ilustratīvs attēls</label>
            <input type="file" name="image" accept='image/*' value="{{ old('image') }}">
            @if ($errors->has('image'))
                <div class='bg-[#f54242] text-white rounded-md p-2 mt-2'>{{ $errors->first('image') }}</div>
            @endif
        </div>

        <div>
            @php
                $days_eng = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
                $days_lv = ['Pirmdiena', 'Otrdiena', 'Trešdiena', 'Ceturtdiena', 'Piektdiena', 'Sestdiena', 'Svētdiena'];
            @endphp

            <h2 class='mb-2 text-lg'>Nodarbības grafiks</h2>

            <p>
                Šeit jūs varat izveidot grupu nodarbības veida grafiku pa dienām. Atzīmējiet, kurās dienās notiks nodarbības, un tad katrai dienai norādiet nodarbības sākuma un beigu laikus. Ņemiet vērā, ka grafikam ir jāatbilst šiem noteikumiem:
                <br>
            </p>
            <ul class="list-disc mt-4 mb-4">
                <li>Nodarbības beigu laiks nevar būt vienāds vai agrāks par sākuma laiku</li>
                <li>Nodarbības ilgums ir vismaz 30 minūtes</li>
                <li>Nodarbības ilgums nepārsniedz 120 minūtes</li>
                <li>Nodarbībai jānotiek sporta zāles darba laikā (darba dienās 08:00-22:00, brīvdienās 09:00-20:00)</li>
            </ul>

            @for ($i = 0; $i < count($days_lv); $i++)
                <div class='flex flex-row items-center justify-between gap-x-2 text-lg'>
                    <div class='flex flex-row items-center gap-x-4'>
                        <input type="checkbox" name="{{ $days_eng[$i] }}" id="" data-day="{{ $days_eng[$i] }}" class="day_checkbox" @if(old($days_eng[$i])) checked @endif>
                        <label for="{{ $days_eng[$i] }}">{{ $days_lv[$i] }}</label>
                    </div>

                    <div class='flex flex-row gap-x-4'>
                        <div class='flex flex-col'>
                            <label for="start_time_{{ $days_eng[$i] }}">Nodarbības sākums:</label>
                            <input type="time" name="start_time_{{ $days_eng[$i] }}" id="" @if (!old($days_eng[$i])) disabled @endif class='start_time_{{ $days_eng[$i] }} rounded-md @if (!old($days_eng[$i])) cursor-not-allowed @endif' value="{{ old('start_time_' . $days_eng[$i]) }}">
                        </div>

                        <div class='flex flex-col'>
                            <label for="end_time_{{ $days_eng[$i] }}">Nodarbības beigas:</label>
                            <input type="time" name="end_time_{{ $days_eng[$i] }}" id="" @if (!old($days_eng[$i])) disabled @endif class='end_time_{{ $days_eng[$i] }} rounded-md @if (!old($days_eng[$i])) cursor-not-allowed @endif' value="{{ old('end_time_' . $days_eng[$i]) }}">
                        </div>
                    </div>
                </div>
            @endfor
        </div>
        @if ($errors->has('schedule'))
            <div class='bg-[#f54242] text-white rounded-md p-2 mt-2'>{!! nl2br($errors->first('schedule')) !!}</div>
        @endif

        <div class="flex flex-col">
            <label class='text-lg' for="max_participants">Maksimālais apmeklētāju skaits (10-50)</label>
            <input class='w-fit' type="number" name="max_participants" min="10" max="50" required value="{{ old('max_participants') }}">
        </div>
        @if ($errors->has('max_participants'))
            <div class='bg-[#f54242] text-white rounded-md p-2 mt-2'>{{ $errors->first('max_participants') }}</div>
        @endif

        @if (Auth::user()->role === 'coach')
            <input type="hidden" name="coach_id" value="{{ Auth::user()->coach_id }}">
        @else
            <label class='text-lg' for="coach_id">Treneris</label>
            <h2>Izvēlieties treneri, kas vadīs nodarbības.</h2>
            <select name="coach_id" required class='w-fit'>
                @foreach ($coaches as $coach)
                    <option value="{{ $coach->coach_id }}">{{ $coach->name }}  {{ $coach->surname }} (p.k. {{ $coach->personal_id }})</option>
                @endforeach
            </select>
        @endif
        <x-main_button type='submit' class='mt-8 text-lg w-1/2 mx-auto p-4'>Izveidot jaunu grupu nodarbības veidu</x-main_button>
    </form>

    <script>
        // Enable time inputs when a weekday is selected and disable them when it is unselected
        const day_checkboxes = document.querySelectorAll('.day_checkbox');

        for (let i = 0; i < day_checkboxes.length; i++) {

            day_checkboxes[i].addEventListener('change', function() {
                const day = this.dataset.day;
                const time_inputs = document.querySelectorAll('.start_time_' + day + ', .end_time_' + day);

                if (this.checked) {
                    for (let j = 0; j < time_inputs.length; j++) {
                        time_inputs[j].disabled = false;
                        time_inputs[j].classList.remove('cursor-not-allowed');
                    }
                } else {
                    for (let j = 0; j < time_inputs.length; j++) {
                        time_inputs[j].disabled = true;
                        time_inputs[j].classList.add('cursor-not-allowed');
                        time_inputs[j].value = "";
                    }
                }
            });
        }

    </script>
@endsection