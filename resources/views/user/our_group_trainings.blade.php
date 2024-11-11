@extends('layouts.' . Auth::user()->role)

@section('title', Auth::user()->role === 'client' ? 'Mūsu grupu nodarbības' : 'Visas grupu nodarbības')

@section('content')

    @if (Auth::user()->role === 'client')
        <h1 class='text-center text-3xl font-bold mt-8'>Mūsu grupu nodarbības</h1>
    @else
        <h1 class='text-center text-3xl font-bold mt-8'>Visas grupu nodarbības</h1>
    @endif

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
                            <x-main_button class='w-1/3 mr-auto'>Pieteikties</x-main_button>
                        @endif
                    @else
                        <h2 class='text-red-600'><span class='font-bold text-black'>Pieteikušies: </span>{{ $group_training->clients_signed_up }} / {{ $group_training->max_clients}}</h2>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
@endsection