@extends('layouts.lms-ui')

@section('body')
@php
    $activeMenu = 'LEARN';
    $units = [
        [
            'name' => 'Unit 1',
            'topic' => 'Perkenalan dan Salam',
            'description' => 'Pelajari salam dasar, ungkapan perkenalan, dan latihan kecil untuk membaca situasi harian.',
            'status' => 'completed',
            'statusLabel' => 'Selesai',
            'progress' => 100,
            'completed' => 4,
            'total' => 4,
            'next' => 'Siap diulang',
        ],
        [
            'name' => 'Unit 2',
            'topic' => 'Hiragana Awal',
            'description' => 'Bangun dasar membaca Jepang melalui huruf vokal, baris ka, dan latihan pengenalan bentuk.',
            'status' => 'in_progress',
            'statusLabel' => 'Sedang jalan',
            'progress' => 55,
            'completed' => 2,
            'total' => 5,
            'next' => 'Lanjut step 3',
        ],
        [
            'name' => 'Unit 3',
            'topic' => 'Kosakata Kelas',
            'description' => 'Kenali benda-benda kelas, instruksi guru, dan pola kalimat pendek untuk latihan N5.',
            'status' => 'locked',
            'statusLabel' => 'Terkunci',
            'progress' => 0,
            'completed' => 0,
            'total' => 5,
            'next' => 'Selesaikan Unit 2',
        ],
        [
            'name' => 'Unit 4',
            'topic' => 'Angka dan Waktu',
            'description' => 'Latihan membaca angka, jam, tanggal, dan kebiasaan harian dengan ritme yang pelan.',
            'status' => 'locked',
            'statusLabel' => 'Terkunci',
            'progress' => 0,
            'completed' => 0,
            'total' => 6,
            'next' => 'Segera terbuka',
        ],
    ];
    $steps = [
        ['index' => 1, 'title' => 'Vokal dasar', 'subtitle' => 'Materi', 'status' => 'completed', 'label' => 'Selesai', 'type' => 'Materi'],
        ['index' => 2, 'title' => 'Baris ka', 'subtitle' => 'Latihan membaca', 'status' => 'completed', 'label' => 'Selesai', 'type' => 'Latihan'],
        ['index' => 3, 'title' => 'Cocokkan huruf', 'subtitle' => 'Quiz singkat', 'status' => 'active', 'label' => 'Aktif', 'type' => 'Quiz'],
        ['index' => 4, 'title' => 'Mini guidebook', 'subtitle' => 'Rangkuman bentuk huruf', 'status' => 'locked', 'label' => 'Terkunci', 'type' => 'Guidebook'],
    ];
@endphp

<div class="app-container">
    @include('partials.student-sidebar')

    <main class="main-content">
        <section class="learning-shell">
            <header class="learning-hero">
                <div class="learning-hero__copy">
                    <span class="learning-hero__eyebrow">Ruang belajar N5</span>
                    <h1>Lanjut pelan-pelan, tetap kelihatan arahnya.</h1>
                    <p>
                        Pilih unit yang lagi kamu jalani, buka step aktif, dan biarkan progresmu
                        tumbuh sedikit demi sedikit tanpa harus terasa berat.
                    </p>
                </div>

                <div class="learning-hero__meta">
                    <div class="learning-stat-card">
                        <span class="learning-stat-card__label">Unit aktif</span>
                        <strong>{{ count($units) }}</strong>
                    </div>
                    <div class="learning-stat-card">
                        <span class="learning-stat-card__label">Step selesai</span>
                        <strong>6</strong>
                    </div>
                    <button type="button" class="learning-continue-btn" data-ui-only>
                        Lanjut Unit 2
                    </button>
                </div>
            </header>

            <section class="unit-overview">
                <div class="section-heading">
                    <div>
                        <span class="ds-section-eyebrow">Pilih ritmemu</span>
                        <h2 class="section-heading__title">Unit belajar yang siap kamu buka</h2>
                    </div>
                    <p class="section-heading__desc">
                        Setiap kartu nunjukin progres dan langkah berikutnya, jadi kamu bisa
                        lanjut tanpa mikir mulai dari mana lagi.
                    </p>
                </div>

                <div class="unit-grid">
                    @foreach ($units as $unit)
                        <button type="button" class="unit-card" data-ui-only>
                            <div class="unit-card__head">
                                <span class="unit-card__index">{{ $unit['name'] }}</span>
                                <span class="unit-card__status unit-card__status--{{ $unit['status'] }}">{{ $unit['statusLabel'] }}</span>
                            </div>
                            <div class="unit-card__body">
                                <h3>{{ $unit['topic'] }}</h3>
                                <p>{{ $unit['description'] }}</p>
                            </div>
                            <div class="unit-card__meta">
                                <div class="unit-card__progress">
                                    <div class="unit-card__progress-label">
                                        <span>Progres</span>
                                        <strong>{{ $unit['progress'] }}%</strong>
                                    </div>
                                    <div class="unit-card__progress-bar">
                                        <span style="width: {{ $unit['progress'] }}%"></span>
                                    </div>
                                </div>
                                <div class="unit-card__footer">
                                    <span>{{ $unit['completed'] }}/{{ $unit['total'] }} step</span>
                                    <span>{{ $unit['next'] }}</span>
                                </div>
                            </div>
                        </button>
                    @endforeach
                </div>
            </section>

            <section class="unit-detail">
                <div class="unit-detail__layout">
                    <div class="unit-detail__main">
                        <header class="unit-detail__hero">
                            <div class="unit-detail__hero-copy">
                                <span class="unit-detail__eyebrow">Unit 2</span>
                                <h2>Hiragana Awal</h2>
                                <p>
                                    Contoh detail unit statis dari tampilan sumber. Semua progress, step,
                                    dan status di sini adalah dummy data untuk kebutuhan UI.
                                </p>
                            </div>
                            <div class="unit-detail__hero-meta">
                                <div class="unit-detail__status">Sedang jalan</div>
                                <div class="unit-progress">
                                    <div class="unit-progress__label">
                                        <span>Progres</span>
                                        <strong>55%</strong>
                                    </div>
                                    <div class="unit-progress__bar"><span style="width: 55%"></span></div>
                                </div>
                                <div class="unit-detail__counts">
                                    <span>2 selesai</span>
                                    <span>5 total step</span>
                                </div>
                            </div>
                        </header>

                        <div class="section-heading section-heading--compact">
                            <div>
                                <span class="ds-section-eyebrow">Alur kecil</span>
                                <h3 class="section-heading__title">Step yang bisa kamu jalani</h3>
                            </div>
                            <p class="section-heading__desc">
                                Step aktif dibuat lebih hangat, step selesai tetap bisa dibuka ulang,
                                dan sisanya menunggu sampai waktunya kebuka.
                            </p>
                        </div>

                        <div class="step-roadmap">
                            @foreach ($steps as $step)
                                <div class="roadmap-step-wrap">
                                    <button type="button" class="roadmap-step roadmap-step--{{ $step['status'] }}" {{ $step['status'] === 'locked' ? 'disabled aria-disabled=true' : '' }} data-ui-only>
                                        <div class="roadmap-step__marker">
                                            <span>{{ $step['status'] === 'completed' ? '✓' : $step['index'] }}</span>
                                        </div>
                                        <div class="roadmap-step__content">
                                            <div class="roadmap-step__topline">
                                                <span class="roadmap-step__eyebrow">Step {{ $step['index'] }}</span>
                                                <span class="roadmap-step__status roadmap-step__status--{{ $step['status'] }}">{{ $step['label'] }}</span>
                                            </div>
                                            <h4>{{ $step['title'] }}</h4>
                                            <p>{{ $step['subtitle'] }}</p>
                                            <div class="roadmap-step__footer">
                                                <span>{{ $step['type'] }}</span>
                                                <span>{{ $step['status'] === 'locked' ? 'Selesaikan step sebelumnya' : 'Buka materi' }}</span>
                                            </div>
                                        </div>
                                    </button>
                                    @if (!$loop->last)
                                        <div class="roadmap-step__connector" aria-hidden="true"></div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <aside class="unit-detail__sidebar">
                        <div class="detail-sidecard">
                            <span class="ds-section-eyebrow">Fokus berikutnya</span>
                            <h3>Step 3</h3>
                            <p>Cocokkan huruf siap kamu buka sebagai langkah kecil berikutnya.</p>
                            <button type="button" class="learning-continue-btn learning-continue-btn--full" data-ui-only>
                                Buka step aktif
                            </button>
                        </div>

                        <div class="detail-sidecard">
                            <span class="ds-section-eyebrow">Catatan belajar</span>
                            <h3>Rangkuman kecil</h3>
                            <p>Area ringkasan dummy untuk meniru tampilan source tanpa membawa route atau API lesson.</p>
                            <button type="button" class="detail-sidecard__ghost" disabled>Segera tersedia</button>
                        </div>
                    </aside>
                </div>
            </section>
        </section>
    </main>
</div>
@endsection
