<?php

namespace Database\Seeders;

use App\Models\Option;
use App\Models\Question;
use App\Models\User;
use App\Models\Quiz;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
  public function run(): void
  {
    // Create user
    $user = User::factory()->create([
      'name' => 'Jabbar Ali Panggabean',
      'email' => 'jabbarpanggabean@gmail.com',
      'password' => bcrypt('bism!LLAH99'),
    ]);

    // List of quizzes
    $quizzes = [
      [
        'title' => 'Quiz Tanpa Batas Waktu',
        'duration' => 2700,
        'open_gate_time' => null,
        'close_gate_time' => null,
        'announcement_time' => null,
        'is_required_monitoring' => true,
      ],
      [
        'title' => 'Quiz Akan Dibuka Besok',
        'duration' => 3600,
        'open_gate_time' => Carbon::now()->addDay(),
        'close_gate_time' => Carbon::now()->addDays(2),
        'announcement_time' => Carbon::now()->addDays(3),
        'is_required_monitoring' => true,
      ],
      [
        'title' => 'Quiz Sedang Berlangsung',
        'duration' => 1800,
        'open_gate_time' => Carbon::now()->subHour(),
        'close_gate_time' => Carbon::now()->addHour(),
        'announcement_time' => Carbon::now()->addDay(),
        'is_required_monitoring' => true,
      ],
      [
        'title' => 'Quiz Sudah Tutup',
        'duration' => 3000,
        'open_gate_time' => Carbon::now()->subDays(2),
        'close_gate_time' => Carbon::now()->subDay(),
        'announcement_time' => Carbon::now()->addHours(2),
        'is_required_monitoring' => true,
      ],
      [
        'title' => 'Quiz Nonaktif',
        'duration' => 2700,
        'open_gate_time' => Carbon::now()->subHour(),
        'close_gate_time' => Carbon::now()->addHour(),
        'announcement_time' => Carbon::now()->addDays(2),
        'is_required_monitoring' => true,
      ],
    ];

    // Create quizzes and questions
    foreach ($quizzes as $quizData) {
      $quiz = Quiz::create([
        'user_id' => $user->id,
        'title' => $quizData['title'],
        'slug' => Str::slug($quizData['title']),
        'description' => 'Quiz ini terdiri dari beberapa soal untuk menguji pengetahuan kamu. Ikuti sekarang!',
        'duration' => $quizData['duration'],
        'open_gate_time' => $quizData['open_gate_time'],
        'close_gate_time' => $quizData['close_gate_time'],
        'announcement_time' => $quizData['announcement_time'],
        'category' => 'Umum',
        'level' => 'Pemula',
        'img_featured' => null,
        'is_required_monitoring' => $quizData['is_required_monitoring'],
        'created_by' => $user->id,
      ]);

      // Tambahkan 3 soal ke tiap quiz
      for ($i = 1; $i <= 3; $i++) {
        $question = Question::create([
          'quiz_id' => $quiz->id,
          'question_text' => "Apa jawaban yang benar untuk soal ke-$i pada quiz '{$quiz->title}'?",
        ]);

        // Buat 4 opsi, satu di antaranya benar
        for ($j = 1; $j <= 4; $j++) {
          Option::create([
            'question_id' => $question->id,
            'option_text' => "Pilihan $j",
            'is_correct' => $j === 2, // Anggap pilihan ke-2 selalu benar
          ]);
        }
      }
    }
  }
}
