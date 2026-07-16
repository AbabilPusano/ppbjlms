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
                    <div class="section-heading"
                        style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; flex-wrap: wrap; gap: 16px;">
                        <div>
                            <span class="ds-section-eyebrow">Pilih ritmemu</span>
                            <h2 class="section-heading__title" style="margin: 4px 0 0;">Pilihan Modul</h2>
                            <p class="section-heading__desc" style="margin: 4px 0 0;">
                                Daftar materi pelajaran yang sudah bisa kamu akses.
                            </p>
                        </div>
                        <div class="level-selector-container" style="display: flex; align-items: center; gap: 8px;">
                            <span style="font-size: 0.9rem; font-weight: 700; color: var(--text-muted);">Level Aktif:</span>
                            <div class="guru-level-select-wrapper"
                                style="position: relative; display: inline-flex; align-items: center; background: var(--card-bg); border: 2px solid var(--border); border-radius: 12px; padding: 4px 12px;">
                                <span class="material-symbols-outlined"
                                    style="font-size: 18px; color: var(--text-muted); margin-right: 6px;">tune</span>
                                <select class="guru-level-select"
                                    onchange="window.location.href = '?level_id=' + this.value" aria-label="Pilih Level"
                                    style="border: none; background: transparent; font-weight: 800; color: var(--text); font-size: 0.95rem; cursor: pointer; outline: none; padding-right: 20px;">
                                    @foreach($levels as $lvl)
                                        <option value="{{ $lvl->id }}" {{ $level->id === $lvl->id ? 'selected' : '' }}>
                                            {{ $lvl->code }} - {{ $lvl->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    @if ($units->isEmpty())
                        <div class="detail-sidecard">
                            <span class="ds-section-eyebrow">Belum ada konten</span>
                            <h3>Unit belajar belum tersedia</h3>
                            <p>Konten akan muncul otomatis setelah data section, unit, dan step tersedia di database.</p>
                        </div>
                    @else
                        <div class="unit-accordion-list" style="display: flex; flex-direction: column; gap: 16px;">
                            @foreach ($units as $index => $unit)
                                <div class="unit-accordion-item"
                                    style="border: 2px solid var(--line); border-radius: 16px; overflow: hidden; background: var(--card-bg, #fff); transition: all 0.2s ease; box-shadow: 0 4px 12px rgba(0,0,0,0.03);">
                                    {{-- Accordion Header / Unit Card --}}
                                    <div class="unit-accordion-header"
                                        style="padding: 20px; display: flex; justify-content: space-between; align-items: center; cursor: {{ $unit['unlocked'] ? 'pointer' : 'not-allowed' }}; background: {{ !$unit['unlocked'] ? '#f9f9f9' : 'transparent' }}; opacity: {{ $unit['unlocked'] ? '1' : '0.6' }};"
                                        @if($unit['unlocked']) onclick="toggleUnitAccordion('{{ $unit['id'] }}')" @endif>
                                        <div style="display: flex; flex-direction: column; gap: 4px; flex: 1;">
                                            <div style="display: flex; align-items: center; gap: 12px;">
                                                <span
                                                    style="font-weight: 800; font-size: 1.1rem; color: var(--text-muted);">{{ $unit['name'] }}</span>
                                                <span class="unit-card__status unit-card__status--{{ $unit['status'] }}"
                                                    style="padding: 4px 8px; border-radius: 8px; font-size: 0.75rem; font-weight: 700;">
                                                    {{ $unit['statusLabel'] }}
                                                </span>
                                            </div>
                                            <h3 style="margin: 4px 0 0; font-size: 1.25rem; font-weight: 800; color: var(--text);">
                                                {{ $unit['topic'] }}</h3>
                                            <p style="margin: 4px 0 0; font-size: 0.9rem; color: var(--text-muted);">
                                                {{ $unit['description'] }}</p>
                                        </div>

                                        <div style="display: flex; align-items: center; gap: 24px;">
                                            <div style="text-align: right;">
                                                <div style="font-size: 0.85rem; color: var(--text-muted); margin-bottom: 4px;">
                                                    Progres: <strong>{{ $unit['progress'] }}%</strong></div>
                                                <div
                                                    style="width: 120px; height: 8px; background: var(--border); border-radius: 4px; overflow: hidden;">
                                                    <div
                                                        style="width: {{ $unit['progress'] }}%; height: 100%; background: var(--accent);">
                                                    </div>
                                                </div>
                                            </div>
                                            <div style="font-size: 0.9rem; font-weight: 700; color: var(--text-muted);">
                                                {{ $unit['completed'] }}/{{ $unit['total'] }} step
                                            </div>
                                            <div>
                                                @if ($unit['unlocked'])
                                                    <span class="material-symbols-outlined accordion-arrow" id="arrow-{{ $unit['id'] }}"
                                                        style="font-size: 24px; transition: transform 0.2s ease;">expand_more</span>
                                                @else
                                                    <span class="material-symbols-outlined"
                                                        style="font-size: 24px; color: var(--text-muted);">lock</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Accordion Content / Node List --}}
                                    @if ($unit['unlocked'])
                                        <div class="unit-accordion-content" id="content-{{ $unit['id'] }}"
                                            style="display: none; border-top: 2px solid var(--border); padding: 20px; background: var(--bg);">
                                            @php
                                                // Load steps for this specific unit
                                                $unitSteps = collect();
                                                $unitModel = $unit['model'];
                                                $firstOpenStepFound = false;
                                                if ($unitModel) {
                                                    $unitSteps = $unitModel->nodes->values()->map(function ($node, int $stepIdx) use ($unit, $unitModel, &$firstOpenStepFound) {
                                                        $completed = $node->progress->isNotEmpty();
                                                        $status = 'locked';

                                                        if ($unit['unlocked']) {
                                                            if ($completed) {
                                                                $status = 'completed';
                                                            } elseif (!$firstOpenStepFound) {
                                                                $status = 'active';
                                                                $firstOpenStepFound = true;
                                                            }
                                                        }

                                                        return [
                                                            'id' => $node->id,
                                                            'index' => $stepIdx + 1,
                                                            'title' => $node->title,
                                                            'status' => $status,
                                                            'type' => $node->type === 'quiz' ? 'Quiz' : 'Materi',
                                                            'url' => route('lesson.show', ['unit' => $unit['position'], 'level' => $stepIdx + 1]),
                                                        ];
                                                    });
                                                }
                                            @endphp

                                            @if ($unitSteps->isEmpty())
                                                <p style="color: var(--text-muted); text-align: center; margin: 0;">Belum ada materi
                                                    pembelajaran untuk unit ini.</p>
                                            @else
                                                <div style="display: flex; flex-direction: column; gap: 12px;">
                                                    @foreach ($unitSteps as $step)
                                                        @if ($step['status'] === 'locked')
                                                            <div
                                                                style="display: flex; align-items: center; justify-content: space-between; padding: 12px 16px; border: 2px solid var(--line); border-radius: 12px; background: #fafafa; opacity: 0.6; cursor: not-allowed;">
                                                        @else
                                                                <a href="{{ $step['url'] }}"
                                                                    style="display: flex; align-items: center; justify-content: space-between; padding: 12px 16px; border: 2px solid var(--line); border-radius: 12px; background: var(--card-bg, #fff); text-decoration: none; transition: border-color 0.2s;"
                                                                    onmouseover="this.style.borderColor='var(--accent)'"
                                                                    onmouseout="this.style.borderColor='var(--line)'">
                                                            @endif
                                                                <div style="display: flex; align-items: center; gap: 16px;">
                                                                    <div
                                                                        style="width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 800; background: {{ $step['status'] === 'completed' ? 'var(--accent)' : ($step['status'] === 'active' ? '#e8f0fe' : '#f1f1f1') }}; color: {{ $step['status'] === 'completed' ? '#fff' : ($step['status'] === 'active' ? 'var(--accent)' : 'var(--text-muted)') }};">
                                                                        {!! $step['status'] === 'completed' ? '✓' : $step['index'] !!}
                                                                    </div>
                                                                    <div>
                                                                        <div style="font-weight: 700; font-size: 0.95rem; color: var(--text);">
                                                                            {{ $step['title'] }}</div>
                                                                        <div style="font-size: 0.8rem; color: var(--text-muted);">Tipe:
                                                                            {{ $step['type'] }}</div>
                                                                    </div>
                                                                </div>
                                                                <div>
                                                                    @if ($step['status'] === 'completed')
                                                                        <span
                                                                            style="font-size: 0.85rem; font-weight: 700; color: var(--accent);">Selesai</span>
                                                                    @elseif ($step['status'] === 'active')
                                                                        <span
                                                                            style="font-size: 0.85rem; font-weight: 700; color: #1a73e8;">Mulai</span>
                                                                    @else
                                                                        <span class="material-symbols-outlined"
                                                                            style="font-size: 20px; color: var(--text-muted);">lock</span>
                                                                    @endif
                                                                </div>
                                                                @if ($step['status'] === 'locked')
                                                                    </div>
                                                                @else
                                                            </a>
                                                        @endif
                                                    @endforeach
                                                </div>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @endif
                </section>
            </section>
        </main>
    </div>

    <script>
        function toggleUnitAccordion(unitId) {
            const content = document.getElementById('content-' + unitId);
            const arrow = document.getElementById('arrow-' + unitId);
            if (content.style.display === 'none') {
                content.style.display = 'block';
                arrow.style.transform = 'rotate(180deg)';
            } else {
                content.style.display = 'none';
                arrow.style.transform = 'rotate(0deg)';
            }
        }
    </script>
@endsection