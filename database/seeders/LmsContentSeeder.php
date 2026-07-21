<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class LmsContentSeeder extends Seeder
{
    /**
     * Seed LMS content migrated from the previous Neon database.
     */
    public function run(): void
    {
        DB::transaction(function (): void {
            DB::table('sections')->upsert($this->sections(), ['id'], [
                'name',
                'description',
                'created_at',
                'updated_at',
            ]);

            foreach ($this->levels() as $lvl) {
                DB::table('levels')->updateOrInsert(
                    ['code' => $lvl['code']],
                    [
                        'name' => $lvl['name'],
                        'created_at' => $lvl['created_at'],
                        'updated_at' => $lvl['updated_at'],
                    ]
                );
            }

            DB::table('units')->upsert($this->unitsWithLevelData(), ['id'], [
                'sectionId',
                'level_id',
                'title',
                'description',
                'type',
                'category_japanese',
                'category_romaji',
                'order',
                'sort_order',
                'is_active',
                'created_at',
                'updated_at',
            ]);

            DB::table('nodes')->upsert($this->nodes(), ['id'], [
                'unitId',
                'title',
                'type',
                'order',
                'created_at',
                'updated_at',
            ]);
        });
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function levels(): array
    {
        return array_map(fn(array $section): array => [
            'code' => $section['name'],
            'name' => $section['description'] ?? $section['name'],
            'created_at' => $section['created_at'],
            'updated_at' => $section['updated_at'],
        ], $this->sections());
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function unitsWithLevelData(): array
    {
        return array_map(function (array $unit): array {
            $section = collect($this->sections())->firstWhere('id', $unit['sectionId']);
            $levelId = $section ? DB::table('levels')->where('code', $section['name'])->value('id') : null;
            $category = $this->categoryDefaults($unit['type']);

            return [
                ...$unit,
                'level_id' => $levelId,
                'category_japanese' => $category['japanese'],
                'category_romaji' => $category['romaji'],
                'sort_order' => $unit['order'],
                'is_active' => true,
            ];
        }, $this->units());
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

    /**
     * @return array<int, array<string, mixed>>
     */
    private function sections(): array
    {
        return [
            [
                'id' => 'cmnh4d3av0000nk1nirwwblji',
                'name' => 'N5',
                'description' => 'JLPT N5 - Pemula',
                'created_at' => '2026-04-02 06:54:18.050',
                'updated_at' => '2026-04-02 06:54:18.050',
            ],
            [
                'id' => 'cmnh4d3eq0001nk1nr9l2c93t',
                'name' => 'N4',
                'description' => 'JLPT N4 - Dasar',
                'created_at' => '2026-04-02 06:54:18.195',
                'updated_at' => '2026-04-02 06:54:18.195',
            ],
            [
                'id' => 'cmnh4d3ic0002nk1nis79ock2',
                'name' => 'N3',
                'description' => 'JLPT N3 - Menengah',
                'created_at' => '2026-04-02 06:54:18.321',
                'updated_at' => '2026-04-02 06:54:18.321',
            ],
            [
                'id' => 'cmnh4d3lt0003nk1nzt0qgfw3',
                'name' => 'N2',
                'description' => 'JLPT N2 - Mahir',
                'created_at' => '2026-04-02 06:54:18.450',
                'updated_at' => '2026-04-02 06:54:18.450',
            ],
            [
                'id' => 'cmnh4d3pa0004nk1n7rr4r0kl',
                'name' => 'N1',
                'description' => 'JLPT N1 - Ahli',
                'created_at' => '2026-04-02 06:54:18.570',
                'updated_at' => '2026-04-02 06:54:18.570',
            ],
        ];
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function units(): array
    {
        return [
            [
                'id' => 'cmnh4va8f00004s1n8ufw4sc6',
                'sectionId' => 'cmnh4d3av0000nk1nirwwblji',
                'title' => 'Hiragana Dasar',
                'description' => 'Mempelajari huruf Hiragana あ〜ん',
                'type' => 'hiragana',
                'order' => 0,
                'created_at' => '2026-04-02 07:08:26.895',
                'updated_at' => '2026-04-20 03:13:13.890',
            ],
            [
                'id' => 'cmnh4vaa400014s1nun5bfy4c',
                'sectionId' => 'cmnh4d3av0000nk1nirwwblji',
                'title' => 'Katakana Dasar',
                'description' => 'Mempelajari huruf Katakana ア〜ン',
                'type' => 'katakana',
                'order' => 1,
                'created_at' => '2026-04-02 07:08:26.956',
                'updated_at' => '2026-04-20 03:13:13.891',
            ],
            [
                'id' => 'cmqoosov9000304jrx3jmb7dn',
                'sectionId' => 'cmnh4d3av0000nk1nirwwblji',
                'title' => 'perkenalan tes',
                'description' => 'ini adalah test1',
                'type' => 'kanji',
                'order' => 2,
                'created_at' => '2026-06-22 03:59:48.453',
                'updated_at' => '2026-06-22 03:59:48.453',
            ],
        ];
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function nodes(): array
    {
        return [
            [
                'id' => 'cmnh5ceot0003vs1nlqg6r7l6',
                'unitId' => 'cmnh4vaa400014s1nun5bfy4c',
                'title' => 'Katakana ア行 (a-i-u-e-o)',
                'type' => 'lesson',
                'order' => 0,
                'created_at' => '2026-04-02 07:34:12.317',
                'updated_at' => '2026-04-02 07:34:12.317',
            ],
            [
                'id' => 'cmnh5ceq40004vs1nozkf79bo',
                'unitId' => 'cmnh4vaa400014s1nun5bfy4c',
                'title' => 'Katakana カ行 (ka-ki-ku-ke-ko)',
                'type' => 'lesson',
                'order' => 1,
                'created_at' => '2026-04-02 07:34:12.364',
                'updated_at' => '2026-04-02 07:34:12.364',
            ],
            [
                'id' => 'cmnh5selz0001vs1nzfmng1lp',
                'unitId' => 'cmnh4va8f00004s1n8ufw4sc6',
                'title' => 'Hiragana か行 (ka-ki-ku-ke-ko)',
                'type' => 'lesson',
                'order' => 1,
                'created_at' => '2026-04-02 07:34:12.215',
                'updated_at' => '2026-04-02 07:38:18.560',
            ],
            [
                'id' => 'cmnh5cekg0000vs1nwqnas3nw',
                'unitId' => 'cmnh4va8f00004s1n8ufw4sc6',
                'title' => 'Hiragana あ行 (a-i-u-e-o)',
                'type' => 'lesson',
                'order' => 0,
                'created_at' => '2026-04-02 07:34:12.160',
                'updated_at' => '2026-04-02 07:38:18.559',
            ],
            [
                'id' => 'cmnh5sena0002vs1n3yf5ampx',
                'unitId' => 'cmnh4va8f00004s1n8ufw4sc6',
                'title' => 'Quiz Hiragana Dasar',
                'type' => 'quiz',
                'order' => 2,
                'created_at' => '2026-04-02 07:34:12.262',
                'updated_at' => '2026-04-02 07:38:18.561',
            ],
        ];
    }
}
