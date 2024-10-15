<?php

use App\Http\Controllers\LoginController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('welcome');
})->name('login');

Route::post('/login', [LoginController::class, 'authenticate'])->name('login_post');

// Admin routes
Route::middleware(['auth:admin'])->group(function () {

    Route::middleware(['admin'])->group(function () {
        // Admin's homepage
        Route::get('/admin_homepage', function () {
            return view('admin.admin_homepage');
        })->name('admin_homepage');

        // Admin's logout
        Route::post('/logout_admin', function () {
            Auth::guard('admin')->logout();
            return redirect()->route('login');
        })->name('logout_admin');
    });
});

// require __DIR__ . '/auth.php';
