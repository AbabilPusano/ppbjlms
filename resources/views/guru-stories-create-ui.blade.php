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
                <p>Pilih level dan tema cerita bahasa Jepang yang akan dibuat otomatis oleh AI.</p>
            </div>
        </div>

        {{-- STEP 1: Generate Form --}}
        <section class="gs-create-card" id="gs-step-generate">

            <div class="gs-form-grid" style="grid-template-columns: 1fr 1fr;">
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
            </div>

            <div class="guru-unit-form gs-theme-wrap" style="margin-bottom: 20px;">
                <label for="gs-theme">
                    <span>Tema Cerita</span>
                    <input type="text" id="gs-theme" placeholder="Pilih dari contoh di bawah atau ketik tema kustom sendiri..."
                           class="gs-input" maxlength="200">
                </label>
            </div>

            <div class="gs-chips-container">
                <div class="gs-chips-head">
                    <span>💡 Contoh Tema & Kustom:</span>
                    <button type="button" id="gs-add-custom-chip-btn" class="gs-add-custom-btn">
                        <span class="material-symbols-outlined">add_circle</span>
                        + Tambah Tema Kustom
                    </button>
                </div>
                <div id="gs-chips-wrapper" class="gs-example-chips">
                    <!-- Dynamic chips rendered via JavaScript -->
                </div>
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
                    <h2>Review & Edit Hasil Cerita</h2>
                    <p>Cerita sudah berhasil di-generate. Kamu dapat mengedit teks sebelum disimpan ke database.</p>
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

<style>
.gs-create-card {
    background: #ffffff;
    border: 1px solid #e5e7eb;
    border-radius: 16px;
    padding: 28px;
    margin-bottom: 24px;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
}
.gs-form-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 16px;
    margin-bottom: 20px;
}
@media (max-width: 640px) {
    .gs-form-grid { grid-template-columns: 1fr; }
}
.gs-select, .gs-input {
    width: 100%;
    min-height: 48px;
    padding: 10px 16px;
    border: 1px solid #d1d5db;
    border-radius: 10px;
    background: #ffffff;
    font-family: inherit;
    font-size: 14px;
    color: #111827;
    transition: border-color 0.15s ease, box-shadow 0.15s ease;
}
.gs-select:focus, .gs-input:focus {
    outline: none;
    border-color: #be185d;
    box-shadow: 0 0 0 3px rgba(190, 24, 93, 0.15);
}
.gs-chips-container {
    margin-bottom: 24px;
    background: #f9fafb;
    border: 1px solid #f3f4f6;
    border-radius: 14px;
    padding: 18px;
}
.gs-chips-head {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    margin-bottom: 14px;
    flex-wrap: wrap;
}
.gs-chips-head > span {
    font-size: 13px;
    font-weight: 800;
    color: #374151;
    display: flex;
    align-items: center;
    gap: 6px;
}
.gs-add-custom-btn {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: #fdf2f8;
    color: #be185d;
    border: 1px dashed #fbcfe8;
    border-radius: 9999px;
    padding: 6px 16px;
    font-size: 12px;
    font-weight: 800;
    cursor: pointer;
    transition: all 0.15s ease;
}
.gs-add-custom-btn:hover {
    background: #fce7f3;
    border-color: #f472b6;
    color: #9d174d;
}
.gs-example-chips {
    display: flex;
    align-items: center;
    gap: 8px;
    flex-wrap: wrap;
    max-height: 260px;
    overflow-y: auto;
    padding-right: 4px;
}
.gs-chip {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 8px 16px;
    border-radius: 9999px;
    background: #ffffff;
    border: 1px solid #e5e7eb;
    font-size: 13px;
    font-weight: 600;
    color: #374151;
    cursor: pointer;
    transition: all 0.15s ease;
    box-shadow: 0 1px 2px rgba(0, 0, 0, 0.03);
}
.gs-chip:hover {
    background: #fdf2f8;
    border-color: #fbcfe8;
    color: #db2777;
    transform: translateY(-1px);
}
.gs-chip.active {
    background: #be185d;
    border-color: #be185d;
    color: #ffffff;
    box-shadow: 0 2px 8px rgba(190, 24, 93, 0.25);
}
.gs-chip--custom {
    background: #fffbeb;
    border-color: #fde68a;
    color: #92400e;
}
.gs-chip--custom.active {
    background: #d97706;
    border-color: #d97706;
    color: #ffffff;
}
.gs-chip-del {
    margin-left: 4px;
    font-weight: bold;
    font-size: 14px;
    line-height: 1;
    opacity: 0.7;
    padding: 0 3px;
}
.gs-chip-del:hover {
    opacity: 1;
    color: #ef4444;
}
.gs-generate-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    width: 100%;
    min-height: 52px;
    padding: 14px 28px;
    border-radius: 12px;
    border: none;
    background: linear-gradient(135deg, #db2777 0%, #be185d 100%);
    color: #ffffff;
    font-family: inherit;
    font-size: 15px;
    font-weight: 800;
    cursor: pointer;
    box-shadow: 0 4px 14px rgba(190, 24, 93, 0.35);
    transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    margin-top: 12px;
}
.gs-generate-btn:hover:not(:disabled) {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(190, 24, 93, 0.45);
    background: linear-gradient(135deg, #f43f5e 0%, #e11d48 100%);
}
.gs-generate-btn:disabled {
    opacity: 0.65;
    cursor: not-allowed;
}
.gs-generate-btn--save {
    background: linear-gradient(135deg, #059669 0%, #047857 100%);
    box-shadow: 0 4px 14px rgba(5, 150, 105, 0.35);
}
.gs-generate-btn--save:hover:not(:disabled) {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    box-shadow: 0 6px 20px rgba(5, 150, 105, 0.45);
}
.gs-error-box {
    padding: 14px 18px;
    border-radius: 10px;
    background: #fef2f2;
    border: 1px solid #fecaca;
    color: #b91c1c;
    font-size: 14px;
    font-weight: 600;
    margin-top: 16px;
    margin-bottom: 16px;
}
.gs-modal-backdrop {
    position: fixed;
    top: 0; left: 0; right: 0; bottom: 0;
    background: rgba(0, 0, 0, 0.5);
    backdrop-filter: blur(4px);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 9999;
    padding: 20px;
}
.gs-modal {
    background: #ffffff;
    border-radius: 16px;
    width: 100%;
    max-width: 480px;
    padding: 24px;
    box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1), 0 10px 10px -5px rgba(0,0,0,0.04);
}
.gs-modal-head {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 16px;
}
.gs-modal-head h3 {
    margin: 0;
    font-size: 18px;
    font-weight: 800;
    color: #111827;
}
.gs-modal-actions {
    display: flex;
    justify-content: flex-end;
    gap: 10px;
    margin-top: 20px;
}
</style>

{{-- Custom Theme Modal --}}
<div id="gs-custom-theme-modal" class="gs-modal-backdrop" hidden>
    <div class="gs-modal">
        <div class="gs-modal-head">
            <h3>✨ Tambah Tema Kustom Baru</h3>
            <button type="button" class="gs-chip-del" id="gs-modal-close" style="font-size:20px;">&times;</button>
        </div>
        <div style="display:flex; flex-direction:column; gap:12px;">
            <label class="guru-unit-form">
                <span>Nama Singkat Tema (Label Chip)</span>
                <input type="text" id="gs-custom-label" class="gs-input" placeholder="Contoh: 🎪 Festival Musim Panas">
            </label>
            <label class="guru-unit-form">
                <span>Deskripsi Tema (Prompts AI)</span>
                <input type="text" id="gs-custom-prompt" class="gs-input" placeholder="Contoh: Pergi ke Natsumatsuri bersama teman memakai yukata dan melihat kembang api">
            </label>
        </div>
        <div class="gs-modal-actions">
            <button type="button" class="guru-unit-button" id="gs-modal-cancel">Batal</button>
            <button type="button" class="guru-unit-button guru-unit-button--primary" id="gs-modal-submit">
                <span class="material-symbols-outlined">add</span> Simpan Tema
            </button>
        </div>
    </div>
</div>

<script>
(function () {
    const CSRF   = document.querySelector('meta[name="csrf-token"]').content;
    const genBtn = document.getElementById('gs-generate-btn');
    const saveBtn= document.getElementById('gs-save-btn');
    const errBox = document.getElementById('gs-error');
    const stepPreview = document.getElementById('gs-step-preview');
    const pagesContainer = document.getElementById('gs-pages-container');

    const PRESET_THEMES = [
        // Kehidupan & Belanja
        { label: '🛒 Supermarket', theme: 'Pergi ke supermarket membeli sayuran dan buah segar' },
        { label: '🛍️ Harajuku', theme: 'Belanja pakaian baru di mall Harajuku' },
        { label: '🏪 Konbini', theme: 'Membeli bento dan minuman hangat di minimarket (konbini)' },
        { label: '🏠 Apartemen Baru', theme: 'Pindahan rumah ke apartemen baru di Tokyo' },

        // Sekolah & Pendidikan
        { label: '📚 Perpustakaan', theme: 'Belajar bersama teman di perpustakaan sekolah' },
        { label: '🎓 Ujian Semester', theme: 'Mempersiapkan ujian akhir semester di sekolah' },
        { label: '🏫 Kelas Baru', theme: 'Perkenalan diri di depan teman-teman kelas baru' },
        { label: '⚽ Klub Olahraga', theme: 'Latihan di klub olahraga sekolah setelah jam pelajaran' },

        // Makanan & Kuliner
        { label: '🍜 Makan Ramen', theme: 'Memesan ramen lezat di kedai ramen lokal' },
        { label: '🍵 Minum Teh', theme: 'Minum teh hijau hangat bersama nenek di rumah' },
        { label: '🍣 Sushi Pertama', theme: 'Makan sushi pertama kali bersama keluarga' },
        { label: '🍡 Festival Jajanan', theme: 'Membeli jajanan di festival malam (Matsuri)' },

        // Wisata & Budaya
        { label: '⛩️ Wisata Kyoto', theme: 'Liburan ke Kyoto mengunjungi kuil bersejarah' },
        { label: '🚅 Shinkansen', theme: 'Naik kereta cepat Shinkansen menuju Osaka' },
        { label: '🌸 Hanami Sakura', theme: 'Piknik Hanami melihat bunga sakura mekar di taman' },
        { label: '♨️ Onsen Musim Dingin', theme: 'Berendam air panas (onsen) saat musim dingin' },

        // Pekerjaan & Kantor
        { label: '💼 Wawancara Kerja', theme: 'Wawancara kerja pertama di perusahaan Jepang' },
        { label: '💻 Rapat Kantor', theme: 'Rapat presentasi proyek baru dengan rekan kerja' },
        { label: '🍺 Nomikai Kantor', theme: 'Acara minum bersama (Nomikai) dengan teman kantor' },

        // Hobi & Waktu Luang
        { label: '🎮 Main Game', theme: 'Bermain game online bersama sahabat di akhir pekan' },
        { label: '🎨 Gambar Manga', theme: 'Menggambar komik manga di kamar' },
        { label: '🐈 Kafe Kucing', theme: 'Bersantai dan bermain dengan kucing di cat cafe' },

        // Kesehatan & Medis
        { label: '🏥 Periksa Dokter', theme: 'Berobat ke klinik karena demam dan batuk' },
        { label: '💊 Apotek', theme: 'Membeli obat flu dan vitamin di apotek' },
        { label: '🏃 Lari Pagi', theme: 'Lari pagi di sekitar komplek rumah' }
    ];

    // Load persisted custom themes from localStorage
    let customThemes = [];
    try {
        const saved = localStorage.getItem('lms_custom_story_themes');
        if (saved) customThemes = JSON.parse(saved);
    } catch (e) {
        customThemes = [];
    }

    const chipsWrapper = document.getElementById('gs-chips-wrapper');
    const addCustomBtn = document.getElementById('gs-add-custom-chip-btn');
    const themeInput = document.getElementById('gs-theme');
    const modal = document.getElementById('gs-custom-theme-modal');
    const modalLabel = document.getElementById('gs-custom-label');
    const modalPrompt = document.getElementById('gs-custom-prompt');
    let generatedPages = [];

    function saveCustomThemes() {
        try {
            localStorage.setItem('lms_custom_story_themes', JSON.stringify(customThemes));
        } catch (e) {}
    }

    function renderChips() {
        chipsWrapper.innerHTML = '';

        PRESET_THEMES.forEach(item => {
            const btn = document.createElement('button');
            btn.type = 'button';
            btn.className = 'gs-chip';
            if (themeInput.value.trim() === item.theme) {
                btn.classList.add('active');
            }
            btn.dataset.theme = item.theme;
            btn.textContent = item.label;
            btn.addEventListener('click', () => {
                themeInput.value = item.theme;
                highlightChip(btn);
            });
            chipsWrapper.appendChild(btn);
        });

        // Render custom added themes
        customThemes.forEach((item, index) => {
            const btn = document.createElement('button');
            btn.type = 'button';
            btn.className = 'gs-chip gs-chip--custom';
            if (themeInput.value.trim() === item.theme) {
                btn.classList.add('active');
            }
            btn.dataset.theme = item.theme;
            btn.innerHTML = `${escHtml(item.label)} <span class="gs-chip-del" data-idx="${index}">&times;</span>`;
            btn.addEventListener('click', (e) => {
                if (e.target.classList.contains('gs-chip-del')) {
                    e.stopPropagation();
                    customThemes.splice(index, 1);
                    saveCustomThemes();
                    renderChips();
                    return;
                }
                themeInput.value = item.theme;
                highlightChip(btn);
            });
            chipsWrapper.appendChild(btn);
        });
    }

    function highlightChip(activeBtn) {
        document.querySelectorAll('.gs-chip').forEach(c => c.classList.remove('active'));
        activeBtn.classList.add('active');
    }

    // Modal controls
    addCustomBtn?.addEventListener('click', () => {
        modalLabel.value = '';
        modalPrompt.value = '';
        modal.hidden = false;
        modalLabel.focus();
    });

    document.getElementById('gs-modal-close')?.addEventListener('click', () => modal.hidden = true);
    document.getElementById('gs-modal-cancel')?.addEventListener('click', () => modal.hidden = true);

    document.getElementById('gs-modal-submit')?.addEventListener('click', () => {
        const label = modalLabel.value.trim();
        const promptText = modalPrompt.value.trim() || label;

        if (!label) {
            alert('Masukkan nama singkat untuk tema kustom!');
            return;
        }

        const newCustom = {
            label: label.startsWith('✨') ? label : '✨ ' + label,
            theme: promptText
        };

        customThemes.push(newCustom);
        saveCustomThemes();
        themeInput.value = promptText;
        modal.hidden = true;
        renderChips();
    });

    themeInput?.addEventListener('input', () => {
        const val = themeInput.value.trim();
        document.querySelectorAll('.gs-chip').forEach(c => {
            c.classList.toggle('active', c.dataset.theme === val);
        });
    });

    renderChips();

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
            showError('Isi atau pilih tema cerita terlebih dahulu ya!');
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
