<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;

class LoginController extends Controller {
    /**
     * Handle an authentication attempt.
     */
    public function authenticate(Request $request) {

        $role = $request->role;

        if ($role === 'admin') {

            $credentials = $request->validate([
                'password' => ['required'],
            ], [
                'password.required' => 'Lūdzu, ievadiet paroli!'
            ]);

            // Try to authenticate user as an administrator
            if (Auth::guard('admin')->attempt(['login' => 'admin1', 'password' => $credentials['password']])) {
                $request->session()->regenerate();

                return redirect()->route('admin_homepage');
            }
        } else {
            $credentials = $request->validate([
                'personal_id' => ['required'],
                'password' => ['required'],
            ], [
                'personal_id.required' => 'Lūdzu, ievadiet personas kodu!',
                'password.required' => 'Lūdzu, ievadiet paroli!'
            ]);

            // Try to authenticate user as a client

            if ($role === 'client') {
                if (Auth::guard('client')->attempt(['personal_id' => $credentials['personal_id'], 'password' => $credentials['password']])) {
                    $request->session()->regenerate();

                    return redirect()->route('client_homepage');
                }
            } else {
                // Try to authenticate user as a coach

                if (Auth::guard('coach')->attempt(['personal_id' => $credentials['personal_id'], 'password' => $credentials['password']])) {
                    $request->session()->regenerate();
                    return redirect()->route('coach_homepage');
                }
            }
        }

        // Returning the role to the view so that we know which radio button to enable
        return back()->withErrors([
            'password' => 'Nepareizs personas kods vai parole!'
        ])->with('role', $role);
    }
}
