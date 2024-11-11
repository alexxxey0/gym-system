@extends('layouts.' . Auth::user()->role)

@section('title', 'Manas grupu nodarbīas')

@section('content')

    <h1 class='text-center text-3xl font-bold mt-8'>Manas grupu nodarbības</h1>

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
                        @if (Auth::user()->role === 'client')
                            <x-main_button class='w-1/3 mr-auto'>Pieteikties</x-main_button>
                        @endif
                    @else
                        <h2 class='text-red-600'><span class='font-bold text-black'>Pieteikušies: </span>{{ $group_training->clients_signed_up }} / {{ $group_training->max_clients}}</h2>
                    @endif
                    <x-main_link class='w-1/2 mr-auto' href="{{ route('edit_group_training_page', ['training_id' => $group_training->training_id]) }}">Rediģēt nodarbības informāciju</x-main_link>
                    <x-main_button class='bg-red-500 w-1/2 mr-auto'>Atcelt nodarbības veidu</x-main_button>
                </div>
            </div>
            <hr class='w-10/12 mx-auto'>
        @endforeach
    </div>
@endsection