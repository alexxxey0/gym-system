@extends('layouts.' . Auth::user()->role)

@section('title', 'Manas grupu nodarbības')

@section('content')

    <h1 class='text-center text-3xl font-bold mt-8'>Manas grupu nodarbības</h1>

    @if (count($group_trainings) === 0)
        <style>
            html, body {
                height: 100%;
            }
        </style>
        <h1 class='text-center text-3xl font-bold mt-8'>Jūs neesat pieteicies nevienai nodarbībai</h1>
    @else

        <div class='flex flex-col mt-12 mb-12 gap-y-12'>
            @foreach($group_trainings as $group_training)
                <div class='flex flex-row w-8/12 mx-auto gap-x-8 items-start'>
                    @if (isset($group_training->path_to_image))
                        <img class='max-w-[40%] border-4 border-black rounded-sm' src="{{ asset('storage/' . $group_training->path_to_image) }}" alt="">
                    @else
                        <img class='max-w-[40%] border-4 border-black rounded-sm' src="{{ asset('storage/group_trainings_pictures/group_training_default_picture.jpg') }}" alt="">
                    @endif

                    <div class='flex flex-col gap-y-4 w-full'>
                        <h2 class='font-bold text-2xl'>{{ $group_training->name }}</h2>
                        <p>{{ $group_training->description }}</p>
                        <h2 class='text-lg'><span class='font-bold'>Treneris: </span>{{ $group_training->coach->name }} {{ $group_training->coach->surname }}</h2>
                        
                        <ul class='list-none'>
                            @foreach($group_training->schedule as $day => $times)
                                <li><span class='font-bold'>{{ $days_translations[$day] }}: </span>{{ $times['start'] }}-{{ $times['end'] }}</li>
                            @endforeach
                        </ul>

                        @if ($group_training->clients_signed_up < $group_training->max_clients)
                            <h2 class='text-green-600'><span class='font-bold text-black'>Pieteikušies: </span>{{ $group_training->clients_signed_up }} / {{ $group_training->max_clients}}</h2>
                        @else
                            <h2 class='text-red-600'><span class='font-bold text-black'>Pieteikušies: </span>{{ $group_training->clients_signed_up }} / {{ $group_training->max_clients}}</h2>
                        @endif

                        @if (Auth::user()->role === 'client')
                            <form action='{{ route('quit_group_training') }}' method="POST" onsubmit="return confirm_quitting_training(this);">
                                @csrf
                                <input type="hidden" name="training_id" value="{{ $group_training->training_id }}">
                                <input type="hidden" name="training_name" value="{{ $group_training->name }}">
                                <x-main_button type='submit' class='w-1/3 mr-auto bg-red-500 active:bg-red-700'>Atteikties</x-main_button>
                            </form>
                        @endif

                    </div>
                </div>
                <hr class='w-10/12 mx-auto'>
            @endforeach
        </div>
    @endif

    <script>
        function confirm_quitting_training(form) {
            let form_data = new FormData(form);
            const confirm_message = `Vai tiešām gribāt atteikties no grupu nodarbības ${form_data.get('training_name')}?`;

            return confirm(confirm_message);
        }
    </script>
@endsection