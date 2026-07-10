<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.store');
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

Route::get('/', function () {
    return view('welcome');
})->middleware(['auth', 'role:siswa,guru'])->name('student.dashboard');

Route::middleware(['auth', 'role:siswa,guru'])->group(function (): void {
    Route::view('/stories', 'stories-ui')->name('stories.index');
    Route::view('/letters', 'letters-ui')->name('letters.index');
    Route::view('/profile', 'profile-ui')->name('profile.show');
    Route::view('/settings/profile', 'settings-profile-ui')->name('profile.edit');
    Route::view('/lesson/n5/unit/{unit}/level/{level}', 'lesson-ui')->name('lesson.show');
});

Route::view('/guru/dashboard', 'guru-dashboard-ui')
    ->middleware(['auth', 'role:guru'])
    ->name('guru.dashboard');
