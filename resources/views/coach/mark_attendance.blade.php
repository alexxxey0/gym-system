@extends('layouts.' . Auth::user()->role)

@section('title', 'Atzīmēt nodarbības apmeklējumu')

@section('content')

    <style>
        .clients_list {
            box-shadow: rgba(100, 100, 111, 0.2) 0px 7px 29px 0px;
        }
    </style>

    <h1 class='text-center font-bold text-3xl mt-8'>Nodarbības apmeklējums</h1>
    <div class='mx-auto text-xl w-fit mt-4'>
        <h2>Nodarbība: {{ $group_training->name }}</h2>
        <h2>Nodarbības datums: {{ $group_training_date }}</h2>
    </div>

    <form class='clients_list grid grid-cols-3 place-items-start mt-8 w-8/12 mx-auto p-4 rounded-lg' action="{{ route('save_attendance') }}" method="POST">
        @csrf
        <h2 class='text-lg'>Klients</h2>
        <h2 class='text-lg place-self-center'>Ieradās</h2>
        <h2 class='text-lg place-self-center'>Neieradās</h2>

        @php
            $counter = 1;
        @endphp
        @foreach($clients as $client)
            <div class='flex flex-row gap-x-2 text-lg'>
                <span class='font-bold'>{{ $counter }}.</span>
                <span>{{ $client->name }}  {{ $client->surname }}</span>
            </div>

            @php
                $client_attended = $clients_attendance[$client->client_id] ?? null;
            @endphp

            <input class='w-[20px] h-[20px] place-self-center' type='radio' name="attended_client_{{ $client->client_id }}" value='yes' id="" @if (!isset($client_attended) or (isset($client_attended) and $client_attended)) checked @endif>

            <input class='w-[20px] h-[20px] place-self-center' type="radio" name="attended_client_{{ $client->client_id }}" value='no' id="" @if (isset($client_attended) and !$client_attended) checked @endif>
            

            @php
                $counter++;
            @endphp
        @endforeach
        
        <input type="hidden" name="training_id" value="{{ $group_training->training_id }}">
        <input type="hidden" name="training_date" value="{{ $group_training_date }}">
        <x-main_button type='submit' class='w-1/4 mx-auto p-2 col-span-3 mt-8'>Saglabāt</x-main_button>

    </form>

@endsection