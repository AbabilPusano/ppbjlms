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

        $adminWhatsAppUrl = $this->adminWhatsAppUrl();
        $failedUsername = trim((string) $request->old('username'));
        $passwordResetWhatsAppUrl = null;

        if ($adminWhatsAppUrl && $request->session()->get('login_failed') && $failedUsername !== '') {
            $message = "Halo Admin, saya ingin mereset password saya. Username saya adalah {$failedUsername}.";
            $passwordResetWhatsAppUrl = $adminWhatsAppUrl.'?text='.rawurlencode($message);
        }

        return view('login-ui', compact('adminWhatsAppUrl', 'passwordResetWhatsAppUrl'));
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
                ->with('login_failed', true)
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
            'admin' => redirect()->route('guru.dashboard'),
            'guru' => redirect()->route('guru.dashboard'),
            'siswa' => redirect()->route('student.dashboard'),
            default => redirect()->route('login'),
        };
    }

    private function adminWhatsAppUrl(): ?string
    {
        $number = preg_replace('/\D+/', '', (string) config('services.whatsapp.admin_number'));

        if (! $number) {
            return null;
        }

        if (str_starts_with($number, '0')) {
            $number = '62'.substr($number, 1);
        }

        return 'https://wa.me/'.$number;
    }
}
