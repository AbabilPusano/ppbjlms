@extends('layouts.lms-ui', ['title' => 'Dashboard Guru - LMS PPBJ'])

@section('body')
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
                <p>Guru</p>
                <span>Administrator</span>
            </div>
            <strong>G</strong>
        </div>
    </header>

    <aside class="guru-sidebar">
        <div class="guru-logo">
            <span class="material-symbols-outlined" aria-hidden="true">school</span>
            <strong>GURU LMS</strong>
        </div>

        <p class="guru-menu-label">Menu Utama</p>
        <nav class="guru-nav" aria-label="Navigasi guru">
            <a href="#" class="active" data-ui-only><span class="material-symbols-outlined">home</span>Home<i></i></a>
            <a href="#" data-ui-only><span class="material-symbols-outlined">category</span>Units</a>
            <a href="#" data-ui-only><span class="material-symbols-outlined">account_tree</span>Materi</a>
            <a href="#" data-ui-only><span class="material-symbols-outlined">inventory_2</span>Bank Soal</a>
            <a href="#" data-ui-only><span class="material-symbols-outlined">menu_book</span>Stories</a>
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
            <a href="#" class="guru-card guru-card--white guru-card--wide" data-ui-only>
                <span class="material-symbols-outlined">category</span>
                <div><h3>Kelola Unit</h3><p>Detail unit pembelajaran per section.</p></div>
            </a>
            <a href="#" class="guru-card guru-card--white" data-ui-only>
                <span class="material-symbols-outlined">account_tree</span>
                <div><h3>Kelola Materi</h3><p>Hubungkan antar materi dan node.</p></div>
            </a>
            <a href="#" class="guru-card guru-card--purple" data-ui-only>
                <span class="material-symbols-outlined">inventory_2</span>
                <div><h3>Bank Soal</h3><p>Pilihan ganda, susun kata, dan latihan.</p></div>
            </a>
            <div class="guru-card guru-card--white guru-card--disabled">
                <span class="material-symbols-outlined">groups</span>
                <div><h3>Data Siswa</h3><p>Pantau progres siswa.</p><small>Segera Hadir</small></div>
            </div>
        </div>

        <section class="guru-stats">
            <div><span class="material-symbols-outlined">check_circle</span><p><strong>98%</strong><small>Unit Selesai</small></p></div>
            <div><span class="material-symbols-outlined">quiz</span><p><strong>1,240</strong><small>Soal Aktif</small></p></div>
            <div><span class="material-symbols-outlined">pending_actions</span><p><strong>24</strong><small>Tugas Menunggu</small></p></div>
        </section>
    </main>
</div>
@endsection
