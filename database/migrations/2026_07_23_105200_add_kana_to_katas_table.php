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
        Schema::table('katas', function (Blueprint $table) {
            $table->string('kana', 191)->nullable()->after('kata');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('katas', function (Blueprint $table) {
            $table->dropColumn('kana');
        });
    }
};
