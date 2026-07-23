@extends('layouts.lms-ui', ['title' => 'Kelola Cerita - LMS PPBJ'])

@section('body')
@php($currentUser = auth()->user())
@php($isAdmin = $currentUser?->role === 'admin')

<div class="guru-shell">
    <header class="guru-topbar">
        <button type="button" class="guru-hamburger" data-guru-menu aria-label="Toggle menu">
            <span class="material-symbols-outlined" aria-hidden="true">menu</span>
        </button>
        <div>
            <p>Kelola Cerita</p>
        </div>
        <div class="guru-user">
            <div>
                <p>{{ $currentUser?->name ?? 'Guru' }}</p>
                <span>{{ ucfirst($currentUser?->role ?? 'guru') }}</span>
            </div>
            <strong>{{ strtoupper(substr($currentUser?->name ?? 'G', 0, 1)) }}</strong>
        </div>
    </header>

    <aside class="guru-sidebar">
        <div class="guru-logo">
            <span class="material-symbols-outlined" aria-hidden="true">school</span>
            <strong>GURU LMS</strong>
        </div>

        <p class="guru-menu-label">Menu Utama</p>
        <nav class="guru-nav" aria-label="Navigasi guru">
            <a href="{{ route('guru.dashboard') }}"><span class="material-symbols-outlined">home</span>Dashboard</a>
            <a href="{{ route('guru.units.index') }}"><span class="material-symbols-outlined">category</span>Unit Pembelajaran</a>
            <a href="{{ route('guru.stories.index') }}" class="active"><span class="material-symbols-outlined">menu_book</span>Kelola Cerita<i></i></a>
            @include('partials.admin-account-nav')
            <a href="{{ route('guru.penilaian.index') }}" class="{{ request()->routeIs('guru.penilaian.*') ? 'active' : '' }}"><span class="material-symbols-outlined">checklist</span>Penilaian{!! request()->routeIs('guru.penilaian.*') ? '<i></i>' : '' !!}</a>
            <a href="{{ route('guru.referensi.index') }}" class="{{ request()->routeIs('guru.referensi.*') ? 'active' : '' }}"><span class="material-symbols-outlined">database</span>Bank Referensi{!! request()->routeIs('guru.referensi.*') ? '<i></i>' : '' !!}</a>
            <a href="#" data-ui-only><span class="material-symbols-outlined">settings</span>Pengaturan</a>
        </nav>

        <div class="guru-back">
            <a href="{{ url('/') }}"><span class="material-symbols-outlined">arrow_back</span>Kembali ke LMS</a>
        </div>
    </aside>

    <main class="guru-main guru-units-main">
        {{-- Flash message --}}
        @if (session('success'))
            <div class="gs-flash gs-flash--success" role="alert">
                <span class="material-symbols-outlined">check_circle</span>
                {{ session('success') }}
            </div>
        @endif

        <div class="guru-heading guru-units-heading">
            <div>
                <h1>KELOLA CERITA</h1>
                <p>Buat dan kelola cerita pendek bahasa Jepang dengan bantuan AI untuk siswa.</p>
            </div>
            <div class="guru-units-toolbar">
                <a href="{{ route('guru.stories.create') }}" class="guru-unit-button guru-unit-button--primary">
                    <span class="material-symbols-outlined" aria-hidden="true">auto_awesome</span>
                    BUAT CERITA BARU
                </a>
            </div>
        </div>

        {{-- Summary --}}
        <section class="guru-units-summary" aria-label="Ringkasan cerita">
            <div>
                <span>Total cerita</span>
                <strong>{{ $stories->flatten()->count() }}</strong>
            </div>
            <div>
                <span>Level tersedia</span>
                <strong>{{ $sections->count() }}</strong>
            </div>
        </section>

        {{-- Story list per section --}}
        @if ($stories->isEmpty())
            <section class="guru-units-state">
                <span class="material-symbols-outlined" aria-hidden="true">menu_book</span>
                <p>Belum ada cerita. Klik <strong>Buat Cerita Baru</strong> untuk mulai generate dengan AI.</p>
                <a href="{{ route('guru.stories.create') }}" class="guru-unit-button guru-unit-button--primary">Buat Cerita Pertama</a>
            </section>
        @else
            @foreach ($sections as $section)
                @if (isset($stories[$section->id]) && $stories[$section->id]->isNotEmpty())
                    <section class="gs-section" aria-label="Cerita {{ $section->name }}">
                        <div class="gs-section-head">
                            <div class="gs-level-badge">{{ $section->name }}</div>
                            <h2>{{ $section->description }}</h2>
                            <span>{{ $stories[$section->id]->count() }} cerita</span>
                        </div>
                        <div class="gs-list">
                            @foreach ($stories[$section->id] as $story)
                                <article class="gs-item">
                                    <div class="gs-item__icon">
                                        <span class="material-symbols-outlined">auto_stories</span>
                                    </div>
                                    <div class="gs-item__body">
                                        <h3>{{ $story->title }}</h3>
                                        <p>{{ count($story->content ?? []) }} halaman &middot; Level {{ $section->name }}</p>
                                    </div>
                                    <div class="gs-item__actions">
                                        <a href="{{ route('stories.show', $story) }}" class="guru-unit-button" target="_blank">
                                            <span class="material-symbols-outlined">visibility</span>
                                            Preview
                                        </a>
                                        <form method="POST" action="{{ route('guru.stories.destroy', $story) }}" onsubmit="return confirm('Hapus cerita ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="guru-unit-button guru-unit-button--danger">
                                                <span class="material-symbols-outlined">delete</span>
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </article>
                            @endforeach
                        </div>
                    </section>
                @endif
            @endforeach
        @endif
    </main>
</div>

<style>
.gs-section {
    background: #ffffff;
    border: 1px solid #e5e7eb;
    border-radius: 16px;
    padding: 24px;
    margin-bottom: 24px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.02);
}
.gs-section-head {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 20px;
    padding-bottom: 14px;
    border-bottom: 1px solid #f3f4f6;
}
.gs-level-badge {
    background: #be185d;
    color: #ffffff;
    font-weight: 800;
    font-size: 13px;
    padding: 4px 12px;
    border-radius: 9999px;
}
.gs-section-head h2 {
    font-size: 18px;
    font-weight: 800;
    margin: 0;
    color: #111827;
}
.gs-section-head span {
    font-size: 13px;
    color: #6b7280;
    margin-left: auto;
}
.gs-list {
    display: flex;
    flex-direction: column;
    gap: 12px;
}
.gs-item {
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 16px 20px;
    background: #f9fafb;
    border: 1px solid #f3f4f6;
    border-radius: 12px;
    transition: all 0.15s ease;
}
.gs-item:hover {
    background: #ffffff;
    border-color: #fbcfe8;
    box-shadow: 0 4px 12px rgba(190, 24, 93, 0.08);
}
.gs-item__icon {
    width: 44px;
    height: 44px;
    border-radius: 10px;
    background: #fdf2f8;
    color: #be185d;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}
.gs-item__body {
    flex: 1;
    min-width: 0;
}
.gs-item__body h3 {
    font-size: 16px;
    font-weight: 700;
    color: #111827;
    margin: 0 0 4px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.gs-item__body p {
    font-size: 13px;
    color: #6b7280;
    margin: 0;
}
.gs-item__actions {
    display: flex;
    align-items: center;
    gap: 8px;
}
.guru-unit-button--danger {
    background: #fef2f2 !important;
    color: #dc2626 !important;
    border-color: #fecaca !important;
}
.guru-unit-button--danger:hover {
    background: #fee2e2 !important;
    border-color: #fca5a5 !important;
}
</style>

<script>
    // Hamburger menu toggle
    document.querySelector('[data-guru-menu]')?.addEventListener('click', () => {
        document.querySelector('.guru-shell').classList.toggle('guru-menu-open');
    });
</script>
@endsection
