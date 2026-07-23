<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kanji;
use Illuminate\Support\Str;

class KanjiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $filePath = resource_path('views/letters-ui.blade.php');
        if (!file_exists($filePath)) {
            $this->command->error("View file letters-ui.blade.php tidak ditemukan di {$filePath}");
            return;
        }

        $content = file_get_contents($filePath);

        // Regex untuk mencocokkan data Kanji N5 dari objek JavaScript LETTER_DATA
        $pattern = "/['\"]([^'\"]+)['\"]:\s*\{\s*type:\s*['\"]kanji['\"]\s*,\s*romaji:\s*['\"]([^'\"]*)['\"]\s*,\s*meaning:\s*['\"]([^'\"]*)['\"]\s*,\s*onyomi:\s*['\"]([^'\"]*)['\"]\s*,\s*kunyomi:\s*['\"]([^'\"]*)['\"]\s*,.*?jlpt:\s*['\"]N5['\"]/";

        if (preg_match_all($pattern, $content, $matches, PREG_SET_ORDER)) {
            $count = 0;
            foreach ($matches as $m) {
                $symbol = $m[1];
                $onyomi = $m[4] !== '-' ? $m[4] : null;
                $kunyomi = $m[5] !== '-' ? $m[5] : null;
                $meaning = $m[3];

                Kanji::updateOrCreate(
                    ['kanji' => $symbol],
                    [
                        'id' => (string) Str::uuid(),
                        'onyomi' => $onyomi,
                        'kunyomi' => $kunyomi,
                        'makna' => $meaning,
                        'level' => 'N5',
                    ]
                );
                $count++;
            }
            $this->command->info("Berhasil mengimpor {$count} Kanji N5 ke database.");
        } else {
            $this->command->error("Gagal mencocokkan data Kanji N5 menggunakan regular expression.");
        }
    }
}
