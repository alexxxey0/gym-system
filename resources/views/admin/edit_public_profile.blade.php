@extends('layouts.admin')

@section('title', "Trenera publiskā profila datu rediģēšana")

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

    <h1 class='font-bold text-center text-2xl mt-8'>Trenera {{ $coach->name }} {{ $coach->surname }} publiskā profila datu rediģēšana</h1>

    <div class='my-16'>
        <form class='coach_info flex flex-col text-lg w-fit gap-y-4 mx-auto' action="{{ route('edit_public_profile_admin') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div>
                <label for="personal_description">Personiskais apraksts</label>
                <textarea name="personal_description" id="personal_description" cols="60" rows="10" class='rounded-md text-lg' maxlength="2000"></textarea>
            </div>
            @if ($errors->has('personal_description'))
                <div class='bg-[#f54242] text-white rounded-md p-2 mt-2'>{{ $errors->first('personal_description') }}</div>
            @endif

            <div>
                <label for="contact_phone">Kontakttelefons</label>
                <input type="text" name='contact_phone' class='rounded-md' id='contact_phone' maxlength="20">
            </div>
            @if ($errors->has('contact_phone'))
                <div class='bg-[#f54242] text-white rounded-md p-2 mt-2'>{{ $errors->first('contact_phone') }}</div>
            @endif

            <div>
                <label for="contact_email">Kontakte-pasts</label>
                <input type="email" name="contact_email" class='rounded-md' id='contact_email' maxlength="50">
            </div>
            @if ($errors->has('contact_email'))
                <div class='bg-[#f54242] text-white rounded-md p-2 mt-2'>{{ $errors->first('contact_email') }}</div>
            @endif

            <label for="profile_picture">Profila attēls</label>
            <input type="file" name="profile_picture" id="profile_picture" accept='image/*'>

            @if ($errors->has('profile_picture'))
                <div class='bg-[#f54242] text-white rounded-md p-2 mt-2'>{{ $errors->first('profile_picture') }}</div>
            @endif

            <input type="hidden" name='coach_id' value="{{ $coach->coach_id }}">
            <button type="submit" class='mt-12 bg-[#007BFF] active:bg-[#0056b3] text-white p-4 mx-auto rounded-md text-center text-xl'>Saglabāt izmaiņas</button>
        </form>
    </div>

    <script>
        // Fill the inputs with coach's existing data
        const personal_description = document.querySelector('#personal_description');
        const contact_phone = document.querySelector('#contact_phone');
        const contact_email = document.querySelector('#contact_email');

        personal_description.value = "{{ $coach->personal_description ?? '' }}";
        contact_phone.value = "{{ $coach->contact_phone ?? '' }}";
        contact_email.value = "{{ $coach->contact_email ?? '' }}";
    </script>
@endsection