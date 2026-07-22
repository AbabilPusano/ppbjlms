<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LettersPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_letters_page_renders_when_no_levels_exist(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get('/letters')
            ->assertOk()
            ->assertSee('Ruang huruf Jepang')
            ->assertSee('Belum ada data level');
    }
}
