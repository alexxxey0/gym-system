<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Membership;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

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

    public function view_client_profile_as_client(Request $request) {
        $client = Auth::user();

        $membership_id = $client->membership_id;
        $membership_name = Membership::select('membership_name')->where('membership_id', $membership_id)->value('membership_name');
        $client['membership_name'] = $membership_name;

        return view('client.client_profile', [
            'client' => $client
        ]);
    }
}
