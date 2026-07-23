<?php

namespace Tests\Feature;

use App\Models\Level;
use App\Models\Node;
use App\Models\NodeStep;
use App\Models\Section;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GuruStepValidationTest extends TestCase
{
    use RefreshDatabase;

    private User $guru;
    private Node $nodeA;
    private Node $nodeB;

    protected function setUp(): void
    {
        parent::setUp();

        $this->guru = User::factory()->create(['role' => 'guru']);

        $level = Level::create(['code' => 'N5', 'name' => 'JLPT N5']);
        $section = Section::create(['id' => 'n5', 'name' => 'N5', 'description' => 'JLPT N5']);
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
            'is_active' => true,
        ]);

        $this->nodeA = Node::create([
            'id' => 'node-a',
            'unitId' => $unit->id,
            'title' => 'Node A',
            'type' => 'lesson',
            'order' => 1,
        ]);

        $this->nodeB = Node::create([
            'id' => 'node-b',
            'unitId' => $unit->id,
            'title' => 'Node B',
            'type' => 'lesson',
            'order' => 2,
        ]);
    }

    public function test_check_character_detects_existing_character(): void
    {
        NodeStep::create([
            'id' => 'step-1',
            'node_id' => $this->nodeA->id,
            'type' => 'character',
            'character_symbol' => 'あ',
            'character_pronunciation' => 'a',
            'sort_order' => 1,
        ]);

        $response = $this->actingAs($this->guru)
            ->getJson(route('guru.api.steps.check-character', ['symbol' => 'あ']));

        $response->assertOk()
            ->assertJson([
                'exists' => true,
                'node_title' => 'Node A',
            ]);

        $responseEmpty = $this->actingAs($this->guru)
            ->getJson(route('guru.api.steps.check-character', ['symbol' => 'い']));

        $responseEmpty->assertOk()
            ->assertJson([
                'exists' => false,
            ]);

        // check exclusion works (when editing same step)
        $responseExclude = $this->actingAs($this->guru)
            ->getJson(route('guru.api.steps.check-character', [
                'symbol' => 'あ',
                'exclude_step_id' => 'step-1'
            ]));

        $responseExclude->assertOk()
            ->assertJson([
                'exists' => false,
            ]);
    }

    public function test_store_step_validates_duplicate_character(): void
    {
        NodeStep::create([
            'id' => 'step-1',
            'node_id' => $this->nodeA->id,
            'type' => 'character',
            'character_symbol' => 'あ',
            'character_pronunciation' => 'a',
            'sort_order' => 1,
        ]);

        // Attempting to add 'あ' in Node B should fail
        $response = $this->actingAs($this->guru)
            ->postJson(route('guru.api.steps.store'), [
                'node_id' => $this->nodeB->id,
                'type' => 'character',
                'character_symbol' => 'あ',
                'character_pronunciation' => 'a',
            ]);

        $response->assertStatus(422)
            ->assertJson([
                'message' => 'huruf sudah ada di node Node A',
            ]);

        // Attempting to add 'い' should succeed
        $responseSuccess = $this->actingAs($this->guru)
            ->postJson(route('guru.api.steps.store'), [
                'node_id' => $this->nodeB->id,
                'type' => 'character',
                'character_symbol' => 'い',
                'character_pronunciation' => 'i',
            ]);

        $responseSuccess->assertStatus(201);
    }

    public function test_update_step_validates_duplicate_character(): void
    {
        NodeStep::create([
            'id' => 'step-1',
            'node_id' => $this->nodeA->id,
            'type' => 'character',
            'character_symbol' => 'あ',
            'character_pronunciation' => 'a',
            'sort_order' => 1,
        ]);

        $step2 = NodeStep::create([
            'id' => 'step-2',
            'node_id' => $this->nodeB->id,
            'type' => 'character',
            'character_symbol' => 'い',
            'character_pronunciation' => 'i',
            'sort_order' => 1,
        ]);

        // Updating step-2 to 'あ' should fail
        $response = $this->actingAs($this->guru)
            ->putJson(route('guru.api.steps.update', $step2), [
                'type' => 'character',
                'character_symbol' => 'あ',
                'character_pronunciation' => 'a',
            ]);

        $response->assertStatus(422)
            ->assertJson([
                'message' => 'huruf sudah ada di node Node A',
            ]);

        // Updating step-2 to 'い' (no change) or 'う' should succeed
        $responseSuccess = $this->actingAs($this->guru)
            ->putJson(route('guru.api.steps.update', $step2), [
                'type' => 'character',
                'character_symbol' => 'う',
                'character_pronunciation' => 'u',
            ]);

        $responseSuccess->assertOk();
    }
}
