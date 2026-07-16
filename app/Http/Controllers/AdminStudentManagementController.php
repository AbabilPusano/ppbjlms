<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class AdminStudentManagementController extends Controller
{
    public function index(Request $request): View
    {
        $activeRole = $request->string('tab')->toString() === 'guru' ? 'guru' : 'siswa';

        return view('admin-student-management-ui', [
            'accounts' => User::query()
                ->where('role', $activeRole)
                ->orderBy('username')
                ->paginate(15)
                ->withQueryString(),
            'activeRole' => $activeRole,
        ]);
    }

    public function resetPassword(User $account): RedirectResponse
    {
        abort_unless(in_array($account->role, ['siswa', 'guru'], true), 404);

        $account->update([
            'password' => Hash::make($account->username),
        ]);

        return back()
            ->with('status', 'account-password-reset')
            ->with('reset_username', $account->username)
            ->with('reset_role', $account->role);
    }
}
