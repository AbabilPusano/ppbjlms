<?php

namespace Tests\Feature;

use App\Models\Level;
use App\Models\Node;
use App\Models\NodeStep;
use App\Models\Question;
use App\Models\Section;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GuruMaterialPreviewTest extends TestCase
{
    use RefreshDatabase;

    public function test_guru_can_preview_the_exact_material_without_completion_endpoint(): void
    {
        $guru = User::factory()->create(['role' => 'guru']);
        $material = $this->createMaterial();

        NodeStep::create([
            'id' => 'step-second',
            'node_id' => $material->id,
            'type' => 'character',
            'character_symbol' => 'あ',
            'character_pronunciation' => 'a',
            'sort_order' => 2,
        ]);

        NodeStep::create([
            'id' => 'step-first',
            'node_id' => $material->id,
            'type' => 'text',
            'content' => 'Pengantar yang tampil lebih dahulu.',
            'sort_order' => 1,
        ]);

        NodeStep::create([
            'id' => 'step-third',
            'node_id' => $material->id,
            'type' => 'quiz',
            'quiz_instruction' => 'PILIH JAWABAN YANG BENAR',
            'quiz_query' => 'Pilih bacaan huruf あ',
            'quiz_options' => ['a', 'i', 'u'],
            'quiz_correct_index' => 0,
            'sort_order' => 3,
        ]);

        $response = $this->actingAs($guru)
            ->get(route('guru.materials.preview', $material));

        $response
            ->assertOk()
            ->assertSee('Mode Preview Guru')
            ->assertSee($material->title)
            ->assertSee('Pengantar yang tampil lebih dahulu.')
            ->assertSee('Pilih bacaan huruf \\u3042', false)
            ->assertSee(route('guru.units.materials', $material->unitId), false)
            ->assertDontSee('data-complete-url', false);

        $content = $response->getContent();
        $this->assertLessThan(
            strpos($content, 'Pilih bacaan huruf \\u3042'),
            strpos($content, 'Pengantar yang tampil lebih dahulu.'),
        );
        $this->assertDatabaseCount('user_progress', 0);
    }

    public function test_admin_can_preview_material(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $material = $this->createMaterial();

        $this->actingAs($admin)
            ->get(route('guru.materials.preview', $material))
            ->assertOk();
    }

    public function test_student_and_guest_cannot_open_teacher_preview(): void
    {
        $student = User::factory()->create(['role' => 'siswa']);
        $material = $this->createMaterial();
        $previewUrl = route('guru.materials.preview', $material);

        $this->actingAs($student)
            ->get($previewUrl)
            ->assertForbidden();

        $this->app['auth']->guard()->logout();

        $this->get($previewUrl)
            ->assertRedirect(route('login'));
    }

    public function test_empty_material_preview_shows_manage_steps_action(): void
    {
        $guru = User::factory()->create(['role' => 'guru']);
        $material = $this->createMaterial();

        $this->actingAs($guru)
            ->get(route('guru.materials.preview', $material))
            ->assertOk()
            ->assertSee('Isi unit ini belum memiliki langkah pembelajaran.')
            ->assertSee('Kelola langkah')
            ->assertSee(route('guru.materials.questions', $material), false)
            ->assertDontSee('data-lesson-footer', false)
            ->assertDontSee('data-complete-url', false);
    }

    public function test_preview_uses_questions_as_fallback_when_steps_are_empty(): void
    {
        $guru = User::factory()->create(['role' => 'guru']);
        $material = $this->createMaterial();

        Question::create([
            'id' => 'fallback-question',
            'nodeId' => $material->id,
            'type' => 'multiple-choice',
            'question' => 'Soal lama tetap tampil?',
            'options' => [
                ['key' => 'A', 'text' => 'Ya'],
                ['key' => 'B', 'text' => 'Tidak'],
            ],
            'correctAnswer' => ['text' => 'Ya'],
            'relatedCharacters' => [],
            'order' => 0,
        ]);

        $this->actingAs($guru)
            ->get(route('guru.materials.preview', $material))
            ->assertOk()
            ->assertSee('Soal lama tetap tampil?')
            ->assertSee('Ya')
            ->assertDontSee('Isi unit ini belum memiliki langkah pembelajaran.');
    }

    private function createMaterial(): Node
    {
        $level = Level::create(['code' => 'N5', 'name' => 'JLPT N5 - Pemula']);
        $section = Section::create([
            'id' => 'n5',
            'name' => 'N5',
            'description' => 'JLPT N5 - Pemula',
        ]);
        $unit = Unit::create([
            'id' => 'hiragana',
            'sectionId' => $section->id,
            'level_id' => $level->id,
            'title' => 'Hiragana Dasar',
            'description' => 'Mempelajari hiragana.',
            'type' => 'hiragana',
            'category_japanese' => 'かな',
            'category_romaji' => 'hiragana',
            'order' => 0,
            'sort_order' => 0,
            'is_active' => false,
        ]);

        return Node::create([
            'id' => 'hiragana-a',
            'unitId' => $unit->id,
            'title' => 'Kolom A',
            'type' => 'lesson',
            'order' => 0,
        ]);
    }
}
