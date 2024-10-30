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

        .coaches_list {
            display: grid;
            grid-template-columns: repeat({{ $attribute_count }}, 1fr);
        }

        .coach_cell {
            padding-block: 0.5rem;
            border-bottom: 1px solid black;
        }
    </style>

    <h1 class='font-bold text-center text-3xl my-8'>Visi treneri</h1>

    <p class='w-10/12 mx-auto text-lg'>
        Šeit jūs varat apskatīt sarakstu ar visiem sporta zāles treneriem. Nospiediet uz treneri, lai atvērtu viņa profilu, kur ir iespējams apskatīt detalizētāku informāciju, kā arī veikt darbības, saistītas ar šo treneri (piemēram, rediģēt vai dzēst viņa informāciju, utt.).
    </p>

    <div>
        <!-- Search bar for searching coaches by some attribute -->
        <div class='flex flex-col w-11/12 mx-auto mt-12'>
            <h2 class='font-bold text-xl'>Meklēšana</h2>
            <div class='flex flex-row gap-x-2'>
                <div class='flex flex-col'>
                    <label for="search_option">Meklēt pēc:</label>
                    <select name="search_option" class='' id='search_option'>
                        <option value="personalId">Personas kods</option>
                        <option value="name">Vārds</option>
                        <option value="surname">Uzvārds</option>
                        <option value="phone">Telefona numurs</option>
                        <option value="email">E-pasts</option>
                    </select>
                </div>
                <div class='flex flex-col'>
                    <label for="search_field">Meklējamā vērtība:</label>
                    <input type="text" name='search_field' id='search_field'>
                </div>
            </div>
        </div>
        <!-- Table with all the coaches -->
        <div class='coaches_list grid grid-cols-{{ $attribute_count }} mx-auto w-11/12 mt-4 mb-12 border-2 border-black rounded-md p-6'>
            @foreach($displayed_attributes as $attribute)
                <div>
                    <h1 class='font-bold text-center'>{{ $attribute }}</h1>
                </div>
            @endforeach

            @foreach($coaches as $coach)
                <div class='coach_row contents' data-personal-id='{{ $coach->personal_id }}' data-id='{{ $coach->coach_id }}' data-name='{{ $coach->name }}' data-surname='{{ $coach->surname }}' data-phone='{{ $coach->phone }}' data-email='{{ $coach->email }}'>
                    <div class='coach_cell'>
                        <h1 class='text-center'>{{ $coach->personal_id }}</h1>
                    </div>
                    <div class='coach_cell'>
                        <h1 class='text-center'>{{ $coach->name }}</h1>
                    </div>
                    <div class='coach_cell'>
                        <h1 class='text-center'>{{ $coach->surname }}</h1>
                    </div>
                    <div class='coach_cell'>
                        <h1 class='text-center'>{{ $coach->phone }}</h1>
                    </div>
                    <div class='coach_cell'>
                        <h1 class='text-center'>{{ $coach->email }}</h1>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <script>
        const coach_rows = document.querySelectorAll('.coach_row');
    
        for (let i = 0; i < coach_rows.length; i++) {
            // Add gray background and pointer cursor to the row when the user hovers over it
            coach_rows[i].addEventListener('mouseover', function () {
                const personal_id = this.dataset.personalId;
                const row_cells = this.querySelectorAll('.coach_cell');
    
                for (let j = 0; j < row_cells.length; j++) {
                    row_cells[j].classList.add('bg-gray-200');
                    row_cells[j].classList.add('cursor-pointer');
                }
    
            });

            // Remove gray background from the row when the user stops hovering over it
            coach_rows[i].addEventListener('mouseout', function () {
                const personal_id = this.dataset.personalId;
                const row_cells = this.querySelectorAll('.coach_cell');
    
                for (let j = 0; j < row_cells.length; j++) {
                    row_cells[j].classList.remove('bg-gray-200');
                }
    
            });

            // Open user's profile page when the user clicks on a row
            coach_rows[i].addEventListener('click', function () {
                const coach_id = this.dataset.id;
                var url = "{{ route('view_coach_profile', ['coach_id' => ':coach_id']) }}";
                url = url.replace(':coach_id', coach_id);
                window.location.href = url;
            });
        }

        // Filter the coach list using the search string
        search_field.addEventListener('input', function () {
            const search_option = document.querySelector('#search_option').value;
            const search_string = document.querySelector('#search_field').value;
            console.log(search_option);

            for (let i = 0; i < coach_rows.length; i++) {
                const coach_cells = coach_rows[i].querySelectorAll('.coach_cell');

                for (let j = 0; j < coach_cells.length; j++) {
                    coach_cells[j].classList.add('hidden');

                    if ((coach_rows[i].dataset[search_option].toLowerCase()).includes(search_string.toLowerCase())) {
                        coach_cells[j].classList.remove('hidden');
                    }
                }
            }
        });
    </script>
@endsection