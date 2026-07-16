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
        $admin = User::factory()->create([
            'name' => 'Admin Penguji',
            'role' => 'admin',
        ]);

        $this->actingAs($admin)
            ->get('/guru/dashboard')
            ->assertOk()
            ->assertSee('Generate Akun');

        $this->actingAs($admin)
            ->get('/guru/student-accounts')
            ->assertOk()
            ->assertSee('GENERATE AKUN SISWA')
            ->assertSee('Akun Guru')
            ->assertSee('Reset Password')
            ->assertSee('Admin Penguji')
            ->assertSee('Admin');
    }

    public function test_guru_can_generate_student_accounts_but_cannot_access_admin_account_tools(): void
    {
        $guru = User::factory()->create([
            'name' => 'Guru Penguji',
            'role' => 'guru',
        ]);

        $this->actingAs($guru)
            ->get('/guru/dashboard')
            ->assertOk()
            ->assertSee('Generate Akun')
            ->assertSee('Akun Siswa')
            ->assertDontSee('Akun Guru')
            ->assertDontSee('Reset Password');

        $this->actingAs($guru)
            ->get('/guru/student-accounts')
            ->assertOk()
            ->assertSee('GENERATE AKUN SISWA')
            ->assertSee('Guru Penguji')
            ->assertSee('Guru')
            ->assertDontSee('Akun Guru')
            ->assertDontSee('Reset Password');

        $this->actingAs($guru)
            ->post('/guru/student-accounts', [
                'prefix' => 'kelas',
                'count' => 1,
            ])
            ->assertRedirect('/guru/student-accounts')
            ->assertSessionHas('status', 'student-accounts-generated');

        $student = User::query()->where('username', 'kelas001')->firstOrFail();

        $this->assertSame('siswa', $student->role);
        $this->assertTrue(Hash::check('kelas001', $student->password));

        $this->actingAs($guru)
            ->get('/guru/teacher-accounts')
            ->assertForbidden();

        $this->actingAs($guru)
            ->post('/guru/teacher-accounts', [
                'prefix' => 'pengajar',
                'count' => 1,
            ])
            ->assertForbidden();

        $this->actingAs($guru)
            ->get('/guru/students')
            ->assertForbidden();

        $this->actingAs($guru)
            ->patch("/guru/accounts/{$guru->id}/reset-password")
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

        $this->assertSame('kelas001', $generatedAccounts[0]['username']);
        $this->assertSame($generatedAccounts[0]['username'], $generatedAccounts[0]['password']);
        $this->assertTrue(Hash::check('kelas001', $student->password));
    }

    public function test_admin_can_generate_teacher_accounts_with_username_as_initial_password(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)
            ->get('/guru/teacher-accounts')
            ->assertOk()
            ->assertSee('GENERATE AKUN GURU')
            ->assertSee('Password awal sama dengan username');

        $response = $this->actingAs($admin)
            ->post('/guru/teacher-accounts', [
                'prefix' => 'pengajar',
                'count' => 2,
            ])
            ->assertRedirect('/guru/teacher-accounts')
            ->assertSessionHas('status', 'teacher-accounts-generated')
            ->assertSessionHas('generated_accounts');

        $generatedAccounts = $response->baseResponse->getSession()->get('generated_accounts');

        $this->assertCount(2, $generatedAccounts);
        $this->assertSame('pengajar001', $generatedAccounts[0]['username']);
        $this->assertSame('pengajar001', $generatedAccounts[0]['password']);
        $this->assertDatabaseHas('users', [
            'username' => 'pengajar001',
            'email' => 'pengajar001@teacher.local',
            'role' => 'guru',
        ]);

        $teacher = User::query()->where('username', 'pengajar001')->firstOrFail();

        $this->assertTrue(Hash::check('pengajar001', $teacher->password));
    }

    public function test_admin_can_view_students_with_fifteen_records_per_page(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        foreach (range(1, 16) as $number) {
            User::factory()->create([
                'role' => 'siswa',
                'username' => sprintf('murid%03d', $number),
            ]);
        }

        $this->actingAs($admin)
            ->get('/guru/students')
            ->assertOk()
            ->assertSee('KELOLA PASSWORD AKUN')
            ->assertSee('Siswa')
            ->assertSee('Guru')
            ->assertSee('murid001')
            ->assertSee('murid015')
            ->assertDontSee('murid016')
            ->assertSee('Halaman 1 dari 2');

        $this->actingAs($admin)
            ->get('/guru/students?page=2')
            ->assertOk()
            ->assertSee('murid016')
            ->assertDontSee('murid001')
            ->assertSee('Halaman 2 dari 2');
    }

    public function test_admin_can_reset_student_password_to_username_without_changing_profile(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $student = User::factory()->create([
            'name' => 'Nama Murid',
            'username' => 'murid007',
            'email' => 'murid007@student.local',
            'password' => Hash::make('password-lama'),
            'role' => 'siswa',
        ]);

        $this->actingAs($admin)
            ->from('/guru/students?page=2')
            ->patch("/guru/accounts/{$student->id}/reset-password")
            ->assertRedirect('/guru/students?page=2')
            ->assertSessionHas('status', 'account-password-reset')
            ->assertSessionHas('reset_username', 'murid007')
            ->assertSessionHas('reset_role', 'siswa');

        $student->refresh();

        $this->assertTrue(Hash::check('murid007', $student->password));
        $this->assertSame('Nama Murid', $student->name);
        $this->assertSame('murid007@student.local', $student->email);
        $this->assertSame('siswa', $student->role);
    }

    public function test_admin_can_view_and_paginate_teacher_accounts_separately(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        User::factory()->create(['role' => 'siswa', 'username' => 'murid_tidak_tampil']);

        foreach (range(1, 16) as $number) {
            User::factory()->create([
                'role' => 'guru',
                'username' => sprintf('pengajar%03d', $number),
            ]);
        }

        $this->actingAs($admin)
            ->get('/guru/students?tab=guru')
            ->assertOk()
            ->assertSee('pengajar001')
            ->assertSee('pengajar015')
            ->assertDontSee('pengajar016')
            ->assertDontSee('murid_tidak_tampil')
            ->assertSee('Halaman 1 dari 2');

        $this->actingAs($admin)
            ->get('/guru/students?tab=guru&page=2')
            ->assertOk()
            ->assertSee('pengajar016')
            ->assertDontSee('pengajar001')
            ->assertSee('Halaman 2 dari 2');
    }

    public function test_admin_can_reset_teacher_password_to_username_without_changing_profile(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $teacher = User::factory()->create([
            'name' => 'Nama Guru',
            'username' => 'guru_target',
            'email' => 'guru_target@teacher.local',
            'password' => Hash::make('password-lama'),
            'role' => 'guru',
        ]);

        $this->actingAs($admin)
            ->from('/guru/students?tab=guru&page=2')
            ->patch("/guru/accounts/{$teacher->id}/reset-password")
            ->assertRedirect('/guru/students?tab=guru&page=2')
            ->assertSessionHas('status', 'account-password-reset')
            ->assertSessionHas('reset_username', 'guru_target')
            ->assertSessionHas('reset_role', 'guru');

        $teacher->refresh();

        $this->assertTrue(Hash::check('guru_target', $teacher->password));
        $this->assertSame('Nama Guru', $teacher->name);
        $this->assertSame('guru_target@teacher.local', $teacher->email);
        $this->assertSame('guru', $teacher->role);
    }

    public function test_password_reset_endpoint_rejects_admin_accounts(): void
    {
        $admin = User::factory()->create([
            'username' => 'admin_target',
            'password' => Hash::make('password-lama'),
            'role' => 'admin',
        ]);

        $this->actingAs($admin)
            ->patch("/guru/accounts/{$admin->id}/reset-password")
            ->assertNotFound();

        $this->assertTrue(Hash::check('password-lama', $admin->refresh()->password));
    }

    public function test_student_cannot_access_admin_account_tools(): void
    {
        $student = User::factory()->create(['role' => 'siswa']);

        $this->actingAs($student)->get('/guru/student-accounts')->assertForbidden();
        $this->actingAs($student)->get('/guru/teacher-accounts')->assertForbidden();
        $this->actingAs($student)->get('/guru/students')->assertForbidden();
        $this->actingAs($student)
            ->patch("/guru/accounts/{$student->id}/reset-password")
            ->assertForbidden();
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
