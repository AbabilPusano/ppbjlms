<?php

namespace Tests\Feature;

use App\Models\Level;
use App\Models\Node;
use App\Models\NodeStep;
use App\Models\Section;
use App\Models\Unit;
use App\Models\User;
use App\Models\UserProgress;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class StudentDashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_student_lesson_still_uses_shared_content_and_completion_endpoint(): void
    {
        $student = User::factory()->create(['role' => 'siswa']);
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
            'order' => 0,
            'is_active' => true,
        ]);
        $node = Node::create([
            'id' => 'hiragana-a',
            'unitId' => $unit->id,
            'title' => 'Kolom A',
            'type' => 'lesson',
            'order' => 0,
        ]);

        NodeStep::create([
            'id' => 'student-step',
            'node_id' => $node->id,
            'type' => 'text',
            'content' => 'Konten dari builder bersama.',
            'sort_order' => 0,
        ]);

        $this->actingAs($student)
            ->get(route('lesson.show', ['unit' => 1, 'level' => 1]))
            ->assertOk()
            ->assertSee('Konten dari builder bersama.')
            ->assertSee('data-complete-url="'.route('api.v1.nodes.complete.web', $node).'"', false);
    }

    public function test_dashboard_shows_empty_state_when_learning_content_is_missing(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get('/')
            ->assertOk()
            ->assertSee('Unit belajar belum tersedia')
            ->assertDontSee('Hiragana Awal')
            ->assertDontSee('dummy');
    }

    public function test_dashboard_uses_database_units_and_authenticated_user_progress(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $level = Level::create(['code' => 'N5', 'name' => 'JLPT N5 - Pemula']);
        $section = Section::create([
            'id' => 'n5',
            'name' => 'N5',
            'description' => 'JLPT N5 - Pemula',
        ]);

        $firstUnit = Unit::create([
            'id' => 'hiragana',
            'sectionId' => $section->id,
            'level_id' => $level->id,
            'title' => 'Hiragana Dasar',
            'description' => 'Mempelajari huruf Hiragana',
            'type' => 'hiragana',
            'order' => 0,
            'is_active' => true,
        ]);

        $secondUnit = Unit::create([
            'id' => 'katakana',
            'sectionId' => $section->id,
            'level_id' => $level->id,
            'title' => 'Katakana Dasar',
            'description' => 'Mempelajari huruf Katakana',
            'type' => 'katakana',
            'order' => 1,
            'is_active' => true,
        ]);

        $firstNode = Node::create([
            'id' => 'hiragana-a',
            'unitId' => $firstUnit->id,
            'title' => 'Hiragana あ行',
            'type' => 'lesson',
            'order' => 0,
        ]);

        Node::create([
            'id' => 'hiragana-ka',
            'unitId' => $firstUnit->id,
            'title' => 'Hiragana か行',
            'type' => 'lesson',
            'order' => 1,
        ]);

        Node::create([
            'id' => 'katakana-a',
            'unitId' => $secondUnit->id,
            'title' => 'Katakana ア行',
            'type' => 'lesson',
            'order' => 0,
        ]);

        UserProgress::create([
            'id' => (string) Str::uuid(),
            'userId' => $user->id,
            'nodeId' => $firstNode->id,
            'status' => 'completed',
        ]);

        UserProgress::create([
            'id' => (string) Str::uuid(),
            'userId' => $otherUser->id,
            'nodeId' => 'hiragana-ka',
            'status' => 'completed',
        ]);

        $this->actingAs($user)
            ->get('/')
            ->assertOk()
            ->assertSee('Ruang belajar N5')
            ->assertSee('Hiragana Dasar')
            ->assertSee('Katakana Dasar')
            ->assertSee('1/2 step')
            ->assertSee('50%')
            ->assertSee('Sedang berjalan')
            ->assertSee('Terkunci')
            ->assertSee('Hiragana か行')
            ->assertDontSee('2/2 step');
    }
}
