@extends('layouts.lms-ui', ['title' => $story->title . ' - LMS PPBJ'])

@section('body')
@php
    $pages = $story->content ?? [];
    $totalPages = count($pages);
@endphp

<div class="app-container">
    @include('partials.student-sidebar', ['activeMenu' => 'STORIES'])

    <main class="main-content">
        <section class="learning-shell sr-shell">

            {{-- Header --}}
            <header class="sr-header">
                <a href="{{ route('stories.index') }}" class="sr-back">
                    <span class="material-symbols-outlined">arrow_back</span>
                    Kembali ke Daftar Cerita
                </a>
                <div class="sr-header-info">
                    <span class="sr-level-badge">{{ $story->section?->name ?? 'JLPT' }}</span>
                    <h1 class="sr-title">{{ $story->title }}</h1>
                    <p class="sr-subtitle">{{ $totalPages }} halaman &middot; Klik kata bergaris bawah untuk melihat arti</p>
                </div>
                <div class="sr-toggles" role="group" aria-label="Tampilan teks">
                    <button type="button" class="sr-toggle" id="sr-toggle-romaji" aria-pressed="false">
                        <span class="material-symbols-outlined">translate</span>
                        Romaji
                    </button>
                    <button type="button" class="sr-toggle" id="sr-toggle-tl" aria-pressed="false">
                        <span class="material-symbols-outlined">language</span>
                        Terjemahan
                    </button>
                </div>
            </header>

            {{-- Tooltip popup (shared, positioned by JS) --}}
            <div class="sr-tooltip" id="sr-tooltip" hidden role="tooltip">
                <div class="sr-tooltip__word" id="sr-tt-word"></div>
                <div class="sr-tooltip__reading" id="sr-tt-reading">
                    <span class="material-symbols-outlined">translate</span>
                    <span id="sr-tt-reading-text"></span>
                    <span id="sr-tt-romaji-text" style="color: var(--text-muted, #666); font-size: 0.9em; margin-left: 4px;"></span>
                </div>
                <div class="sr-tooltip__meaning" id="sr-tt-meaning"></div>
            </div>

            @if ($totalPages === 0)
                <div class="sr-empty">
                    <span class="material-symbols-outlined">menu_book</span>
                    <p>Cerita ini belum memiliki isi.</p>
                </div>
            @else
                <div class="sr-reader" id="sr-reader">
                    {{-- Kolom Kiri: Cover Image --}}
                    <div class="sr-reader-left">
                        <div class="sr-cover">
                            <img src="https://image.pollinations.ai/prompt/{{ urlencode($story->title) }}?width=800&height=600&nologo=true" alt="Cover Cerita">
                        </div>
                    </div>

                    {{-- Kolom Kanan: Isi Halaman dan Kontrol --}}
                    <div class="sr-reader-right">
                        @foreach ($pages as $i => $page)
                            <article
                                class="sr-page {{ $i === 0 ? 'sr-page--active' : '' }}"
                                data-page="{{ $i }}"
                                aria-hidden="{{ $i === 0 ? 'false' : 'true' }}"
                            >
                                <div class="sr-page-num">{{ $i + 1 }} / {{ $totalPages }}</div>

                                {{-- Japanese text rendered as plain text, JS will highlight words --}}
                                <div class="sr-japanese" lang="ja"
                                     data-text="{{ $page['japanese'] ?? '' }}"
                                     data-words="{{ json_encode($page['words'] ?? [], JSON_UNESCAPED_UNICODE) }}"
                                >{{ $page['japanese'] ?? '' }}</div>

                                <div class="sr-romaji" style="display: none;">
                                    <span class="sr-romaji-label">Romaji</span>
                                    {{ $page['romaji'] ?? '' }}
                                </div>

                                <div class="sr-translation" style="display: none;">
                                    <span class="sr-tl-label">
                                        <span class="material-symbols-outlined">flag</span>
                                        Terjemahan
                                    </span>
                                    {{ $page['translation'] ?? '' }}
                                </div>
                            </article>
                        @endforeach

                        {{-- Navigation --}}
                        <nav class="sr-nav" aria-label="Navigasi halaman">
                            <button type="button" class="sr-nav-btn" id="sr-prev" disabled aria-label="Halaman sebelumnya">
                                <span class="material-symbols-outlined">chevron_left</span>
                            </button>
                            <div class="sr-progress-dots" id="sr-dots" role="tablist">
                                @foreach ($pages as $i => $page)
                                    <button type="button"
                                        class="sr-dot {{ $i === 0 ? 'sr-dot--active' : '' }}"
                                        data-target="{{ $i }}"
                                        aria-label="Halaman {{ $i + 1 }}"
                                        role="tab"
                                        aria-selected="{{ $i === 0 ? 'true' : 'false' }}"></button>
                                @endforeach
                            </div>
                            <button type="button" class="sr-nav-btn" id="sr-next" aria-label="Halaman berikutnya"
                                {{ $totalPages <= 1 ? 'disabled' : '' }}>
                                <span class="material-symbols-outlined">chevron_right</span>
                            </button>
                        </nav>

                        <div class="sr-finish" id="sr-finish" hidden>
                            <span class="material-symbols-outlined">stars</span>
                            <h3>Kamu sudah membaca semua halaman!</h3>
                            <p>Bagus! Terus latihan membaca setiap hari.</p>
                            <a href="{{ route('stories.index') }}" class="learning-continue-btn">Baca Cerita Lain</a>
                        </div>
                    </div>
                </div>
            @endif
        </section>
    </main>
</div>

<script>
(function () {
    const total = {{ $totalPages }};
    if (total === 0) return;

    // ─── Highlight words in Japanese text ─────────────────────────────────────
    function buildWordHighlights(container) {
        const text  = container.dataset.text || container.textContent;
        const words = JSON.parse(container.dataset.words || '[]');

        if (!words.length) return; // no word data, leave as plain text

        // Sort by word length descending to match longer words first (avoid partial matches)
        const sorted = [...words].sort((a, b) => (b.word || '').length - (a.word || '').length);

        // Build a map for quick lookup
        const wordMap = {};
        sorted.forEach(w => { if (w.word) wordMap[w.word] = w; });

        // Escape special regex characters
        const escapeRegex = s => s.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');

        // Build combined regex from all word keys
        const pattern = sorted
            .filter(w => w.word && w.word.trim())
            .map(w => escapeRegex(w.word))
            .join('|');

        if (!pattern) return;

        const regex = new RegExp('(' + pattern + ')', 'g');
        const parts = text.split(regex);

        container.innerHTML = '';
        parts.forEach(part => {
            if (wordMap[part]) {
                const span = document.createElement('span');
                span.className = 'sr-word';
                span.textContent = part;
                span.dataset.word    = wordMap[part].word    || '';
                span.dataset.reading = wordMap[part].reading || '';
                span.dataset.romaji  = wordMap[part].romaji  || '';
                span.dataset.meaning = wordMap[part].meaning || '';
                span.setAttribute('tabindex', '0');
                span.setAttribute('role', 'button');
                container.appendChild(span);
            } else {
                container.appendChild(document.createTextNode(part));
            }
        });
    }

    // Apply to all pages
    document.querySelectorAll('.sr-japanese[data-words]').forEach(buildWordHighlights);

    // ─── Page navigation ──────────────────────────────────────────────────────
    let current = 0;
    const pages  = document.querySelectorAll('.sr-page');
    const dots   = document.querySelectorAll('.sr-dot');
    const prev   = document.getElementById('sr-prev');
    const next   = document.getElementById('sr-next');
    const finish = document.getElementById('sr-finish');

    function goTo(idx) {
        pages[current].classList.remove('sr-page--active');
        pages[current].setAttribute('aria-hidden', 'true');
        dots[current].classList.remove('sr-dot--active');
        dots[current].setAttribute('aria-selected', 'false');
        current = idx;
        pages[current].classList.add('sr-page--active');
        pages[current].setAttribute('aria-hidden', 'false');
        dots[current].classList.add('sr-dot--active');
        dots[current].setAttribute('aria-selected', 'true');
        prev.disabled = current === 0;
        next.disabled = current === total - 1;
        finish.hidden = current !== total - 1;
        hideTooltip();
    }

    prev.addEventListener('click', () => { if (current > 0) goTo(current - 1); });
    next.addEventListener('click', () => { if (current < total - 1) goTo(current + 1); });
    dots.forEach(dot => dot.addEventListener('click', () => goTo(parseInt(dot.dataset.target))));
    document.addEventListener('keydown', e => {
        if (e.key === 'ArrowRight' && current < total - 1) goTo(current + 1);
        if (e.key === 'ArrowLeft'  && current > 0)         goTo(current - 1);
        if (e.key === 'Escape') hideTooltip();
    });

    // ─── Toggle Romaji ────────────────────────────────────────────────────────
    let showRomaji = false;
    document.getElementById('sr-toggle-romaji').addEventListener('click', function () {
        showRomaji = !showRomaji;
        this.classList.toggle('is-active', showRomaji);
        this.setAttribute('aria-pressed', showRomaji);
        document.querySelectorAll('.sr-romaji').forEach(el => el.style.display = showRomaji ? '' : 'none');
    });

    // ─── Toggle Translation ───────────────────────────────────────────────────
    let showTl = false;
    document.getElementById('sr-toggle-tl').addEventListener('click', function () {
        showTl = !showTl;
        this.classList.toggle('is-active', showTl);
        this.setAttribute('aria-pressed', showTl);
        document.querySelectorAll('.sr-translation').forEach(el => el.style.display = showTl ? '' : 'none');
    });

    // ─── Word tooltip ─────────────────────────────────────────────────────────
    const tooltip   = document.getElementById('sr-tooltip');
    const ttWord    = document.getElementById('sr-tt-word');
    const ttReading = document.getElementById('sr-tt-reading-text');
    const ttRomaji  = document.getElementById('sr-tt-romaji-text');
    const ttMeaning = document.getElementById('sr-tt-meaning');
    let   activeWord = null;

    function showTooltip(wordEl) {
        const word    = wordEl.dataset.word;
        const reading = wordEl.dataset.reading;
        const romaji  = wordEl.dataset.romaji;
        const meaning = wordEl.dataset.meaning;
        if (!word && !reading && !meaning) return;

        if (activeWord && activeWord !== wordEl) activeWord.classList.remove('sr-word--active');
        activeWord = wordEl;
        wordEl.classList.add('sr-word--active');

        ttWord.textContent    = word;
        ttReading.textContent = reading;
        ttRomaji.textContent  = romaji ? `(${romaji})` : '';
        ttMeaning.textContent = meaning;
        tooltip.hidden = false;

        // Position below the clicked word
        const rect = wordEl.getBoundingClientRect();
        let top  = rect.bottom + window.scrollY + 10;
        let left = rect.left + window.scrollX;

        // Prevent tooltip from going off right edge
        const ttW = 230;
        if (left + ttW > window.innerWidth - 12) {
            left = Math.max(8, window.innerWidth - ttW - 12);
        }

        tooltip.style.top  = top + 'px';
        tooltip.style.left = left + 'px';
    }

    function hideTooltip() {
        tooltip.hidden = true;
        if (activeWord) { activeWord.classList.remove('sr-word--active'); activeWord = null; }
    }

    document.getElementById('sr-reader').addEventListener('click', e => {
        const wordEl = e.target.closest('.sr-word');
        if (wordEl) {
            e.stopPropagation();
            if (wordEl === activeWord && !tooltip.hidden) { hideTooltip(); }
            else { showTooltip(wordEl); }
        } else {
            hideTooltip();
        }
    });

    document.getElementById('sr-reader').addEventListener('keydown', e => {
        if (e.key === 'Enter' || e.key === ' ') {
            const wordEl = e.target.closest('.sr-word');
            if (wordEl) { e.preventDefault(); showTooltip(wordEl); }
        }
    });
})();
</script>
@endsection
