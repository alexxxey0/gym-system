@extends('layouts.admin')

@section('title', 'Klienta abonementa pagarināšana')

@section('content')
    <style>
        html, body {
            height: 100%
        }
    </style>

    @php
        use Carbon\Carbon;    
    @endphp

    @if (!Carbon::parse($client->membership_until)->isPast())
        <h1 class='text-center text-2xl mt-12'>Jūs nevarat pagarināt abonementu šim klientam, jo viņa abonements vēl nav beidzies.</h1>
    @else
    <h1 class='text-center font-bold text-3xl mt-12'>Klienta {{ $client->name}}  {{ $client->surname }} abonementa pagarināšana</h1>

    <p class='mt-8 text-lg text-center'>Šī klienta abonements ir beidzies. Pagarinot klienta abonementu, tas tiks pagarināts uz 1 mēnesi no pašreizējā datuma, tātad, līdz {{ now()->addMonth()->format('Y-m-d') }}.</p>

    <form action="{{ route('extend_client_membership') }}" method="POST" class='flex flex-row justify-center mx-auto w-1/3 mt-8' onsubmit="return show_confirmation(this);">
        @csrf
            <div class='flex flex-col border-2 border-gray-300 rounded-md p-4'>
                <label for="membership_name">Vēlamais abonementa veids:</label>
                <select name="membership_name" id="membership_name">
                    @foreach($memberships as $membership)
                        <option value="{{ $membership['membership_name'] }}" id="{{ str_replace(' ', '_', $membership['membership_name']) }}">{{ $membership['membership_name'] }}</option>
                    @endforeach
                </select>
                <p id="membership_price" class='mt-2'></p>

                <div class='mt-2 flex flex-col'>
                    <label for="payment_method">Maksājuma veids:</label>
                    <select name="payment_method" id="payment_method" class='rounded-md'>
                        <option value="cash">Skaidra nauda</option>
                        <option value="card">Bankas karte</option>
                    </select>
                </div>

                <x-main_button type='submit' class='mt-4'>Pagarināt abonementu</x-main_button>
            </div>

            <input type="hidden" name="client_id" value="{{ $client->client_id }}">
    </form>

    <script>
        
        const membership_selection = document.querySelector('#membership_name');
        const client_membership = "{{ $client->membership_name }}";
        const membership_price = document.querySelector('#membership_price');
        const memberships_prices = {!! $memberships_prices !!};

        if (client_membership) {
            membership_selection.value = client_membership;
            
            const membership_selection_text = document.querySelector('#' + client_membership.replace(/ /g, '_'));
            membership_selection_text.textContent = client_membership + ' (Pašreizējais)';
            membership_price.innerHTML = "<span class='font-bold'>Maksājuma summa: </span>" + memberships_prices[client_membership] + '€';

        } else {
            membership_selection.value = "{{ $memberships[0]['membership_name'] }}";
            membership_price.innerHTML = "<span class='font-bold'>Maksājuma summa: </span>" + memberships_prices["{{ $memberships[0]['membership_name'] }}"] + '€';
        }

        membership_name.addEventListener('change', function() {
            const selected_membership = this.value;

            membership_price.innerHTML = "<span class='font-bold'>Maksājuma summa: </span>" + memberships_prices[selected_membership] + '€';
        });

        function show_confirmation(form) {
            let form_data = new FormData(form);
            const payment_options_names = {'card': 'Bankas karte', 'cash': 'Skaidra nauda'};
            
            let confirm_message = `
Jūs gribat pagarināt klienta {{ $client->name }} {{ $client->surname }} (p.k. {{ $client->personal_id }}) abonementu.

Izvēlētais abonementa veids: ${form_data.get('membership_name')}
Abonements tiks pagarināts līdz: {{ now()->addMonth()->format('Y-m-d') }}
Maksājuma summa: ${memberships_prices[form_data.get('membership_name')]}€
Maksājuma veids: ${payment_options_names[form_data.get('payment_method')]}

Nospiežot "Apstiprināt", jūs apstiprināt, ka klients ir veicis maksājumu.`;

            return confirm(confirm_message);
        }
        
    </script>
    @endif
@endsection