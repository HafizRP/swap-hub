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
        Schema::create('usability_feedbacks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->string('respondent_name')->nullable();
            $table->string('respondent_role')->default('Mahasiswa');
            // Standard Brooke (1996) 10-Item Likert Scale (1 = Sangat Tidak Setuju, 5 = Sangat Setuju)
            $table->unsignedTinyInteger('q1');
            $table->unsignedTinyInteger('q2');
            $table->unsignedTinyInteger('q3');
            $table->unsignedTinyInteger('q4');
            $table->unsignedTinyInteger('q5');
            $table->unsignedTinyInteger('q6');
            $table->unsignedTinyInteger('q7');
            $table->unsignedTinyInteger('q8');
            $table->unsignedTinyInteger('q9');
            $table->unsignedTinyInteger('q10');
            // Calculated academic SUS score (0.00 - 100.00)
            $table->decimal('sus_score', 5, 2);
            $table->string('grade', 5)->default('B');
            $table->string('adjective_rating', 50)->default('Good');
            $table->text('qualitative_feedback')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('usability_feedbacks');
    }
};
