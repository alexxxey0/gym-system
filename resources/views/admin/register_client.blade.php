@extends('layouts.admin')

@section('title', 'Jauna klienta reģistrācija')

@section('content')
    <h1 class='font-bold text-center mt-4 text-2xl'>Jauna klienta reģistrācija</h1>

    <!-- New client registration form -->

    <form action="{{ route('register_client_post') }}" method='POST' class='w-1/3 mx-auto flex flex-col gap-y-6 mt-6 mb-16'>
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

        <div class='flex flex-row items-center gap-x-4'>
            <label for="assign_membership">Uzreiz piešķirt klientam abonementu</label>
            <input type="checkbox" name="assign_membership" id="assign_membership">
        </div>

        <div class='hidden flex-col membership_selection'>
            <label for="membership">Abonements</label>
            <select name="membership_name" id="membership_name" class='rounded-md'>
                @foreach ($memberships as $membership)
                    <option value="{{ $membership }}">{{ $membership }}</option>
                @endforeach
            </select>
            <p class='mt-2'>Abonements tiks piešķirts uz vienu mēnesi</p>
        </div>

        <button type="submit" class='bg-[#007BFF] active:bg-[#0056b3] w-fit mx-auto py-2 px-6 text-white rounded-md text-xl font-bold'>Reģistrēt</button>
    </form>

    <script>
        const assign_membership_checkbox = document.querySelector('#assign_membership');
        const membership_selection = document.querySelector('.membership_selection');
        const membership_name = document.querySelector('#membership_name');

        assign_membership_checkbox.addEventListener('change', function() {
            if (this.checked) {
                membership_selection.classList.remove('hidden');
                membership_selection.classList.add('flex');
                membership_name.required = true;
            } else {
                membership_selection.classList.remove('flex');
                membership_selection.classList.add('hidden');
                membership_name.required = false;
            }
        });
    </script>
@endsection