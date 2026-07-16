<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AdminTeacherAccountController extends Controller
{
    public function index(): View
    {
        return view('admin-teacher-accounts-ui', [
            'teacherCount' => User::query()->where('role', 'guru')->count(),
            'recentTeachers' => User::query()
                ->where('role', 'guru')
                ->latest()
                ->limit(8)
                ->get(['name', 'username', 'email', 'created_at']),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'count' => ['required', 'integer', 'min:1', 'max:50'],
            'prefix' => ['required', 'string', 'max:24', 'regex:/^[A-Za-z0-9_]+$/'],
        ]);

        $prefix = Str::lower($validated['prefix']);
        $nextNumber = 1;
        $accounts = [];

        for ($index = 0; $index < $validated['count']; $index++) {
            $username = $this->nextAvailableUsername($prefix, $nextNumber);

            User::create([
                'name' => 'Guru '.strtoupper($username),
                'username' => $username,
                'email' => $username.'@teacher.local',
                'password' => Hash::make($username),
                'role' => 'guru',
                'bio' => 'Akun guru hasil generate admin.',
                'avatar' => 'G',
                'streak' => 0,
                'totalXP' => 0,
                'gems' => 0,
                'league' => 'Bronze',
            ]);

            $accounts[] = [
                'name' => 'Guru '.strtoupper($username),
                'username' => $username,
                'password' => $username,
            ];
        }

        return redirect()
            ->route('admin.teacher-accounts.index')
            ->with('generated_accounts', $accounts)
            ->with('status', 'teacher-accounts-generated');
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
