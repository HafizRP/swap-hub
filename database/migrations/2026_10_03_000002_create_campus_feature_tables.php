<?php

declare(strict_types=1);

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
        // 1. Pivot or column for required skills on projects
        if (! Schema::hasTable('project_skill')) {
            Schema::create('project_skill', function (Blueprint $table) {
                $table->foreignId('project_id')->constrained()->cascadeOnDelete();
                $table->foreignId('skill_id')->constrained()->cascadeOnDelete();
                $table->primary(['project_id', 'skill_id']);
            });
        }

        // 2. Credits transaction table & credits balance on users
        if (! Schema::hasColumn('users', 'credits')) {
            Schema::table('users', function (Blueprint $table) {
                $table->integer('credits')->default(0);
            });
        }

        Schema::create('credit_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->integer('amount');
            $table->string('type'); // 'award', 'spend', 'transfer_in', 'transfer_out'
            $table->string('reason');
            $table->nullableMorphs('reference');
            $table->timestamps();
        });

        // 3. Study Sessions
        Schema::create('study_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('host_id')->constrained('users')->cascadeOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('meeting_url');
            $table->string('status')->default('scheduled'); // scheduled, active, completed, cancelled
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('ends_at')->nullable();
            $table->timestamps();
        });

        Schema::create('study_session_user', function (Blueprint $table) {
            $table->foreignId('study_session_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->primary(['study_session_id', 'user_id']);
        });

        // 4. Code Reviews
        Schema::create('code_review_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('repository_url')->nullable();
            $table->string('pr_url')->nullable();
            $table->integer('bounty_credits')->default(0);
            $table->string('status')->default('open'); // open, in_review, completed, cancelled
            $table->timestamps();
        });

        Schema::create('code_review_submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('code_review_request_id')->constrained()->cascadeOnDelete();
            $table->foreignId('reviewer_id')->constrained('users')->cascadeOnDelete();
            $table->text('feedback');
            $table->string('status')->default('pending'); // pending, accepted, rejected
            $table->timestamps();
        });

        // 5. Badges
        Schema::create('badges', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description')->nullable();
            $table->string('icon')->nullable();
            $table->timestamps();
        });

        Schema::create('badge_user', function (Blueprint $table) {
            $table->foreignId('badge_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->timestamp('awarded_at')->nullable();
            $table->primary(['badge_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('badge_user');
        Schema::dropIfExists('badges');
        Schema::dropIfExists('code_review_submissions');
        Schema::dropIfExists('code_review_requests');
        Schema::dropIfExists('study_session_user');
        Schema::dropIfExists('study_sessions');
        Schema::dropIfExists('credit_transactions');
        if (Schema::hasColumn('users', 'credits')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('credits');
            });
        }
        Schema::dropIfExists('project_skill');
    }
};
