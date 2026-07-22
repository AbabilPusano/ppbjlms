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
        Schema::table('kanjis', function (Blueprint $table) {
            $table->string('level', 10)->nullable()->after('makna');
        });

        Schema::table('katas', function (Blueprint $table) {
            $table->string('level', 10)->nullable()->after('tipe_kata');
        });

        Schema::table('grammars', function (Blueprint $table) {
            $table->string('level', 10)->nullable()->after('kalimat_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kanjis', function (Blueprint $table) {
            $table->dropColumn('level');
        });

        Schema::table('katas', function (Blueprint $table) {
            $table->dropColumn('level');
        });

        Schema::table('grammars', function (Blueprint $table) {
            $table->dropColumn('level');
        });
    }
};
