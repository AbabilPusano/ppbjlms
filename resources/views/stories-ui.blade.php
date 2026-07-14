@extends('layouts.lms-ui', ['title' => 'Cerita - LMS PPBJ'])

@section('body')
@php($activeMenu = 'STORIES')

<div class="app-container">
    @include('partials.student-sidebar')

    <main class="main-content">
        <section class="learning-shell stories-container">
            <header class="learning-hero stories-hero">
                <div class="learning-hero__copy">
                    <span class="learning-hero__eyebrow">Ruang baca harian</span>
                    <h1>Baca cerita pendek, tambah kosakata pelan-pelan.</h1>
                    <p>
                        Pilih cerita yang sudah tersedia, baca dalam sesi singkat, dan jadikan latihan
                        membaca terasa seperti jeda kecil yang nyaman.
                    </p>
                </div>

                <div class="learning-hero__meta">
                    <div class="learning-stat-card">
                        <span class="learning-stat-card__label">Tersedia</span>
                        <strong>{{ $readableStories }}</strong>
                    </div>
                    <div class="learning-stat-card">
                        <span class="learning-stat-card__label">Total cerita</span>
                        <strong>{{ $totalStories }}</strong>
                    </div>
                </div>
            </header>

            <section class="stories-list">
                <div class="section-heading">
                    <div>
                        <span class="ds-section-eyebrow">Pilih bacaan</span>
                        <h2 class="section-heading__title">Cerita yang siap menemani latihanmu</h2>
                    </div>
                    <p class="section-heading__desc">
                        Pilih cerita yang tersedia sekarang, lalu kembali lagi saat koleksi berikutnya sudah siap.
                    </p>
                </div>

                @if ($sections->isEmpty())
                    <div class="detail-sidecard">
                        <span class="ds-section-eyebrow">Belum ada cerita</span>
                        <h3>Koleksi cerita sedang disiapkan</h3>
                        <p>Cerita akan muncul setelah guru menambahkannya melalui halaman kelola cerita.</p>
                    </div>
                @else
                    @foreach ($sections as $section)
                        <section class="stories-collection-section">
                            <div class="stories-collection-header">
                                <div>
                                    <span class="stories-collection-kicker">Koleksi {{ $section->name }}</span>
                                    <h3 class="stories-collection-title">{{ $section->description }}</h3>
                                    <p class="stories-collection-desc">
                                        {{ $section->stories->count() }} cerita tersedia untuk level ini.
                                    </p>
                                </div>
                            </div>

                            <div class="stories-grid">
                                @foreach ($section->stories as $story)
                                    <a href="{{ route('stories.show', $story) }}" class="story-card story-card--peach story-card--link">
                                        <div class="story-card__image" aria-hidden="true" style="position: relative; overflow: hidden;">
                                            <img src="https://image.pollinations.ai/prompt/{{ urlencode($story->title) }}?width=400&height=200&nologo=true" alt="" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: cover; z-index: 0;">
                                        </div>

                                        <div class="story-card__body">
                                            <div class="story-card__meta">
                                                <span class="story-card__category">{{ $section->name }}</span>
                                            </div>
                                            <h3 class="story-card__title">{{ $story->title }}</h3>
                                            <p class="story-card__subtitle">
                                                {{ count($story->content ?? []) }} halaman &middot; Baca sekarang
                                            </p>
                                            <div class="story-card__footer">
                                                <span class="story-card__duration">
                                                    <span class="material-symbols-outlined" aria-hidden="true">schedule</span>
                                                    {{ ceil(count($story->content ?? []) * 1.5) }} menit
                                                </span>
                                                <span class="story-card__open">
                                                    Baca
                                                    <span class="material-symbols-outlined" aria-hidden="true">chevron_right</span>
                                                </span>
                                            </div>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        </section>
                    @endforeach
                @endif
            </section>
        </section>
    </main>
</div>
@endsection
