<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminStudentAccountController;
use App\Http\Controllers\GuruMaterialApiController;
use App\Http\Controllers\GuruUnitApiController;
use App\Http\Controllers\GuruStepApiController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StudentDashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.store');
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

Route::get('/', StudentDashboardController::class)
    ->middleware(['auth', 'role:siswa,guru,admin'])
    ->name('student.dashboard');

Route::middleware(['auth', 'role:siswa,guru,admin'])->group(function (): void {
    Route::view('/stories', 'stories-ui')->name('stories.index');
    Route::view('/letters', 'letters-ui')->name('letters.index');
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/settings/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/settings/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::patch('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');
    Route::view('/lesson/n5/unit/{unit}/level/{level}', 'lesson-ui')->name('lesson.show');
});

Route::view('/guru/dashboard', 'guru-dashboard-ui')
    ->middleware(['auth', 'role:guru,admin'])
    ->name('guru.dashboard');

Route::middleware(['auth', 'role:admin'])->group(function (): void {
    Route::get('/guru/student-accounts', [AdminStudentAccountController::class, 'index'])->name('admin.student-accounts.index');
    Route::post('/guru/student-accounts', [AdminStudentAccountController::class, 'store'])->name('admin.student-accounts.store');
});

Route::middleware(['auth', 'role:guru,admin'])->group(function (): void {
    Route::view('/guru/units', 'guru-units-ui')->name('guru.units.index');
    Route::get('/guru/units/{unit}/materials', [GuruUnitApiController::class, 'manageMaterials'])->name('guru.units.materials');
    Route::view('/guru/materi/{material}/questions', 'guru-questions-ui')->name('guru.materials.questions');

    Route::prefix('api/guru')->name('guru.api.')->group(function (): void {
        Route::get('/levels', [GuruUnitApiController::class, 'levels'])->name('levels.index');
        Route::get('/levels/{level}/units', [GuruUnitApiController::class, 'units'])->name('levels.units.index');
        Route::post('/units', [GuruUnitApiController::class, 'store'])->name('units.store');
        Route::put('/units/{unit}', [GuruUnitApiController::class, 'update'])->name('units.update');
        Route::delete('/units/{unit}', [GuruUnitApiController::class, 'destroy'])->name('units.destroy');
        Route::patch('/units/reorder', [GuruUnitApiController::class, 'reorder'])->name('units.reorder');
        Route::get('/levels/{level}/materials', [GuruMaterialApiController::class, 'materials'])->name('levels.materials.index');
        Route::post('/materials', [GuruMaterialApiController::class, 'storeMaterial'])->name('materials.store');
        Route::put('/materials/{material}', [GuruMaterialApiController::class, 'updateMaterial'])->name('materials.update');
        Route::delete('/materials/{material}', [GuruMaterialApiController::class, 'destroyMaterial'])->name('materials.destroy');
        Route::patch('/materials/reorder', [GuruMaterialApiController::class, 'reorder'])->name('materials.reorder');
        Route::get('/materials/{material}/questions', [GuruMaterialApiController::class, 'questions'])->name('materials.questions.index');
        Route::post('/materials/{material}/questions', [GuruMaterialApiController::class, 'storeQuestion'])->name('materials.questions.store');
        Route::get('/questions/template', [GuruMaterialApiController::class, 'questionTemplate'])->name('questions.template');
        Route::put('/questions/{question}', [GuruMaterialApiController::class, 'updateQuestion'])->name('questions.update');
        Route::delete('/questions/{question}', [GuruMaterialApiController::class, 'destroyQuestion'])->name('questions.destroy');

        // Node Steps API routes
        Route::get('/nodes/{node}/steps', [GuruStepApiController::class, 'index'])->name('steps.index');
        Route::post('/steps', [GuruStepApiController::class, 'store'])->name('steps.store');
        Route::put('/steps/{step}', [GuruStepApiController::class, 'update'])->name('steps.update');
        Route::delete('/steps/{step}', [GuruStepApiController::class, 'destroy'])->name('steps.destroy');
        Route::patch('/steps/reorder', [GuruStepApiController::class, 'reorder'])->name('steps.reorder');
    });
});
