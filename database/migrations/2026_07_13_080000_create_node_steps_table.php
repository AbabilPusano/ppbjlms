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
        // 1. Re-seed static levels including "Huruf" at the beginning
        Schema::disableForeignKeyConstraints();
        DB::table('levels')->truncate();
        Schema::enableForeignKeyConstraints();

        $levels = [
            ['id' => 1, 'code' => 'huruf', 'name' => 'Huruf'],
            ['id' => 2, 'code' => 'N5', 'name' => 'Level N5'],
            ['id' => 3, 'code' => 'N4', 'name' => 'Level N4'],
            ['id' => 4, 'code' => 'N3', 'name' => 'Level N3'],
            ['id' => 5, 'code' => 'N2', 'name' => 'Level N2'],
            ['id' => 6, 'code' => 'N1', 'name' => 'Level N1'],
        ];

        foreach ($levels as $level) {
            DB::table('levels')->insert(array_merge($level, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }

        // 2. Create node_steps table
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
