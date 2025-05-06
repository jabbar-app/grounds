<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use Inertia\Inertia;
use App\Models\QuizResult;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function dashboard()
    {
        $user = User::find(Auth::id());
        $results = QuizResult::with('quiz')
            ->where('user_id', $user->id)
            ->latest()
            ->get();

        return Inertia::render('Dashboard', [
            'results' => $results,
            'registered_quizzes' => $user->registeredQuizzes()->get(),
        ]);
    }

    public function adminDashboard()
    {
        $quizzes = Quiz::withCount('questions')
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        return Inertia::render('AdminDashboard', [
            'quizzes' => $quizzes,
        ]);
    }
}
