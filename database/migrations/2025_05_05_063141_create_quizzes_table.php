<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('quizzes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('slug')->unique()->nullable();
            $table->integer('duration')->default(300); // dalam detik, misal 300 = 5 menit

            $table->timestamp('open_gate_time')->nullable();    // waktu mulai quiz bisa diakses
            $table->timestamp('close_gate_time')->nullable();   // waktu quiz tidak bisa diakses
            $table->timestamp('announcement_time')->nullable(); // waktu pengumuman hasil quiz

            $table->string('category')->nullable();             // misalnya: Matematika, Umum
            $table->string('level')->nullable();                // misalnya: Pemula, Lanjut
            $table->boolean('is_required_monitoring')->default(false); // true jika perlu kamera/mic
            $table->boolean('is_active')->default(true);

            $table->string('img_featured')->nullable();         // path gambar thumbnail quiz
            $table->unsignedBigInteger('created_by');           // siapa yang membuat quiz

            $table->timestamps();

            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quizzes');
    }
};
