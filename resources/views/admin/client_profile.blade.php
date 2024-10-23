@extends('layouts.admin')

@section('title', "Klients $client->name $client->surname")

@section('content')
    <style>
        html, body {
            height: 100%;
        }
        
        .client_info {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
        }
    </style>

    <h1 class='font-bold text-center text-2xl mt-8'>Klienta profils</h1>

    <div class='client_info grid grid-cols-2 mx-auto mt-6 text-lg w-fit gap-x-48 gap-y-2'>
        <h2 class='font-bold'>Klienta ID</h2>
        <h2>{{ $client->client_id }}</h2>
        <h2 class='font-bold'>Vārds</h2>
        <h2>{{ $client->name }}</h2>
        <h2 class='font-bold'>Uzvārds</h2>
        <h2>{{ $client->surname }}</h2>
        <h2 class="font-bold">Personas kods</h2>
        <h2>{{ $client->personal_id }}</h2>
        <h2 class="font-bold">Telefona numurs</h2>
        <h2>{{ $client->phone }}</h2>
        <h2 class="font-bold">E-pasts</h2>
        <h2>{{ $client->email }}</h2>
        <h2 class="font-bold">Abonementa veids</h2>
        <h2>{{ $client->membership_name }}</h2>
        <h2 class="font-bold">Abonements derīgs līdz:</h2>
        <h2>{{ $client->membership_until }}</h2>
        <h2 class="font-bold">Klienta reģistrēšanas datums</h2>
        <h2>{{ $client->created_at }}</h2>
        <h2 class="font-bold">Klienta datu pēdējās rediģēšanas datums</h2>
        <h2>{{ $client->updated_at }}</h2>
    </div>
@endsection