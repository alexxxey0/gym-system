@extends('layouts.' . Auth::user()->role)

@section('title', Auth::user()->role === 'client' ? 'Mūsu treneri' : 'Visi treneri')

@section('content')
    @if (Auth::user()->role === 'client')
        <h1 class='text-center text-3xl font-bold mt-8'>Mūsu treneri</h1>
    @else
        <h1 class='text-center text-3xl font-bold mt-8'>Visi treneri</h1>
    @endif

    <div class='flex flex-col gap-y-16 mt-12 mb-8 items-center'>
    @foreach($coaches as $coach)

        <div class='flex flex-row items-start mx-auto w-1/2 gap-x-4'>
            @if (isset($coach->path_to_image))
                <img class='w-4/12 border-4 border-black rounded-md' src="{{ asset('storage/' . $coach->path_to_image) }}" alt="">
            @else
                <img class='w-4/12 border-4 border-black rounded-md' src="{{ asset('storage/coaches_profile_pictures/default_profile_picture.jpg') }}" alt="">
            @endif

            <div class='flex flex-row w-7/12'>

                <div class='flex flex-col ml-4 gap-y-4'>
                    <div class=''>
                        <h2 class='font-bold text-2xl'>{{ $coach->name }} {{ $coach->surname }}</h2>
                        <p>{{ $coach->personal_description }}</p>
                    </div>

                    <div>
                        @if (isset($coach->contact_phone))
                            <p><span class='font-bold'>Kontakttelefons:</span> {{ $coach->contact_phone }}</p>
                        @endif
                        @if (isset($coach->contact_email))
                            <p><span class='font-bold'>Kontakte-pasts:</span> <a class='text-blue-800 hover:text-blue-900 hover:underline' href="mailto:{{ $coach->contact_email }}">{{ $coach->contact_email }}</a></p>
                        @endif
                    </div>

                    <div>
                        @if(isset($coach->group_trainings) and count($coach->group_trainings) > 0)
                            <h2 class='text-lg'>Vada nodarbības:</h2>
                            <ul class='list-disc list-inside'>
                                @foreach($coach->group_trainings as $group_training)
                                    <li>{{ $group_training->name }}</li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        
    @endforeach
    </div>
@endsection