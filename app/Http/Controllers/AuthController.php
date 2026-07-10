<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function showLogin(Request $request): RedirectResponse|View
    {
        if ($request->user()) {
            return $this->redirectByRole($request);
        }

        return view('login-ui');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        if (! Auth::attempt($credentials)) {
            return back()
                ->withErrors(['username' => 'Username atau password tidak valid.'])
                ->onlyInput('username');
        }

        $request->session()->regenerate();

        return $this->redirectByRole($request);
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    private function redirectByRole(Request $request): RedirectResponse
    {
        return match ($request->user()?->role) {
            'guru' => redirect()->route('guru.dashboard'),
            'siswa' => redirect()->route('student.dashboard'),
            default => redirect()->route('login'),
        };
    }
}
