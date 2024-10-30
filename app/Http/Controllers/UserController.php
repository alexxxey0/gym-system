<?php

namespace App\Http\Controllers;

use App\Models\Membership;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

// This controller is responsible for actions that are available to clients and coaches.

class UserController extends Controller
{   

    public function user_profile_page() {
        $user = Auth::user();

        if ($user->role === 'client') {
            $membership_id = $user->membership_id;
            $membership_name = Membership::select('membership_name')->where('membership_id', $membership_id)->value('membership_name');
            $user['membership_name'] = $membership_name;
        }

        return view('user.user_profile', [
            'user' => $user
        ]);
    }

    public function change_password_page() {
        return view('user.change_password');
    }
}
