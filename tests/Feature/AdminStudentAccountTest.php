<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminStudentAccountTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_open_student_account_generator_from_dashboard(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)
            ->get('/guru/dashboard')
            ->assertOk()
            ->assertSee('Generate Akun');

        $this->actingAs($admin)
            ->get('/guru/student-accounts')
            ->assertOk()
            ->assertSee('GENERATE AKUN SISWA')
            ->assertSee('role admin');
    }

    public function test_guru_cannot_access_student_account_generator(): void
    {
        $guru = User::factory()->create(['role' => 'guru']);

        $this->actingAs($guru)
            ->get('/guru/dashboard')
            ->assertOk()
            ->assertDontSee('Generate Akun');

        $this->actingAs($guru)
            ->get('/guru/student-accounts')
            ->assertForbidden();

        $this->actingAs($guru)
            ->post('/guru/student-accounts', [
                'prefix' => 'kelas',
                'count' => 1,
            ])
            ->assertForbidden();
    }

    public function test_admin_can_generate_student_accounts(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)
            ->post('/guru/student-accounts', [
                'prefix' => 'kelas',
                'count' => 2,
            ])
            ->assertRedirect('/guru/student-accounts')
            ->assertSessionHas('status', 'student-accounts-generated')
            ->assertSessionHas('generated_accounts');

        $generatedAccounts = $response->baseResponse->getSession()->get('generated_accounts');

        $this->assertCount(2, $generatedAccounts);
        $this->assertDatabaseHas('users', [
            'username' => 'kelas001',
            'email' => 'kelas001@student.local',
            'role' => 'siswa',
        ]);
        $this->assertDatabaseHas('users', [
            'username' => 'kelas002',
            'email' => 'kelas002@student.local',
            'role' => 'siswa',
        ]);

        $student = User::query()->where('username', 'kelas001')->firstOrFail();

        $this->assertTrue(Hash::check($generatedAccounts[0]['password'], $student->password));
    }

    public function test_seeded_admin_uses_admin_role_and_password(): void
    {
        $this->seed();

        $admin = User::query()->where('username', 'admin')->firstOrFail();
        $guru = User::query()->where('username', 'guru')->firstOrFail();

        $this->assertSame('admin', $admin->role);
        $this->assertTrue(Hash::check('password', $admin->password));
        $this->assertSame('guru', $guru->role);
    }
}
