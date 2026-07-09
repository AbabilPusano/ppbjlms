@extends('layouts.lms-ui', ['title' => 'Cerita - LMS PPBJ'])

@section('body')
@php
    $activeMenu = 'STORIES';
    $collections = [
        [
            'title' => 'Cerita pendek N5',
            'description' => 'Bacaan ringan untuk latihan kosakata sehari-hari.',
            'stories' => [
                ['title' => 'Boxing Club', 'subtitle' => 'Latihan perkenalan dan aktivitas sore.', 'category' => 'Percakapan', 'duration' => '15 menit', 'tone' => 'peach', 'available' => true, 'icon' => 'auto_stories'],
                ['title' => 'Dino Adventure', 'subtitle' => 'Petualangan kecil dengan pola kalimat dasar.', 'category' => 'Petualangan', 'duration' => '15 menit', 'tone' => 'mint', 'available' => false, 'icon' => 'map'],
                ['title' => 'City Lights', 'subtitle' => 'Membaca arah, tempat, dan kegiatan di kota.', 'category' => 'Kota', 'duration' => '10 menit', 'tone' => 'blue', 'available' => false, 'icon' => 'location_city'],
            ],
        ],
        [
            'title' => 'Cerita perjalanan',
            'description' => 'Bacaan bertema perjalanan yang sedang disiapkan untuk latihan berikutnya.',
            'stories' => [
                ['title' => 'Exploring Onsen', 'subtitle' => 'Ungkapan sopan saat berkunjung ke tempat umum.', 'category' => 'Budaya', 'duration' => '15 menit', 'tone' => 'mint', 'available' => false, 'icon' => 'hot_tub'],
                ['title' => "Haruka's Trip to Kyoto", 'subtitle' => 'Latihan membaca jadwal dan rencana perjalanan.', 'category' => 'Travel', 'duration' => '15 menit', 'tone' => 'peach', 'available' => false, 'icon' => 'train'],
            ],
        ],
    ];
@endphp

<div class="app-container">
    @include('partials.student-sidebar')

    <main class="main-content">
        <section class="learning-shell stories-container">
            <header class="learning-hero stories-hero">
                <div class="learning-hero__copy">
                    <span class="learning-hero__eyebrow">Ruang baca harian</span>
                    <h1>Baca cerita pendek, tambah kosakata pelan-pelan.</h1>
                    <p>
                        Pilih cerita yang sudah terbuka, baca dalam sesi singkat, dan jadikan latihan
                        membaca terasa seperti jeda kecil yang nyaman.
                    </p>
                </div>

                <div class="learning-hero__meta">
                    <div class="learning-stat-card">
                        <span class="learning-stat-card__label">Siap dibaca</span>
                        <strong>1</strong>
                    </div>
                    <div class="learning-stat-card">
                        <span class="learning-stat-card__label">Total cerita</span>
                        <strong>5</strong>
                    </div>
                    <a href="#" class="learning-continue-btn stories-hero__action" data-ui-only>Lanjut baca</a>
                </div>
            </header>

            <section class="stories-list">
                <div class="section-heading">
                    <div>
                        <span class="ds-section-eyebrow">Pilih bacaan</span>
                        <h2 class="section-heading__title">Cerita yang siap menemani latihanmu</h2>
                    </div>
                    <p class="section-heading__desc">
                        Pilih bacaan yang tersedia sekarang, lalu kembali lagi saat koleksi berikutnya sudah siap.
                    </p>
                </div>

                @foreach ($collections as $collection)
                    <section class="stories-collection-section">
                        <div class="stories-collection-header">
                            <div>
                                <span class="stories-collection-kicker">Koleksi bacaan</span>
                                <h3 class="stories-collection-title">{{ $collection['title'] }}</h3>
                                <p class="stories-collection-desc">{{ $collection['description'] }}</p>
                            </div>
                        </div>

                        <div class="stories-grid">
                            @foreach ($collection['stories'] as $story)
                                <article class="story-card story-card--{{ $story['tone'] }} {{ $story['available'] ? '' : 'story-card--locked' }}">
                                    <div class="story-card__image" aria-hidden="true">
                                        <span class="material-symbols-outlined story-card__cover-icon">{{ $story['icon'] }}</span>
                                        @if ($story['available'])
                                            <span class="story-card__status">
                                                <span class="material-symbols-outlined" aria-hidden="true">task_alt</span>
                                                Siap
                                            </span>
                                        @endif
                                    </div>

                                    <div class="story-card__body">
                                        <div class="story-card__meta">
                                            <span class="story-card__category">{{ $story['category'] }}</span>
                                            @unless ($story['available'])
                                                <span class="story-card__lock">Segera hadir</span>
                                            @endunless
                                        </div>
                                        <h3 class="story-card__title">{{ $story['title'] }}</h3>
                                        <p class="story-card__subtitle">{{ $story['subtitle'] }}</p>
                                        <div class="story-card__footer">
                                            <span class="story-card__duration">
                                                <span class="material-symbols-outlined" aria-hidden="true">schedule</span>
                                                {{ $story['duration'] }}
                                            </span>
                                            @if ($story['available'])
                                                <span class="story-card__open">
                                                    Baca
                                                    <span class="material-symbols-outlined" aria-hidden="true">chevron_right</span>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </article>
                            @endforeach
                        </div>
                    </section>
                @endforeach
            </section>
        </section>
    </main>
</div>
@endsection
