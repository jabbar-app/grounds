<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\DashboardController;

Route::get('/', function () {
    return Inertia::render('LandingPage');
})->name('home');

Route::get('/auth/inisiator/redirect', [AuthController::class, 'redirectToInisiator']);
Route::get('/auth/inisiator/callback', [AuthController::class, 'handleInisiatorCallback']);

Route::get('/explore', [QuizController::class, 'explore'])->name('quizzes.explore');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');
    Route::get('/admin/dashboard', [DashboardController::class, 'adminDashboard'])->name('admin.dashboard');

    Route::prefix('quizzes')->group(function () {
        Route::get('/create', [QuizController::class, 'create'])->name('quizzes.create');
        Route::post('', [QuizController::class, 'store'])->name('quizzes.store');

        Route::get('/get/{quiz:slug}', [QuizController::class, 'getQuiz']);
        Route::get('/get/{quiz:slug}/questions', [QuizController::class, 'getQuestions']);

        // Pindahkan ini ke atas!
        Route::get('/history', [QuizController::class, 'history'])->name('quizzes.history');

        Route::get('/{quiz:slug}', [QuizController::class, 'show'])->name('quizzes.show');
        Route::post('/{quiz:slug}/register', [QuizController::class, 'register'])->name('quizzes.register');
        Route::get('/{quiz:slug}/edit', [QuizController::class, 'edit'])->name('quizzes.edit');
        Route::put('/{quiz:slug}', [QuizController::class, 'update'])->name('quizzes.update');
        Route::get('/{quiz:slug}/start', [QuizController::class, 'start'])->name('quizzes.start');
        Route::get('/{quiz:slug}/preview', [QuizController::class, 'preview'])->name('quizzes.preview');
        Route::post('/{quiz:slug}/finish', [QuizController::class, 'finish'])->name('quizzes.finish');
        Route::get('/{quiz:slug}/leaderboard', [QuizController::class, 'leaderboard'])->name('quizzes.leaderboard');
    });

    Route::post('/questions', [QuestionController::class, 'store'])->name('questions.store');
});

require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';
