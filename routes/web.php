<?php

use App\Http\Controllers\AdminStudentAccountController;
use App\Http\Controllers\AdminStudentManagementController;
use App\Http\Controllers\AdminTeacherAccountController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\GuruMaterialApiController;
use App\Http\Controllers\GuruMaterialPreviewController;
use App\Http\Controllers\GuruStepApiController;
use App\Http\Controllers\GuruStoryController;
use App\Http\Controllers\GuruUnitApiController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StudentDashboardController;
use App\Http\Controllers\StudentStoryController;
use Illuminate\Support\Facades\Route;

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.store');
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

Route::get('/', StudentDashboardController::class)
    ->middleware(['auth', 'role:siswa,guru,admin'])
    ->name('student.dashboard');

Route::middleware(['auth', 'role:siswa,guru,admin'])->group(function (): void {
    Route::get('/stories', [StudentStoryController::class, 'index'])->name('stories.index');
    Route::get('/stories/{story}', [StudentStoryController::class, 'show'])->name('stories.show');
    Route::get('/letters', function (\Illuminate\Http\Request $request) {
        $levels = \App\Models\Level::query()->get()->sortBy(function ($lvl) {
            if ($lvl->code === 'huruf') return 0;
            // N5=1, N4=2, N3=3, N2=4, N1=5
            $n = (int) ltrim($lvl->code, 'N');
            return $n > 0 ? (6 - $n) : 99;
        })->values();
        $selectedLevelId = $request->query('level_id');
        $level = null;
        if ($selectedLevelId) {
            $level = \App\Models\Level::query()->find($selectedLevelId);
        }
        if (!$level) {
            $level = \App\Models\Level::query()->where('code', 'huruf')->first()
                ?? $levels->first();
        }
        return view('letters-ui', compact('levels', 'level'));
    })->name('letters.index');
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/settings/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/settings/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::patch('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');
    Route::get('/lesson/n5/unit/{unit}/level/{level}', [StudentDashboardController::class, 'showLesson'])->name('lesson.show');
});

Route::view('/guru/dashboard', 'guru-dashboard-ui')
    ->middleware(['auth', 'role:guru,admin'])
    ->name('guru.dashboard');

Route::middleware(['auth', 'role:guru,admin'])->group(function (): void {
    Route::get('/guru/student-accounts', [AdminStudentAccountController::class, 'index'])->name('admin.student-accounts.index');
    Route::post('/guru/student-accounts', [AdminStudentAccountController::class, 'store'])->name('admin.student-accounts.store');
});

Route::middleware(['auth', 'role:admin'])->group(function (): void {
    Route::get('/guru/teacher-accounts', [AdminTeacherAccountController::class, 'index'])->name('admin.teacher-accounts.index');
    Route::post('/guru/teacher-accounts', [AdminTeacherAccountController::class, 'store'])->name('admin.teacher-accounts.store');
    Route::get('/guru/students', [AdminStudentManagementController::class, 'index'])->name('admin.students.index');
    Route::patch('/guru/accounts/{account}/reset-password', [AdminStudentManagementController::class, 'resetPassword'])->name('admin.accounts.reset-password');
});

Route::middleware(['auth', 'role:guru,admin'])->group(function (): void {
    Route::view('/guru/units', 'guru-units-ui')->name('guru.units.index');
    Route::redirect('/guru/materi', '/guru/units')->name('guru.materi.redirect');
    Route::get('/guru/units/{unit}/materials', [GuruUnitApiController::class, 'manageMaterials'])->name('guru.units.materials');
    Route::get('/guru/materials/{material}/preview', GuruMaterialPreviewController::class)->name('guru.materials.preview');
    Route::get('/guru/materials/import-template', [GuruMaterialApiController::class, 'downloadTemplate'])->name('guru.materials.import-template');
    Route::get('/guru/steps/import-template', [GuruStepApiController::class, 'downloadTemplate'])->name('guru.steps.import-template');
    Route::view('/guru/materi/{material}/questions', 'guru-questions-ui')->name('guru.materials.questions');

    // Stories CRUD for Guru
    Route::get('/guru/stories', [GuruStoryController::class, 'index'])->name('guru.stories.index');
    Route::get('/guru/stories/create', [GuruStoryController::class, 'create'])->name('guru.stories.create');
    Route::post('/guru/stories', [GuruStoryController::class, 'store'])->name('guru.stories.store');
    Route::delete('/guru/stories/{story}', [GuruStoryController::class, 'destroy'])->name('guru.stories.destroy');

    Route::prefix('api/guru')->name('guru.api.')->group(function (): void {
        // AI Story Generation endpoint
        Route::post('/stories/generate', [GuruStoryController::class, 'generate'])->name('stories.generate');

        Route::get('/levels', [GuruUnitApiController::class, 'levels'])->name('levels.index');
        Route::get('/levels/{level}/units', [GuruUnitApiController::class, 'units'])->name('levels.units.index');
        Route::post('/units', [GuruUnitApiController::class, 'store'])->name('units.store');
        Route::put('/units/{unit}', [GuruUnitApiController::class, 'update'])->name('units.update');
        Route::delete('/units/{unit}', [GuruUnitApiController::class, 'destroy'])->name('units.destroy');
        Route::patch('/units/reorder', [GuruUnitApiController::class, 'reorder'])->name('units.reorder');
        Route::post('/units/{unit}/import', [GuruMaterialApiController::class, 'import'])->name('units.import');
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
        Route::post('/nodes/{node}/import-steps', [GuruStepApiController::class, 'importSteps'])->name('nodes.import-steps');
        Route::post('/steps', [GuruStepApiController::class, 'store'])->name('steps.store');
        Route::put('/steps/{step}', [GuruStepApiController::class, 'update'])->name('steps.update');
        Route::delete('/steps/{step}', [GuruStepApiController::class, 'destroy'])->name('steps.destroy');
        Route::patch('/steps/reorder', [GuruStepApiController::class, 'reorder'])->name('steps.reorder');
    });
});
