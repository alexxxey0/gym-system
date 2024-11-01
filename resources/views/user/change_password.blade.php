@extends('layouts.' . Auth::user()->role)

@section('title', "Mainīt paroli")

@section('content')
    <style>
        html, body {
            height: 100%;
        }
    </style>

    <div class='ml-8 mt-12'>
        <x-secondary_link class='' href="{{ route('user_profile_page') }}">← Atpakaļ uz manu profilu</x-secondary_link>
    </div>
    
    <h1 class='font-bold text-2xl text-center mt-8'>Paroles mainīšana</h1>

    <form action="{{ route('change_password') }}" method="POST" class='flex flex-col gap-y-4 w-1/5 mx-auto mt-12'>
        @csrf
        <div class='flex flex-col gap-y-2'>
            <label for="old_password">Pašreizējā parole</label>
            <input type="password" name='old_password' class='rounded-md' required value="{{ old('old_password') }}">
        </div>
        @if ($errors->has('old_password'))
            <div class='bg-[#f54242] text-white rounded-md p-2 mt-2'>{{ $errors->first('old_password') }}</div>
        @endif

        <div class='flex flex-col gap-y-2'>
            <label for="new_password">Jauna parole</label>
            <input type="password" name='new_password' class='rounded-md' required value="{{ old('new_password') }}">
        </div>
        @if ($errors->has('new_password'))
            <div class='bg-[#f54242] text-white rounded-md p-2 mt-2'>{{ $errors->first('new_password') }}</div>
        @endif

        <div class='flex flex-col gap-y-2'>
            <label for="new_password_confirmation" class=''>Apstipriniet jaunu paroli</label>
            <input type="password" name="new_password_confirmation" class='rounded-md' required value="{{ old('new_password_confirmation') }}">
        </div>

        <button type="submit" class='mt-12 bg-[#007BFF] active:bg-[#0056b3] text-white p-4 mx-auto rounded-md text-center text-xl'>Mainīt paroli</button>
    </form>
@endsection