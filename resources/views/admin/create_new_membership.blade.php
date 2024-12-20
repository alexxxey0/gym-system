@extends('layouts.admin')

@section('title', 'Izveidot jaunu abonementa veidu')

@section('content')

    <h1 class='font-bold text-center text-3xl mt-12'>Jauna abonementa veida izveidošana</h1>

    <form action="{{ route('create_new_membership') }}" method="POST" class='w-4/12 mx-auto mt-8 mb-16 flex flex-col gap-y-4'>
        @csrf

        <div class='flex flex-col gap-y-2'>
            <label for="membership_name">Abonementa nosaukums</label>
            <input name='membership_name' type="text" maxlength="50" required class='rounded-md' value="{{ old('membership_name') }}">
        </div>

        @if ($errors->has('membership_name'))
            <div class='bg-[#f54242] text-white rounded-md p-2 mt-2'>{{ $errors->first('membership_name') }}</div>
        @endif

        <div class="flex flex-col gap-y-2">
            <label for="price">Abonementa cena, EUR</label>
            <input type="number" name="price" id="" step="0.01" class='rounded-md' min="0.01" max="100" value="{{ old('price') }}" required>
        </div>

        @if ($errors->has('price'))
            <div class='bg-[#f54242] text-white rounded-md p-2 mt-2'>{{ $errors->first('price') }}</div>
        @endif

        <div class="flex flex-col gap-y-2">
            <label for="group_trainings_included">Vai iekļautas grupu nodarbības?</label>
            <div class='flex flex-row gap-x-2 items-center'>
                <input type="radio" name="group_trainings_included" value='yes' id="group_trainings_yes" required>
                <label for="group_trainings_yes">Jā</label>
            </div>
            <div class='flex flex-row gap-x-2 items-center'>
                <input type="radio" name='group_trainings_included' value='no' id='group_trainings_no'>
                <label for="group_trainings_no">Nē</label>
            </div>
        </div>

        @if ($errors->has('group_trainings_included'))
            <div class='bg-[#f54242] text-white rounded-md p-2 mt-2'>{{ $errors->first('group_trainings_included') }}</div>
        @endif

        <div>
            <h2 class='mb-4 text-lg'>Ieeja darba dienās</h2>
            <div class='flex flex-row gap-x-8'>
                <div class='flex flex-row items-center gap-x-2'>
                    <label for="entry_from_workdays">No:</label>
                    <input type="time" name="entry_from_workdays" min="08:00" max="22:00" id="" value='{{ old('entry_from_workdays') }}' required>
                </div>
                <div class="flex flex-row items-center gap-x-2">
                    <label for="entry_until_workdays">Līdz:</label>
                    <input type="time" name="entry_until_workdays" min="08:00" max="22:00" id="" value='{{ old('entry_until_workdays') }}' required>
                </div>
            </div>
        </div>

        <div>
            <h2 class='mb-4 text-lg'>Ieeja brīvdienās</h2>
            <div class='flex flex-row gap-x-8'>
                <div class='flex flex-row items-center gap-x-2'>
                    <label for="entry_from_weekends">No:</label>
                    <input type="time" name="entry_from_weekends" min="09:00" max="20:00" id="" value='{{ old('entry_from_weekends') }}' required>
                </div>
                <div class="flex flex-row items-center gap-x-2">
                    <label for="entry_until_weekends">Līdz:</label>
                    <input type="time" name="entry_until_weekends" min="09:00" max="20:00" id="" value='{{ old('entry_until_weekends') }}' required>
                </div>
            </div>
        </div>

        @if ($errors->has('entry_times'))
            <div class='bg-[#f54242] text-white rounded-md p-2 mt-2'>{{ $errors->first('entry_times') }}</div>
        @endif

        <x-main_button type='submit' class='w-5/12 mx-auto mt-6 p-4'>Izveidot abonementa veidu</x-main_button>
    </form>
@endsection