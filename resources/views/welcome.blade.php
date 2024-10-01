<!-- resources/views/home.blade.php -->

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
    <h1 class='text-[#007BFF] text-center text-6xl py-8'>Welcome to FitLife!</h1>
    <form class='flex flex-col w-10/12 md:w-1/3 mx-auto shadow-md rounded-md p-4 border-2 border-[#C0C0C0]' action="">
        <label for="personal-id">Personal ID</label>
        <input class='border-2 border-gray-300 rounded-sm' type="text" name='personal-id'>
        <label for="password">Password</label>
        <input class='border-2 border-gray-300 rounded-sm' type="password" name='password'>
        <button class='mt-4 bg-[#007BFF] active:bg-[#0056b3] text-white py-2 w-1/2 md:w-1/4 mx-auto rounded-md' type="submit">Log In</button>
    </form>
    <h2 class='text-gray-300 text-center'></h2>
@endsection
