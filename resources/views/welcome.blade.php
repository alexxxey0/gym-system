<!-- resources/views/dashboard.blade.php -->

@extends('layouts.welcome')

@section('title', 'Welcome to FitLife')

@section('content')
    <style>
        footer {
            position: fixed;
            bottom: 0;
            width: 100%;
        }
    </style>
    <h1 class='text-[#007BFF] text-center text-6xl py-8'>Laipni lūdzam FitLife!</h1>
    <form class='flex flex-col w-10/12 md:w-1/3 mx-auto shadow-md rounded-md p-4 border-2 border-[#C0C0C0]' action="">
        <div id='personal_id_field' class='flex flex-col'>
            <label for="personal-id">Personas kods</label>
            <input class='border-2 border-gray-300 rounded-sm' type="text" name='personal_id' id='personal_id'>
        </div>

        <div id='personal_id_field' class='flex flex-col'>
            <label for="password">Parole</label>
            <input class='border-2 border-gray-300 rounded-sm' type="password" name='password' id='password'>
        </div>

        <div class='flex flex-row gap-x-4 items-center mt-4'>
            <input type="radio" name="role" id="client">
            <label for="client">Klients</label>
        </div>
        
        <div class='flex flex-row gap-x-4 items-center'>
            <input type="radio" name="role" id="coach">
            <label for="coach">Treneris</label>
        </div>
        
        <div class='flex flex-row gap-x-4 items-center'>
            <input type="radio" name="role" id="admin">
            <label for="admin">Administrators</label>
        </div>

        <button class='mt-4 bg-[#007BFF] active:bg-[#0056b3] text-white py-2 w-1/2 md:w-1/4 mx-auto rounded-md' type="submit">Pieslēgties</button>
    </form>
    <h2 class='text-gray-300 text-center'></h2>

    <script>
        const role_selection_buttons = document.querySelectorAll('input[type="radio"]');
        const personal_id_field = document.querySelector('#personal_id_field');

        // Hide the personal id field when logging in as admin
        for (let i = 0; i < role_selection_buttons.length; i++) {
            role_selection_buttons[i].addEventListener('click', function() {
                if (this.id === 'admin') personal_id_field.classList.add('hidden');
                else personal_id_field.classList.remove('hidden');
            });
        }
    </script>
@endsection
