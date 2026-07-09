<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// UI-only preview routes. These render static Blade views with dummy data only.
Route::view('/login', 'login-ui');
Route::view('/stories', 'stories-ui');
Route::view('/letters', 'letters-ui');
Route::view('/profile', 'profile-ui');
Route::view('/settings/profile', 'settings-profile-ui');
Route::view('/guru/dashboard', 'guru-dashboard-ui');
