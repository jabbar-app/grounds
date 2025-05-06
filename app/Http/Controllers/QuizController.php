<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\QuizResult;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class QuizController extends Controller
{
    use AuthorizesRequests;

    public function explore(): Response
    {
        $quizzes = Quiz::withCount('registrations')
            ->where('is_active', true)
            ->orderByDesc('created_at')
            ->get([
                'id',
                'title',
                'slug',
                'description',
                'duration',
                'img_featured',
                'open_gate_time',
                'close_gate_time',
                'category',
                'level',
                'is_required_monitoring'
            ]);

        return Inertia::render('QuizExplore', [
            'quizzes' => $quizzes,
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('CreateQuizPage', [
            'userId' => Auth::id(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:quizzes,slug',
            'description' => 'nullable|string',
            'category' => 'nullable|string|max:100',
            'level' => 'nullable|string|max:100',
            'duration' => 'required|integer|min:60',
            'open_gate_time' => 'nullable|date',
            'close_gate_time' => 'nullable|date',
            'announcement_time' => 'nullable|date',
            'is_required_monitoring' => 'boolean',
            'compressed_image' => 'nullable|string',
        ]);

        // Simpan base64 image jika ada
        if ($request->filled('compressed_image')) {
            $croppedData = $request->input('compressed_image');
            $imageName = time() . '-' . uniqid() . '.webp';
            $imagesFolder = public_path('featured-images');
            $avatarPath = $imagesFolder . '/' . $imageName;

            if (!is_dir($imagesFolder)) {
                mkdir($imagesFolder, 0755, true);
            }

            $imageData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $croppedData));
            if (!file_put_contents($avatarPath, $imageData)) {
                return back()->withErrors(['img_featured' => 'Gagal menyimpan gambar.']);
            }

            $validated['img_featured'] = 'featured-images/' . $imageName;
        }

        $validated['user_id'] = Auth::id();
        $validated['created_by'] = Auth::id();

        $quiz = Quiz::create($validated);

        return response()->json(['id' => $quiz->id]);
    }

    public function edit(Quiz $quiz): Response
    {
        $this->authorize('update', $quiz);

        return Inertia::render('EditQuizPage', [
            'quiz' => [
                'id' => $quiz->id,
                'title' => $quiz->title,
                'slug' => $quiz->slug,
                'description' => $quiz->description,
                'img_featured' => $quiz->img_featured,
                'category' => $quiz->category,
                'level' => $quiz->level,
                'duration' => $quiz->duration,
                'announcement_time' => optional($quiz->announcement_time)->format('Y-m-d\TH:i'),
                'open_gate_time' => optional($quiz->open_gate_time)->format('Y-m-d\TH:i'),
                'close_gate_time' => optional($quiz->close_gate_time)->format('Y-m-d\TH:i'),
                'is_required_monitoring' => $quiz->is_required_monitoring,
            ],
        ]);
    }

    public function update(Request $request, Quiz $quiz): RedirectResponse
    {
        $this->authorize('update', $quiz);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:quizzes,slug,' . $quiz->id,
            'description' => 'nullable|string',
            'img_featured' => 'nullable|string|max:255',
            'category' => 'nullable|string|max:100',
            'level' => 'nullable|string|max:100',
            'duration' => 'required|integer|min:60',
            'announcement_time' => 'nullable|date',
            'open_gate_time' => 'nullable|date',
            'close_gate_time' => 'nullable|date',
            'is_required_monitoring' => 'boolean',
        ]);

        $quiz->update([
            ...$validated,
            'slug' => $validated['slug'] ?? Str::slug($validated['title']),
        ]);

        return redirect()->route('dashboard')->with('toast', 'Quiz berhasil diperbarui!');
    }

    public function preview(Quiz $quiz): Response
    {
        return Inertia::render('QuizPreviewPage', [
            'quiz' => $quiz->load('questions.options'),
        ]);
    }

    public function show(Quiz $quiz): Response
    {
        return Inertia::render('QuizShowPage', [
            'quiz' => $quiz->only([
                'id',
                'title',
                'slug',
                'description',
                'duration',
                'img_featured',
                'open_gate_time',
                'close_gate_time',
                'announcement_time',
                'category',
                'level',
                'is_required_monitoring'
            ]),
            'registered' => $quiz->registrations()
                ->where('user_id', Auth::id())
                ->exists(),
        ]);
    }

    public function register(Quiz $quiz): RedirectResponse
    {
        $user = Auth::user();

        if ($quiz->registrations()->where('user_id', $user->id)->exists()) {
            return back()->with('toast', 'Kamu sudah mendaftar quiz ini.');
        }

        $quiz->registrations()->create([
            'user_id' => $user->id,
        ]);

        return back()->with('toast', 'Berhasil mendaftar quiz. Kamu akan diingatkan saat quiz dimulai!');
    }

    public function start(Quiz $quiz): Response|RedirectResponse
    {
        $existingResult = QuizResult::where('quiz_id', $quiz->id)
            ->where('user_id', Auth::id())
            ->first();

        if ($existingResult) {
            return redirect()
                ->route('quizzes.history', ['quiz' => $quiz->id])
                ->with('toast', 'Kamu sudah pernah mengerjakan quiz ini.');
        }

        if ($quiz->open_gate_time && now()->lt($quiz->open_gate_time)) {
            return Inertia::render('QuizNotAvailablePage', [
                'message' => 'Quiz hanya dapat dimulai setelah ' . $quiz->open_gate_time->format('d M Y H:i'),
            ]);
        }

        $quiz->load('questions.options');

        return Inertia::render('QuizStartPage', [
            'quiz' => $quiz,
        ]);
    }

    public function finish(Request $request, Quiz $quiz): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'score' => 'required|integer',
            'total_questions' => 'required|integer',
        ]);

        $result = QuizResult::create([
            'user_id' => Auth::id(),
            'quiz_id' => $quiz->id,
            'score' => $request->score,
            'total_questions' => $request->total_questions,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Quiz result saved.',
            'result_id' => $result->id,
        ]);
    }

    public function history(): Response
    {
        $results = QuizResult::with(['quiz'])
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        return Inertia::render('QuizHistoryPage', [
            'results' => $results,
        ]);
    }

    public function leaderboard(Quiz $quiz): Response
    {
        if ($quiz->announcement_time && now()->lt($quiz->announcement_time)) {
            return Inertia::render('QuizNotAvailablePage', [
                'message' => 'Leaderboard akan tersedia pada ' . $quiz->announcement_time->format('d M Y H:i'),
            ]);
        }

        $results = QuizResult::with('user')
            ->where('quiz_id', $quiz->id)
            ->orderByDesc('score')
            ->orderBy('created_at')
            ->get();

        return Inertia::render('QuizLeaderboardPage', [
            'quiz' => $quiz,
            'results' => $results,
        ]);
    }

    public function getQuiz(Quiz $quiz)
    {
        return response()->json([
            'id' => $quiz->id,
            'title' => $quiz->title,
            'slug' => $quiz->slug,
            'category' => $quiz->category,
            'level' => $quiz->level,
        ]);
    }

    public function getQuestions(Quiz $quiz)
    {
        $questions = $quiz->questions()
            ->select('id', 'question_text')
            ->latest()
            ->get();

        return response()->json($questions);
    }
}
