@extends('layouts.' . Auth::user()->role)

@section('title', 'Abonementu veidi')

@section('content')

    <h1 class='text-3xl text-center font-bold mt-8'>Abonementu veidi</h1>

    <p class='text-lg mt-6 text-center'>Šeit jūs varat apskatīt visus pieejamos abonementu veidus.</p>

    <div class='flex flex-col gap-y-12 mt-12 mb-12'>
        @foreach($memberships as $membership)
            <div class='border-2 border-black rounded-2xl w-1/2 mx-auto p-8 text-lg '>
                <h2 class='text-3xl font-bold text-center'>{{ $membership->membership_name }}</h2>
                <ul class='list-disc mt-6'>
                    <li><span class='font-bold'>Cena:</span> {{ $membership->price }}€/mēn.</li>
                    @if($membership->group_trainings_included)
                        <li class='font-bold'>Iekļautas grupu nodarbības</li>
                    @else
                        <li class='font-bold'>Nav iekļautas grupu nodarbības</li>
                    @endif
                    <li><span class='font-bold'>Ieeja darba dienās:</span> {{substr($membership->entry_from_workdays, 0, 5)}}-{{substr($membership->entry_until_workdays, 0, 5)}}</li>
                    <li><span class='font-bold'>Ieeja brīvdienās:</span> {{substr($membership->entry_from_weekends, 0, 5)}}-{{substr($membership->entry_until_weekends, 0, 5)}}</li>
                </ul>
            </div>
        @endforeach
    </div>

@endsection