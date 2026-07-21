<?php

namespace Tests\Feature;

use App\Models\Level;
use App\Models\Section;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GuruUnitManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_guru_can_open_units_management_page(): void
    {
        $guru = User::factory()->create(['role' => 'guru']);

        $this->actingAs($guru)
            ->get('/guru/units')
            ->assertOk()
            ->assertSee('KELOLA UNIT')
            ->assertSee('TAMBAH UNIT')
            ->assertSee('Tambah Isi Unit')
            ->assertSee('Isi unit')
            ->assertSee('Pilih Level')
            ->assertDontSee('Modal dummy');
    }

    public function test_old_materials_page_redirects_to_combined_units_page_without_technical_node_label(): void
    {
        $guru = User::factory()->create(['role' => 'guru']);

        $this->actingAs($guru)
            ->get('/guru/materi')
            ->assertRedirect('/guru/units');

        $this->actingAs($guru)
            ->get('/guru/units')
            ->assertOk()
            ->assertSee('KELOLA UNIT PEMBELAJARAN')
            ->assertSee('Tambah Isi Unit')
            ->assertSee('Unit Induk')
            ->assertDontSee('Tambah Node')
            ->assertDontSee('Parent Node')
            ->assertDontSee('Child Node');
    }

    public function test_guru_can_fetch_units_by_level(): void
    {
        $guru = User::factory()->create(['role' => 'guru']);
        $level = Level::create(['code' => 'N5', 'name' => 'JLPT N5 - Pemula']);
        $section = Section::create(['id' => 'n5', 'name' => 'N5', 'description' => 'JLPT N5 - Pemula']);

        Unit::create([
            'id' => 'hiragana',
            'sectionId' => $section->id,
            'level_id' => $level->id,
            'title' => 'Hiragana Dasar',
            'description' => 'Mempelajari huruf Hiragana',
            'type' => 'hiragana',
            'category_japanese' => 'ひらがな',
            'category_romaji' => 'hiragana',
            'order' => 0,
            'sort_order' => 0,
        ]);

        $this->actingAs($guru)
            ->getJson("/api/guru/levels/{$level->id}/units")
            ->assertOk()
            ->assertJsonPath('data.level.code', 'N5')
            ->assertJsonPath('data.units_count', 1)
            ->assertJsonPath('data.units.0.title', 'Hiragana Dasar')
            ->assertJsonPath('data.units.0.category_romaji', 'hiragana');
    }

    public function test_guru_can_create_update_delete_and_reorder_units(): void
    {
        $guru = User::factory()->create(['role' => 'guru']);
        $level = Level::create(['code' => 'N5', 'name' => 'JLPT N5 - Pemula']);

        $createResponse = $this->actingAs($guru)->postJson('/api/guru/units', [
            'level_id' => $level->id,
            'title' => 'Salam Dasar',
            'description' => 'Pembuka percakapan.',
            'category_japanese' => '語彙',
            'category_romaji' => 'goi',
            'sort_order' => 0,
            'is_active' => true,
        ]);

        $createResponse
            ->assertCreated()
            ->assertJsonPath('data.title', 'Salam Dasar');

        $unitId = $createResponse->json('data.id');

        $secondResponse = $this->actingAs($guru)->postJson('/api/guru/units', [
            'level_id' => $level->id,
            'title' => 'Bunpo Awal',
            'description' => 'Pola kalimat dasar.',
            'category_japanese' => '文法',
            'category_romaji' => 'bunpou',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $secondId = $secondResponse->json('data.id');

        $this->actingAs($guru)->putJson("/api/guru/units/{$unitId}", [
            'level_id' => $level->id,
            'title' => 'Salam Harian',
            'description' => 'Sapaan untuk percakapan harian.',
            'category_japanese' => '語彙',
            'category_romaji' => 'goi',
            'sort_order' => 0,
            'is_active' => true,
        ])->assertOk()
            ->assertJsonPath('data.title', 'Salam Harian');

        $this->assertDatabaseHas('units', [
            'id' => $unitId,
            'title' => 'Salam Harian',
            'level_id' => $level->id,
        ]);

        $this->actingAs($guru)->patchJson('/api/guru/units/reorder', [
            'level_id' => $level->id,
            'unit_ids' => [$secondId, $unitId],
        ])->assertOk()
            ->assertJsonPath('data.units.0.id', $secondId)
            ->assertJsonPath('data.units.1.id', $unitId);

        $this->assertDatabaseHas('units', [
            'id' => $secondId,
            'sort_order' => 0,
        ]);

        $this->actingAs($guru)
            ->deleteJson("/api/guru/units/{$unitId}")
            ->assertOk();

        $this->assertSoftDeleted('units', ['id' => $unitId]);
    }

    public function test_guru_can_manage_materials_and_quiz_questions(): void
    {
        $guru = User::factory()->create(['role' => 'guru']);
        $level = Level::create(['code' => 'N5', 'name' => 'JLPT N5 - Pemula']);
        $section = Section::create(['id' => 'n5', 'name' => 'N5', 'description' => 'JLPT N5 - Pemula']);
        $unit = Unit::create([
            'id' => 'hiragana',
            'sectionId' => $section->id,
            'level_id' => $level->id,
            'title' => 'Hiragana Dasar',
            'description' => 'Mempelajari hiragana.',
            'type' => 'hiragana',
            'category_japanese' => 'kana',
            'category_romaji' => 'hiragana',
            'order' => 0,
            'sort_order' => 0,
        ]);

        $lessonResponse = $this->actingAs($guru)->postJson('/api/guru/materials', [
            'unit_id' => $unit->id,
            'type' => 'lesson',
            'title' => 'Hiragana A Row',
            'order' => 0,
        ]);

        $lessonResponse
            ->assertCreated()
            ->assertJsonPath('data.title', 'Hiragana A Row')
            ->assertJsonPath('data.type', 'lesson');

        $quizResponse = $this->actingAs($guru)->postJson('/api/guru/materials', [
            'unit_id' => $unit->id,
            'type' => 'quiz',
            'title' => 'Quiz Hiragana Dasar',
            'order' => 1,
        ]);

        $quizResponse->assertCreated()->assertJsonPath('data.type', 'quiz');
        $quizId = $quizResponse->json('data.id');

        $this->actingAs($guru)->getJson("/api/guru/levels/{$level->id}/materials")
            ->assertOk()
            ->assertJsonPath('data.units.0.materials.0.title', 'Hiragana A Row')
            ->assertJsonPath('data.units.0.materials.1.title', 'Quiz Hiragana Dasar');

        $questionResponse = $this->actingAs($guru)->postJson("/api/guru/materials/{$quizId}/questions", [
            'type' => 'new-letter',
            'letter' => 'a',
            'reading' => 'a',
            'order' => 0,
        ]);

        $questionResponse
            ->assertCreated()
            ->assertJsonPath('data.type', 'new-letter')
            ->assertJsonPath('data.reading', 'a');

        $questionId = $questionResponse->json('data.id');

        $this->actingAs($guru)->putJson("/api/guru/questions/{$questionId}", [
            'type' => 'multiple-choice',
            'question_text' => 'Huruf apa ini?',
            'letter' => 'a',
            'option_a' => 'a',
            'option_b' => 'i',
            'correct_answer' => 'A',
            'order' => 0,
        ])->assertOk()
            ->assertJsonPath('data.type', 'multiple-choice')
            ->assertJsonPath('data.correct_answer', 'A');

        $this->assertDatabaseHas('questions', [
            'id' => $questionId,
            'nodeId' => $quizId,
            'type' => 'multiple-choice',
        ]);

        $this->actingAs($guru)
            ->deleteJson("/api/guru/questions/{$questionId}")
            ->assertOk();

        $this->assertDatabaseMissing('questions', ['id' => $questionId]);
    }

    public function test_student_cannot_access_guru_unit_api(): void
    {
        $student = User::factory()->create(['role' => 'siswa']);

        $this->actingAs($student)
            ->getJson('/api/guru/levels')
            ->assertForbidden();
    }
}
