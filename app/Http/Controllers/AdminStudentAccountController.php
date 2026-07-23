<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AdminStudentAccountController extends Controller
{
    public function index(): View
    {
        return view('admin-student-accounts-ui');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'count' => ['required', 'integer', 'min:1', 'max:50'],
            'prefix' => ['required', 'string', 'max:24', 'regex:/^[A-Za-z0-9_]+$/'],
            'batch' => ['required', 'string', 'max:50'],
        ]);

        $prefix = Str::lower($validated['prefix']);
        $nextNumber = 1;
        $accounts = [];

        for ($index = 0; $index < $validated['count']; $index++) {
            $username = $this->nextAvailableUsername($prefix, $nextNumber);
            $password = $username;

            User::create([
                'name' => $username,
                'username' => $username,
                'email' => $username.'@student.local',
                'password' => Hash::make($password),
                'role' => 'siswa',
                'batch' => $validated['batch'],
                'bio' => 'Akun siswa hasil generate admin.',
                'avatar' => '学',
                'streak' => 0,
                'totalXP' => 0,
                'gems' => 0,
                'league' => 'Bronze',
            ]);

            $accounts[] = [
                'name' => $username,
                'username' => $username,
                'password' => $password,
            ];
        }

        return redirect()
            ->route('admin.student-accounts.index')
            ->with('generated_accounts', $accounts)
            ->with('status', 'student-accounts-generated');
    }

    private function nextAvailableUsername(string $prefix, int &$nextNumber): string
    {
        do {
            $username = sprintf('%s%03d', $prefix, $nextNumber);
            $nextNumber++;
        } while (User::query()->where('username', $username)->exists());

        return $username;
    }
}
