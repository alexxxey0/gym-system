@extends('layouts.' . Auth::user()->role)

@section('title', "Mans profils")

@section('content')
    <style>
        .user_info > div {
            display: flex;
            flex-direction: column;
            
        }
    </style>

    <h1 class='font-bold text-center text-2xl mt-8'>Mans profils</h1>
    <div class='my-16'>
        <div class='user_info flex flex-col text-lg w-fit gap-y-4 mx-auto'>
            <div>
                <h2 class='font-bold'>{{ $user->role === 'client' ? 'Mans klienta ID' : 'Mans trenera ID' }}</h2>
                <h2>{{ $user->role === 'client' ? $user->client_id : $user->coach_id }}</h2>
            </div>
            <div>
                <h2 class='font-bold'>Vārds</h2>
                <h2>{{ $user->name }}</h2>
            </div>
            <div>
                <h2 class='font-bold'>Uzvārds</h2>
                <h2>{{ $user->surname }}</h2>
            </div>
            <div>
                <h2 class="font-bold">Personas kods</h2>
                <h2>{{ $user->personal_id }}</h2>
            </div>
            <div>
                <h2 class="font-bold">Telefona numurs</h2>
                <h2>{{ $user->phone }}</h2>
            </div>
            <div>
                <h2 class="font-bold">E-pasts</h2>
                <h2>{{ $user->email }}</h2>
            </div>
            @if ($user->role === 'client')
                <div>
                    <h2 class="font-bold">Abonementa veids</h2>
                    <h2>{{ $user->membership_name ?? 'Nav' }}</h2>
                </div>
                <div>
                    <h2 class="font-bold">Abonements derīgs līdz:</h2>
                    <h2>{{ $user->membership_until ?? 'Nav' }}</h2>
                </div>
            @endif
            <div>
                <h2 class="font-bold">Mans reģistrēšanas datums</h2>
                <h2>{{ $user->created_at }}</h2>
            </div>
        </div>

        @if ($user->role === 'coach')
            <h1 class='font-bold text-center text-2xl mt-8'>Mana publiskā profila dati</h1>

            <div class='coach_info flex flex-col my-16 text-lg w-fit gap-y-4 mx-auto'>
                <div>
                    <h2 class="font-bold">Mans apraksts</h2>
                    <h2>{{ $user->personal_description ?? 'Nav' }}</h2>
                </div>
                <div>
                    <h2 class="font-bold">Mans kontakttelefons</h2>
                    <h2>{{ $user->contact_phone ?? 'Nav' }}</h2>
                </div>
                <div>
                    <h2 class="font-bold">Mans kontakte-pasts</h2>
                    <h2>{{ $user->contact_email ?? 'Nav' }}</h2>
                </div>
            </div>
        @endif

        <div class='flex flex-col'>
            <a href="{{ route('change_password_page') }}" class='mt-12 bg-[#007BFF] active:bg-[#0056b3] text-white py-2 w-1/2 md:w-1/6 mx-auto rounded-md text-center text-xl'>Mainīt paroli</a>
        </div>
    </div>

    <script>
        
    </script>
@endsection