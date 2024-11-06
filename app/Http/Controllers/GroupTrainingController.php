<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Rules\valid_schedule;

// This controller is responsible for actions that are related to group trainings

class GroupTrainingController extends Controller {
    public function create_new_group_training_page() {
        return view('coach.create_new_group_training');
    }

    public function create_new_group_training(Request $request) {

        $days_eng = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];

        $schedule = array();
        foreach ($days_eng as $day) {
            if (isset($request[$day])) {
                $day_start_time = $request['start_time_' . $day];
                $day_end_time = $request['end_time_' . $day];
                $schedule[$day]['start'] = $day_start_time;
                $schedule[$day]['end'] = $day_end_time;
                $schedule[$day]['day'] = $day;
            }
        }
        $request['schedule'] = $schedule;

        $messages = [
            'title.required' => 'Nodarbības nosaukums ir obligāts lauks.',
            'title.max' => 'Nodarbības nosaukums nevar būt garāk par 50 simboliem.',
            'description.required' => 'Nodarbības apraksts ir obligāts lauks.',
            'description.max' => 'Nodarbības apraksts nevar būt garāk par 2000 simboliem.',
            'image.image' => 'Augšupieladētājam failam ir jābūt attēlam.',
            'max_participants.required' => 'Maksimālais apmeklētāju skaits ir obligāts lauks.',
            'max_participants.min' => 'Maksimālais apmeklētāju skaits nevar būt mazāk par 10.',
            'max_participants.max' => 'Maksimālais apmeklētāju skaits nevar būt lielāk par 50.'
        ];

        $form_data = $request->validate([
            'title' => ['required', 'max:50'],
            'description' => ['required', 'max:2000'],
            'image' => ['image', 'nullable'],
            'max_participants' => ['required', 'numeric', 'min:10', 'max:50'],
            'schedule' => [new valid_schedule]
        ], $messages);

        // Save information about the group training to the database


        return redirect()->back()->with('message', 'Jauns grupu nodarbības veids veiksmīgi izveidots!');
    }
}
