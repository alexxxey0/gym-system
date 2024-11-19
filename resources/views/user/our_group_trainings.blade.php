@extends('layouts.' . Auth::user()->role)

@section('title', Auth::user()->role === 'client' ? 'Mūsu grupu nodarbības' : 'Visas grupu nodarbības')

@section('content')


    @if (Auth::user()->role === 'client')
        <h1 class='text-center text-3xl font-bold mt-8'>Mūsu grupu nodarbības</h1>

        <div class='w-1/2 mx-auto text-lg mt-6'>
            <p class=''>Šeit jūs varat apskatīt sarakstu ar visām mūsu grupu nodarbībām un pieteikties nodarbībām, kas jūs interesē. Atteikties no nodarbības jūs varat sadaļā <a class='underline hover:text-blue-900' href="{{ route('my_group_trainings_client') }}">"Manas grupu nodarbības"</a>.</p>

            @if (!$group_trainings_included)
                <p class=''>Pašlaik jūs nevarat pieteikties grupu nodarbībām, jo jūsu abonementā tās nav iekļautas. Dodieties <a href="">šeit</a>, lai apskatītu pieejamus abonementus.</p>
            @endif
        </div>
    @else
        <h1 class='text-center text-3xl font-bold mt-8'>Visas grupu nodarbības</h1>
    @endif

    @if (count($group_trainings) === 0)
        <style>
            html, body {
                height: 100%;
            }
        </style>
        <h1 class='text-center text-3xl font-bold mt-8'>Nav neviena nodarbības veida</h1>
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
                            @if (Auth::user()->role === 'client')
                                @if ($group_trainings_included)
                                    @if (!$group_training->client_signed_up)
                                        <form action='{{ route('sign_up_for_group_training') }}' method="POST">
                                            @csrf
                                            <input type="hidden" name="training_id" value="{{ $group_training->training_id }}">
                                            <x-main_button type='submit' class='w-1/3 mr-auto'>Pieteikties</x-main_button>
                                        </form>
                                    @endif
                                @else
                                    <x-main_button class='w-1/2 mr-auto bg-gray-400 active:bg-gray-500 cursor-not-allowed' disabled>Grupu nodarbības nav iekļautas jūsu abonementā</x-main_button>
                                @endif
                            @endif
                        @else
                            <h2 class='text-red-600'><span class='font-bold text-black'>Pieteikušies: </span>{{ $group_training->clients_signed_up }} / {{ $group_training->max_clients}}</h2>
                        @endif
                        
                        @if ($group_training->client_signed_up)
                            <x-main_button class='bg-gray-400 active:bg-gray-500 w-1/2 mr-auto p-4' disabled>Jūs esat pieteicies šai nodarbībai</x-main_button>
                        @endif

                        @if (Auth::user()->role === 'admin')
                            <x-main_link class='w-1/2 mr-auto' href="{{ route('edit_group_training_page', ['training_id' => $group_training->training_id]) }}">Rediģēt nodarbības informāciju</x-main_link>
                            <form action="{{ route('cancel_group_training_type') }}" method="POST" onsubmit="return confirm_training_deletion(this);">
                                @csrf
                                <input type="hidden" name="training_id" value="{{ $group_training->training_id }}">
                                <input type="hidden" name="training_name" value="{{ $group_training->name }}">
                                <x-main_button type='submit' class='bg-red-500 active:bg-red-700 w-1/2 mr-auto'>Atcelt nodarbības veidu</x-main_button>
                            </form>
                            @if ($group_training->clients_signed_up > 0)
                                <x-main_link class='w-1/2 mr-auto' href="{{ route('send_notification_page', ['training_id' => $group_training->training_id]) }}">Nosūtīt paziņojumu nodarbības apmeklētājiem</x-main_link>
                            @endif
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