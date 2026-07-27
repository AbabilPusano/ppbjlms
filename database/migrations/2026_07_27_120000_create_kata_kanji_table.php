<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kata_kanji', function (Blueprint $table) {
            $table->string('kata_id', 191);
            $table->string('kanji_id', 191);

            $table->primary(['kata_id', 'kanji_id']);

            $table->foreign('kata_id')
                ->references('id')
                ->on('katas')
                ->onDelete('cascade');

            $table->foreign('kanji_id')
                ->references('id')
                ->on('kanjis')
                ->onDelete('cascade');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kata_kanji');
    }
};
