<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ClientController extends Controller
{
    public function list_clients() {
        $clients = Client::all();
        $displayed_attributes = ['Personas kods', 'Vārds', 'Uzvārds', 'Telefona numurs', 'E-pasts', 'Abonementa veids', 'Abonements derīgs līdz:'];

        return view('admin.clients_list', [
            'clients' => $clients,
            'displayed_attributes' => $displayed_attributes,
            'attribute_count' => count($displayed_attributes)
        ]);
    }
}
