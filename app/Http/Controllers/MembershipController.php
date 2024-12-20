<?php

namespace App\Http\Controllers;

use Stripe\Stripe;
use App\Models\Client;
use App\Models\Payment;
use Stripe\PaymentIntent;
use App\Models\Membership;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redis;
use App\Rules\valid_membership_entry_times;

class MembershipController extends Controller {

    public function extend_membership_page(Request $request) {
        $client = Client::where('client_id', $request->client_id)->first();

        $membership_id = $client->membership_id;
        $membership_name = Membership::select('membership_name')->where('membership_id', $membership_id)->value('membership_name');
        $client['membership_name'] = $membership_name;

        $memberships = Membership::all();

        // Array that matches every membership with its price (needed for updating membership price in JS)
        $memberships_prices = array();

        foreach ($memberships as $membership) {
            $memberships_prices[$membership->membership_name] = $membership->price;
        }

        return view('admin.extend_client_membership', [
            'client' => $client,
            'memberships' => $memberships,
            'memberships_prices' => json_encode($memberships_prices)
        ]);
    }

    public function extend_client_membership(Request $request) {
        if (Auth::user()->role === 'client' and $request->status !== 'succeeded') {
            return redirect()->back()->with('message', 'Kļūda: maksājums neizdevās!');
        }

        $client = Client::where('client_id', $request->client_id)->first();
        $membership_id = Membership::select('membership_id')->where('membership_name', $request->membership_name)->value('membership_id');
        $membership_price = Membership::select('price')->where('membership_name', $request->membership_name)->value('price');

        // Update data about client's membership
        $client->update([
            'membership_until' => now()->addMonth()->format('Y-m-d'),
            'membership_id' => $membership_id
        ]);

        // Save the data about the payment
        Payment::create([
            'client_id' => $request['client_id'],
            'payment_method' => $request['payment_method'],
            'payment_purpose' => 'Extending membership',
            'membership_id' => $membership_id,
            'payment_status' => 'COMPLETED',
            'amount' => floatval($membership_price),
            'completed_at' => now()
        ]);

        if (Auth::user()->role === 'admin') {
            return redirect()->route('view_client_profile', ['client_id' => $request->client_id])->with('message', 'Klienta abonements veiksmīgi pagarināts!');
        }
        return redirect()->route('user_profile_page')->with('message', 'Abonements veiksmīgi pagarināts!');
    }

    public function change_membership_page(Request $request) {
        $client = Client::where('client_id', $request->client_id)->first();

        $membership_id = $client->membership_id;
        $membership_name = Membership::select('membership_name')->where('membership_id', $membership_id)->value('membership_name');
        $client['membership_name'] = $membership_name;

        $memberships = Membership::all();

        // Array that matches every membership with its price (needed for updating membership price in JS)
        $memberships_prices = array();

        foreach ($memberships as $membership) {
            $memberships_prices[$membership->membership_name] = $membership->price;
        }

        return view('admin.change_client_membership', [
            'client' => $client,
            'memberships' => $memberships,
            'memberships_prices' => json_encode($memberships_prices)
        ]);
    }

    public function change_client_membership(Request $request) {
        if (Auth::user()->role === 'client' and $request->status !== 'succeeded') {
            return redirect()->back()->with('message', 'Kļūda: maksājums neizdevās!');
        }

        $client = Client::where('client_id', $request->client_id)->first();
        $membership_id = Membership::select('membership_id')->where('membership_name', $request->membership_name)->value('membership_id');
        $membership_price = Membership::select('price')->where('membership_name', $request->membership_name)->value('price');
        $client_membership_price = Membership::select('price')->where('membership_id', $client->membership_id)->value('price');

        // Update data about client's membership
        $client->update([
            'membership_id' => $membership_id
        ]);

        // Save the data about the payment
        $amount_to_pay = $membership_price - $client_membership_price;

        if ($amount_to_pay > 0) {
            Payment::create([
                'client_id' => $request['client_id'],
                'payment_method' => $request['payment_method'],
                'payment_purpose' => 'Upgrading membership',
                'membership_id' => $membership_id,
                'payment_status' => 'COMPLETED',
                'amount' => floatval($amount_to_pay),
                'completed_at' => now()
            ]);
        }

        if (Auth::user()->role === 'admin') {
            return redirect()->route('view_client_profile', ['client_id' => $request->client_id])->with('message', 'Klienta abonements veiksmīgi pagarināts!');
        }
        return redirect()->route('user_profile_page')->with("message", "Abonements veiksmīgi uzlabots līdz $request->membership_name!");
    }

    public function nullify_client_membership(Request $request) {
        $client = Client::where('client_id', $request->client_id)->first();

        $client->update([
            'membership_id' => null,
            'membership_until' => null
        ]);

        return redirect()->route('view_client_profile', ['client_id' => $request->client_id])->with('message', 'Klienta abonements veiksmīgi anulēts!');
    }

    public function our_memberships() {
        $memberships = Membership::all();

        return view('user.our_memberships', [
            'memberships' => $memberships
        ]);
    }


    public function extend_my_membership_page() {
        $memberships = Membership::all();

        $memberships_prices = array();
        foreach ($memberships as $membership) {
            $memberships_prices[$membership->membership_name] = $membership->price;
        }

        return view('client.extend_my_membership', [
            'memberships' => $memberships,
            'memberships_prices' => json_encode($memberships_prices)
        ]);
    }

    public function get_client_secret(Request $request) {

        $request->validate([
            'amount' => 'required|numeric',
        ]);

        try {
            Stripe::setApiKey(config('services.stripe.secret'));

            $paymentIntent = PaymentIntent::create([
                'amount' => intval($request->amount * 100), // Amount in euro cents
                'currency' => 'eur',
                'payment_method_types' => ['card'],
            ]);

            return response()->json([
                'clientSecret' => $paymentIntent->client_secret,
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function upgrade_my_membership_page() {
        $user_membership_price = Membership::select('price')->where('membership_id', Auth::user()->membership_id)->value('price');

        // Select only the memberships that are more expensive than the current user's membership
        $memberships = Membership::where('price', '>', $user_membership_price)->get();
        $more_expensive_memberships = array();
        foreach ($memberships as $membership) {
            if (floatval($membership->price) > floatval($user_membership_price)) $more_expensive_memberships[] = $membership;
        }

        $memberships_prices = array();
        foreach ($memberships as $membership) {
            $memberships_prices[$membership->membership_name] = $membership->price;
        }

        return view('client.upgrade_my_membership', [
            'memberships' => $memberships,
            'memberships_prices' => json_encode($memberships_prices),
            'more_expensive_memberships' => $more_expensive_memberships,
            'user_membership_price' => $user_membership_price
        ]);
    }


    public function create_new_membership_page() {

        return view('admin.create_new_membership');
    }


    public function create_new_membership(Request $request) {

        $entry_times = array();
        $entry_times['entry_from_workdays'] = $request->entry_from_workdays;
        $entry_times['entry_until_workdays'] = $request->entry_until_workdays;
        $entry_times['entry_from_weekends'] = $request->entry_from_weekends;
        $entry_times['entry_until_weekends'] = $request->entry_until_weekends;
        $request['entry_times'] = $entry_times;

        $form_fields = $request->validate([
            'membership_name' => ['required', 'max:30', 'unique:memberships'],
            'price' => ['required', 'numeric', 'decimal:0,2', 'min:0.01', 'max:100'],
            'group_trainings_included' => ['required'],
            'entry_times' => [new valid_membership_entry_times]
        ]);

        $membership = Membership::create([
            'membership_name' => $form_fields['membership_name'],
            'price' => $form_fields['price'],
            'group_trainings_included' => $form_fields['group_trainings_included'] === 'yes' ? true : false,
            'entry_from_workdays' => $entry_times['entry_from_workdays'],
            'entry_until_workdays' => $entry_times['entry_until_workdays'],
            'entry_from_weekends' => $entry_times['entry_from_weekends'],
            'entry_until_weekends' => $entry_times['entry_until_weekends']
        ]);

        return redirect()->route('our_memberships')->with('message', 'Jauns abonementa veids veiksmīgi izveidots!');
    }
}
