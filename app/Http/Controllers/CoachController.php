<?php

namespace App\Http\Controllers;

use App\Models\Coach;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redis;

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

    public function edit_profile_page(Request $request) {
        $coach = Coach::where('coach_id', $request->coach_id)->first();

        return view('admin.edit_coach_profile', [
            'coach' => $coach
        ]);
    }

    public function edit_profile(Request $request) {
        $coach = Coach::where('coach_id', $request->coach_id)->first();

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
            'personal_id' => ['required', 'regex:/^\d{6}-?\d{5}$/', 'max:12', 'unique:coaches,personal_id,' . $coach->coach_id . ',coach_id'],
            'phone' => ['required', 'max:20', 'unique:coaches,phone,' . $coach->coach_id . ',coach_id'],
            'email' => ['required', 'max:50', 'unique:coaches,email,' . $coach->coach_id . ',coach_id']
        ], $error_messages);

        $coach->update([
            'name' => $form_data['name'],
            'surname' => $form_data['surname'],
            'personal_id' => $form_data['personal_id'],
            'phone' => $form_data['phone'],
            'email' => $form_data['email']
        ]);

        return redirect()->back()->with('message', 'Trenera dati veiksmīgi rediģēti!');
    }

    public function edit_public_profile_page(Request $request) {

        $coach = Coach::where('coach_id', $request->coach_id)->first();
        return view('admin.edit_public_profile', [
            'coach' => $coach
        ]);
    }

    public function edit_public_profile(Request $request) {
        if (isset(Auth::user()->coach_id)) {
            $coach_id = Auth::user()->coach_id;
        } else {
            $coach_id = $request->coach_id;
        }

        $coach = Coach::where('coach_id', $coach_id)->first();

        $messages = [
            'personal_description.max' => 'Personiskais apraksts nevar būt garāk par 2000 simboliem!',
            'contact_phone.max' => 'Kontakttelefons nevar būt garāks par 20 simboliem!',
            'contact_email.max' => 'Kontakte-pasts nevar būt garāks par 50 simboliem!',
            'profile_picture.image' => 'Augšupielādētajam failam ir jābūt attēlam!',
            'profile_picture.max' => 'Faila izmērs nedrīkst pārsniegt 5MB!'
        ];

        $form_data = $request->validate([
            'personal_description' => ['max:2000'],
            'contact_phone' => ['max:20'],
            'contact_email' => ['max:50'],
            'profile_picture' => ['image', 'max:5000', 'nullable']
        ], $messages);

        // Save the profile picture to the server
        if (isset($request['profile_picture'])) {
            $profile_picture = $request->file('profile_picture');
            $path = $profile_picture->store('coaches_profile_pictures', 'public');
        }

        $coach->update([
            'personal_description' => $form_data['personal_description'],
            'contact_phone' => $form_data['contact_phone'],
            'contact_email' => $form_data['contact_email'],
            'path_to_image' => $path ?? $coach->path_to_image
        ]);

        return redirect()->back()->with('message', 'Publiskā profila dati veiksmīgi atjaunoti!');
    }

    public function our_coaches_page() {
        $coaches = Coach::all();

        return view('user.our_coaches', [
            'coaches' => $coaches
        ]);
    }
}
