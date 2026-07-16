@extends('layouts.lms-ui', ['title' => 'Buat Cerita - LMS PPBJ'])

@section('body')
@php($currentUser = auth()->user())
@php($isAdmin = $currentUser?->role === 'admin')

<div class="guru-shell" id="gs-create-shell">
    <header class="guru-topbar">
        <button type="button" class="guru-hamburger" data-guru-menu aria-label="Toggle menu">
            <span class="material-symbols-outlined" aria-hidden="true">menu</span>
        </button>
        <div>
            <p>Buat Cerita Baru</p>
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
        </nav>

        <div class="guru-back">
            <a href="{{ route('guru.stories.index') }}"><span class="material-symbols-outlined">arrow_back</span>Kembali ke Daftar</a>
        </div>
    </aside>

    <main class="guru-main guru-units-main">
        <div class="guru-heading">
            <div>
                <h1>BUAT CERITA BARU</h1>
                <p>Masukkan level dan tema, cerita bahasa Jepang akan dibuat otomatis lengkap dengan romaji dan terjemahan.</p>
            </div>
        </div>

        {{-- STEP 1: Generate Form --}}
        <section class="gs-create-card" id="gs-step-generate">
            <div class="gs-create-card__head">
                <div>
                    <h2>Pilih Level & Tema</h2>
                    <p>Kosakata dan tata bahasa akan disesuaikan dengan level JLPT yang kamu pilih.</p>
                </div>
            </div>

            <div class="gs-form-grid">
                <div class="guru-unit-form">
                    <label for="gs-level">
                        <span>Level JLPT</span>
                        <select id="gs-level" class="gs-select">
                            <option value="N5">N5 — Pemula</option>
                            <option value="N4">N4 — Dasar</option>
                            <option value="N3">N3 — Menengah</option>
                            <option value="N2">N2 — Mahir</option>
                            <option value="N1">N1 — Ahli</option>
                        </select>
                    </label>
                </div>
                <div class="guru-unit-form">
                    <label for="gs-pages">
                        <span>Jumlah Halaman</span>
                        <select id="gs-pages" class="gs-select">
                            <option value="3">3 halaman</option>
                            <option value="5" selected>5 halaman</option>
                            <option value="7">7 halaman</option>
                            <option value="10">10 halaman</option>
                        </select>
                    </label>
                </div>
                <div class="guru-unit-form gs-theme-wrap">
                    <label for="gs-theme">
                        <span>Tema Cerita</span>
                        <input type="text" id="gs-theme" placeholder="Contoh: Membeli bento di kantin sekolah"
                               class="gs-input" maxlength="200">
                    </label>
                </div>
            </div>

            <div class="gs-example-chips">
                <span>Contoh tema:</span>
                <button type="button" class="gs-chip" data-theme="Pergi ke supermarket membeli sayuran">🛒 Supermarket</button>
                <button type="button" class="gs-chip" data-theme="Bertemu teman baru di stasiun kereta">🚉 Stasiun</button>
                <button type="button" class="gs-chip" data-theme="Memesan ramen di restoran">🍜 Makan Ramen</button>
                <button type="button" class="gs-chip" data-theme="Liburan ke Kyoto melihat kuil">⛩️ Kyoto</button>
                <button type="button" class="gs-chip" data-theme="Belajar di perpustakaan dengan teman">📚 Perpustakaan</button>
            </div>

            <div id="gs-error" class="gs-error-box" hidden></div>

            <button type="button" id="gs-generate-btn" class="gs-generate-btn">
                <span class="material-symbols-outlined" aria-hidden="true">auto_awesome</span>
                <span id="gs-btn-label">Generate Cerita Otomatis</span>
            </button>
        </section>

        {{-- STEP 2: Preview & Edit --}}
        <section class="gs-create-card" id="gs-step-preview" hidden>
            <div class="gs-create-card__head">
                <div>
                    <h2>Review & Edit Cerita</h2>
                    <p>Cerita sudah siap. Kamu bisa edit teks sebelum disimpan.</p>
                </div>
                <button type="button" class="guru-unit-button" id="gs-regenerate-btn">
                    <span class="material-symbols-outlined">refresh</span>
                    Generate Ulang
                </button>
            </div>

            <div class="gs-preview-title">
                <label class="guru-unit-form" style="width:100%">
                    <span>Judul Cerita</span>
                    <input type="text" id="gs-title" class="gs-input" placeholder="Judul cerita...">
                </label>
                <label class="guru-unit-form">
                    <span>Simpan ke Level</span>
                    <select id="gs-section" class="gs-select">
                        @foreach ($sections as $section)
                            <option value="{{ $section->id }}">{{ $section->name }} — {{ $section->description }}</option>
                        @endforeach
                    </select>
                </label>
            </div>

            <div id="gs-pages-container" class="gs-pages-list"></div>

            <button type="button" id="gs-save-btn" class="gs-generate-btn gs-generate-btn--save">
                <span class="material-symbols-outlined" aria-hidden="true">save</span>
                <span id="gs-save-label">Simpan Cerita ke Database</span>
            </button>
        </section>
    </main>
</div>

<script>
(function () {
    const CSRF   = document.querySelector('meta[name="csrf-token"]').content;
    const genBtn = document.getElementById('gs-generate-btn');
    const saveBtn= document.getElementById('gs-save-btn');
    const errBox = document.getElementById('gs-error');
    const stepPreview = document.getElementById('gs-step-preview');
    const pagesContainer = document.getElementById('gs-pages-container');

    let generatedPages = [];

    // ─── Chips ────────────────────────────────────────────────────────────────
    document.querySelectorAll('.gs-chip').forEach(chip => {
        chip.addEventListener('click', () => {
            document.getElementById('gs-theme').value = chip.dataset.theme;
        });
    });

    // ─── Hamburger ────────────────────────────────────────────────────────────
    document.querySelector('[data-guru-menu]')?.addEventListener('click', () => {
        document.querySelector('.guru-shell').classList.toggle('guru-menu-open');
    });

    // ─── Generate ─────────────────────────────────────────────────────────────
    genBtn.addEventListener('click', generate);
    document.getElementById('gs-regenerate-btn')?.addEventListener('click', generate);

    async function generate() {
        const theme = document.getElementById('gs-theme').value.trim();
        const level = document.getElementById('gs-level').value;
        const pages = document.getElementById('gs-pages').value;

        if (!theme) {
            showError('Isi tema cerita terlebih dahulu ya!');
            document.getElementById('gs-theme').focus();
            return;
        }

        hideError();
        setGenerating(true);

        try {
            const res = await fetch('{{ route("guru.api.stories.generate") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': CSRF,
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ level, theme, pages: parseInt(pages) }),
            });

            const data = await res.json();

            if (!data.success) {
                showError(data.error || 'Terjadi kesalahan saat generate cerita.');
                return;
            }

            renderPreview(data.story);
        } catch (e) {
            showError('Tidak bisa terhubung ke server. Coba lagi.');
        } finally {
            setGenerating(false);
        }
    }

    function renderPreview(story) {
        generatedPages = story.pages || [];

        document.getElementById('gs-title').value =
            story.title_translation
                ? `${story.title} - ${story.title_translation}`
                : story.title;

        // Set section dropdown to match level
        const levelVal = document.getElementById('gs-level').value;
        const sectionSel = document.getElementById('gs-section');
        [...sectionSel.options].forEach(opt => {
            if (opt.text.startsWith(levelVal)) opt.selected = true;
        });

        pagesContainer.innerHTML = '';
        generatedPages.forEach((page, idx) => {
            const el = document.createElement('div');
            el.className = 'gs-page-item';
            el.innerHTML = `
                <div class="gs-page-num">Halaman ${idx + 1}</div>
                <div class="gs-page-fields">
                    <label class="guru-unit-form">
                        <span>🇯🇵 Bahasa Jepang</span>
                        <textarea class="gs-textarea gs-jp" rows="2" data-field="japanese" data-idx="${idx}">${escHtml(page.japanese)}</textarea>
                    </label>
                    <label class="guru-unit-form">
                        <span>🔤 Romaji</span>
                        <textarea class="gs-textarea" rows="2" data-field="romaji" data-idx="${idx}">${escHtml(page.romaji)}</textarea>
                    </label>
                    <label class="guru-unit-form">
                        <span>🇮🇩 Terjemahan</span>
                        <textarea class="gs-textarea" rows="2" data-field="translation" data-idx="${idx}">${escHtml(page.translation)}</textarea>
                    </label>
                </div>`;
            pagesContainer.appendChild(el);
        });

        // Live-sync edits back to generatedPages
        pagesContainer.querySelectorAll('textarea').forEach(ta => {
            ta.addEventListener('input', () => {
                const idx   = parseInt(ta.dataset.idx);
                const field = ta.dataset.field;
                generatedPages[idx][field] = ta.value;
            });
        });

        stepPreview.hidden = false;
        stepPreview.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }

    // ─── Save ─────────────────────────────────────────────────────────────────
    saveBtn.addEventListener('click', async () => {
        const title     = document.getElementById('gs-title').value.trim();
        const sectionId = document.getElementById('gs-section').value;

        if (!title) { showError('Judul cerita tidak boleh kosong.'); return; }
        if (generatedPages.length === 0) { showError('Belum ada halaman cerita.'); return; }

        setSaving(true);
        hideError();

        try {
            const res = await fetch('{{ route("guru.stories.store") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': CSRF,
                    'Accept': 'application/json',
                },
                body: JSON.stringify({
                    section_id: sectionId,
                    title,
                    pages: generatedPages,
                }),
            });

            const data = await res.json();

            if (data.success) {
                window.location.href = '{{ route("guru.stories.index") }}';
            } else {
                const errs = data.errors ? Object.values(data.errors).flat().join(' ') : (data.message || 'Gagal menyimpan.');
                showError(errs);
            }
        } catch (e) {
            showError('Tidak bisa terhubung ke server.');
        } finally {
            setSaving(false);
        }
    });

    // ─── Helpers ──────────────────────────────────────────────────────────────
    function showError(msg) {
        errBox.textContent = msg;
        errBox.hidden = false;
        errBox.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }
    function hideError() { errBox.hidden = true; }

    function setGenerating(on) {
        genBtn.disabled = on;
        document.getElementById('gs-btn-label').textContent = on
            ? 'Sedang membuat cerita...'
            : 'Generate Cerita Otomatis';
        genBtn.classList.toggle('is-loading', on);
    }

    function setSaving(on) {
        saveBtn.disabled = on;
        document.getElementById('gs-save-label').textContent = on
            ? 'Menyimpan...'
            : 'Simpan Cerita ke Database';
    }

    function escHtml(str) {
        return String(str ?? '')
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;');
    }
})();
</script>
@endsection
