<?php

namespace Tests\Feature;

use App\Models\Node;
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
        $section = Section::create([
            'id' => 'n5',
            'name' => 'N5',
            'description' => 'JLPT N5 - Pemula',
        ]);

        $firstUnit = Unit::create([
            'id' => 'hiragana',
            'sectionId' => $section->id,
            'title' => 'Hiragana Dasar',
            'description' => 'Mempelajari huruf Hiragana',
            'type' => 'hiragana',
            'order' => 0,
        ]);

        $secondUnit = Unit::create([
            'id' => 'katakana',
            'sectionId' => $section->id,
            'title' => 'Katakana Dasar',
            'description' => 'Mempelajari huruf Katakana',
            'type' => 'katakana',
            'order' => 1,
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
