<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Client;
use App\Models\Payment;
use App\Models\Membership;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redis;

// This controller is responsible for client-related actions that are avaiable to the administrator. It allows him to view clients' data, modify it etc.

class ClientController extends Controller {

    public function list_clients() {
        $clients = Client::all();
        $displayed_attributes = ['Personas kods', 'Vārds', 'Uzvārds', 'Telefona numurs', 'E-pasts', 'Abonementa veids', 'Abonements derīgs līdz:'];

        // Get client's membership name
        for ($i = 0; $i < count($clients); $i++) {
            if (isset($clients[$i]->membership_id)) {
                $membership_name = Membership::select('membership_name')->where('membership_id', $clients[$i]->membership_id)->value('membership_name');
                $clients[$i]['membership_name'] = $membership_name;
            }
        }

        return view('admin.clients_list', [
            'clients' => $clients,
            'displayed_attributes' => $displayed_attributes,
            'attribute_count' => count($displayed_attributes)
        ]);
    }

    public function view_client_profile(Request $request) {
        $client = Client::where('client_id', $request->client_id)->first();

        $membership_id = $client->membership_id;
        $membership_name = Membership::select('membership_name')->where('membership_id', $membership_id)->value('membership_name');
        $client['membership_name'] = $membership_name;

        return view('admin.client_profile', [
            'client' => $client
        ]);
    }

    public function edit_profile_page(Request $request) {
        $client = Client::where('client_id', $request->client_id)->first();
        $memberships = Membership::all();

        $membership_id = $client->membership_id;
        $membership_name = Membership::select('membership_name')->where('membership_id', $membership_id)->value('membership_name');
        $client['membership_name'] = $membership_name;

        return view('admin.edit_client_profile', [
            'client' => $client,
            'memberships' => $memberships
        ]);
    }

    public function edit_profile(Request $request) {
        $client = Client::where('client_id', $request->client_id)->first();

        $error_messages = [
            'name.required' => 'Vārds ir obligāts lauks.',
            'name.max' => 'Vārds nevar būt garāks par 30 simboliem.',

            'surname.required' => 'Uzvārds ir obligāts lauks.',
            'surname.max' => 'Uzvārds nevar būt garāks par 30 simboliem.',

            'personal_id.required' => 'Personas kods ir obligāts lauks.',
            'personal_id.regex' => 'Personas kods ir nepareizā formātā. Tam jābūt formātā 123456-12345.',
            'personal_id.max' => 'Personas kods nevar būt garāks par 12 simboliem.',
            'personal_id.unique' => 'Šāds personas kods jau ir reģistrēts.',

            'phone.required' => 'Telefona numurs ir obligāts lauks.',
            'phone.max' => 'Telefona numurs nevar būt garāks par 20 simboliem.',
            'phone.unique' => 'Šāds telefona numurs jau ir reģistrēts.',

            'email.required' => 'E-pasts ir obligāts lauks.',
            'email.max' => 'E-pasta adrese nevar būt garāka par 50 simboliem.',
            'email.unique' => 'Šāda e-pasta adrese jau ir reģistrēta.',
        ];

        $form_data = $request->validate([
            'name' => ['required', 'max:30'],
            'surname' => ['required', 'max:30'],
            'personal_id' => ['required', 'regex:/^\d{6}-?\d{5}$/', 'max:12', 'unique:clients,personal_id,' . $client->client_id . ',client_id'],
            'phone' => ['required', 'max:20', 'unique:clients,phone,' . $client->client_id . ',client_id'],
            'email' => ['required', 'max:50', 'unique:clients,email,' . $client->client_id . ',client_id']
        ], $error_messages);

        $client->update([
            'name' => $form_data['name'],
            'surname' => $form_data['surname'],
            'personal_id' => $form_data['personal_id'],
            'phone' => $form_data['phone'],
            'email' => $form_data['email']
        ]);

        return redirect()->back()->with('message', 'Klienta dati veiksmīgi rediģēti!');
    }

}
