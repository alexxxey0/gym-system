<?php

use App\Http\Controllers\ClientController;
use App\Http\Controllers\CoachController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('welcome');
})->name('login');

Route::post('/login', [LoginController::class, 'authenticate'])->name('login_post');

// Routes for clients and coaches
Route::middleware(['auth:client,coach'])->group(function () {

    // Change password page
    Route::get('/change_password', [UserController::class, 'change_password_page'])->name('change_password_page');

    // User's own profile page
    Route::get('/my_profile', [UserController::class, 'user_profile_page'])->name('user_profile_page');

});

// Admin routes
Route::middleware(['auth:admin'])->group(function () {

    // Admin's homepage
    Route::get('/admin_homepage', function () {
        return view('admin.admin_homepage');
    })->name('admin_homepage');

    // Register new client page
    Route::get('/register_client', [RegistrationController::class, 'view_register_client_form'])->name('register_client');

    // Register new client (action)
    Route::post('/register_client_post', [RegistrationController::class, 'register_client'])->name('register_client_post');

    // Register new coach page
    Route::get('/register_coach', function () {
        return view('admin.register_coach');
    })->name('register_coach');

    // Register new coach (action)
    Route::post('/register_coach_post', [RegistrationController::class, 'register_coach'])->name('register_coach_post');

    // View the list of all clients
    Route::get('/clients_list', [ClientController::class, 'list_clients'])->name('clients_list');

    // View the list of all coaches
    Route::get('/coaches_list', [CoachController::class, 'list_coaches'])->name('coaches_list');

    // View client's profile page (admin's view)
    Route::get('/client/{client_id}', [ClientController::class, 'view_client_profile'])->name('view_client_profile');

    // View coach's profile page
    Route::get('/coach/{coach_id}', [CoachController::class, 'view_coach_profile'])->name('view_coach_profile');

    // Admin's logout
    Route::post('/logout_admin', function () {
        Auth::guard('admin')->logout();
        return redirect()->route('login');
    })->name('logout_admin');
});

// Routes for clients

Route::middleware(['auth:client'])->group(function () {

    // Client's homepage
    Route::get('/client_homepage', function () {
        return view('client.client_homepage');
    })->name('client_homepage');

    // Client's logout
    Route::post('/logout_client', function () {
        Auth::guard('client')->logout();
        return redirect()->route('login');
    })->name('logout_client');
});


// Routes for coaches
Route::middleware(['auth:coach'])->group(function () {

    // Coach's homepage
    Route::get('/coach_homepage', function () {
        return view('coach.coach_homepage');
    })->name('coach_homepage');

    // Coach's profile
    Route::get('/coach_profile', function () {
        return view('coach.coach_profile');
    })->name('coach_profile');

    // Coach's logout
    Route::post('/logout_coach', function () {
        Auth::guard('coach')->logout();
        return redirect()->route('login');
    })->name('logout_coach');
});


// require __DIR__ . '/auth.php';
