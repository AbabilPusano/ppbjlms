@extends('layouts.lms-ui', ['title' => 'Login - LMS PPBJ'])

@section('body')
<main class="login-page">
    <section class="login-form-area" aria-label="Form login">

        {{-- Brand mark --}}
        <div class="login-brand-mark">
            <div class="login-logo">P</div>
            <div>
                <strong>LMS PPBJ</strong>
                <span>Platform Pembelajaran Bahasa Jepang</span>
            </div>
        </div>

        <div class="login-form-card">
            <div class="login-form-head">
                <h2>Selamat datang</h2>
                <p>Masukkan username dan password akun Anda untuk melanjutkan.</p>
            </div>

            @if ($errors->any())
                <div class="login-error" role="alert">
                    <span class="material-symbols-outlined" aria-hidden="true">error</span>
                    {{ $errors->first() }}
                </div>

                @if ($passwordResetWhatsAppUrl)
                    <a
                        class="login-reset-password"
                        href="{{ $passwordResetWhatsAppUrl }}"
                        target="_blank"
                        rel="noopener noreferrer"
                    >
                        <span class="material-symbols-outlined" aria-hidden="true">lock_reset</span>
                        <span>Lupa Password? Konfirmasi Admin</span>
                    </a>
                @endif
            @endif

            <form class="login-form" action="{{ route('login.store') }}" method="post">
                @csrf
                <div class="login-field">
                    <label for="username">
                        <span class="material-symbols-outlined" aria-hidden="true">person</span>
                        Username
                    </label>
                    <input id="username" name="username" type="text" value="{{ old('username') }}" placeholder="contoh: siswa01" autocomplete="username" required autofocus>
                </div>

                <div class="login-field">
                    <label for="password">
                        <span class="material-symbols-outlined" aria-hidden="true">lock</span>
                        Password
                    </label>
                    <div class="login-password">
                        <input id="password" name="password" type="password" placeholder="••••••••" autocomplete="current-password" required>
                        <button type="button" data-toggle-password aria-label="Tampilkan password">
                            <span class="material-symbols-outlined" aria-hidden="true">visibility</span>
                        </button>
                    </div>
                </div>

                <button type="submit" class="login-submit">
                    <span>Masuk</span>
                    <span class="material-symbols-outlined" aria-hidden="true">arrow_forward</span>
                </button>
            </form>

            <div class="login-help">
                @if ($adminWhatsAppUrl)
                    <a
                        class="login-contact-admin"
                        href="{{ $adminWhatsAppUrl }}"
                        target="_blank"
                        rel="noopener noreferrer"
                    >
                        <span class="material-symbols-outlined" aria-hidden="true">chat</span>
                        <span>Hubungi Admin</span>
                    </a>
                @else
                    <p>Kontak administrator belum dikonfigurasi.</p>
                @endif
            </div>
        </div>

    </section>
</main>
@endsection
