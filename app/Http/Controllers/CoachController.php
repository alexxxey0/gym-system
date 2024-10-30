<?php

namespace App\Http\Controllers;

use App\Models\Coach;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

// This controller is responsible for coach-related actions that are avaiable to the administrator. It allows him to view coaches' data, modify it etc.

class CoachController extends Controller {
    public function list_coaches() {
        $coaches = Coach::all();
        $displayed_attributes = ['Personas kods', 'Vārds', 'Uzvārds', 'Telefona numurs', 'E-pasts'];

        return view('admin.coaches_list', [
            'coaches' => $coaches,
            'displayed_attributes' => $displayed_attributes,
            'attribute_count' => count($displayed_attributes)
        ]);
    }

    public function view_coach_profile(Request $request) {
        $coach = Coach::where('coach_id', $request->coach_id)->first();

        return view('admin.coach_profile', [
            'coach' => $coach
        ]);
    }
}
