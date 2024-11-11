<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Coach;
use Illuminate\Http\Request;
use App\Models\GroupTraining;
use App\Rules\valid_schedule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

// This controller is responsible for actions that are related to group trainings

class GroupTrainingController extends Controller {
    public function create_new_group_training_page() {
        if (Auth::user()->role === 'admin') {
            $coaches = Coach::all();
        } else {
            $coaches = null;
        }

        return view('coach.create_new_group_training', [
            'coaches' => $coaches,
        ]);
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

        // Save the profile picture to the server
        if (isset($request['image'])) {
            $image = $request->file('image');
            $path = $image->store('group_trainings_pictures', 'public');
        }

        // Save information about the group training to the database
        GroupTraining::create([
            'name' => $form_data['title'],
            'description' => $form_data['description'],
            'coach_id' => $request['coach_id'],
            'schedule' => json_encode($form_data['schedule']),
            'clients_signed_up' => 0,
            'max_clients' => intval($form_data['max_participants']),
            'path_to_image' => $path ?? null,
            'active' => true
        ]);


        return redirect()->back()->with('message', 'Jauns grupu nodarbības veids veiksmīgi izveidots!');
    }

    public function our_group_trainings_page() {
        $group_trainings = GroupTraining::where('active', true)->get();

        for ($i = 0; $i < count($group_trainings); $i++) {
            $coach = Coach::where('coach_id', $group_trainings[$i]['coach_id'])->first();
            $group_trainings[$i]['coach'] = $coach;

            $group_trainings[$i]['schedule'] = json_decode($group_trainings[$i]['schedule'], true);
        }

        $days_translations = [
            'monday' => 'Pirmdiena',
            'tuesday' => 'Otrdiena',
            'wednesday' => 'Trešdiena',
            'thursday' => 'Ceturtdiena',
            'friday' => 'Piektdiena',
            'saturday' => 'Sestdiena',
            'sunday' => 'Svētdiena'
        ];

        return view('user.our_group_trainings', [
            'group_trainings' => $group_trainings,
            'days_translations' => $days_translations
        ]);
    }

    public function my_group_trainings() {
        $group_trainings = GroupTraining::where('coach_id', Auth::user()->coach_id)->where('active', true)->get();

        for ($i = 0; $i < count($group_trainings); $i++) {
            $group_trainings[$i]['schedule'] = json_decode($group_trainings[$i]['schedule'], true);
        }

        $days_translations = [
            'monday' => 'Pirmdiena',
            'tuesday' => 'Otrdiena',
            'wednesday' => 'Trešdiena',
            'thursday' => 'Ceturtdiena',
            'friday' => 'Piektdiena',
            'saturday' => 'Sestdiena',
            'sunday' => 'Svētdiena'
        ];

        return view('coach.my_group_trainings', [
            'group_trainings' => $group_trainings,
            'days_translations' => $days_translations
        ]);
    }

    public function edit_group_training_page(Request $request) {
        $group_training = GroupTraining::where('training_id', $request->training_id)->first();
        $group_training['schedule'] = json_decode($group_training['schedule'], true);

        if (Auth::user()->role === 'admin') {
            $coaches = Coach::all();
        } else {
            $coaches = null;
        }

        return view('coach.edit_group_training', [
            'group_training' => $group_training,
            'coaches' => $coaches
        ]);
    }

    public function edit_group_training(Request $request) {
        $days_eng = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];

        $schedule = array();
        foreach ($days_eng as $day) {
            if (isset($request[$day])) {
                $day_start_time = $request['start_time_' . $day];
                $day_end_time = $request['end_time_' . $day];
                $schedule[$day]['start'] = $day_start_time;
                $schedule[$day]['end'] = $day_end_time;
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

        // Save the profile picture to the server
        if (isset($request['image'])) {
            $image = $request->file('image');
            $path = $image->store('group_trainings_pictures', 'public');
        }

        $group_training = GroupTraining::where('training_id', $request->training_id)->first();

        // Save information about the group training to the database
        $group_training->update([
            'name' => $form_data['title'],
            'description' => $form_data['description'],
            'coach_id' => $request['coach_id'],
            'schedule' => json_encode($form_data['schedule']),
            'max_clients' => intval($form_data['max_participants']),
            'path_to_image' => $path ?? $group_training->path_to_image
        ]);


        return redirect()->back()->with('message', 'Nodarbības dati veiksmīgi rediģēti!');
    }

    public function cancel_group_training(Request $request) {
        $group_training = GroupTraining::where('training_id', $request->training_id)->first();

        if (Auth::user()->role === 'coach' and $group_training->coach_id !== Auth::user()->coach_id) {
            return redirect()->back()->with('message', 'Kļūda: jums nav tiesību atcelt šo nodarbības veidu!');
        } else {
            $group_training->update([
                'active' => false
            ]);

            return redirect()->back()->with('message', 'Nodarbības veids veiksmīgi atcelts!');
        }
    }
}
