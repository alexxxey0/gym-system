<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Coach;
use App\Models\Client;
use App\Models\Membership;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

// This controller is responsible for actions that are available to clients and coaches.

class UserController extends Controller {

    public function user_profile_page(Request $request) {
        $user = Auth::user();
        $membership_expired = false;

        if ($user->role === 'client') {
            $membership_id = $user->membership_id;
            $membership_name = Membership::select('membership_name')->where('membership_id', $membership_id)->value('membership_name');
            $user['membership_name'] = $membership_name;

            if (isset($user->membership_until) and Carbon::parse($user->membership_until)->isPast()) {
                $membership_expired = true;
            }
        }

        if (isset($request->payment_completed)) {
            return view('user.user_profile', [
                'user' => $user,
                'membership_expired' => $membership_expired,
                'message' => 'Jūsu abonements veiksmīgi pagarināts!'
            ]);
        }

        return view('user.user_profile', [
            'user' => $user,
            'membership_expired' => $membership_expired
        ]);
    }

    public function change_password_page() {
        return view('user.change_password');
    }

    public function change_password(Request $request) {

        if (Auth::user()->role === 'client') {
            $user = Client::where('client_id', Auth::user()->client_id);
        } else {
            $user = Coach::where('coach_id', Auth::user()->coach_id);
        }

        // Check if the current password is correct
        if (!Hash::check($request->old_password, Auth::user()->password)) {
            throw ValidationException::withMessages([
                'old_password' => 'Pašreizējā parole ir nepareiza!',
            ]);
        }

        $messages = [
            'new_password.confirmed' => 'Jauna paroles apstiprinājums nesakrīt ar jaunu paroli',
            'new_password.min' => 'Parolei ir jābūt vismaz 6 simbolu garai.'
        ];

        $form_data = $request->validate([
            'old_password' => ['required'],
            'new_password' => ['required', 'confirmed', 'min:6']
        ], $messages);

        $new_password_hash = Hash::make($request->new_password);
        $user->update(['password' => $new_password_hash]);

        return redirect()->back()->with('message', 'Parole veiksmīgi nomainīta!');
    }

    public function edit_public_profile_page() {

        return view('user.edit_public_profile');
    }
}
