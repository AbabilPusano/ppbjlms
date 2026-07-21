<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Create node_steps table
        Schema::create('node_steps', function (Blueprint $table) {
            $table->string('id', 191)->primary();
            $table->string('node_id', 191);
            $table->string('type', 50); // "text" | "character" | "quiz"

            // Text step type content
            $table->text('content')->nullable();

            // Character step type contents
            $table->string('character_symbol', 191)->nullable();
            $table->string('character_pronunciation', 191)->nullable();

            // Quiz step type contents
            $table->string('quiz_instruction', 191)->nullable();
            $table->string('quiz_query', 191)->nullable();
            $table->json('quiz_options')->nullable();
            $table->integer('quiz_correct_index')->nullable();

            $table->integer('sort_order')->default(0);
            $table->timestamps(3);

            $table->foreign('node_id')
                ->references('id')
                ->on('nodes')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('node_steps');
    }
};
