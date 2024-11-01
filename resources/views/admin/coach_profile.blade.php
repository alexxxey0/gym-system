@extends('layouts.admin')

@section('title', "Treneris $coach->name $coach->surname")

@section('content')
    <style>
        .coach_info > div {
            display: flex;
            flex-direction: column;
            
        }
    </style>

    <h1 class='font-bold text-center text-2xl mt-8'>Trenera personīgie dati</h1>

    <div class='coach_info flex flex-col my-16 text-lg w-fit gap-y-4 mx-auto'>
        <div>
            <h2 class='font-bold'>Trenera ID</h2>
            <h2>{{ $coach->coach_id }}</h2>
        </div>
        <div>
            <h2 class='font-bold'>Vārds</h2>
            <h2>{{ $coach->name }}</h2>
        </div>
        <div>
            <h2 class='font-bold'>Uzvārds</h2>
            <h2>{{ $coach->surname }}</h2>
        </div>
        <div>
            <h2 class="font-bold">Personas kods</h2>
            <h2>{{ $coach->personal_id }}</h2>
        </div>
        <div>
            <h2 class="font-bold">Telefona numurs</h2>
            <h2>{{ $coach->phone }}</h2>
        </div>
        <div>
            <h2 class="font-bold">E-pasts</h2>
            <h2>{{ $coach->email }}</h2>
        </div>
        <div>
            <h2 class="font-bold">Trenera reģistrēšanas datums</h2>
            <h2>{{ $coach->created_at }}</h2>
        </div>
        <div>
            <h2 class="font-bold">Trenera datu pēdējās rediģēšanas datums</h2>
            <h2>{{ $coach->updated_at }}</h2>
        </div>
    </div>

    <h1 class='font-bold text-center text-2xl mt-8'>Trenera publiskā profila dati</h1>

    <div class='coach_info flex flex-col my-16 text-lg w-fit gap-y-4 mx-auto'>
        <div>
            <h2 class="font-bold">Trenera apraksts</h2>
            <h2>{{ $coach->personal_description ?? 'Nav' }}</h2>
        </div>
        <div>
            <h2 class="font-bold">Trenera kontakttelefons</h2>
            <h2>{{ $coach->contact_phone ?? 'Nav' }}</h2>
        </div>
        <div>
            <h2 class="font-bold">Trenera kontakte-pasts</h2>
            <h2>{{ $coach->contact_email ?? 'Nav' }}</h2>
        </div>

        <div class='flex flex-col gap-y-8 mt-12 w-8/12 mx-auto'>
            <x-main_link href="{{ route('edit_coach_profile_page', ['coach_id' => $coach->coach_id]) }}" class='text-xl'>Rediģēt trenera personīgos datus</x-main_link>
            <x-main_link href="{{ route('edit_public_profile_admin_page', ['coach_id' => $coach->coach_id]) }}" class='text-xl'>Rediģēt trenera publiskā profila datus</x-main_link>
        </div>
    </div>
@endsection