@extends('layouts.' . Auth::user()->role)

@section('title', 'Manas grupu nodarbīas')

@section('content')

    <h1 class='text-center text-3xl font-bold mt-8'>Manas grupu nodarbības</h1>

    @if (count($group_trainings) === 0)
        <style>
            html, body {
                height: 100%;
            }
        </style>
        <h1 class='text-center text-3xl font-bold mt-8'>Jums nav neviena nodarbības veida</h1>
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
                        <x-main_link class='w-1/2 mr-auto' href="{{ route('edit_group_training_page', ['training_id' => $group_training->training_id]) }}">Rediģēt nodarbības informāciju</x-main_link>
                        <form action="{{ route('cancel_group_training') }}" method="POST" onsubmit="return confirm_training_deletion(this);">
                            @csrf
                            <input type="hidden" name="training_id" value="{{ $group_training->training_id }}">
                            <input type="hidden" name="training_name" value="{{ $group_training->name }}">
                            <x-main_button type='submit' class='bg-red-500 active:bg-red-700 w-1/2 mr-auto'>Atcelt nodarbības veidu</x-main_button>
                        </form>
                        @if ($group_training->clients_signed_up > 0)
                            <x-main_link class='w-1/2 mr-auto' href="{{ route('send_notification_page', ['training_id' => $group_training->training_id]) }}">Nosūtīt paziņojumu nodarbības apmeklētājiem</x-main_link>
                        @endif
                    </div>
                </div>
                <hr class='w-10/12 mx-auto'>
            @endforeach
        </div>

        <script>
            function confirm_training_deletion(form) {
                let form_data = new FormData(form);

                const confirm_message = `Vai tiešām gribat atcelt nodarbības veidu ${form_data.get('training_name')}?`;
                return confirm(confirm_message);
            }
        </script>
    @endif
@endsection