@extends('layouts.client')

@section('title', 'Uzlabot manu abonementu')

@section('content')

<style>
    html, body {
        height: 100%;
    }
</style>

<h1 class='text-center font-bold text-3xl mt-12'>Uzlabot manu abonementu</h1>

<form id='payment-form' class='w-1/2 mx-auto mt-8 flex flex-col gap-y-4'>
    <div class='flex flex-col'>
        <label for="membership_name">Vēlamais abonementa veids</label>
        <select name="membership_name" id="membership_name" class='rounded-md'>
            @foreach($more_expensive_memberships as $membership)
                <option value="{{ $membership->membership_name }}">{{ $membership->membership_name }}</option>
            @endforeach
        </select>
    </div>

    <div class='flex flex-col gap-y-2 mt-4'>
        <p><span class='font-bold'>Maksājuma summa: </span><span id='amount'>0</span>€</p>
        <p>Maksājuma summa ir starpība starp izvēlēto un pašreizējo abonementu.</p>
        <div id="card-element" class='border-[#007bff] border-2 rounded-md p-4'></div>
        <div id="card-errors" role="alert" class='text-red-500'></div>
    </div>

    <x-main_button type='submit' class='w-fit mx-auto p-4'>Veikt maksājumu</x-main_button>
</form>

<form id='upgrade_membership_form' action="{{ route('change_client_membership') }}" method="POST" class='hidden'>
    @csrf
    <input type="hidden" name='payment_completed' value="1">
    <input type="hidden" name="client_id" value="{{ Auth::user()->client_id }}">
    <input type="hidden" name="membership_name" id='upgrade_form_membership_name'>
    <input type="hidden" name="payment_method" value="Stripe">
    <input type="hidden" name='status' id='status'>
</form>

<script src="https://js.stripe.com/v3/"></script>

<script>
    const membership_name_selection = document.querySelector('#membership_name');
    const memberships_prices = {!! $memberships_prices !!};
    const user_membership_price = {{ $user_membership_price }};

    let selected_membership = membership_name_selection.value;
    let selected_membership_price = memberships_prices[selected_membership];
    const amount = document.querySelector('#amount');
    amount.textContent = Number(selected_membership_price) - Number(user_membership_price);


    // Define custom styles for the card element
    const style = {
        base: {
            color: '#000000', // Text color
            lineHeight: '24px',
            fontFamily: 'Arial, sans-serif',
            fontSize: '16px',
            fontSmoothing: 'antialiased',
            '::placeholder': {
                color: '#a9a9a9', // Placeholder text color
            },
        },
        invalid: {
            color: '#e54242', // Invalid card error text color
            iconColor: '#e54242',
        },
        focus: {
            // Remove the border when the element is focused
            border: 'none',
            outline: 'none',  // Removes the outline around the element
        },
    };

    const stripe = Stripe("{{ config('services.stripe.key') }}");
    const elements = stripe.elements();
    const cardElement = elements.create('card', {
        style: style,
        hidePostalCode: true
    });
    cardElement.mount('#card-element');

    const form = document.getElementById('payment-form');
    form.addEventListener('submit', async (event) => {
        event.preventDefault();

        console.log()

        const { clientSecret } = await fetch('{{ route("get_client_secret") }}', {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({
                amount: parseFloat(amount.textContent),
                _token: "{{ csrf_token() }}"
            }),
        }).then(res => res.json());

        const { paymentIntent, error } = await stripe.confirmCardPayment(clientSecret, {
            payment_method: { card: cardElement }
        });

        if (error) {
            document.getElementById('card-errors').textContent = error.message;
        } else {
            const upgrade_membership_form = document.querySelector('#upgrade_membership_form');
            document.querySelector('#upgrade_form_membership_name').value = selected_membership;
            document.querySelector('#status').value = paymentIntent.status;
            upgrade_membership_form.submit();
        }
    });

    membership_name_selection.addEventListener('change', function() {
        selected_membership = this.value;
        selected_membership_price = memberships_prices[selected_membership];
        amount.textContent = Number(selected_membership_price) - Number(user_membership_price);
    });

</script>

@endsection