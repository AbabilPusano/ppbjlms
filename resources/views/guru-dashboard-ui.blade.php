@extends('layouts.lms-ui', ['title' => 'Dashboard Guru - LMS PPBJ'])

@section('body')
@php($currentUser = auth()->user())
@php($isAdmin = $currentUser?->role === 'admin')
@php($roleLabel = $isAdmin ? 'Admin' : 'Guru')

<div class="guru-shell">
    <header class="guru-topbar">
        <button type="button" class="guru-hamburger" data-guru-menu aria-label="Toggle menu">
            <span class="material-symbols-outlined" aria-hidden="true">menu</span>
        </button>
        <div>
            <p>Overview</p>
        </div>
        <div class="guru-user">
            <div>
                <p>{{ $currentUser?->name ?? $roleLabel }}</p>
                <span>{{ ucfirst($currentUser?->role ?? 'guru') }}</span>
            </div>
            <strong>{{ strtoupper(substr($currentUser?->name ?? $roleLabel, 0, 1)) }}</strong>
        </div>
    </header>

    <aside class="guru-sidebar">
        <div class="guru-logo">
            <span class="material-symbols-outlined" aria-hidden="true">school</span>
            <strong>GURU LMS</strong>
        </div>

        <p class="guru-menu-label">Menu Utama</p>
        <nav class="guru-nav" aria-label="Navigasi guru">
            <a href="{{ route('guru.dashboard') }}" class="active"><span class="material-symbols-outlined">home</span>Dashboard<i></i></a>
            <a href="{{ route('guru.units.index') }}"><span class="material-symbols-outlined">category</span>Unit Pembelajaran</a>
            @if ($isAdmin)
                <a href="{{ route('admin.student-accounts.index') }}"><span class="material-symbols-outlined">person_add</span>Generate Akun</a>
            @endif
            <a href="#" data-ui-only><span class="material-symbols-outlined">checklist</span>Penilaian</a>
            <a href="#" data-ui-only><span class="material-symbols-outlined">settings</span>Pengaturan</a>
        </nav>

        <div class="guru-back">
            <a href="{{ url('/') }}"><span class="material-symbols-outlined">arrow_back</span>Kembali ke LMS</a>
        </div>
    </aside>

    <main class="guru-main">
        <div class="guru-heading">
            <div>
                <h1>Dashboard</h1>
                <p>Selamat datang kembali. Pilih modul di bawah untuk mulai mengelola.</p>
            </div>
        </div>

        <div class="guru-bento">
            <a href="#" class="guru-card guru-card--pink guru-card--wide" data-ui-only>
                <span class="material-symbols-outlined">menu_book</span>
                <div><h3>Kelola Stories</h3><p>Kelola cerita dari setiap Section N5 sampai N1.</p></div>
            </a>
            <a href="{{ route('guru.units.index') }}" class="guru-card guru-card--white guru-card--wide">
                <span class="material-symbols-outlined">category</span>
                <div><h3>Kelola Unit & Materi</h3><p>Susun unit, isi pembelajaran, quiz, dan soal dari satu tempat.</p></div>
            </a>
            <a href="#" class="guru-card guru-card--purple" data-ui-only>
                <span class="material-symbols-outlined">inventory_2</span>
                <div><h3>Bank Soal</h3><p>Pilihan ganda, susun kata, dan latihan.</p></div>
            </a>
            <div class="guru-card guru-card--white guru-card--disabled">
                <span class="material-symbols-outlined">groups</span>
                <div><h3>Data Siswa</h3><p>Pantau progres siswa.</p><small>Segera Hadir</small></div>
            </div>
            @if ($isAdmin)
                <a href="{{ route('admin.student-accounts.index') }}" class="guru-card guru-card--white guru-card--wide">
                    <span class="material-symbols-outlined">person_add</span>
                    <div><h3>Generate Akun Siswa</h3><p>Buat akun login siswa secara cepat dengan username dan password otomatis.</p></div>
                </a>
            @endif
        </div>

        <section class="guru-stats">
            <div><span class="material-symbols-outlined">check_circle</span><p><strong>98%</strong><small>Unit Selesai</small></p></div>
            <div><span class="material-symbols-outlined">quiz</span><p><strong>1,240</strong><small>Soal Aktif</small></p></div>
            <div><span class="material-symbols-outlined">pending_actions</span><p><strong>24</strong><small>Tugas Menunggu</small></p></div>
        </section>
    </main>
</div>
@endsection
