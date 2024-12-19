@extends('layouts.' . Auth::user()->role)

@section('title', 'Visas sporta zāles')

@section('content')

    <h1 class='font-bold text-3xl text-center mt-8'>Visas sporta zāles</h1>

    <div class='w-4/12 mx-auto flex flex-col gap-y-8 mt-8 mb-16'>
        @foreach($gyms as $gym)
            <div class='flex flex-col gap-y-2 border-[#007bff] border-2 rounded-lg shadow-md p-4'>
                <h2 class='font-bold text-2xl'>{{ $gym->name }}</h2>
                <p>{{ $gym->description }}</p>
                <p><span class='font-bold'>Adrese: </span><a href='https://maps.google.com/?q={{ $gym->address }}' class='hover:text-[#007bff]'>{{ $gym->address }}</a></p>
            </div>
        @endforeach
    </div>

@endsection