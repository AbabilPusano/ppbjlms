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
        // 1. Modifikasi tabel users bawaan Laravel
        Schema::table('users', function (Blueprint $table) {
            $table->string('username', 191)->unique()->after('id');
            $table->string('role', 50)->comment('guru | siswa')->after('password');
            $table->text('bio')->nullable()->after('email');
            $table->string('avatar', 255)->nullable()->after('bio');
            $table->integer('streak')->default(0)->after('avatar');
            $table->integer('totalXP')->default(0)->after('streak');
            $table->integer('gems')->default(0)->after('totalXP');
            $table->string('league', 50)->default('Bronze')->after('gems');
        });

        // 2. Buat tabel sections
        Schema::create('sections', function (Blueprint $table) {
            $table->string('id', 191)->primary();
            $table->string('name', 191)->unique(); // e.g., "N5", "N4", etc.
            $table->text('description')->nullable();
            $table->timestamps(3); // createdAt dan updatedAt dengan presisi milidetik (DATETIME(3))
        });

        // 3. Buat tabel units
        Schema::create('units', function (Blueprint $table) {
            $table->string('id', 191)->primary();
            $table->string('sectionId', 191);
            $table->string('title', 191);
            $table->text('description')->nullable();
            $table->string('type', 50); // "grammar", "kanji", "vocabulary", "bunpou"
            $table->integer('order')->default(0);
            $table->timestamps(3);

            $table->foreign('sectionId')->references('id')->on('sections')->onDelete('cascade')->onUpdate('cascade');
        });

        // 4. Buat tabel nodes
        Schema::create('nodes', function (Blueprint $table) {
            $table->string('id', 191)->primary();
            $table->string('unitId', 191);
            $table->string('title', 191);
            $table->string('type', 50); // "lesson", "quiz", "checkpoint"
            $table->integer('order')->default(0);
            $table->timestamps(3);

            $table->foreign('unitId')->references('id')->on('units')->onDelete('cascade')->onUpdate('cascade');
        });

        // 5. Buat tabel questions
        Schema::create('questions', function (Blueprint $table) {
            $table->string('id', 191)->primary();
            $table->string('nodeId', 191);
            $table->string('type', 50); // "multiple-choice", "drag-drop"
            $table->text('question');
            $table->json('options'); // Kolom JSON
            $table->json('correctAnswer');
            $table->json('relatedCharacters');
            $table->integer('order')->default(0);
            $table->timestamps(3);

            $table->foreign('nodeId')->references('id')->on('nodes')->onDelete('cascade')->onUpdate('cascade');
        });

        // 6. Buat tabel stories
        Schema::create('stories', function (Blueprint $table) {
            $table->string('id', 191)->primary();
            $table->string('sectionId', 191);
            $table->string('title', 191);
            $table->json('content');
            $table->integer('order')->default(0);
            $table->timestamps(3);

            $table->foreign('sectionId')->references('id')->on('sections')->onDelete('cascade')->onUpdate('cascade');
        });

        // 7. Buat tabel user_progress
        Schema::create('user_progress', function (Blueprint $table) {
            $table->string('id', 191)->primary();
            $table->foreignId('userId')->constrained('users')->onDelete('cascade')->onUpdate('cascade');
            $table->string('nodeId', 191);
            $table->string('status', 50)->default('completed');
            $table->timestamps(3);

            $table->unique(['userId', 'nodeId']);
            $table->foreign('nodeId')->references('id')->on('nodes')->onDelete('cascade')->onUpdate('cascade');
        });

        // 8. Buat tabel question_responses
        Schema::create('question_responses', function (Blueprint $table) {
            $table->string('id', 191)->primary();
            $table->foreignId('userId')->constrained('users')->onDelete('cascade')->onUpdate('cascade');
            $table->string('questionId', 191);
            $table->boolean('isCorrect');
            $table->timestamp('createdAt', 3)->useCurrent();

            $table->foreign('questionId')->references('id')->on('questions')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('question_responses');
        Schema::dropIfExists('user_progress');
        Schema::dropIfExists('stories');
        Schema::dropIfExists('questions');
        Schema::dropIfExists('nodes');
        Schema::dropIfExists('units');
        Schema::dropIfExists('sections');

        // Rollback kolom baru pada tabel users
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'username',
                'role',
                'bio',
                'avatar',
                'streak',
                'totalXP',
                'gems',
                'league'
            ]);
        });
    }
};
