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
        Schema::table('users', function (Blueprint $table) {
            $table->string('major')->nullable();
            $table->string('university')->nullable();
            $table->text('bio')->nullable();
            $table->string('avatar')->nullable();
            $table->string('github_username')->nullable();
            $table->string('github_token')->nullable();
            $table->integer('reputation_points')->default(100);
            $table->string('phone')->nullable();
            $table->year('graduation_year')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'major', 'university', 'bio', 'avatar', 
                'github_username', 'github_token', 'reputation_points',
                'phone', 'graduation_year'
            ]);
        });
    }
};
