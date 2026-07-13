<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('levels', function (Blueprint $table) {
            $table->id();
            $table->string('code', 20)->unique();
            $table->string('name', 191);
            $table->timestamps();
        });

        Schema::table('units', function (Blueprint $table) {
            $table->unsignedBigInteger('level_id')->nullable()->after('sectionId');
            $table->string('category_japanese', 191)->default('学習')->after('description');
            $table->string('category_romaji', 191)->default('gakushu')->after('category_japanese');
            $table->integer('sort_order')->default(0)->after('order');
            $table->boolean('is_active')->default(true)->after('sort_order');
            $table->softDeletes();

            $table->index('level_id');
            $table->index(['level_id', 'sort_order']);
        });

        $now = now();

        DB::table('sections')
            ->orderBy('name')
            ->get()
            ->each(function (object $section) use ($now): void {
                DB::table('levels')->updateOrInsert(
                    ['code' => $section->name],
                    [
                        'name' => $section->description ?: $section->name,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ],
                );
            });

        DB::table('units')
            ->orderBy('order')
            ->get()
            ->each(function (object $unit): void {
                $section = DB::table('sections')->where('id', $unit->sectionId)->first();
                $level = $section ? DB::table('levels')->where('code', $section->name)->first() : null;
                $category = $this->categoryDefaults((string) $unit->type);

                DB::table('units')
                    ->where('id', $unit->id)
                    ->update([
                        'level_id' => $level?->id,
                        'category_japanese' => $category['japanese'],
                        'category_romaji' => $category['romaji'],
                        'sort_order' => (int) $unit->order,
                        'is_active' => true,
                    ]);
            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('units', function (Blueprint $table) {
            $table->dropIndex(['level_id']);
            $table->dropIndex(['level_id', 'sort_order']);
            $table->dropColumn([
                'level_id',
                'category_japanese',
                'category_romaji',
                'sort_order',
                'is_active',
                'deleted_at',
            ]);
        });

        Schema::dropIfExists('levels');
    }

    /**
     * @return array{japanese: string, romaji: string}
     */
    private function categoryDefaults(string $type): array
    {
        return match (Str::lower($type)) {
            'hiragana' => ['japanese' => 'ひらがな', 'romaji' => 'hiragana'],
            'katakana' => ['japanese' => 'カタカナ', 'romaji' => 'katakana'],
            'kanji' => ['japanese' => '漢字', 'romaji' => 'kanji'],
            'grammar', 'bunpou' => ['japanese' => '文法', 'romaji' => 'bunpou'],
            'vocabulary' => ['japanese' => '語彙', 'romaji' => 'goi'],
            default => ['japanese' => '学習', 'romaji' => Str::of($type)->replace(['_', '-'], ' ')->lower()->toString() ?: 'gakushu'],
        };
    }
};
