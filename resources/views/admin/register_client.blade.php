@extends('layouts.admin')

@section('title', 'Jauna klienta reģistrācija')

@section('content')
    <h1 class='font-bold text-center mt-4 text-2xl'>Jauna klienta reģistrācija</h1>

    <!-- New client registration form -->

    <form action="{{ route('register_client_post') }}" method='POST' class='w-1/3 mx-auto flex flex-col gap-y-6 mt-6 mb-16' onsubmit="return show_registration_confirmation(this);">
        @csrf
        <div class='flex flex-col'>
            <label for="name">Vārds</label>
            <input type="text" required maxlength="30" class='rounded-md' name='name' value="{{ old('name') }}">
        </div>

        @if ($errors->has('name'))
            <div class='bg-[#f54242] text-white rounded-md p-2 mt-2'>{{ $errors->first('name') }}</div>
        @endif

        <div class='flex flex-col'>
            <label for="surname">Uzvārds</label>
            <input type="text" required maxlength="30" class='rounded-md' name='surname' value="{{ old('surname') }}">
        </div>

        @if ($errors->has('surname'))
            <div class='bg-[#f54242] text-white rounded-md p-2 mt-2'>{{ $errors->first('surname') }}</div>
        @endif

        <div class='flex flex-col'>
            <label for="personal_id">Personas kods</label>
            <input type="text" placeholder="123456-12345" required maxlength="12" class='rounded-md' name='personal_id' value="{{ old('personal_id') }}">
        </div>

        @if ($errors->has('personal_id'))
            <div class='bg-[#f54242] text-white rounded-md p-2 mt-2'>{{ $errors->first('personal_id') }}</div>
        @endif

        <div class='flex flex-col'>
            <label for="phone">Telefona numurs</label>
            <div class='flex flex-row gap-x-2'>
                <input type="text" value="+371" class='w-2/12 rounded-md' disabled>
                <input type="text" required maxlength="8" class='rounded-md w-10/12' name='phone' value="{{ old('phone') }}">
            </div>
        </div>

        @if ($errors->has('phone'))
            <div class='bg-[#f54242] text-white rounded-md p-2 mt-2'>{{ $errors->first('phone') }}</div>
        @endif

        <div class='flex flex-col'>
            <label for="email">E-pasts</label>
            <input type="email" name="email" required maxlength="50" class='rounded-md' name='email' value="{{ old('email') }}">
        </div>

        @if ($errors->has('email'))
            <div class='bg-[#f54242] text-white rounded-md p-2 mt-2'>{{ $errors->first('email') }}</div>
        @endif

        <div class="flex flex-col">
            <label for="gym">Sporta zāle</label>
            <select name="gym" id="" class='rounded-md' required>
                @foreach ($gyms as $gym)
                    <option value="{{ $gym->name }}">{{ $gym->name }}</option>
                @endforeach
            </select>
        </div>

        <div class='flex flex-row items-center gap-x-4'>
            <label for="assign_membership">Uzreiz piešķirt klientam abonementu</label>
            <input type="checkbox" name="assign_membership" id="assign_membership">
        </div>

        <div class='hidden flex-col membership_selection gap-y-8'>
            <div class='rounded-md border-2 border-gray-300 p-4 flex flex-col gap-y-2'>
                <label for="membership">Abonements</label>
                <select name="membership_name" id="membership_name" class='rounded-md'>
                    @foreach ($memberships as $membership)
                        <option value="{{ $membership->membership_name }}">{{ $membership->membership_name }}</option>
                    @endforeach
                </select>
                <p class='mt-2'>Abonements tiks piešķirts uz vienu mēnesi</p>
            </div>

            <div class='rounded-md border-2 border-gray-300 p-4 flex flex-col gap-y-2'>
                <p>Maksājuma summa: <span id='membership_price' class='font-bold'></span> €</p>
                <input type="hidden" name="amount" id='amount'>

                <label for="payment_method">Maksājuma veids</label>
                <select name="payment_method" id="payment_method" class='rounded-md'>
                    <option value="cash">Skaidra nauda</option>
                    <option value="card">Bankas karte</option>
                </select>
            </div>

            
        </div>

        <button type="submit" class='bg-[#007BFF] active:bg-[#0056b3] w-fit mx-auto py-2 px-6 text-white rounded-md text-xl font-bold'>Reģistrēt</button>
    </form>

    <script>
        const assign_membership_checkbox = document.querySelector('#assign_membership');
        const membership_selection = document.querySelector('.membership_selection');
        const membership_name = document.querySelector('#membership_name');
        const payment_method_selection = document.querySelector('#payment_method');

        assign_membership_checkbox.addEventListener('change', function() {
            if (this.checked) {
                membership_selection.classList.remove('hidden');
                membership_selection.classList.add('flex');
                membership_name.required = true;
                payment_method_selection.required = true;
            } else {
                membership_selection.classList.remove('flex');
                membership_selection.classList.add('hidden');
                membership_name.required = false;
                payment_method_selection.required = false;
            }
        });

        const membership_price = document.querySelector('#membership_price');
        const memberships_prices = {!! $memberships_prices !!};
        
        // Initial displaying of the membership's price
        const selected_membership = membership_name.value;
        membership_price.textContent = memberships_prices[selected_membership];

        membership_name.addEventListener('change', function() {
            // Updating selected membership's price
            const selected_membership = this.value;
            membership_price.textContent = memberships_prices[selected_membership];
        });

        function show_registration_confirmation(form) {
            let form_data = new FormData(form);

            if (!assign_membership_checkbox.checked) {
                let confirm_message = `
Jūs gribat reģistrēt jaunu klientu ar datiem:
Vārds: ${form_data.get('name')}
Uzvārds: ${form_data.get('surname')}
Personas kods: ${form_data.get('personal_id')}
Telefona numurs: ${form_data.get('phone')}
E-pasts: ${form_data.get('email')}
Sporta zāle: ${form_data.get('gym')}

Nospiežot "Apstiprināt", jūs apstiprināt, ka klienta dati ir patiesi.`;

                return confirm(confirm_message);
            } else {
                const payment_options_names = {'card': 'Bankas karte', 'cash': 'Skaidra nauda'};

                const amount_input = document.querySelector('#amount');
                amount_input.value = membership_price.textContent;

                let confirm_message = `
Jūs gribat reģistrēt jaunu klientu ar datiem:
Vārds: ${form_data.get('name')}
Uzvārds: ${form_data.get('surname')}
Personas kods: ${form_data.get('personal_id')}
Telefona numurs: ${form_data.get('phone')}
E-pasts: ${form_data.get('email')}

Klientam tiks piešķirts abonements: ${form_data.get('membership_name')}
Maksājuma summa: ${membership_price.textContent} €
Maksājuma veids: ${payment_options_names[form_data.get('payment_method')]}


Nospiežot "Apstiprināt", jūs apstiprināt, ka klienta dati ir patiesi un klients ir veicis maksājumu.`;

                return confirm(confirm_message);
            }
        }
    </script>
@endsection