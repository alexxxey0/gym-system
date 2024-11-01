@extends('layouts.' . Auth::user()->role)

@section('title', "Mans profils")

@section('content')
    <style>
        .user_info > div {
            display: flex;
            flex-direction: column;
        }
    </style>

    <div class='my-12'>
        <div class='user_info flex flex-col text-lg w-fit gap-y-4 mx-auto'>
            <h1 class='font-bold text-center text-2xl mt-8'>Mans profils</h1>
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
        

            @if ($user->role === 'coach')
                <h1 class='font-bold text-center text-2xl mt-16'>Mana publiskā profila dati</h1>

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
                <div>
                    <h2 class='font-bold'>Mans profila attēls</h2>
                    @if (isset(Auth::user()->path_to_image))
                        <img class='mt-4 max-w-xs border-8 border-black rounded-md' src="{{ asset('storage/' . Auth::user()->path_to_image) }}" alt="Mans profila attēls">
                    @else
                        <h2>Nav</h2>
                    @endif
                </div>
            @endif
        </div>

        <div class='flex flex-col w-1/3 mx-auto'>
            <a href="{{ route('change_password_page') }}" class='mt-12 bg-[#007BFF] active:bg-[#0056b3] text-white p-4 rounded-md text-center text-xl'>Mainīt paroli</a>

            @if (Auth::user()->role === 'coach')
                <a href="{{ route('edit_public_profile_coach_page') }}" class='mt-8 bg-[#007BFF] active:bg-[#0056b3] text-white p-4 rounded-md text-center text-xl'>Rediģēt mana publiskā profila datus</a>
            @endif
        </div>

    </div>

    <script>
    </script>
@endsection