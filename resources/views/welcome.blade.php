<!-- resources/views/dashboard.blade.php -->

@extends('layouts.welcome')

@section('title', 'Laipni lūdzam Fitlife!')

@section('content')
    <h1 class='text-[#007BFF] text-center text-6xl py-8'>Laipni lūdzam FitLife!</h1>
    <form class='flex flex-col w-10/12 md:w-1/3 mx-auto shadow-md rounded-md p-4 border-2 border-[#C0C0C0]' action="{{ route('login_post') }}" method="POST">
        @csrf
        <div id='personal_id_field' class='flex flex-col'>
            <label for="personal-id">Personas kods</label>
            <input class='border-2 border-gray-300 rounded-sm' type="text" name='personal_id' id='personal_id' placeholder="000000-00000">
        </div>

        <div id='personal_id_field' class='flex flex-col'>
            <label for="password">Parole</label>
            <input class='border-2 border-gray-300 rounded-sm' type="password" name='password' id='password' required>
        </div>

        @if ($errors->has('password'))
            <div class='bg-[#f54242] text-white rounded-md p-2 mt-2'>{{ $errors->first('password') }}</div>
        @endif

        <div class='flex flex-row gap-x-4 items-center mt-4'>
            <input type="radio" name="role" id="client" value='client'>
            <label for="client">Klients</label>
        </div>
        
        <div class='flex flex-row gap-x-4 items-center'>
            <input type="radio" name="role" id="coach" value='coach'>
            <label for="coach">Treneris</label>
        </div>
        
        <div class='flex flex-row gap-x-4 items-center'>
            <input type="radio" name="role" id="admin" value='admin'>
            <label for="admin">Administrators</label>
        </div>

        <button disabled class='login_button cursor-not-allowed mt-4 bg-[#007BFF] active:bg-[#0056b3] text-white py-2 w-1/2 md:w-1/4 mx-auto rounded-md' type="submit">Pieslēgties</button>
    </form>
    <h2 class='text-gray-300 text-center'></h2>

    <script>
        const role_selection_buttons = document.querySelectorAll('input[type="radio"]');
        const personal_id_field = document.querySelector('#personal_id_field');
        const personal_id_input = document.querySelector('#personal_id');
        const login_button = document.querySelector('.login_button');

        // Hide the personal id field when logging in as admin
        for (let i = 0; i < role_selection_buttons.length; i++) {
            role_selection_buttons[i].addEventListener('click', function() {
                if (this.id === 'admin') {
                    personal_id_field.classList.add('hidden');
                    personal_id_input.required = false;
                } else {
                    personal_id_field.classList.remove('hidden');
                    personal_id_input.required = true;
                }

                // Enable the login button after a role has been selected
                login_button.disabled = false;
                login_button.classList.remove('cursor-not-allowed');

            });
        }

    </script>

    @if (session('role'))
        <script>
            window.onload = function() {
                const selected_role_button = document.querySelector('#{{ session("role") }}');
                const login_button = document.querySelector('.login_button');
                selected_role_button.click();

                login_button.disabled = false;
                login_button.classList.remove('cursor-not-allowed');
            }
        </script>
    @endif
@endsection
