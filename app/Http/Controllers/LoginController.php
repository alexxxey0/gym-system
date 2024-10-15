<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

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

            //dd($credentials);

            if (Auth::guard('admin')->attempt(['login' => 'admin1', 'password' => $credentials['password']])) {
                $request->session()->regenerate();

                return redirect()->route('admin_homepage');
            }

            return back()->withErrors([
                'password' => 'Nepareiza parole!'
            ])->with('role', $role);
        } else {
            $credentials = $request->validate([
                'personal_id' => ['required',],
                'password' => ['required'],
            ], [
                'personal_id.required' => 'Lūdzu, ievadiet personas kodu!',
                'password.required' => 'Lūdzu, ievadiet paroli!'
            ]);
        }

        // Returning the role to the view so that we know which radio button to enable
        return redirect()->route('home')->with('role', $role);
    }
}
