<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Payment;
use App\Models\Membership;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

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

        return redirect()->route('view_client_profile', ['client_id' => $request->client_id])->with('message', 'Klienta abonements veiksmīgi pagarināts!');
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

        return redirect()->route('view_client_profile', ['client_id' => $request->client_id])->with('message', 'Klienta abonements veiksmīgi pagarināts!');
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
}
