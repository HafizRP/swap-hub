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
        if (! Schema::hasTable('messages')) {
            return;
        }
        Schema::table('messages', function (Blueprint $table) {
            $table->index(['conversation_id', 'created_at'], 'messages_conversation_id_created_at_index');
        });

        if (! Schema::hasTable('project_members')) {
            return;
        }
        Schema::table('project_members', function (Blueprint $table) {
            $table->index(['project_id', 'status'], 'project_members_project_id_status_index');
            $table->index(['user_id', 'status'], 'project_members_user_id_status_index');
        });

        if (! Schema::hasTable('credit_transactions')) {
            return;
        }
        Schema::table('credit_transactions', function (Blueprint $table) {
            $table->index(['user_id', 'created_at'], 'credit_transactions_user_id_created_at_index');
        });

        if (! Schema::hasTable('tasks')) {
            return;
        }
        Schema::table('tasks', function (Blueprint $table) {
            $table->index(['project_id', 'status'], 'tasks_project_id_status_index');
            $table->index(['project_id', 'milestone_id'], 'tasks_project_id_milestone_id_index');
        });

        if (! Schema::hasTable('study_sessions')) {
            return;
        }
        Schema::table('study_sessions', function (Blueprint $table) {
            $table->index(['status', 'starts_at'], 'study_sessions_status_starts_at_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('messages')) {
            Schema::table('messages', function (Blueprint $table) {
                $table->dropIndex('messages_conversation_id_created_at_index');
            });
        }

        if (Schema::hasTable('project_members')) {
            Schema::table('project_members', function (Blueprint $table) {
                $table->dropIndex('project_members_project_id_status_index');
                $table->dropIndex('project_members_user_id_status_index');
            });
        }

        if (Schema::hasTable('credit_transactions')) {
            Schema::table('credit_transactions', function (Blueprint $table) {
                $table->dropIndex('credit_transactions_user_id_created_at_index');
            });
        }

        if (Schema::hasTable('tasks')) {
            Schema::table('tasks', function (Blueprint $table) {
                $table->dropIndex('tasks_project_id_status_index');
                $table->dropIndex('tasks_project_id_milestone_id_index');
            });
        }

        if (Schema::hasTable('study_sessions')) {
            Schema::table('study_sessions', function (Blueprint $table) {
                $table->dropIndex('study_sessions_status_starts_at_index');
            });
        }
    }
};
