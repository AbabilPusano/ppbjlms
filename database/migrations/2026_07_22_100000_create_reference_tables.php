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
        // 1. Table Hurufs
        Schema::create('hurufs', function (Blueprint $table) {
            $table->string('id', 191)->primary();
            $table->string('tipe', 50); // hiragana | katakana
            $table->string('simbol', 191);
            $table->string('cara_baca', 191);
            $table->timestamps();
        });

        // 2. Table Kanjis
        Schema::create('kanjis', function (Blueprint $table) {
            $table->string('id', 191)->primary();
            $table->string('kanji', 191);
            $table->string('onyomi', 191)->nullable();
            $table->string('kunyomi', 191)->nullable();
            $table->string('makna', 191);
            $table->timestamps();
        });

        // 3. Table Katas
        Schema::create('katas', function (Blueprint $table) {
            $table->string('id', 191)->primary();
            $table->string('kata', 191);
            $table->string('romaji', 191);
            $table->string('arti', 191);
            $table->string('kanji_id', 191)->nullable();
            $table->text('deskripsi')->nullable();
            $table->string('tipe_kata', 191)->nullable(); // e.g. kata benda, kata kerja
            $table->timestamps();

            $table->foreign('kanji_id')->references('id')->on('kanjis')->onDelete('set null')->onUpdate('cascade');
        });

        // 4. Table Kalimats
        Schema::create('kalimats', function (Blueprint $table) {
            $table->string('id', 191)->primary();
            $table->string('kalimat', 191);
            $table->string('romaji', 191);
            $table->string('arti', 191);
            $table->text('deskripsi')->nullable();
            $table->timestamps();
        });

        // 5. Table Pivot Kalimat-Kata (Many-to-Many)
        Schema::create('kalimat_kata', function (Blueprint $table) {
            $table->string('kalimat_id', 191);
            $table->string('kata_id', 191);

            $table->primary(['kalimat_id', 'kata_id']);
            $table->foreign('kalimat_id')->references('id')->on('kalimats')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('kata_id')->references('id')->on('katas')->onDelete('cascade')->onUpdate('cascade');
        });

        // 6. Table Grammars
        Schema::create('grammars', function (Blueprint $table) {
            $table->string('id', 191)->primary();
            $table->string('struktur', 191);
            $table->string('kalimat_id', 191)->nullable();
            $table->text('deskripsi')->nullable();
            $table->timestamps();

            $table->foreign('kalimat_id')->references('id')->on('kalimats')->onDelete('set null')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grammars');
        Schema::dropIfExists('kalimat_kata');
        Schema::dropIfExists('kalimats');
        Schema::dropIfExists('katas');
        Schema::dropIfExists('kanjis');
        Schema::dropIfExists('hurufs');
    }
};
