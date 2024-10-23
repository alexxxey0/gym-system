@extends('layouts.admin')

@section('title', 'Visi treneri')

@section('content')
    <style>
        .coaches_list > div {
            border-bottom: 1px solid gray;
            padding-block: 1rem;
        }

        .coaches_list h1 {
            word-wrap: break-word;
        }
    </style>

    <h1 class='font-bold text-center text-3xl my-8'>Visi treneri</h1>

    <p class='w-10/12 mx-auto text-lg'>Šeit jūs varat apskatīt sarakstu ar visiem sporta zāles treneriem. Nospiediet uz treneri, lai atvērtu viņa profilu, kur ir iespējams apskatīt detalizētāku informāciju, kā arī veikt darbības, saistītas ar šo treneri (piemēram, rediģēt vai dzēst viņa informāciju).</p>
    <!-- Table with all the coaches -->
    <div class='coaches_list grid grid-cols-{{ $attribute_count }} mx-auto w-11/12 mt-4 mb-12 border-2 border-black rounded-md p-6'>
        @foreach($displayed_attributes as $attribute)
            <div>
                <h1 class='font-bold text-center'>{{ $attribute }}</h1>
            </div>
        @endforeach

        @foreach($coaches as $coach)
            <div class='coach_cell' data-personal-id='{{ $coach->personal_id }}'>
                <h1 class='text-center'>{{ $coach->personal_id }}</h1>
            </div>
            <div class='coach_cell' data-personal-id='{{ $coach->personal_id }}'>
                <h1 class='text-center'>{{ $coach->name }}</h1>
            </div>
            <div class='coach_cell' data-personal-id='{{ $coach->personal_id }}'>
                <h1 class='text-center'>{{ $coach->surname }}</h1>
            </div>
            <div class='coach_cell' data-personal-id='{{ $coach->personal_id }}'>
                <h1 class='text-center'>{{ $coach->phone }}</h1>
            </div>
            <div class='coach_cell' data-personal-id='{{ $coach->personal_id }}'>
                <h1 class='text-center'>{{ $coach->email }}</h1>
            </div>
        @endforeach
    </div>

    <script>
        const coach_cells = document.querySelectorAll('.coach_cell');
    
        for (let i = 0; i < coach_cells.length; i++) {
            coach_cells[i].addEventListener('mouseover', function () {
                const personal_id = this.dataset.personalId;
                const row_cells = document.querySelectorAll('.coach_cell[data-personal-id="' + personal_id + '"]');
    
                for (let j = 0; j < row_cells.length; j++) {
                    row_cells[j].classList.add('bg-gray-200');
                    row_cells[j].classList.add('cursor-pointer');
                }
    
            });
        }

        for (let i = 0; i < coach_cells.length; i++) {
            coach_cells[i].addEventListener('mouseout', function () {
                const personal_id = this.dataset.personalId;
                const row_cells = document.querySelectorAll('.coach_cell[data-personal-id="' + personal_id + '"]');
    
                for (let j = 0; j < row_cells.length; j++) {
                    row_cells[j].classList.remove('bg-gray-200');
                }
    
            });
        }
    </script>
@endsection