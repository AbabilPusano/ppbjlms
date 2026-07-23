<?php

namespace Tests\Feature;

use App\Models\Kalimat;
use App\Models\Kanji;
use App\Models\Kata;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GuruReferenceFilterTest extends TestCase
{
    use RefreshDatabase;

    public function test_guru_can_search_and_filter_reference_data_by_level(): void
    {
        $guru = User::factory()->create(['role' => 'guru']);

        Kanji::create([
            'id' => 'kanji-n5',
            'kanji' => '日',
            'onyomi' => 'ニチ',
            'kunyomi' => 'ひ',
            'makna' => 'matahari',
            'level' => 'N5',
        ]);
        Kanji::create([
            'id' => 'kanji-n4',
            'kanji' => '海',
            'onyomi' => 'カイ',
            'kunyomi' => 'うみ',
            'makna' => 'laut',
            'level' => 'N4',
        ]);

        $this->actingAs($guru)
            ->get('/guru/referensi?tab=kanji&level=N5&q=matahari')
            ->assertOk()
            ->assertSee('日')
            ->assertDontSee('海')
            ->assertSee('Filter Referensi')
            ->assertSee('Semua Level');
    }

    public function test_kalimat_level_filter_uses_related_vocabulary_level(): void
    {
        $guru = User::factory()->create(['role' => 'guru']);
        $kata = Kata::create([
            'id' => 'kata-n4',
            'kata' => 'うみ',
            'romaji' => 'umi',
            'arti' => 'laut',
            'level' => 'N4',
        ]);
        $kalimat = Kalimat::create([
            'id' => 'kalimat-n4',
            'kalimat' => 'うみへいきます。',
            'romaji' => 'Umi e ikimasu.',
            'arti' => 'Saya pergi ke laut.',
        ]);
        $kalimat->katas()->attach($kata);

        $this->actingAs($guru)
            ->get('/guru/referensi?tab=kalimat&level=N4&q=laut')
            ->assertOk()
            ->assertSee('うみへいきます。');
    }
}
