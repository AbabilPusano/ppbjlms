@extends('layouts.lms-ui')

@section('body')
    <div class="app-container">
        @include('partials.student-sidebar')

        <main class="main-content">
            <section class="learning-shell">
                <header class="learning-hero">
                    <div class="learning-hero__copy">
                        <span class="learning-hero__eyebrow">Ruang belajar N5</span>
                        <h1>Selamat Datang di Ruang Pembelajaran</h1>
                        <p>
                            Mari lanjutkan progres belajarmu hari ini. Silakan pilih modul yang tersedia di bawah ini untuk
                            mulai berlatih.
                        </p>
                    </div>

                    <div class="learning-hero__meta">
                        <div class="learning-stat-card">
                            <span class="learning-stat-card__label">Unit aktif</span>
                            <strong>{{ $units->count() }}</strong>
                        </div>
                        <div class="learning-stat-card">
                            <span class="learning-stat-card__label">Step selesai</span>
                            <strong>{{ $completedSteps }}/{{ $totalSteps }}</strong>
                        </div>
                        @if ($continueUrl)
                            <a href="{{ $continueUrl }}" class="learning-continue-btn">
                                {{ $continueLabel }}
                            </a>
                        @else
                            <button type="button" class="learning-continue-btn" disabled>
                                {{ $continueLabel }}
                            </button>
                        @endif
                    </div>
                </header>

                <section class="unit-overview">
                    <div class="section-heading">
                        <div>
                            <span class="ds-section-eyebrow">Pilih ritmemu</span>
                            <h2 class="section-heading__title">Pilihan Modul</h2>
                        </div>
                        <p class="section-heading__desc">
                            Daftar materi pelajaran yang sudah bisa kamu akses.
                        </p>
                    </div>

                    @if ($units->isEmpty())
                        <div class="detail-sidecard">
                            <span class="ds-section-eyebrow">Belum ada konten</span>
                            <h3>Unit belajar belum tersedia</h3>
                            <p>Konten akan muncul otomatis setelah data section, unit, dan step tersedia di database.</p>
                        </div>
                    @else
                        <div class="unit-grid">
                            @foreach ($units as $unit)
                                @if ($unit['unlocked'])
                                    <a href="{{ $unit['url'] }}" class="unit-card">
                                @else
                                        <button type="button" class="unit-card" disabled aria-disabled="true">
                                    @endif
                                        <div class="unit-card__head">
                                            <span class="unit-card__index">{{ $unit['name'] }}</span>
                                            <span
                                                class="unit-card__status unit-card__status--{{ $unit['status'] }}">{{ $unit['statusLabel'] }}</span>
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
                                                <span>{{ $unit['lessonCount'] }} lesson, {{ $unit['quizCount'] }} quiz</span>
                                                <span>{{ $unit['next'] }}</span>
                                            </div>
                                        </div>
                                        @if ($unit['unlocked'])
                                            </a>
                                        @else
                                    </button>
                                @endif
                            @endforeach
                        </div>
                    @endif
                </section>

                @if ($currentUnit)
                    <section class="unit-detail">
                        <div class="unit-detail__layout">
                            <div class="unit-detail__main">
                                <header class="unit-detail__hero">
                                    <div class="unit-detail__hero-copy">
                                        <span class="unit-detail__eyebrow">{{ $currentUnit['name'] }}</span>
                                        <h2>{{ $currentUnit['topic'] }}</h2>
                                        <p>{{ $currentUnit['description'] }}</p>
                                    </div>
                                    <div class="unit-detail__hero-meta">
                                        <div class="unit-detail__status">{{ $currentUnit['statusLabel'] }}</div>
                                        <div class="unit-progress">
                                            <div class="unit-progress__label">
                                                <span>Progres</span>
                                                <strong>{{ $currentUnit['progress'] }}%</strong>
                                            </div>
                                            <div class="unit-progress__bar"><span
                                                    style="width: {{ $currentUnit['progress'] }}%"></span></div>
                                        </div>
                                        <div class="unit-detail__counts">
                                            <span>{{ $currentUnit['completed'] }} selesai</span>
                                            <span>{{ $currentUnit['total'] }} total step</span>
                                        </div>
                                    </div>
                                </header>

                                <div class="section-heading section-heading--compact">
                                    <div>
                                        <span class="ds-section-eyebrow">Alur Belajar</span>
                                        <h3 class="section-heading__title">Tahapan Belajar</h3>
                                    </div>
                                    <p class="section-heading__desc">
                                        Kerjakan materi secara berurutan agar proses belajarmu lebih terarah.
                                    </p>
                                </div>

                                @if ($steps->isEmpty())
                                    <div class="detail-sidecard">
                                        <span class="ds-section-eyebrow">Belum ada step</span>
                                        <h3>{{ $currentUnit['topic'] }}</h3>
                                        <p>Unit ini sudah tersedia, tapi step pembelajarannya belum ada di database.</p>
                                    </div>
                                @else
                                    <div class="step-roadmap">
                                        @foreach ($steps as $step)
                                            <div class="roadmap-step-wrap">
                                                @if ($step['status'] === 'locked')
                                                    <button type="button" class="roadmap-step roadmap-step--{{ $step['status'] }}" disabled
                                                        aria-disabled="true">
                                                @else
                                                        <a href="{{ $step['url'] }}"
                                                            class="roadmap-step roadmap-step--{{ $step['status'] }}">
                                                    @endif
                                                        <div class="roadmap-step__marker">
                                                            <span>{{ $step['status'] === 'completed' ? '✓' : $step['index'] }}</span>
                                                        </div>
                                                        <div class="roadmap-step__content">
                                                            <div class="roadmap-step__topline">
                                                                <span class="roadmap-step__eyebrow">Step {{ $step['index'] }}</span>
                                                                <span
                                                                    class="roadmap-step__status roadmap-step__status--{{ $step['status'] }}">{{ $step['label'] }}</span>
                                                            </div>
                                                            <h4>{{ $step['title'] }}</h4>
                                                            <p>{{ $step['subtitle'] }}</p>
                                                            <div class="roadmap-step__footer">
                                                                <span>{{ $step['type'] }}</span>
                                                                <span>{{ $step['status'] === 'locked' ? 'Selesaikan step sebelumnya' : 'Buka materi' }}</span>
                                                            </div>
                                                        </div>
                                                        @if ($step['status'] === 'locked')
                                                            </button>
                                                        @else
                                                    </a>
                                                @endif
                                                @if (!$loop->last)
                                                    <div class="roadmap-step__connector" aria-hidden="true"></div>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>

                            <aside class="unit-detail__sidebar">
                                <div class="detail-sidecard">
                                    <span class="ds-section-eyebrow">Fokus berikutnya</span>
                                    <h3>{{ $focusStep ? 'Step ' . $focusStep['index'] : $currentUnit['name'] }}</h3>
                                    <p>
                                        {{ $focusStep ? $focusStep['title'] . ' siap kamu buka sebagai langkah kecil berikutnya.' : 'Semua step di unit ini sudah selesai.' }}
                                    </p>
                                    @if ($focusStep)
                                        <a href="{{ $focusStep['url'] }}" class="learning-continue-btn learning-continue-btn--full">
                                            Buka step aktif
                                        </a>
                                    @endif
                                </div>

                                <div class="detail-sidecard">
                                    <span class="ds-section-eyebrow">Ringkasan unit</span>
                                    <h3>{{ $currentUnit['completed'] }}/{{ $currentUnit['total'] }} step selesai</h3>
                                    <p>
                                        Unit ini berisi {{ $currentUnit['lessonCount'] }} lesson dan
                                        {{ $currentUnit['quizCount'] }} quiz dari data pembelajaran yang tersimpan.
                                    </p>
                                </div>
                            </aside>
                        </div>
                    </section>
                @endif
            </section>
        </main>
    </div>
@endsection