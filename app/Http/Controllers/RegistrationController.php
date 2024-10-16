<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class RegistrationController extends Controller {

    // Register a new client
    public function register_client(Request $request) {

        function add_dash($personal_id) {
            // Check if the ID matches the pattern without a dash
            if (preg_match('/^\d{6}\d{5}$/', $personal_id)) {
                // Add a dash after the 6th digit
                $personal_id = preg_replace('/^(\d{6})(\d{5})$/', '$1-$2', $personal_id);
            }
            return $personal_id;
        }
        $request['personal_id'] = add_dash($request['personal_id']);

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
            'personal_id' => ['required', 'regex:/^\d{6}-?\d{5}$/', 'max:12', 'unique:clients'],
            'phone' => ['required', 'max:20', 'unique:clients'],
            'email' => ['required', 'max:50', 'unique:clients']
        ], $error_messages);

        $temporary_password = Str::random(10);

        $client = Client::create([
            'name' => $form_data['name'],
            'surname' => $form_data['surname'],
            'personal_id' => $form_data['personal_id'],
            'password' => Hash::make($temporary_password),
            'phone' => $form_data['phone'],
            'email' => $form_data['email'],
            'role' => 'client'
        ]);

        // Send the temporary password to the user's email
        Mail::send('emails.client.welcome', ['name' => $client->name, 'surname' => $client->surname, 'personal_id' => $client->personal_id, 'temporary_password' => $temporary_password], function ($message) use ($client) {
            $message->to($client->email);
            $message->subject('Esiet sveicināti FitLife!');
        });

        return redirect()->back()->with('message', 'Klients veiksmīgi reģistrēts!');
    }
}
