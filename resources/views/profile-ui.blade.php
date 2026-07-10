@extends('layouts.lms-ui', ['title' => 'Profil - LMS PPBJ'])

@section('body')
@php($activeMenu = 'PROFILE')

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
                        <strong>14</strong>
                    </div>
                    <a href="{{ url('/settings/profile') }}" class="learning-continue-btn profile-hero__action">
                        <span class="material-symbols-outlined" aria-hidden="true">edit</span>
                        Edit profil
                    </a>
                </div>
            </header>

            <section class="profile-card">
                <div class="profile-card__avatar" aria-hidden="true">人</div>
                <div class="profile-card__identity">
                    <span>@rapka</span>
                    <h2>Rapka</h2>
                    <p>Learning Japanese, one calm step at a time.</p>
                    <div class="profile-card__meta">
                        <span><span class="material-symbols-outlined" aria-hidden="true">calendar_month</span>Bergabung March 2026</span>
                        <span><span class="material-symbols-outlined" aria-hidden="true">person</span>rapka@example.com</span>
                    </div>
                </div>
            </section>

            <section class="profile-stats-grid">
                <div class="profile-stat-card"><span>Lesson</span><strong>42</strong></div>
                <div class="profile-stat-card"><span>Streak</span><strong>14</strong></div>
                <div class="profile-stat-card"><span>Cerita</span><strong>3</strong></div>
                <div class="profile-stat-card"><span>Huruf</span><strong>76</strong></div>
            </section>

            <section class="profile-panel">
                <div class="section-heading profile-panel__heading">
                    <div>
                        <span class="ds-section-eyebrow">Rangkuman akun</span>
                        <h2 class="section-heading__title">Aktivitas belajar</h2>
                    </div>
                    <p class="section-heading__desc">
                        Akses cepat ke area belajar yang paling sering dipakai, tanpa elemen backend.
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
                        <p>Perbarui nama, username, email, atau bio profil dummy.</p>
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

            <div class="profile-modal-overlay" data-modal="password" hidden>
                <div class="profile-modal profile-modal--wide">
                    <span class="ds-section-eyebrow">Keamanan</span>
                    <h2>Ganti password</h2>
                    <p>Form ini hanya tampilan. Tidak ada validasi backend atau request password.</p>
                    <div class="profile-form">
                        <label class="profile-field"><span>Password saat ini</span><div class="profile-password-field"><input type="password" placeholder="********"></div></label>
                        <label class="profile-field"><span>Password baru</span><div class="profile-password-field"><input type="password" placeholder="********"></div></label>
                    </div>
                    <div class="profile-modal__actions">
                        <button type="button" class="profile-btn profile-btn--ghost" data-close-modal>Batal</button>
                        <button type="button" class="profile-btn profile-btn--accent" data-close-modal>Simpan password</button>
                    </div>
                </div>
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
