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

        .client_cell {
            padding-block: 0.5rem;
            border-bottom: 1px solid black;
        }
    </style>

    <h1 class='font-bold text-center text-3xl my-8'>Visi klienti</h1>

    <p class='w-10/12 mx-auto text-lg'>
        Šeit jūs varat apskatīt sarakstu ar visiem sporta zāles klientiem. Nospiediet uz klientu, lai atvērtu viņa profilu, kur ir iespējams apskatīt detalizētāku informāciju, kā arī veikt darbības, saistītas ar šo klientu (piemēram, rediģēt vai dzēst viņa informāciju, pagarināt abonementu utt.).
    </p>

    <div>
        <!-- Search bar for searching clients by some attribute -->
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
                        <option value="membershipName">Abonementa veids</option>
                        <option value="membershipUntil">Abonementa derīgs līdz:</option>
                    </select>
                </div>
                <div class='flex flex-col'>
                    <label for="search_field">Meklējamā vērtība:</label>
                    <input type="text" name='search_field' id='search_field'>
                </div>
            </div>
        </div>
        <!-- Table with all the clients -->
        <div class='clients_list grid grid-cols-{{ $attribute_count }} mx-auto w-11/12 mt-4 mb-12 border-2 border-black rounded-md p-6'>
            @foreach($displayed_attributes as $attribute)
                <div>
                    <h1 class='font-bold text-center'>{{ $attribute }}</h1>
                </div>
            @endforeach

            @foreach($clients as $client)
                <div class='client_row contents' data-personal-id='{{ $client->personal_id }}' data-id='{{ $client->client_id }}' data-name='{{ $client->name }}' data-surname='{{ $client->surname }}' data-phone='{{ $client->phone }}' data-email='{{ $client->email }}' data-membership-name='{{ $client->membership_name }}' data-membership-until='{{ $client->membership_until }}'>
                    <div class='client_cell'>
                        <h1 class='text-center'>{{ $client->personal_id }}</h1>
                    </div>
                    <div class='client_cell'>
                        <h1 class='text-center'>{{ $client->name }}</h1>
                    </div>
                    <div class='client_cell'>
                        <h1 class='text-center'>{{ $client->surname }}</h1>
                    </div>
                    <div class='client_cell'>
                        <h1 class='text-center'>{{ $client->phone }}</h1>
                    </div>
                    <div class='client_cell'>
                        <h1 class='text-center'>{{ $client->email }}</h1>
                    </div>
                    <div class='client_cell'>
                        <h1 class='text-center'>{{ $client->membership_name ?? 'Nav' }}</h1>
                    </div>
                    <div class='client_cell'>
                        <h1 class='text-center'>{{ $client->membership_until ?? 'Nav' }}</h1>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <script>
        const client_rows = document.querySelectorAll('.client_row');
    
        for (let i = 0; i < client_rows.length; i++) {
            // Add gray background and pointer cursor to the row when the user hovers over it
            client_rows[i].addEventListener('mouseover', function () {
                const personal_id = this.dataset.personalId;
                const row_cells = this.querySelectorAll('.client_cell');
    
                for (let j = 0; j < row_cells.length; j++) {
                    row_cells[j].classList.add('bg-gray-200');
                    row_cells[j].classList.add('cursor-pointer');
                }
    
            });

            // Remove gray background from the row when the user stops hovering over it
            client_rows[i].addEventListener('mouseout', function () {
                const personal_id = this.dataset.personalId;
                const row_cells = this.querySelectorAll('.client_cell');
    
                for (let j = 0; j < row_cells.length; j++) {
                    row_cells[j].classList.remove('bg-gray-200');
                }
    
            });

            // Open user's profile page when the user clicks on a row
            client_rows[i].addEventListener('click', function () {
                const client_id = this.dataset.id;
                let url = "{{ route('view_client_profile', ['client_id' => ':client_id']) }}";
                url = url.replace(':client_id', client_id);
                window.location.href = url;
            });
        }

        // Filter the client list using the search string
        search_field.addEventListener('input', function () {
            const search_option = document.querySelector('#search_option').value;
            const search_string = document.querySelector('#search_field').value;
            console.log(search_option);

            for (let i = 0; i < client_rows.length; i++) {
                const client_cells = client_rows[i].querySelectorAll('.client_cell');

                for (let j = 0; j < client_cells.length; j++) {
                    client_cells[j].classList.add('hidden');

                    if ((client_rows[i].dataset[search_option].toLowerCase()).includes(search_string.toLowerCase())) {
                        client_cells[j].classList.remove('hidden');
                    }
                }
            }
        });
    </script>
@endsection