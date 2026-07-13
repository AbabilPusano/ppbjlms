<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_profile_pages_use_authenticated_user_from_database(): void
    {
        $user = User::factory()->create([
            'name' => 'Nama Dari Database',
            'username' => 'siswa_db',
            'email' => 'siswa@example.com',
            'bio' => 'Belajar dari data asli.',
            'streak' => 7,
            'totalXP' => 120,
            'gems' => 9,
            'league' => 'Silver',
        ]);

        $this->actingAs($user)
            ->get('/profile')
            ->assertOk()
            ->assertSee('Nama Dari Database')
            ->assertSee('@siswa_db')
            ->assertSee('siswa@example.com')
            ->assertSee('Belajar dari data asli.')
            ->assertDontSee('Rapka');

        $this->actingAs($user)
            ->get('/settings/profile')
            ->assertOk()
            ->assertSee('Nama Dari Database')
            ->assertSee('siswa_db')
            ->assertSee('siswa@example.com')
            ->assertSee('Belajar dari data asli.')
            ->assertDontSee('rapka@example.com');
    }

    public function test_profile_update_only_changes_name(): void
    {
        $user = User::factory()->create([
            'name' => 'Nama Lama',
            'username' => 'username_lama',
            'email' => 'lama@example.com',
            'bio' => 'Bio lama.',
        ]);

        $this->actingAs($user)
            ->patch('/settings/profile', [
                'name' => 'Nama Baru',
                'username' => 'username_baru',
                'email' => 'baru@example.com',
                'bio' => 'Bio baru.',
            ])
            ->assertRedirect('/settings/profile')
            ->assertSessionHas('status', 'profile-updated');

        $user->refresh();

        $this->assertSame('Nama Baru', $user->name);
        $this->assertSame('username_lama', $user->username);
        $this->assertSame('lama@example.com', $user->email);
        $this->assertSame('Bio lama.', $user->bio);
    }

    public function test_user_can_update_password_with_current_password(): void
    {
        $user = User::factory()->create([
            'password' => Hash::make('password-lama'),
        ]);

        $this->actingAs($user)
            ->patch('/profile/password', [
                'current_password' => 'password-lama',
                'password' => 'password-baru',
                'password_confirmation' => 'password-baru',
            ])
            ->assertRedirect('/profile')
            ->assertSessionHas('status', 'password-updated');

        $this->assertTrue(Hash::check('password-baru', $user->refresh()->password));
    }

    public function test_user_cannot_update_password_with_wrong_current_password(): void
    {
        $user = User::factory()->create([
            'password' => Hash::make('password-lama'),
        ]);

        $this->actingAs($user)
            ->from('/profile')
            ->patch('/profile/password', [
                'current_password' => 'salah-password',
                'password' => 'password-baru',
                'password_confirmation' => 'password-baru',
            ])
            ->assertRedirect('/profile')
            ->assertSessionHasErrors('current_password', null, 'password');

        $this->assertTrue(Hash::check('password-lama', $user->refresh()->password));
    }
}
