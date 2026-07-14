<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\LmsController;
use App\Http\Controllers\Api\V1\ProgressController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->name('api.v1.')->group(function (): void {
    Route::get('/health', fn () => response()->json([
        'success' => true,
        'message' => 'PPBJ LMS API ready.',
        'data' => ['version' => 'v1'],
    ]))->name('health');

    Route::post('/login', [AuthController::class, 'login'])->name('login');

    Route::middleware('api.token')->group(function (): void {
        Route::get('/me', [AuthController::class, 'me'])->name('me');
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

        Route::get('/sections', [LmsController::class, 'sections'])->name('sections.index');
        Route::get('/sections/{section}', [LmsController::class, 'section'])->name('sections.show');
        Route::get('/sections/{section}/units', [LmsController::class, 'sectionUnits'])->name('sections.units');

        Route::get('/units/{unit}', [LmsController::class, 'unit'])->name('units.show');
        Route::get('/units/{unit}/nodes', [LmsController::class, 'unitNodes'])->name('units.nodes');

        Route::get('/nodes/{node}', [LmsController::class, 'node'])->name('nodes.show');
        Route::get('/nodes/{node}/questions', [LmsController::class, 'nodeQuestions'])->name('nodes.questions');
        Route::get('/nodes/{node}/steps', [LmsController::class, 'nodeSteps'])->name('nodes.steps');
        Route::post('/nodes/{node}/complete', [ProgressController::class, 'complete'])->name('nodes.complete');

        Route::get('/stories', [LmsController::class, 'stories'])->name('stories.index');
        Route::get('/progress', [ProgressController::class, 'index'])->name('progress.index');
    });

    // Web session authenticated complete endpoint helper
    Route::post('/nodes/{node}/complete-web', [ProgressController::class, 'complete'])
        ->middleware(['web', 'auth'])
        ->name('nodes.complete.web');
});
