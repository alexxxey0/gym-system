@extends('layouts.coach')

@section('title', 'Trenera galvenā lapa')

@section('content')
    <style>
        html, body {
            height: 100%;
        }
    </style>

    <div class='p-4 w-1/2 mx-auto text-lg'>
        <p class='text-center text-xl'>Sveicināti, treneris/-e {{ Auth::user()->name }} {{ Auth::user()->surname }}!</p>
        <br>
        <p>
        Mēs priecājamies, ka jūs vadāt ceļu mūsu biedriem. Šī sistēma ir izstrādāta, lai palīdzētu jums pārvaldīt nodarbības un sazināties ar klientiem. Sākot ar profila datu atjaunināšanu un beidzot ar grupu nodarbību plānošanu un apmeklējumu izsekošanu, jums ir visi rīki, kas nepieciešami nodarbību pārvaldīšanai. Varat viegli sakārtot savu nodarbību kalendāru, pārvaldīt klientu reģistrēšanos un informēt klientus par izmaiņām nodarbību grafikā.
        </p>
        <br>
        <p>Novēlam veiksmīgus treniņus!</p>
    </div>
@endsection