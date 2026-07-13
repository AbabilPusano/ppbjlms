@extends('layouts.lms-ui', ['title' => 'Edit Profil - LMS PPBJ'])

@section('body')
@php($activeMenu = 'PROFILE')
@php($bio = $user->bio ?: 'Belum ada bio.')

<div class="app-container">
    @include('partials.student-sidebar')

    <main class="main-content">
        <section class="settings-profile-page">
            <header class="learning-hero settings-profile-hero">
                <div class="learning-hero__copy">
                    <span class="learning-hero__eyebrow">Pengaturan profil</span>
                    <h1>Rapikan identitas belajarmu dengan tenang.</h1>
                    <p>
                        Saat ini hanya nama yang bisa diperbarui. Username, email, dan bio tetap
                        mengikuti data akun yang tersimpan.
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
                        <h2>{{ $user->name }}</h2>
                        <p>{{ $bio }}</p>
                    </div>
                    <button type="button" class="settings-profile-button settings-profile-button--ghost" disabled>
                        <span class="material-symbols-outlined" aria-hidden="true">image</span>
                        Ubah avatar
                    </button>
                </aside>

                <form class="settings-profile-form" action="{{ route('profile.update') }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <div class="settings-profile-form__head">
                        <div>
                            <span class="ds-section-eyebrow">Detail akun</span>
                            <h2>Edit profile</h2>
                            <p>Nama ini diambil dari database dan akan tampil di halaman profil.</p>
                        </div>
                        <span class="settings-profile-saved" @unless (session('status') === 'profile-updated') hidden @endunless>
                            <span class="material-symbols-outlined" aria-hidden="true">task_alt</span>
                            Tersimpan
                        </span>
                    </div>

                    <div class="settings-profile-fields">
                        <label class="settings-profile-field">
                            <span>Nama</span>
                            <input name="name" type="text" value="{{ old('name', $user->name) }}" placeholder="Nama lengkap" maxlength="191" required>
                            <small>Nama ini akan tampil di halaman profil.</small>
                            @error('name')
                                <small>{{ $message }}</small>
                            @enderror
                        </label>
                        <label class="settings-profile-field">
                            <span>Username</span>
                            <div class="settings-profile-username">
                                <span aria-hidden="true">@</span>
                                <input type="text" value="{{ $user->username }}" placeholder="username" disabled>
                            </div>
                            <small>Username belum bisa diubah dari halaman ini.</small>
                        </label>
                        <label class="settings-profile-field">
                            <span>Email</span>
                            <input type="email" value="{{ $user->email }}" placeholder="nama@email.com" disabled>
                            <small>Email belum bisa diubah dari halaman ini.</small>
                        </label>
                        <label class="settings-profile-field settings-profile-field--wide">
                            <span>Bio</span>
                            <textarea placeholder="Belum ada bio." disabled>{{ $bio }}</textarea>
                            <small>Bio belum bisa diubah dari halaman ini.</small>
                        </label>
                    </div>

                    <div class="settings-profile-actions">
                        <a href="{{ url('/profile') }}" class="settings-profile-button settings-profile-button--ghost">Batal</a>
                        <button type="submit" class="settings-profile-button settings-profile-button--accent">
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
