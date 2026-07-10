<?php

namespace Tests\Feature;

use App\Models\ApiToken;
use App\Models\Node;
use App\Models\Section;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Tests\TestCase;

class ApiV1Test extends TestCase
{
    use RefreshDatabase;

    public function test_health_endpoint_returns_api_status(): void
    {
        $response = $this->getJson('/api/v1/health');

        $response
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.version', 'v1');
    }

    public function test_user_can_login_and_receive_api_token(): void
    {
        User::factory()->create([
            'username' => 'siswa',
            'password' => Hash::make('password'),
        ]);

        $response = $this->postJson('/api/v1/login', [
            'username' => 'siswa',
            'password' => 'password',
            'device_name' => 'android',
        ]);

        $response
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.token_type', 'Bearer')
            ->assertJsonStructure([
                'data' => [
                    'access_token',
                    'expires_at',
                    'user' => ['id', 'username', 'role'],
                ],
            ]);

        $this->assertDatabaseCount('api_tokens', 1);
    }

    public function test_protected_endpoint_requires_valid_bearer_token(): void
    {
        $this->getJson('/api/v1/me')
            ->assertUnauthorized()
            ->assertJsonPath('success', false);

        $user = User::factory()->create(['username' => 'siswa']);
        $plainToken = $this->createTokenFor($user);

        $this->withHeader('Authorization', "Bearer {$plainToken}")
            ->getJson('/api/v1/me')
            ->assertOk()
            ->assertJsonPath('data.username', 'siswa');
    }

    public function test_authenticated_user_can_read_sections(): void
    {
        $user = User::factory()->create();
        $plainToken = $this->createTokenFor($user);

        $section = Section::create([
            'id' => 'n5',
            'name' => 'N5',
            'description' => 'Materi dasar.',
        ]);

        Unit::create([
            'id' => 'n5-unit-1',
            'sectionId' => $section->id,
            'title' => 'Salam dasar',
            'description' => 'Pembuka percakapan.',
            'type' => 'vocabulary',
            'order' => 1,
        ]);

        $this->withHeader('Authorization', "Bearer {$plainToken}")
            ->getJson('/api/v1/sections')
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.0.id', 'n5')
            ->assertJsonPath('data.0.units_count', 1);
    }

    public function test_authenticated_user_can_complete_node_progress(): void
    {
        $user = User::factory()->create();
        $plainToken = $this->createTokenFor($user);

        $section = Section::create([
            'id' => 'n5',
            'name' => 'N5',
            'description' => 'Materi dasar.',
        ]);

        $unit = Unit::create([
            'id' => 'n5-unit-1',
            'sectionId' => $section->id,
            'title' => 'Salam dasar',
            'description' => 'Pembuka percakapan.',
            'type' => 'vocabulary',
            'order' => 1,
        ]);

        $node = Node::create([
            'id' => 'n5-node-1',
            'unitId' => $unit->id,
            'title' => 'Hajimemashite',
            'type' => 'lesson',
            'order' => 1,
        ]);

        $this->withHeader('Authorization', "Bearer {$plainToken}")
            ->postJson("/api/v1/nodes/{$node->id}/complete")
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.status', 'completed');

        $this->assertDatabaseHas('user_progress', [
            'userId' => $user->id,
            'nodeId' => $node->id,
            'status' => 'completed',
        ]);
    }

    private function createTokenFor(User $user): string
    {
        $plainToken = Str::random(80);

        ApiToken::create([
            'user_id' => $user->id,
            'name' => 'test-device',
            'token' => hash('sha256', $plainToken),
            'abilities' => ['*'],
            'expires_at' => now()->addDay(),
        ]);

        return $plainToken;
    }
}
