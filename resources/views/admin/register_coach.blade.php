@extends('layouts.admin')

@section('title', 'Jauna trenera reģistrācija')

@section('content')
    <h1 class='font-bold text-center mt-4 text-2xl'>Jauna trenera reģistrācija</h1>

    <!-- New coach registration form -->

    <form action="{{ route('register_coach_post') }}" method='POST' class='w-1/3 mx-auto flex flex-col gap-y-6 mt-6 mb-16' onsubmit="return show_registration_confirmation(this);">
        @csrf
        <div class='flex flex-col'>
            <label for="name">Vārds</label>
            <input type="text" required maxlength="30" class='rounded-md' name='name' value="{{ old('name') }}">
        </div>

        @if ($errors->has('name'))
            <div class='bg-[#f54242] text-white rounded-md p-2 mt-2'>{{ $errors->first('name') }}</div>
        @endif

        <div class='flex flex-col'>
            <label for="surname">Uzvārds</label>
            <input type="text" required maxlength="30" class='rounded-md' name='surname' value="{{ old('surname') }}">
        </div>

        @if ($errors->has('surname'))
            <div class='bg-[#f54242] text-white rounded-md p-2 mt-2'>{{ $errors->first('surname') }}</div>
        @endif

        <div class='flex flex-col'>
            <label for="personal_id">Personas kods</label>
            <input type="text" placeholder="123456-12345" required maxlength="12" class='rounded-md' name='personal_id' value="{{ old('personal_id') }}">
        </div>

        @if ($errors->has('personal_id'))
            <div class='bg-[#f54242] text-white rounded-md p-2 mt-2'>{{ $errors->first('personal_id') }}</div>
        @endif

        <div class='flex flex-col'>
            <label for="phone">Telefona numurs</label>
            <input type="text" required maxlength="20" class='rounded-md' name='phone' value="{{ old('phone') }}">
        </div>

        @if ($errors->has('phone'))
            <div class='bg-[#f54242] text-white rounded-md p-2 mt-2'>{{ $errors->first('phone') }}</div>
        @endif

        <div class='flex flex-col'>
            <label for="email">E-pasts</label>
            <input type="email" name="email" required maxlength="50" class='rounded-md' name='email' value="{{ old('email') }}">
        </div>

        @if ($errors->has('email'))
            <div class='bg-[#f54242] text-white rounded-md p-2 mt-2'>{{ $errors->first('email') }}</div>
        @endif

        <button type="submit" class='bg-[#007BFF] active:bg-[#0056b3] w-fit mx-auto py-2 px-6 text-white rounded-md text-xl font-bold'>Reģistrēt</button>
    </form>

    
    <script>
        function show_registration_confirmation(form) {
            var form_data = new FormData(form);
            var confirm_message = `
Jūs gribat reģistrēt jaunu treneri ar datiem:
Vārds: ${form_data.get('name')}
Uzvārds: ${form_data.get('surname')}
Personas kods: ${form_data.get('personal_id')}
Telefona numurs: ${form_data.get('phone')}
E-pasts: ${form_data.get('email')}

Nospiežot "Apstiprināt", jūs apstiprināt, ka trenera dati ir patiesi.`;

            return confirm(confirm_message);
        }
    </script>

@endsection