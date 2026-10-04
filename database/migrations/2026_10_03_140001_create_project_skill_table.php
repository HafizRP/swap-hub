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
        if (! Schema::hasTable('project_skill')) {
            Schema::create('project_skill', function (Blueprint $table) {
                $table->id();
                $table->foreignId('project_id')->constrained()->onDelete('cascade');
                $table->foreignId('skill_id')->constrained()->onDelete('cascade');
                $table->enum('importance', ['required', 'preferred'])->default('required');
                $table->timestamps();

                $table->unique(['project_id', 'skill_id']);
            });
        } else {
            Schema::table('project_skill', function (Blueprint $table) {
                if (! Schema::hasColumn('project_skill', 'importance')) {
                    $table->enum('importance', ['required', 'preferred'])->default('required')->after('skill_id');
                }
                if (! Schema::hasColumn('project_skill', 'created_at')) {
                    $table->timestamps();
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_skill');
    }
};
