@extends('layouts.admin')

@section('title', "Klients $client->name $client->surname")

@section('content')
    <style>
        .client_info > div {
            display: flex;
            flex-direction: column;
            
        }
    </style>

    <h1 class='font-bold text-center text-2xl mt-8'>Klienta profils</h1>

    <div class='client_info flex flex-col my-16 text-lg w-fit gap-y-4 mx-auto'>
        <div>
            <h2 class='font-bold'>Klienta ID</h2>
            <h2>{{ $client->client_id }}</h2>
        </div>
        <div>
            <h2 class='font-bold'>Vārds</h2>
            <h2>{{ $client->name }}</h2>
        </div>
        <div>
            <h2 class='font-bold'>Uzvārds</h2>
            <h2>{{ $client->surname }}</h2>
        </div>
        <div>
            <h2 class="font-bold">Personas kods</h2>
            <h2>{{ $client->personal_id }}</h2>
        </div>
        <div>
            <h2 class="font-bold">Telefona numurs</h2>
            <h2>{{ $client->phone }}</h2>
        </div>
        <div>
            <h2 class="font-bold">E-pasts</h2>
            <h2>{{ $client->email }}</h2>
        </div>
        <div>
            <h2 class="font-bold">Abonementa veids</h2>
            <h2>{{ $client->membership_name ?? 'Nav' }}</h2>
        </div>
        <div>
            <h2 class="font-bold">Abonements derīgs līdz:</h2>
            <h2>{{ $client->membership_until ?? 'Nav' }}</h2>
        </div>
        <div>
            <h2 class="font-bold">Klienta reģistrēšanas datums</h2>
            <h2>{{ $client->created_at }}</h2>
        </div>
        <div>
            <h2 class="font-bold">Klienta datu pēdējās rediģēšanas datums</h2>
            <h2>{{ $client->updated_at }}</h2>
        </div>

        <x-main_link href="{{ route('edit_client_profile_page', ['client_id' => $client->client_id]) }}" class='mt-8 text-xl w-8/12 mx-auto'>Rediģēt klienta datus</x-main_link>
    </div>
@endsection