@extends('layouts.admin')

@section('title', 'Klienta abonementa veida mainīšana')

@section('content')
    <style>
        html, body {
            height: 100%
        }
    </style>

    @php
        use Carbon\Carbon;    
    @endphp

    @if (Carbon::parse($client->membership_until)->isPast())
    <h1 class='text-center text-2xl mt-12'>Jūs nevarat mainīt abonementa veidu šim klientam, jo viņa abonements ir beidzies.</h1>
    @else
    <h1 class='text-center font-bold text-3xl mt-12'>Klienta {{ $client->name}}  {{ $client->surname }} abonementa veida mainīšana</h1>

    <p class='mt-8 text-lg text-center mx-auto w-8/12'>Jums ir iespēja mainīt klienta abonementa veidu. Gadījumā, ja klients grib nomainīt abonementu uz dargāku, viņam ir jāpiemaksā. Maksājuma summa ir starpība starp dargāku un pašreizējo abonementu.</p>

    <form action="{{ route('change_client_membership') }}" method="POST" class='flex flex-row justify-center mx-auto w-1/3 mt-8' onsubmit="return show_confirmation(this);">
    @csrf
        <div class='flex flex-col border-2 border-gray-300 rounded-md p-4'>
            <label for="membership_name">Vēlamais abonementa veids:</label>
            <select name="membership_name" id="membership_name">
                @foreach($memberships as $membership)
                    <option value="{{ $membership['membership_name'] }}" id="{{ str_replace(' ', '_', $membership['membership_name']) }}">{{ $membership['membership_name'] }}</option>
                @endforeach
            </select>
            
            <p id="membership_price" class='mt-2'></p>

            <div id='payment_info' class='mt-2 flex flex-col'>
                <label for="payment_method">Maksājuma veids:</label>
                <select name="payment_method" id="payment_method" class='rounded-md'>
                    <option value="cash">Skaidra nauda</option>
                    <option value="card">Bankas karte</option>
                </select>
            </div>

            <x-main_button type='submit' class='mt-4'>Mainīt abonementa veidu</x-main_button>
        </div>

        <input type="hidden" name="client_id" value="{{ $client->client_id }}">
    </form>

    <script>
        
        const membership_selection = document.querySelector('#membership_name');
        const client_membership = "{{ $client->membership_name }}";
        const membership_price = document.querySelector('#membership_price');
        const memberships_prices = {!! $memberships_prices !!};
        const payment_info = document.querySelector('#payment_info');
        let selected_membership_price = parseFloat(memberships_prices[this.value]);
        let client_membership_price = parseFloat(memberships_prices[client_membership]);

        // Remove client's active membership from the selection
        for (let i = 0; i < membership_selection.length; i++) {
            if (membership_selection.options[i].value === client_membership) {
                membership_selection.options[i].remove();
            }
        }

        selected_membership_price = parseFloat(memberships_prices[membership_selection.value]);
        client_membership_price = parseFloat(memberships_prices[client_membership]);

        let amount_to_pay = 0;
        if (selected_membership_price < client_membership_price) {
            amount_to_pay = 0;
            payment_info.classList.add('hidden');
        } else {
            amount_to_pay = selected_membership_price - client_membership_price;
        }

        membership_price.innerHTML = "<span class='font-bold'>Maksājuma summa: </span>" + amount_to_pay.toString() + '€';

        membership_selection.addEventListener('change', function() {
            selected_membership_price = parseFloat(memberships_prices[this.value]);
            client_membership_price = parseFloat(memberships_prices[client_membership]);

            if (selected_membership_price < client_membership_price) {
                amount_to_pay = 0;
                payment_info.classList.add('hidden');
            } else {
                amount_to_pay = selected_membership_price - client_membership_price;
                payment_info.classList.remove('hidden');
            }

            membership_price.innerHTML = "<span class='font-bold'>Maksājuma summa: </span>" + amount_to_pay.toString() + '€';
        });

        function show_confirmation(form) {
            let form_data = new FormData(form);
            const payment_options_names = {'card': 'Bankas karte', 'cash': 'Skaidra nauda'};
            
            let confirm_message = '';
            if (amount_to_pay > 0) {
                confirm_message = `
Jūs gribat mainīt klienta {{ $client->name }} {{ $client->surname }} (p.k. {{ $client->personal_id }}) abonementu.

Izvēlētais abonementa veids: ${form_data.get('membership_name')}
Maksājuma summa: ${amount_to_pay}€
Maksājuma veids: ${payment_options_names[form_data.get('payment_method')]}

Nospiežot "Apstiprināt", jūs apstiprināt, ka klients ir veicis maksājumu.`;
            } else {
                confirm_message = `
Jūs gribat mainīt klienta {{ $client->name }} {{ $client->surname }} (p.k. {{ $client->personal_id }}) abonementu.

Izvēlētais abonementa veids: ${form_data.get('membership_name')}
Maksājuma summa: ${amount_to_pay}€`;
            }

            return confirm(confirm_message);
        }
        
    </script>

    @endif
@endsection