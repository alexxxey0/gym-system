<?php

namespace App\Http\Controllers;

use App\Models\Coach;
use App\Models\Client;
use App\Models\Payment;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Membership;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

// This controller is responsible for registrating new clients and coaches.

class RegistrationController extends Controller {

    // Show the page with the client registration form
    public function view_register_client_form(Request $request) {

        $memberships = Membership::all();

        // Array that matches every membership with its price (needed for updating membership price in JS)
        $memberships_prices = array();

        foreach ($memberships as $membership) {
            $memberships_prices[$membership->membership_name] = $membership->price;
        }

        return view('admin.register_client', [
            'memberships' => $memberships,
            'memberships_prices' => json_encode($memberships_prices)
        ]);
    }

    // Register a new client
    public function register_client(Request $request) {

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
            'phone' => ['required', 'unique:clients', 'regex:/^\d{8}$/'],
            'email' => ['required', 'max:50', 'unique:clients', 'email']
        ], $error_messages);

        $temporary_password = Str::random(10);

        if (isset($request->assign_membership)) {
            // Get the selected membership's ID
            $membership_name = $request->membership_name;
            $membership_id = Membership::select('membership_id')->where('membership_name', $membership_name)->value('membership_id');
            $date_month_from_now = now()->addMonth()->format('Y-m-d');
        } else {
            $membership_id = null;
            $date_month_from_now = null;
        }

        $client = Client::create([
            'name' => $form_data['name'],
            'surname' => $form_data['surname'],
            'personal_id' => $form_data['personal_id'],
            'password' => Hash::make($temporary_password),
            'phone' => $form_data['phone'],
            'email' => $form_data['email'],
            'role' => 'client',
            'membership_id' => $membership_id,
            'membership_until' => $date_month_from_now
        ]);

        // Send the temporary password to the user's email
        Mail::send('emails.client.welcome', ['name' => $client->name, 'surname' => $client->surname, 'personal_id' => $client->personal_id, 'temporary_password' => $temporary_password, 'membership_name' => $membership_name ?? null, 'membership_until' => $client->membership_until ?? null], function ($message) use ($client) {
            $message->to($client->email);
            $message->subject('Esiet sveicināti FitLife!');
        });

        // Save the payment data if the user is assigned a membership on registration
        if (isset($request->assign_membership)) {
            Payment::create([
                'client_id' => $client->client_id,
                'payment_method' => $request->payment_method,
                'payment_purpose' => 'Buying membership',
                'membership_id' => $client->membership_id,
                'payment_status' => 'COMPLETED',
                'amount' => floatval($request->amount),
                'completed_at' => now()
            ]);
        }

        return redirect()->back()->with('message', 'Klients veiksmīgi reģistrēts!');
    }

    // Register a new coach
    public function register_coach(Request $request) {

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
            'personal_id' => ['required', 'regex:/^\d{6}-?\d{5}$/', 'max:12', 'unique:coaches'],
            'phone' => ['required', 'unique:coaches', 'regex:/^\d{8}$/'],
            'email' => ['required', 'max:50', 'unique:coaches', 'email']
        ], $error_messages);

        $temporary_password = Str::random(10);

        $coach = Coach::create([
            'name' => $form_data['name'],
            'surname' => $form_data['surname'],
            'personal_id' => $form_data['personal_id'],
            'password' => Hash::make($temporary_password),
            'phone' => $form_data['phone'],
            'email' => $form_data['email'],
            'role' => 'coach'
        ]);

        // Send the temporary password to the user's email
        Mail::send('emails.coach.welcome', ['name' => $coach->name, 'surname' => $coach->surname, 'personal_id' => $coach->personal_id, 'temporary_password' => $temporary_password], function ($message) use ($coach) {
            $message->to($coach->email);
            $message->subject('Esiet sveicināti FitLife!');
        });

        return redirect()->back()->with('message', 'Treneris veiksmīgi reģistrēts!');
    }
}
