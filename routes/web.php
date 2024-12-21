<?php

use App\Models\Membership;
use App\Models\GroupTraining;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GymController;
use PHPUnit\Framework\Attributes\Group;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CoachController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\MembershipController;
use App\Http\Controllers\StatisticsController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\GroupTrainingController;

Route::get('/', function () {
    return view('welcome');
})->name('login');

Route::post('/login', [LoginController::class, 'authenticate'])->name('login_post');

// Routes for any authenticated user
Route::middleware(['auth:client,coach,admin'])->group(function () {

    // Page with information about all group trainings
    Route::get('/our_group_trainings', [GroupTrainingController::class, 'our_group_trainings_page'])->name('our_group_trainings');

    // Page with information about available memberships
    Route::get('/our_memberships', [MembershipController::class, 'our_memberships'])->name('our_memberships');

    // Group trainings calendar
    Route::get('/group_trainings_calendar', [GroupTrainingController::class, 'group_trainings_calendar'])->name('group_trainings_calendar');

    // Page with information about all gyms
    Route::get('/our_gyms', [GymController::class, 'our_gyms'])->name('our_gyms');
});

// Routes for clients and coaches
Route::middleware(['auth:client,coach'])->group(function () {

    // Change password page
    Route::get('/change_password', [UserController::class, 'change_password_page'])->name('change_password_page');

    // Change password action
    Route::post('/change_password', [UserController::class, 'change_password'])->name('change_password');

    // User's own profile page
    Route::get('/my_profile', [UserController::class, 'user_profile_page'])->name('user_profile_page');
    Route::post('/my_profile', [UserController::class, 'user_profile_page'])->name('user_profile_page');

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

    // Edit group training (action)
    Route::post('/edit_group_training', [GroupTrainingController::class, 'edit_group_training'])->name('edit_group_training');

    // Send notification page
    Route::get('/send_notification_page/{training_id}', [GroupTrainingController::class, 'send_notification_page'])->name('send_notification_page');

    // Send notification (action)
    Route::post('/send_notification', [GroupTrainingController::class, 'send_notification'])->name('send_notification');

    // Cancel group training type
    Route::post('/cancel_group_training_type', [GroupTrainingController::class, 'cancel_group_training_type'])->name('cancel_group_training_type');

    // Cancel single group training
    Route::post('/cancel_group_training', [GroupTrainingController::class, 'cancel_group_training'])->name('cancel_group_training');

    // Restore single group training
    Route::post('/restore_group_training', [GroupTrainingController::class, 'restore_group_training'])->name('restore_group_training');

    // Mark group training attendance page
    Route::post('/mark_attendance', [GroupTrainingController::class, 'mark_attendance_page'])->name('mark_attendance_page');

    // Save group training attendance
    Route::post('/save_attendance', [GroupTrainingController::class, 'save_attendance'])->name('save_attendance');
});

// Routes for clients and admin
Route::middleware(['auth:client,admin'])->group(function () {

    // Extend client's membership (action)
    Route::post('/extend_client_membership', [MembershipController::class, 'extend_client_membership'])->name('extend_client_membership');
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
    Route::get('client/{client_id}/extend_membership', [MembershipController::class, 'extend_membership_page'])->name('extend_client_membership_page');

    // Change client's membership page
    Route::get('client/{client_id}/change_membership', [MembershipController::class, 'change_membership_page'])->name('change_client_membership_page');

    // Change client's membership (action)
    Route::post('/change_client_membership', [MembershipController::class, 'change_client_membership'])->name('change_client_membership');

    // Nullify client's membership (action)
    Route::post('/nullify_client_membership', [MembershipController::class, 'nullify_client_membership'])->name('nullify_client_membership');

    // Gym statistics page
    Route::get('/gym_statistics', [StatisticsController::class, 'gym_statistics_page'])->name('gym_statistics');

    // Create a new gym page
    Route::get('/create_new_gym', [GymController::class, 'create_new_gym_page'])->name('create_new_gym_page');

    // Create a new gym
    Route::post('/create_new_gym', [GymController::class, 'create_new_gym'])->name('create_new_gym');

    // Edit gym page
    Route::get('/edit_gym/{gym_id}', [GymController::class, 'edit_gym_page'])->name('edit_gym_page');

    // Edit gym (action
    Route::post('/edit_gym', [GymController::class, 'edit_gym'])->name('edit_gym');

    // Create new membership page
    Route::get('/create_new_membership', [MembershipController::class, 'create_new_membership_page'])->name('create_new_membership_page');

    // Create new membership (action)
    Route::post('/create_new_membership', [MembershipController::class, 'create_new_membership'])->name('create_new_membership');

    // Edit membership page
    Route::get('/edit_membership/{membership_id}', [MembershipController::class, 'edit_membership_page'])->name('edit_membership_page');

    // Edit membership (action)
    Route::post('/edit_membership', [MembershipController::class, 'edit_membership'])->name('edit_membership');

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

    // Sign up for group training
    Route::post('/sign_up_for_group_training', [GroupTrainingController::class, 'sign_up_for_group_training'])->name('sign_up_for_group_training');

    // Quit group training
    Route::post('/quit_group_training', [GroupTrainingController::class, 'quit_group_training'])->name('quit_group_training');

    // Client's group trainings
    Route::get('/my_group_trainings_client', [GroupTrainingController::class, 'my_group_trainings_client'])->name('my_group_trainings_client');

    // Extend membership page
    Route::get('/extend_my_membership', [MembershipController::class, 'extend_my_membership_page'])->name('extend_my_membership_page');

    // Get client secret (needed to make a payment)
    Route::post('/get_client_secret', [MembershipController::class, 'get_client_secret'])->name('get_client_secret');

    // Upgrade membership page
    Route::get('/upgrade_my_membership_page', [MembershipController::class, 'upgrade_my_membership_page'])->name('upgrade_my_membership_page');

    // Change client's membership (action)
    Route::post('/change_client_membership', [MembershipController::class, 'change_client_membership'])->name('change_client_membership');

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
    Route::get('/my_group_trainings_coach', [GroupTrainingController::class, 'my_group_trainings_coach'])->name('my_group_trainings_coach');

    // Coach's logout
    Route::post('/logout_coach', function () {
        Auth::guard('coach')->logout();
        return redirect()->route('login');
    })->name('logout_coach');
});


// require __DIR__ . '/auth.php';
