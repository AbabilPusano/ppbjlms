<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoginAdminSupportTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        config(['services.whatsapp.admin_number' => '0812-3456-7890']);
    }

    public function test_login_page_has_admin_contact_without_default_message(): void
    {
        $response = $this->get('/login');

        $response
            ->assertOk()
            ->assertSee('Hubungi Admin')
            ->assertSee('href="https://wa.me/6281234567890"', false)
            ->assertDontSee('Lupa Password? Konfirmasi Admin')
            ->assertDontSee('https://wa.me/6281234567890?text=', false);
    }

    public function test_failed_login_shows_password_reset_link_with_username(): void
    {
        User::factory()->create([
            'username' => 'siswa001',
            'password' => 'password-benar',
        ]);

        $response = $this->followingRedirects()->from('/login')->post('/login', [
            'username' => 'siswa001',
            'password' => 'password-salah',
        ]);

        $message = 'Halo Admin, saya ingin mereset password saya. Username saya adalah siswa001.';

        $response
            ->assertOk()
            ->assertSee('Username atau password tidak valid.')
            ->assertSee('Lupa Password? Konfirmasi Admin')
            ->assertSee('href="https://wa.me/6281234567890?text='.rawurlencode($message).'"', false)
            ->assertDontSee('password-salah');
    }

    public function test_reset_link_is_not_rendered_when_admin_number_is_not_configured(): void
    {
        config(['services.whatsapp.admin_number' => null]);

        $response = $this->withSession([
            'login_failed' => true,
            '_old_input' => ['username' => 'siswa001'],
        ])->get('/login');

        $response
            ->assertOk()
            ->assertSee('Kontak administrator belum dikonfigurasi.')
            ->assertDontSee('Lupa Password? Konfirmasi Admin')
            ->assertDontSee('wa.me', false);
    }
}
