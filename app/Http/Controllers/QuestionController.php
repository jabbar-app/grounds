<?php

namespace App\Http\Controllers;

use App\Models\Option;
use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class QuestionController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'quiz_id' => 'required|exists:quizzes,id',
            'question_text' => 'required|string',
            'options' => 'required|array|min:2',
            'options.*.text' => 'required|string',
            'correct' => 'required|integer|min:0',
        ]);

        try {
            DB::beginTransaction();

            $question = Question::create([
                'quiz_id' => $validated['quiz_id'],
                'question_text' => trim($validated['question_text']),
            ]);

            foreach ($validated['options'] as $index => $opt) {
                Option::create([
                    'question_id' => $question->id,
                    'option_text' => trim($opt['text']),
                    'is_correct' => $index === $validated['correct'],
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Pertanyaan berhasil ditambahkan.',
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();

            Log::error('Gagal menyimpan soal', [
                'error' => $e->getMessage(),
                'payload' => $request->all(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan soal.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
