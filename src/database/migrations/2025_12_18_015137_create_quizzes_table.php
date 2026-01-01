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
            // Hubungkan soal ke Topic tertentu
            $table->foreignId('topic_id')->constrained('topics')->onDelete('cascade');
            
            $table->text('question');       // Pertanyaan
            $table->string('option_a')->nullable();
            $table->string('option_b')->nullable();
            $table->string('option_c')->nullable();
            $table->string('option_d')->nullable();
            $table->char('correct_answer')->nullable(); // Kunci Jawaban: 'a', 'b', 'c', atau 'd'
            $table->text('explanation')->nullable(); // Penjelasan (muncul setelah jawab)
            $table->timestamps();
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
