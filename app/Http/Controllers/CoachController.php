<?php

namespace App\Http\Controllers;

use App\Models\Coach;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CoachController extends Controller
{
    public function list_coaches() {
        $coaches = Coach::all();

        return view('admin.coaches_list', [
            'coaches' => $coaches
        ]);
    }
}
