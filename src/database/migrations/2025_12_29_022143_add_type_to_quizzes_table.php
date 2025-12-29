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
        Schema::table('quizzes', function (Blueprint $table) {
            // Kita set default 'multiple_choice' agar soal lama tidak rusak
            $table->string('type')->default('multiple_choice')->after('topic_id');
        });
    }

    public function down(): void
    {
        Schema::table('quizzes', function (Blueprint $table) {
            $table->dropColumn('type');
        });
    }
};
