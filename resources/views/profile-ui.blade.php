@extends('layouts.lms-ui', ['title' => 'Profil - LMS PPBJ'])

@section('body')
@php($activeMenu = 'PROFILE')
@php($bio = $user->bio ?: 'Belum ada bio.')
@php($joinedAt = $user->created_at?->translatedFormat('F Y') ?? '-')
@php($passwordModalOpen = $errors->password->isNotEmpty())

<div class="app-container">
    @include('partials.student-sidebar')

    <main class="main-content">
        <section class="learning-shell profile-shell profile-page">
            <header class="learning-hero profile-hero">
                <div class="learning-hero__copy">
                    <span class="learning-hero__eyebrow">Profil belajar</span>
                    <h1>Akunmu, progresmu, dan ritme belajar yang lagi dijaga.</h1>
                    <p>
                        Lihat identitas akun, ritme belajar, dan pintasan materi dalam tampilan
                        yang sama tenangnya dengan ruang belajar utama.
                    </p>
                </div>

                <div class="learning-hero__meta">
                    <div class="learning-stat-card">
                        <span class="learning-stat-card__label">Streak</span>
                        <strong>{{ $user->streak }}</strong>
                    </div>
                    <a href="{{ url('/settings/profile') }}" class="learning-continue-btn profile-hero__action">
                        <span class="material-symbols-outlined" aria-hidden="true">edit</span>
                        Edit profil
                    </a>
                </div>
            </header>

            <section class="profile-card">
                <div class="profile-card__avatar" aria-hidden="true">{{ $user->avatar ?: '人' }}</div>
                <div class="profile-card__identity">
                    <span>{{ '@'.$user->username }}</span>
                    <h2>{{ $user->name }}</h2>
                    <p>{{ $bio }}</p>
                    <div class="profile-card__meta">
                        <span><span class="material-symbols-outlined" aria-hidden="true">calendar_month</span>Bergabung {{ $joinedAt }}</span>
                        <span><span class="material-symbols-outlined" aria-hidden="true">person</span>{{ $user->email }}</span>
                    </div>
                </div>
            </section>

            <section class="profile-stats-grid">
                <div class="profile-stat-card"><span>XP</span><strong>{{ $user->totalXP }}</strong></div>
                <div class="profile-stat-card"><span>Streak</span><strong>{{ $user->streak }}</strong></div>
                <div class="profile-stat-card"><span>Gems</span><strong>{{ $user->gems }}</strong></div>
                <div class="profile-stat-card"><span>League</span><strong>{{ $user->league }}</strong></div>
            </section>

            <section class="profile-panel">
                <div class="section-heading profile-panel__heading">
                    <div>
                        <span class="ds-section-eyebrow">Rangkuman akun</span>
                        <h2 class="section-heading__title">Aktivitas belajar</h2>
                    </div>
                    <p class="section-heading__desc">
                        Akses cepat ke area belajar yang paling sering dipakai.
                    </p>
                </div>

                <div class="profile-activity-list" aria-label="Aktivitas belajar">
                    <a href="{{ url('/letters') }}" class="profile-activity-card">
                        <span class="material-symbols-outlined" aria-hidden="true">translate</span>
                        <span>Latihan huruf</span>
                        <p>Lanjutkan sesi huruf Jepang dari dashboard utama.</p>
                    </a>
                    <a href="{{ url('/stories') }}" class="profile-activity-card">
                        <span class="material-symbols-outlined" aria-hidden="true">auto_stories</span>
                        <span>Cerita pendek</span>
                        <p>Baca cerita ringan untuk memperkuat kosakata harian.</p>
                    </a>
                    <a href="{{ url('/settings/profile') }}" class="profile-activity-card">
                        <span class="material-symbols-outlined" aria-hidden="true">settings</span>
                        <span>Pengaturan akun</span>
                        <p>Perbarui nama yang tampil di profil belajarmu.</p>
                    </a>
                </div>
            </section>

            <section class="profile-actions">
                <a href="{{ url('/settings/profile') }}" class="profile-btn profile-btn--outline">
                    <span class="material-symbols-outlined" aria-hidden="true">edit</span>
                    Edit profil
                </a>
                <button type="button" class="profile-btn profile-btn--outline" data-profile-modal="password">
                    <span class="material-symbols-outlined" aria-hidden="true">lock</span>
                    Ganti password
                </button>
                <button type="button" class="profile-btn profile-btn--ghost" data-profile-modal="logout">
                    <span class="material-symbols-outlined" aria-hidden="true">logout</span>
                    Keluar
                </button>
            </section>

            @if (session('status') === 'password-updated')
                <div class="profile-toast" role="status">
                    <span class="material-symbols-outlined" aria-hidden="true">task_alt</span>
                    Password berhasil diganti.
                </div>
            @endif

            <div class="profile-modal-overlay" data-modal="password" @unless ($passwordModalOpen) hidden @endunless>
                <form class="profile-modal profile-modal--wide" action="{{ route('profile.password.update') }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <span class="ds-section-eyebrow">Keamanan</span>
                    <h2>Ganti password</h2>
                    <p>Masukkan password saat ini, lalu buat password baru minimal 8 karakter.</p>

                    @if ($passwordModalOpen)
                        <div class="profile-error">
                            @foreach ($errors->password->all() as $error)
                                <span>{{ $error }}</span>
                            @endforeach
                        </div>
                    @endif

                    <div class="profile-form">
                        <label class="profile-field">
                            <span>Password saat ini</span>
                            <div class="profile-password-field">
                                <input name="current_password" type="password" placeholder="********" autocomplete="current-password" required>
                            </div>
                        </label>
                        <label class="profile-field">
                            <span>Password baru</span>
                            <div class="profile-password-field">
                                <input name="password" type="password" placeholder="minimal 8 karakter" autocomplete="new-password" required>
                            </div>
                        </label>
                        <label class="profile-field">
                            <span>Konfirmasi password baru</span>
                            <div class="profile-password-field">
                                <input name="password_confirmation" type="password" placeholder="ulang password baru" autocomplete="new-password" required>
                            </div>
                        </label>
                    </div>
                    <div class="profile-modal__actions">
                        <button type="button" class="profile-btn profile-btn--ghost" data-close-modal>Batal</button>
                        <button type="submit" class="profile-btn profile-btn--accent">Simpan password</button>
                    </div>
                </form>
            </div>

            <div class="profile-modal-overlay" data-modal="logout" hidden>
                <div class="profile-modal">
                    <span class="ds-section-eyebrow">Session</span>
                    <h2>Keluar dari LMS?</h2>
                    <p>Sesi belajarmu akan diakhiri dan kamu akan kembali ke halaman login.</p>
                    <div class="profile-modal__actions">
                        <button type="button" class="profile-btn profile-btn--ghost" data-close-modal>Batal</button>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="profile-btn profile-btn--danger">Keluar</button>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </main>
</div>
@endsection
