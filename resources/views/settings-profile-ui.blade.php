@extends('layouts.lms-ui', ['title' => 'Edit Profil - LMS PPBJ'])

@section('body')
@php($activeMenu = 'PROFILE')

<div class="app-container">
    @include('partials.student-sidebar')

    <main class="main-content">
        <section class="settings-profile-page">
            <header class="learning-hero settings-profile-hero">
                <div class="learning-hero__copy">
                    <span class="learning-hero__eyebrow">Pengaturan profil</span>
                    <h1>Rapikan identitas belajarmu dengan tenang.</h1>
                    <p>
                        Perbarui nama, username, email, dan bio supaya profilmu tetap terasa
                        personal saat kamu lanjut belajar.
                    </p>
                </div>
                <div class="learning-hero__meta">
                    <div class="learning-stat-card">
                        <span class="learning-stat-card__label">Status akun</span>
                        <strong>Aktif</strong>
                    </div>
                    <a href="{{ url('/profile') }}" class="learning-continue-btn settings-profile-hero__link">
                        <span class="material-symbols-outlined" aria-hidden="true">arrow_back</span>
                        Kembali
                    </a>
                </div>
            </header>

            <section class="settings-profile-layout" aria-label="Edit profile">
                <aside class="settings-profile-card">
                    <div class="settings-profile-card__avatar" aria-hidden="true">
                        <span class="material-symbols-outlined">person</span>
                    </div>
                    <div>
                        <span class="ds-section-eyebrow">Foto profil</span>
                        <h2>Rapka</h2>
                        <p>Gunakan identitas yang membuat ruang belajar terasa seperti milikmu.</p>
                    </div>
                    <button type="button" class="settings-profile-button settings-profile-button--ghost" data-ui-only>
                        <span class="material-symbols-outlined" aria-hidden="true">image</span>
                        Ubah avatar
                    </button>
                </aside>

                <form class="settings-profile-form" action="#" method="get" data-ui-only>
                    {{-- UI only: tidak ada submit handler, validasi backend, atau update database. --}}
                    <div class="settings-profile-form__head">
                        <div>
                            <span class="ds-section-eyebrow">Detail akun</span>
                            <h2>Edit profile</h2>
                            <p>Pastikan informasi ini tetap jelas dan mudah dikenali.</p>
                        </div>
                        <span class="settings-profile-saved" hidden data-save-pill>
                            <span class="material-symbols-outlined" aria-hidden="true">task_alt</span>
                            Tersimpan
                        </span>
                    </div>

                    <div class="settings-profile-fields">
                        <label class="settings-profile-field">
                            <span>Nama</span>
                            <input type="text" value="Rapka" placeholder="Nama lengkap">
                            <small>Nama ini akan tampil di halaman profil.</small>
                        </label>
                        <label class="settings-profile-field">
                            <span>Username</span>
                            <div class="settings-profile-username">
                                <span aria-hidden="true">@</span>
                                <input type="text" value="rapka" placeholder="username">
                            </div>
                            <small>Pakai huruf kecil agar mudah dicari.</small>
                        </label>
                        <label class="settings-profile-field">
                            <span>Email</span>
                            <input type="email" value="rapka@example.com" placeholder="nama@email.com">
                            <small>Email dipakai untuk informasi akun dan pemulihan.</small>
                        </label>
                        <label class="settings-profile-field settings-profile-field--wide">
                            <span>Bio</span>
                            <textarea placeholder="Ceritakan sedikit tentang perjalanan belajarmu...">Learning Japanese, one calm step at a time.</textarea>
                            <small>Tulis singkat saja, cukup yang membuat profil terasa hidup.</small>
                        </label>
                    </div>

                    <div class="settings-profile-actions">
                        <a href="{{ url('/profile') }}" class="settings-profile-button settings-profile-button--ghost">Batal</a>
                        <button type="button" class="settings-profile-button settings-profile-button--accent" data-fake-save>
                            <span class="material-symbols-outlined" aria-hidden="true">save</span>
                            Simpan perubahan
                        </button>
                    </div>
                </form>
            </section>
        </section>
    </main>
</div>
@endsection
