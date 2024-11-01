@extends('layouts.admin')

@section('title', "Trenera datu rediģēšana")

@section('content')
    <style>
        .coach_info > div {
            display: flex;
            flex-direction: column;
            
        }
    </style>

    <div class='ml-8 mt-12'>
        <x-secondary_link class='' href="{{ route('view_coach_profile', ['coach_id' => $coach->coach_id]) }}">← Atpakaļ uz trenera profilu</x-secondary_link>
    </div>

    <h1 class='font-bold text-center text-2xl mt-8'>Trenera datu rediģēšana</h1>

    <form action="{{ route('edit_coach_profile') }}" method="POST" class='coach_info flex flex-col my-16 text-lg w-1/3 gap-y-4 mx-auto'>
        @csrf
        <div>
            <h2 class='font-bold'>Vārds</h2>
            <input type="text" name='name' maxlength="30" value="{{ old('name') ?? $coach->name }}" class='rounded-md' required>
        </div>
        @if ($errors->has('name'))
            <div class='bg-[#f54242] text-white rounded-md p-2 mt-2'>{{ $errors->first('name') }}</div>
        @endif

        <div>
            <h2 class='font-bold'>Uzvārds</h2>
            <input type="text" name='surname' maxlength="30" value="{{ old('surname') ?? $coach->surname }}" class='rounded-md' required>
        </div>
        @if ($errors->has('surname'))
            <div class='bg-[#f54242] text-white rounded-md p-2 mt-2'>{{ $errors->first('surname') }}</div>
        @endif

        <div>
            <h2 class="font-bold">Personas kods</h2>
            <input type="text" name='personal_id' maxlength="12" value="{{ old('personal_id') ?? $coach->personal_id }}" class='rounded-md' required>
        </div>
        @if ($errors->has('personal_id'))
            <div class='bg-[#f54242] text-white rounded-md p-2 mt-2'>{{ $errors->first('personal_id') }}</div>
        @endif

        <div>
            <h2 class="font-bold">Telefona numurs</h2>
            <input type="text" name='phone' maxlength="20" value="{{ old('phone') ?? $coach->phone }}" class='rounded-md' required>
        </div>
        @if ($errors->has('phone'))
            <div class='bg-[#f54242] text-white rounded-md p-2 mt-2'>{{ $errors->first('phone') }}</div>
        @endif

        <div>
            <h2 class="font-bold">E-pasts</h2>
            <input type="email" name='email' maxlength="50" value="{{ old('email') ?? $coach->email }}" class='rounded-md' required>
        </div>
        @if ($errors->has('email'))
            <div class='bg-[#f54242] text-white rounded-md p-2 mt-2'>{{ $errors->first('email') }}</div>
        @endif

        <input type="hidden" name="coach_id" value="{{ $coach->coach_id }}">
        <button type="submit" class='mt-12 bg-[#007BFF] active:bg-[#0056b3] text-white p-4 mx-auto rounded-md text-center text-xl'>Saglabāt izmaiņas</button>
    </form>

    <script>
    </script>
@endsection