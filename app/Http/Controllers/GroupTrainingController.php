<?php

namespace App\Http\Controllers;

use DateTime;
use Carbon\Carbon;
use App\Models\Gym;
use App\Models\Coach;
use App\Models\Client;
use App\Models\Attendance;
use App\Models\Membership;
use App\Models\Notification;
use Illuminate\Http\Request;
use App\Models\GroupTraining;
use App\Rules\valid_schedule;
use App\Models\ClientTraining;
use App\Models\CanceledTraining;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redis;

// This controller is responsible for actions that are related to group trainings

class GroupTrainingController extends Controller {
    public function create_new_group_training_page() {
        if (Auth::user()->role === 'admin') {
            $coaches = Coach::all();
        } else {
            $coaches = null;
        }

        $gyms = Gym::all();

        return view('coach.create_new_group_training', [
            'coaches' => $coaches,
            'gyms' => $gyms
        ]);
    }

    public function create_new_group_training(Request $request) {

        $days_eng = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];

        // "schedule" array is used to build the group training's schedule from the request
        $schedule = array();

        foreach ($days_eng as $day) {
            // If the user has selected that the training is taking place on some day, add training's start and end times this day to the "schedule" array
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
            'schedule' => [new valid_schedule], // custom validation rule
            'gym' => ['required']
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
            'active' => true,
            'gym_id' => $form_data['gym']
        ]);


        return redirect()->back()->with('message', 'Jauns grupu nodarbības veids veiksmīgi izveidots!');
    }

    public function our_group_trainings_page() {

        // If the user is a client, check whether his membership includes group trainings
        $group_trainings_included = null;
        if (Auth::user()->role === 'client' and isset(Auth::user()->membership_id)) {
            $membership = Membership::where('membership_id', Auth::user()->membership_id)->first();

            if ($membership->group_trainings_included) $group_trainings_included = true;
            else $group_trainings_included = false;
        }

        $group_trainings = GroupTraining::where('active', true)->get();

        for ($i = 0; $i < count($group_trainings); $i++) {
            $coach = Coach::where('coach_id', $group_trainings[$i]['coach_id'])->first();
            $group_trainings[$i]['coach'] = $coach;
            $group_trainings[$i]['schedule'] = json_decode($group_trainings[$i]['schedule'], true);

            $group_trainings[$i]['gym'] = Gym::where('gym_id', $group_trainings[$i]['gym_id'])->first();

            if (Auth::user()->role === 'client') {
                if (ClientTraining::where('client_id', Auth::user()->client_id)->where('training_id', $group_trainings[$i]['training_id'])->exists()) {
                    $group_trainings[$i]['client_signed_up'] = true;
                } else {
                    $group_trainings[$i]['client_signed_up'] = false;
                }
            }
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

        $gyms = Gym::all();

        return view('user.our_group_trainings', [
            'group_trainings' => $group_trainings,
            'days_translations' => $days_translations,
            'group_trainings_included' => $group_trainings_included,
            'gyms' => $gyms
        ]);
    }

    public function my_group_trainings_coach() {
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

    public function my_group_trainings_client() {
        $client_trainings = ClientTraining::where('client_id', Auth::user()->client_id)->pluck('training_id')->toArray();
        $group_trainings = GroupTraining::whereIn('training_id', $client_trainings)->get();

        for ($i = 0; $i < count($group_trainings); $i++) {
            $group_trainings[$i]['schedule'] = json_decode($group_trainings[$i]['schedule'], true);

            $coach = Coach::where('coach_id', $group_trainings[$i]['coach_id'])->first();
            $group_trainings[$i]['coach'] = $coach;
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

        return view('client.my_group_trainings', [
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

        $gyms = Gym::all();

        return view('coach.edit_group_training', [
            'group_training' => $group_training,
            'coaches' => $coaches,
            'gyms' => $gyms
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
            'schedule' => [new valid_schedule],
            'gym' => ['required']
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
            'path_to_image' => $path ?? $group_training->path_to_image,
            'gym_id' => $form_data['gym']
        ]);


        return redirect()->back()->with('message', 'Nodarbības dati veiksmīgi rediģēti!');
    }

    public function cancel_group_training_type(Request $request) {
        $group_training = GroupTraining::where('training_id', $request->training_id)->first();

        if (Auth::user()->role === 'coach' and $group_training->coach_id !== Auth::user()->coach_id) {
            return redirect()->back()->with('message', 'Kļūda: jums nav tiesību atcelt šo nodarbības veidu!');
        } else {
            $group_training->update([
                'active' => false
            ]);

            // Delete records about the clients who are signed up for this group training
            ClientTraining::where('training_id', $request->training_id)->delete();

            return redirect()->back()->with('message', 'Nodarbības veids veiksmīgi atcelts!');
        }
    }

    public function sign_up_for_group_training(Request $request) {

        ClientTraining::create([
            'client_id' => Auth::user()->client_id,
            'training_id' => $request->training_id
        ]);

        // Increment the count of clients signed up by 1
        $group_training = GroupTraining::where('training_id', $request->training_id)->first();

        $group_training->update([
            'clients_signed_up' => intval($group_training->clients_signed_up) + 1
        ]);

        return redirect()->back()->with('message', 'Jūs veiksmīgi pieteicāties nodarbībai!');
    }

    public function quit_group_training(Request $request) {

        // Decrement the count of clients signed up by 1
        $group_training = GroupTraining::where('training_id', $request->training_id)->first();

        $group_training->update([
            'clients_signed_up' => intval($group_training->clients_signed_up) - 1
        ]);

        ClientTraining::where('client_id', Auth::user()->client_id)->where('training_id', $request->training_id)->delete();

        return redirect()->back()->with('message', 'Jūs veiksmīgi atteicāties no nodarbības!');
    }

    public function send_notification_page(Request $request) {
        $group_training = GroupTraining::where('training_id', $request->training_id)->first();

        return view('coach.send_notification_page', [
            'group_training' => $group_training
        ]);
    }

    public function send_notification(Request $request) {

        $form_data = $request->validate([
            'topic' => ['required', 'max:100'],
            'text' => ['required', 'max:1000']
        ]);

        if (Auth::user()->role === 'coach') {
            $coach_id = $request->coach_id;
            $from_admin = false;
        } else {
            $coach_id = null;
            $from_admin = true;
        }

        // Save the notification to the database
        Notification::create([
            'notification_topic' => $form_data['topic'],
            'notification_text' => $form_data['text'],
            'sender_coach_id' => $coach_id,
            'from_admin' => $from_admin,
            'receiver_training_id' => $request->training_id
        ]);


        // Send the notification to clients' emails
        $clients_ids = ClientTraining::where('training_id', $request->training_id)->pluck('client_id')->toArray();
        $clients = Client::whereIn('client_id', $clients_ids)->get();

        if (!$from_admin) {
            $coach = Coach::where('coach_id', $coach_id)->first();
            $coach_full_name = $coach->name . " " . $coach->surname;
        } else {
            $coach_full_name = null;
        }

        foreach ($clients as $client) {
            Mail::send('emails.notification', ['topic' => $form_data['topic'], 'text' => $form_data['text'], 'from_admin' => $from_admin, 'coach_full_name' => $coach_full_name], function ($message) use ($client, $form_data) {
                $message->to($client->email);
                $message->subject($form_data['topic']);
            });
        }

        if (Auth::user()->role === 'coach') {
            return redirect()->route('my_group_trainings_coach')->with('message', 'Paziņojums veiksmīgi nosūtīts!');
        } else {
            return redirect()->route('our_group_trainings')->with('message', 'Paziņojums veiksmīgi nosūtīts!');
        }
    }

    public function group_trainings_calendar() {

        $events = array();
        $group_trainings = GroupTraining::where('active', true)->get();
        if (Auth::user()->role === 'client') {
            $client_trainings = ClientTraining::where('client_id', Auth::user()->client_id)->pluck('training_id')->toArray();
        } else {
            $client_trainings = array();
        }

        foreach ($group_trainings as $group_training) {
            $schedule = json_decode($group_training->schedule, true);
            $coach = Coach::where('coach_id', $group_training->coach_id)->first();
            $clients_count = count(ClientTraining::where('training_id', $group_training->training_id)->get()->toArray());

            $gym = Gym::where('gym_id', $group_training->gym_id)->first();

            foreach ($schedule as $day => $times) {
                // Add trainings to the calendar starting from the last week and up to 4 weeks ahead

                $current_date = new DateTime();
                $weekday = new DateTime();
                $weekday->modify('last week');
                $weekday->modify($day);

                for ($i = 0; $i < 5; $i++) {
                    $event = array();
                    $event['title'] = $group_training->name;

                    // Split the time string into hours and minutes
                    list($start_hour, $start_minute) = explode(':', $times['start']);
                    list($end_hour, $end_minute) = explode(':', $times['end']);

                    $start_time = clone $weekday;
                    $end_time = clone $weekday;
                    $start_time->setTime($start_hour, $start_minute);
                    $end_time->setTime($end_hour, $end_minute);
                    $event['start'] = $start_time->format('Y-m-d\TH:i:s');
                    $event['end'] = $end_time->format('Y-m-d\TH:i:s');
                    $event['extendedProps'] = array();
                    $event['extendedProps']['coach_id'] = $group_training->coach_id;
                    $event['extendedProps']['coach_full_name'] = $coach->name . " " . $coach->surname;
                    $event['extendedProps']['gym_name'] = $gym->name;
                    $event['extendedProps']['gym_id'] = $gym->gym_id;
                    $event['extendedProps']['time_and_date'] = $start_time->format('Y-m-d') . " " . $start_time->format('H:i') . "-" . $end_time->format('H:i');
                    $event['extendedProps']['training_id'] = $group_training->training_id;
                    $event['extendedProps']['training_date'] = $start_time->format('Y-m-d');
                    $event['extendedProps']['clients_count'] = $clients_count;

                    $event['classNames'] = array();
                    if (CanceledTraining::where('training_id', $group_training->training_id)->where('training_date', $start_time->format('Y-m-d'))->exists()) {
                        $event['classNames'][] = 'canceled_training_bg_lg';
                        $event['extendedProps']['canceled'] = true;
                    } else {
                        $event['extendedProps']['canceled'] = false;
                    }

                    if (in_array($group_training->training_id, $client_trainings)) {
                        $event['extendedProps']['client_signed_up'] = true;
                    } else {
                        $event['extendedProps']['client_signed_up'] = false;
                    }

                    if ($start_time > $current_date) {
                        $event['backgroundColor'] = "#007bff";
                        $event['borderColor'] = "#007bff";
                    } else if ($end_time < $current_date) {
                        $event['backgroundColor'] = "#a9a9a9";
                        $event['borderColor'] = "#a9a9a9";
                    } else {
                        $event['backgroundColor'] = "#50C878";
                        $event['borderColor'] = "#50C878";
                    }

                    $events[] = $event;

                    $weekday->modify('+1 week');
                }
            }
        }

        $gyms = Gym::all();

        return view('user.group_trainings_calendar', [
            'events' => json_encode($events),
            'gyms' => $gyms
        ]);
    }

    public function cancel_group_training(Request $request) {
        $group_training = GroupTraining::where('training_id', $request->training_id)->first();

        if (Auth::user()->role === 'coach' and $group_training->coach_id !== Auth::user()->coach_id) {
            return redirect()->back()->with('message', 'Kļūda: jums nav tiesību atcelt šo nodarbību!');
        } else {
            CanceledTraining::create([
                'training_id' => $request->training_id,
                'training_date' => $request->training_date
            ]);

            // Send a notification to all the clients who are signed up to this training
            $clients_ids = ClientTraining::where('training_id', $group_training->training_id)->pluck('client_id')->toArray();
            $clients = Client::whereIn('client_id', $clients_ids)->get();

            foreach ($clients as $client) {
                Mail::send('emails.client.training_canceled', ['training_name' => $group_training->name, 'training_date' => $request->training_date], function ($message) use ($client, $group_training) {
                    $message->to($client->email);
                    $message->subject('Atcelta nodarbība: ' . $group_training->name);
                });
            }

            return redirect()->back()->with('message', 'Nodarbība veiksmīgi atcelta!');
        }
    }

    public function restore_group_training(Request $request) {
        $group_training = GroupTraining::where('training_id', $request->training_id)->first();

        if (Auth::user()->role === 'coach' and $group_training->coach_id !== Auth::user()->coach_id) {
            return redirect()->back()->with('message', 'Kļūda: jums nav tiesību atjaunot šo nodarbību!');
        } else {
            CanceledTraining::where('training_id', $request->training_id)->where('training_date', $request->training_date)->delete();

            // Send a notification to all the clients who are signed up to this training
            $clients_ids = ClientTraining::where('training_id', $group_training->training_id)->pluck('client_id')->toArray();
            $clients = Client::whereIn('client_id', $clients_ids)->get();

            foreach ($clients as $client) {
                Mail::send('emails.client.training_restored', ['training_name' => $group_training->name, 'training_date' => $request->training_date], function ($message) use ($client, $group_training) {
                    $message->to($client->email);
                    $message->subject('Atjaunota nodarbība: ' . $group_training->name);
                });
            }

            return redirect()->back()->with('message', 'Nodarbība veiksmīgi atjaunota!');
        }
    }

    public function mark_attendance_page(Request $request) {
        $clients_ids = ClientTraining::select('client_id')->where('training_id', $request->training_id)->pluck('client_id')->toArray();
        $clients = Client::whereIn('client_id', $clients_ids)->orderBy('surname', 'ASC')->get();
        $group_training = GroupTraining::where('training_id', $request->training_id)->first();

        $attendance = Attendance::where('training_id', $request->training_id)->where('training_date', $request->training_date)->get();
        $clients_attendance = array();
        if (count($attendance) > 0) {
            foreach ($attendance as $attendant) {
                $clients_attendance[$attendant->client_id] = $attendant->attended;
            }
        }


        return view('coach.mark_attendance', [
            'clients' => $clients,
            'group_training' => $group_training,
            'group_training_date' => $request->training_date,
            'clients_attendance' => $clients_attendance
        ]);
    }

    public function save_attendance(Request $request) {
        $training = GroupTraining::where('training_id', $request->training_id)->first();
        if (Auth::user()->role === 'coach' and $training->coach_id !== Auth::user()->coach_id) {
            return redirect()->back()->with('message', 'Kļūda! Jums nav tiesību atzīmēt apmeklējumu šai nodarbībai');
        }

        $attendance_exists = Attendance::where('training_id', $request->training_id)->where('training_date', $request->training_date)->exists();
        $clients_ids = ClientTraining::select('client_id')->where('training_id', $request->training_id)->pluck('client_id')->toArray();

        if (!$attendance_exists) {
            foreach ($clients_ids as $client_id) {
                if ($request['attended_client_' . $client_id] === 'yes') $attended = true;
                else $attended = false;

                Attendance::create([
                    'client_id' => $client_id,
                    'training_id' => $request->training_id,
                    'training_date' => $request->training_date,
                    'attended' => $attended,
                ]);
            }
        } else {
            foreach ($clients_ids as $client_id) {
                if ($request['attended_client_' . $client_id] === 'yes') $attended = true;
                else $attended = false;

                $attendance = Attendance::where('training_id', $request->training_id)->where('training_date', $request->training_date)->where('client_id', $client_id);
                $attendance->update([
                    'attended' => $attended
                ]);
            }
        }

        return redirect()->route('group_trainings_calendar')->with('message', 'Informācija par nodarbības apmeklējumu veiksmīgi saglabāta!');
    }
}
