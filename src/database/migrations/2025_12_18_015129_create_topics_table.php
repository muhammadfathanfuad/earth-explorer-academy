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
    Schema::create('topics', function (Blueprint $table) {
        $table->id();
        $table->string('title');        // Judul Bab (misal: Lapisan Bumi)
        $table->string('slug')->unique(); // URL ramah (misal: lapisan-bumi)
        $table->text('summary');        // Ringkasan pendek untuk di Card
        $table->json('content')->nullable();    // Isi materi lengkap
        $table->string('image')->nullable(); // Gambar cover
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('topics');
    }
};
