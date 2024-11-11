<?php

use App\Http\Controllers\ClientController;
use App\Http\Controllers\CoachController;
use App\Http\Controllers\GroupTrainingController;
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

// Routes for any authenticated user
Route::middleware(['auth:client,coach,admin'])->group(function() {
    // "Our group trainings" page
    Route::get('/our_group_trainings', [GroupTrainingController::class, 'our_group_trainings_page'])->name('our_group_trainings');

});

// Routes for clients and coaches
Route::middleware(['auth:client,coach'])->group(function () {

    // Change password page
    Route::get('/change_password', [UserController::class, 'change_password_page'])->name('change_password_page');

    // Change password action
    Route::post('/change_password', [UserController::class, 'change_password'])->name('change_password');

    // User's own profile page
    Route::get('/my_profile', [UserController::class, 'user_profile_page'])->name('user_profile_page');

    // "Our coaches" page
    Route::get('/our_coaches', [CoachController::class, 'our_coaches_page'])->name('our_coaches');
});

// Routes for coaches and admin
Route::middleware('auth:coach,admin')->group(function () {

    // Create new group training page
    Route::get('/create_new_group_training', [GroupTrainingController::class, 'create_new_group_training_page'])->name('create_new_group_training_page');

    // Create new group training (action)
    Route::post('/create_new_group_training', [GroupTrainingController::class, 'create_new_group_training'])->name('create_new_group_training');

    // Edit group training page
    Route::get('/edit_group_training/{training_id}', [GroupTrainingController::class, 'edit_group_training_page'])->name('edit_group_training_page');
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

    // Client profile editing page
    Route::get('/client/{client_id}/edit', [ClientController::class, 'edit_profile_page'])->name('edit_client_profile_page');

    // Edit client's profile (action)
    Route::post('/client/edit', [ClientController::class, 'edit_profile'])->name('edit_client_profile');

    // Coach profile editing page
    Route::get('/coach/{coach_id}/edit', [CoachController::class, 'edit_profile_page'])->name('edit_coach_profile_page');

    // Edit coach's profile (action)
    Route::post('/coach/edit', [CoachController::class, 'edit_profile'])->name('edit_coach_profile');

    // Edit coach's public profile page (admin's view)
    Route::get('/coach/{coach_id}/edit_public_profile', [CoachController::class, 'edit_public_profile_page'])->name('edit_public_profile_admin_page');

    // Edit coach's public profile (action)
    Route::post('/coach/edit_public_profile', [CoachController::class, 'edit_public_profile'])->name('edit_public_profile_admin');

    // Extend client's membership page
    Route::get('client/{client_id}/extend_membership', [ClientController::class, 'extend_membership_page'])->name('extend_client_membership_page');

    // Extend client's membership (action)
    Route::post('/extend_client_membership', [ClientController::class, 'extend_client_membership'])->name('extend_client_membership');

    // Change client's membership page
    Route::get('client/{client_id}/change_membership', [ClientController::class, 'change_membership_page'])->name('change_client_membership_page');

    // Change client's membership (action)
    Route::post('/change_client_membership', [ClientController::class, 'change_client_membership'])->name('change_client_membership');

    // Nullify client's membership (action)
    Route::post('/nullify_client_membership', [ClientController::class, 'nullify_client_membership'])->name('nullify_client_membership');

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

    // Edit coach's public profile page (coach's view)
    Route::get('/edit_my_public_profile', [UserController::class, 'edit_public_profile_page'])->name('edit_public_profile_coach_page');

    // Edit coach's public profile (action)
    Route::post('/edit_my_public_profile', [CoachController::class, 'edit_public_profile'])->name('edit_public_profile_coach');

    // Coach's group trainings
    Route::get('/my_group_trainings', [GroupTrainingController::class, 'my_group_trainings'])->name('my_group_trainings');

    // Coach's logout
    Route::post('/logout_coach', function () {
        Auth::guard('coach')->logout();
        return redirect()->route('login');
    })->name('logout_coach');
});


// require __DIR__ . '/auth.php';
