@extends('layouts.admin')

@section('title', 'Izveidot jaunu sporta zāli')

@section('content')

    <h1 class='font-bold text-center text-3xl mt-12'>Jaunas sporta zāles izveidošana</h1>

    <form action="{{ route('create_new_gym') }}" method="POST" class='w-6/12 mx-auto mt-8 mb-16 flex flex-col gap-y-4'>
        @csrf

        <div class='flex flex-col gap-y-2'>
            <label for="name">Sporta zāles nosaukums</label>
            <input name='name' type="text" maxlength="50" required class='rounded-md'>
        </div>

        <div class="flex flex-col gap-y-2">
            <label for="description">Sporta zāles apraksts</label>
            <textarea name="description" id="" cols="30" rows="10" required maxlength="1000" class='rounded-md'></textarea>
        </div>

        <div class="flex flex-col gap-y-2">
            <label for="address">Sporta zāles adrese</label>
            <input name='address' type="text" maxlength="100" required class='rounded-md' placeholder="Brīvības gatve 200, Rīga, LV-1039">
        </div>

        <x-main_button type='submit' class='w-3/12 mx-auto mt-4'>Izveidot sporta zāli</x-main_button>
    </form>
@endsection