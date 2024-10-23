@extends('layouts.admin')

@section('title', 'Visi klienti')

@section('content')
    <style>
        .clients_list > div {
            border-bottom: 1px solid gray;
            padding-block: 1rem;
        }

        .clients_list h1 {
            word-wrap: break-word;
        }

        .clients_list {
            display: grid;
            grid-template-columns: repeat({{ $attribute_count }}, 1fr);
        }
    </style>

    <h1 class='font-bold text-center text-3xl my-8'>Visi klienti</h1>

    <p class='w-10/12 mx-auto text-lg'>Šeit jūs varat apskatīt sarakstu ar visiem sporta zāles klientiem. Nospiediet uz klientu, lai atvērtu viņa profilu, kur ir iespējams apskatīt detalizētāku informāciju, kā arī veikt darbības, saistītas ar šo klientu (piemēram, rediģēt vai dzēst viņa informāciju, pagarināt abonementu utt.).</p>

    <!-- Table with all the clients -->
    <div class='clients_list grid grid-cols-{{ $attribute_count }} mx-auto w-11/12 mt-4 mb-12 border-2 border-black rounded-md p-6'>
        @foreach($displayed_attributes as $attribute)
            <div>
                <h1 class='font-bold text-center'>{{ $attribute }}</h1>
            </div>
        @endforeach

        @foreach($clients as $client)
            <div class='client_cell' data-personal-id='{{ $client->personal_id }}' data-id='{{ $client->client_id }}'>
                <h1 class='text-center'>{{ $client->personal_id }}</h1>
            </div>
            <div class='client_cell' data-personal-id='{{ $client->personal_id }}' data-id='{{ $client->client_id }}'>
                <h1 class='text-center'>{{ $client->name }}</h1>
            </div>
            <div class='client_cell' data-personal-id='{{ $client->personal_id }}' data-id='{{ $client->client_id }}'>
                <h1 class='text-center'>{{ $client->surname }}</h1>
            </div>
            <div class='client_cell' data-personal-id='{{ $client->personal_id }}' data-id='{{ $client->client_id }}'>
                <h1 class='text-center'>{{ $client->phone }}</h1>
            </div>
            <div class='client_cell' data-personal-id='{{ $client->personal_id }}' data-id='{{ $client->client_id }}'>
                <h1 class='text-center'>{{ $client->email }}</h1>
            </div>
            <div class='client_cell' data-personal-id='{{ $client->personal_id }}' data-id='{{ $client->client_id }}'>
                <h1 class='text-center'>{{ $client->membership_name ?? 'Nav' }}</h1>
            </div>
            <div class='client_cell' data-personal-id='{{ $client->personal_id }}' data-id='{{ $client->client_id }}'>
                <h1 class='text-center'>{{ $client->membership_until ?? 'Nav' }}</h1>
            </div>
        @endforeach
    </div>

    <script>
        const client_cells = document.querySelectorAll('.client_cell');
    
        for (let i = 0; i < client_cells.length; i++) {
            // Add gray background and pointer cursor to the row when the user hovers over it
            client_cells[i].addEventListener('mouseover', function () {
                const personal_id = this.dataset.personalId;
                const row_cells = document.querySelectorAll('.client_cell[data-personal-id="' + personal_id + '"]');
    
                for (let j = 0; j < row_cells.length; j++) {
                    row_cells[j].classList.add('bg-gray-200');
                    row_cells[j].classList.add('cursor-pointer');
                }
    
            });

            // Remove gray background from the row when the user stops hovering over it
            client_cells[i].addEventListener('mouseout', function () {
                const personal_id = this.dataset.personalId;
                const row_cells = document.querySelectorAll('.client_cell[data-personal-id="' + personal_id + '"]');
    
                for (let j = 0; j < row_cells.length; j++) {
                    row_cells[j].classList.remove('bg-gray-200');
                }
    
            });

            // Open user's profile page when the user clicks on a row
            client_cells[i].addEventListener('click', function () {
                const client_id = this.dataset.id;
                var url = "{{ route('view_client_profile', ['client_id' => ':client_id']) }}";
                url = url.replace(':client_id', client_id);
                window.location.href = url;
            });

        }
    </script>
@endsection