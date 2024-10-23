@extends('layouts.client')

@section('title', 'Klienta galvenā lapa')

@section('content')
    <style>
        html, body {
            height: 100%;
        }
    </style>

    <div class='p-4 w-1/2 mx-auto text-lg'>
        <p class='text-center text-xl'>Sveicināti, {{ Auth::user()->name }} {{ Auth::user()->surname }}!</p>
        <br>
        <p>
        Mēs priecājamies, ka esat daļa no mūsu fitnesa kopienas. Šeit vienuviet atradīsit visu nepieciešamo, lai pārvaldītu savu <span class='italic'>FitLife</span> pieredzi. Neatkarīgi no tā, vai vēlaties pārbaudīt informāciju par savu abonementu, izsekot apmeklējumam vai izpētīt mūsu grupu nodarbības un trenerus, jūs esat tikai viena klikšķa attālumā. Varat arī reģistrēties nodarbībām, pārvaldīt abonementu un ērti rediģēt sava profila datus.
        </p>
        <br>
        <p>Novēlam veiksmīgus treniņus!</p>
    </div>
@endsection