@extends('layouts.' . Auth::user()->role)

@section('title', 'Nosūtīt paziņojumu nodarbības apmeklētājiem')

@section('content')

    <h1 class='text-3xl font-bold text-center mt-8'>Nosūtīt paziņojumu nodarbības <span class='italic'>{{ $group_training->name }}</span> apmeklētājiem</h1>

    <form action="{{ route('send_notification') }}" method="POST" class='mx-auto w-1/2 my-12 flex flex-col gap-y-4'>
        @csrf
        <div class='flex flex-col'>
            <label for="topic">Paziņojuma tēma</label>
            <input class='rounded-sm' type="text" name='topic' required maxlength="100">
            
        </div>
        @if ($errors->has('topic'))
            <div class='bg-[#f54242] text-white rounded-md p-2 mt-2'>{{ $errors->first('topic') }}</div>
        @endif

        <div class='flex flex-col'>
            <label for="text">Paziņojuma teksts</label>
            <textarea class='rounded-sm' name="text" id="" cols="30" rows="10" maxlength="1000" required></textarea>
        </div>
        @if ($errors->has('text'))
            <div class='bg-[#f54242] text-white rounded-md p-2 mt-2'>{{ $errors->first('text') }}</div>
        @endif

        <input type="hidden" name="training_id" value="{{ $group_training->training_id }}">
        @if (Auth::user()->role === 'coach')
            <input type="hidden" name='coach_id' value="{{ Auth::user()->coach_id }}">
        @endif
        <x-main_button class='w-1/3 mx-auto' type='submit'>Nosūtīt paziņojumu</x-main_button>
    </form>



@endsection