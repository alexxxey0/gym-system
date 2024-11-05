<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

// This controller is responsible for actions that are related to group trainings

class GroupTrainingController extends Controller
{
    public function create_new_group_training_page() {
        return view('coach.create_new_group_training');
    }
}
