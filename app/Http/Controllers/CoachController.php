<?php

namespace App\Http\Controllers;

use App\Models\Coach;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CoachController extends Controller
{
    public function list_coaches() {
        $coaches = Coach::all();
        $displayed_attributes = ['Personas kods', 'Vārds', 'Uzvārds', 'Telefona numurs', 'E-pasts'];

        return view('admin.coaches_list', [
            'coaches' => $coaches,
            'displayed_attributes' => $displayed_attributes,
            'attribute_count' => count($displayed_attributes)
        ]);
    }
}
