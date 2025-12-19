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
        Schema::create('github_activities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('activity_type', ['commit', 'pull_request', 'issue', 'review'])->default('commit');
            $table->string('commit_sha')->nullable();
            $table->text('commit_message')->nullable();
            $table->string('branch')->nullable();
            $table->integer('additions')->default(0);
            $table->integer('deletions')->default(0);
            $table->json('metadata')->nullable(); // Store full webhook payload
            $table->timestamp('activity_at');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('github_activities');
    }
};
