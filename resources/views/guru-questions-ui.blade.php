@extends('layouts.lms-ui', ['title' => 'Kelola Langkah Pembelajaran - LMS PPBJ'])

@section('body')
<style>
.guru-kanji-select-wrapper {
    position: relative;
    width: 100%;
}
.guru-kanji-select-dropdown {
    position: absolute;
    top: 100%;
    left: 0;
    width: 100%;
    max-height: 220px;
    background: #fff;
    border: 1px solid var(--border);
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    z-index: 1000;
    margin-top: 4px;
    display: flex;
    flex-direction: column;
}
.guru-kanji-select-options {
    overflow-y: auto;
    max-height: 180px;
    display: flex;
    flex-direction: column;
}
.guru-kanji-select-option {
    padding: 10px 12px;
    font-size: 14px;
    cursor: pointer;
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-bottom: 1px solid #f2f4f7;
}
.guru-kanji-select-option:hover {
    background: #f9fafb;
}
.guru-kanji-select-option.is-selected {
    background: #f4f3ff;
    color: var(--accent);
    font-weight: 600;
}
.guru-kanji-select-loading {
    padding: 10px;
    text-align: center;
    font-size: 12px;
    color: #667085;
    background: #f9fafb;
    border-top: 1px solid var(--border);
}
</style>
<div class="guru-shell" data-guru-questions data-material-id="{{ request()->route('material') }}">
    <header class="guru-topbar">
        <button type="button" class="guru-hamburger" data-guru-menu aria-label="Toggle menu">
            <span class="material-symbols-outlined" aria-hidden="true">menu</span>
        </button>
        <div>
            <p>Kelola Langkah Pembelajaran</p>
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
            <a href="{{ route('guru.dashboard') }}"><span class="material-symbols-outlined">home</span>Dashboard</a>
            <a href="{{ route('guru.units.index') }}" class="active"><span class="material-symbols-outlined">category</span>Unit Pembelajaran<i></i></a>
            @include('partials.admin-account-nav')
            <a href="{{ route('guru.penilaian.index') }}" class="{{ request()->routeIs('guru.penilaian.*') ? 'active' : '' }}"><span class="material-symbols-outlined">checklist</span>Penilaian{!! request()->routeIs('guru.penilaian.*') ? '<i></i>' : '' !!}</a>
            <a href="{{ route('guru.referensi.index') }}" class="{{ request()->routeIs('guru.referensi.*') ? 'active' : '' }}"><span class="material-symbols-outlined">database</span>Bank Referensi{!! request()->routeIs('guru.referensi.*') ? '<i></i>' : '' !!}</a>
            <a href="#" data-ui-only><span class="material-symbols-outlined">settings</span>Pengaturan</a>
        </nav>

        <div class="guru-back">
            <a href="{{ route('guru.units.index') }}"><span class="material-symbols-outlined">arrow_back</span>Kembali ke Unit</a>
        </div>
    </aside>

    <main class="guru-main guru-units-main guru-materials-main">
        <div class="guru-heading guru-units-heading">
            <div>
                <a href="{{ route('guru.units.index') }}" class="guru-inline-back">
                    <span class="material-symbols-outlined" aria-hidden="true">arrow_back</span>
                    Kembali ke Unit
                </a>
                <h1 data-question-title>LANGKAH PEMBELAJARAN</h1>
                <p data-question-subtitle>Memuat unit...</p>
            </div>
            <div class="guru-units-toolbar" style="display: flex; gap: 8px; align-items: center;">
                <button type="button" class="guru-unit-button guru-unit-button--danger" data-delete-selected style="display: none; align-items: center; justify-content: center; gap: 4px;">
                    <span class="material-symbols-outlined" aria-hidden="true">delete_sweep</span>
                    HAPUS TERPILIH (<span data-selected-count>0</span>)
                </button>
                <button type="button" class="guru-unit-button" data-open-import-modal style="background-color: var(--background); border: 1px solid var(--border); color: var(--text-main); display: inline-flex; align-items: center; justify-content: center; gap: 4px;">
                    <span class="material-symbols-outlined" aria-hidden="true">upload_file</span>
                    TAMBAH BANYAK
                </button>
                <button type="button" class="guru-unit-button guru-unit-button--primary" data-open-question-modal>
                    <span class="material-symbols-outlined" aria-hidden="true">add</span>
                    TAMBAH LANGKAH
                </button>
            </div>
        </div>

        <section class="guru-units-state" data-questions-loading>
            <span class="material-symbols-outlined" aria-hidden="true">progress_activity</span>
            <p>Memuat langkah pembelajaran...</p>
        </section>

        <section class="guru-units-state guru-units-state--error" data-questions-error hidden>
            <span class="material-symbols-outlined" aria-hidden="true">error</span>
            <p data-questions-error-message>Gagal memuat langkah.</p>
            <button type="button" class="guru-unit-button" data-retry-questions>Coba lagi</button>
        </section>

        <section class="guru-units-state" data-questions-empty hidden>
            <span class="material-symbols-outlined" aria-hidden="true">school</span>
            <p>Belum ada langkah pembelajaran untuk unit ini.</p>
            <button type="button" class="guru-unit-button guru-unit-button--primary" data-open-question-modal>Tambah langkah pertama</button>
        </section>

        <div class="guru-select-all-container" data-select-all-container style="display: none; align-items: center; justify-content: space-between; gap: 8px; margin-bottom: 12px; background-color: var(--card-bg, #fff); padding: 12px 16px; border-radius: 8px; border: 1px solid var(--border, #eaecf0);">
            <label for="select-all-steps" style="cursor: pointer; font-size: 14px; font-weight: 500; color: var(--text-main);">Pilih Semua Langkah</label>
            <input type="checkbox" id="select-all-steps" style="width: 18px; height: 18px; cursor: pointer; accent-color: var(--accent);">
        </div>

        <section class="guru-question-list" data-question-list aria-label="Daftar langkah"></section>
    </main>

    <div class="guru-modal-overlay" data-question-modal hidden>
        <div class="guru-modal" role="dialog" aria-modal="true" aria-labelledby="question-modal-title">
            <div class="guru-modal__head">
                <div>
                    <span>Langkah Pembelajaran</span>
                    <h2 id="question-modal-title" data-question-modal-title>Tambah Langkah</h2>
                </div>
                <button type="button" class="guru-icon-button" data-close-guru-modal aria-label="Tutup modal">
                    <span class="material-symbols-outlined" aria-hidden="true">close</span>
                </button>
            </div>
            <form class="guru-unit-form" data-question-form>
                <input type="hidden" name="id" data-question-id>
                <label>
                    <span>Tipe Langkah</span>
                    <select name="type" data-question-type required>
                        <option value="text">Teks Pengantar</option>
                        <option value="character">Huruf Baru</option>
                        <option value="vocabulary">Kata Baru</option>
                        <option value="quiz">Kuis Pilihan Ganda</option>
                    </select>
                </label>

                <!-- TEXT STEP FIELDS -->
                <div data-question-fields="text">
                    <label>
                        <span>Isi Konten Teks</span>
                        <textarea name="content" rows="6" placeholder="Masukkan konten teks penjelasan..."></textarea>
                    </label>
                </div>

                <!-- CHARACTER STEP FIELDS -->
                <div data-question-fields="character" hidden>
                    <div class="guru-unit-form__grid">
                        <label>
                            <span>Simbol Huruf</span>
                            <input name="character_symbol" type="text" maxlength="50" placeholder="Contoh: あ">
                        </label>
                        <label>
                            <span>Cara Baca (Romaji/Arti)</span>
                            <input name="character_pronunciation" type="text" maxlength="100" placeholder="Contoh: a">
                        </label>
                    </div>
                </div>

                <!-- VOCABULARY STEP FIELDS -->
                <div data-question-fields="vocabulary" hidden style="display: flex; flex-direction: column; gap: 16px;">
                    <div class="guru-unit-form__grid">
                        <label>
                            <span>Kosakata (Jepang)</span>
                            <input name="vocab_kata" type="text" placeholder="Contoh: 食べる">
                        </label>
                        <label>
                            <span>Cara Baca (Romaji)</span>
                            <input name="vocab_romaji" type="text" placeholder="Contoh: taberu">
                        </label>
                    </div>
                    <label>
                        <span>Arti / Terjemahan</span>
                        <input name="vocab_arti" type="text" placeholder="Contoh: Makan">
                    </label>
                    <div class="guru-unit-form__grid">
                        <label>
                            <span>Hubungkan ke Kanji (Opsional)</span>
                            <div class="guru-kanji-select-wrapper" id="step-kanji-select" data-kanji-select>
                                <div class="guru-kanji-select-control" style="border: 1px solid var(--border); border-radius: 6px; display: flex; align-items: center; background: #fff; padding-right: 8px;">
                                    <input type="text" class="guru-kanji-select-search" placeholder="Cari Kanji (Karakter / Makna)..." autocomplete="off" style="border: none !important; outline: none !important; width: 100%; padding: 8px 12px; border-radius: 6px;">
                                    <span class="material-symbols-outlined guru-kanji-select-arrow" style="cursor: pointer;">arrow_drop_down</span>
                                </div>
                                <div class="guru-kanji-select-dropdown" style="display: none;">
                                    <div class="guru-kanji-select-options"></div>
                                    <div class="guru-kanji-select-loading" style="display: none;">Memuat lebih banyak...</div>
                                </div>
                                <input type="hidden" name="vocab_kanji_id" class="guru-kanji-select-value">
                            </div>
                        </label>
                        <label>
                            <span>Tipe Kata (Opsional)</span>
                            <input name="vocab_tipe_kata" type="text" placeholder="Contoh: Kata Kerja">
                        </label>
                    </div>
                    <div class="guru-unit-form__grid">
                        <label>
                            <span>Level (Opsional)</span>
                            <select name="vocab_level" style="background: #fff; border: 1px solid var(--border); padding: 8px; border-radius: 6px;">
                                <option value="">-- Tanpa Level --</option>
                                <option value="N5">N5</option>
                                <option value="N4">N4</option>
                                <option value="N3">N3</option>
                                <option value="N2">N2</option>
                                <option value="N1">N1</option>
                            </select>
                        </label>
                        <label>
                            <span>Deskripsi Tambahan (Opsional)</span>
                            <input name="vocab_deskripsi" type="text" placeholder="Deskripsi atau penjelasan...">
                        </label>
                    </div>
                </div>

                <!-- QUIZ TYPE SELECT -->
                <div data-question-fields="quiz" hidden>
                    <label>
                        <span>Tipe Kuis</span>
                        <select name="quiz_type" data-quiz-type required>
                            <option value="multiple_choice" selected>Pilihan Ganda</option>
                        </select>
                    </label>

                    <!-- MULTIPLE CHOICE FIELDS -->
                    <div data-quiz-fields="multiple_choice" style="margin-top: 16px;">
                        <label>
                            <span>Instruksi Kuis (Perintah)</span>
                            <input name="quiz_instruction" type="text" placeholder="Contoh: PILIH JAWABAN YANG BENAR" value="PILIH JAWABAN YANG BENAR">
                        </label>
                        <label>
                            <span>Pertanyaan (Kalimat)</span>
                            <input name="quiz_query" type="text" placeholder="Contoh: あ">
                        </label>
                        <div class="guru-unit-form__grid">
                            <label>
                                <span>Pilihan 1 (Index 0)</span>
                                <input name="quiz_option_0" type="text" placeholder="Pilihan 1">
                            </label>
                            <label>
                                <span>Pilihan 2 (Index 1)</span>
                                <input name="quiz_option_1" type="text" placeholder="Pilihan 2">
                            </label>
                            <label>
                                <span>Pilihan 3 (Index 2)</span>
                                <input name="quiz_option_2" type="text" placeholder="Pilihan 3">
                            </label>
                            <label>
                                <span>Pilihan 4 (Index 3)</span>
                                <input name="quiz_option_3" type="text" placeholder="Pilihan 4">
                            </label>
                        </div>
                        <label>
                            <span>Pilihan Jawaban yang Benar</span>
                            <select name="quiz_correct_index">
                                <option value="0">Pilihan 1</option>
                                <option value="1">Pilihan 2</option>
                                <option value="2">Pilihan 3</option>
                                <option value="3">Pilihan 4</option>
                            </select>
                        </label>
                    </div>
                </div>

                <input type="hidden" name="sort_order" value="">
                <p class="guru-unit-form__error" data-question-form-error hidden></p>
                <div class="guru-modal__actions">
                    <button type="button" class="guru-unit-button" data-close-guru-modal>Batal</button>
                    <button type="submit" class="guru-unit-button guru-unit-button--primary" data-question-submit>Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <div class="guru-modal-overlay" data-step-import-modal hidden>
        <div class="guru-modal" role="dialog" aria-modal="true" aria-labelledby="step-import-modal-title">
            <div class="guru-modal__head">
                <div>
                    <span>Bulk Import</span>
                    <h2 id="step-import-modal-title">Tambah Banyak (Langkah Pembelajaran)</h2>
                </div>
                <button type="button" class="guru-icon-button" data-close-guru-modal aria-label="Tutup modal">
                    <span class="material-symbols-outlined" aria-hidden="true">close</span>
                </button>
            </div>
            <form class="guru-unit-form" data-step-import-form>
                <div style="margin-bottom: 16px; background-color: rgba(var(--accent-rgb), 0.05); padding: 12px; border-radius: 8px; border-left: 4px solid var(--accent);">
                    <p style="margin: 0; font-size: 14px; color: var(--text-main);">Silakan unggah file Excel yang berisi daftar langkah pembelajaran.</p>
                </div>
                <div class="guru-upload-zone" id="upload-zone" aria-label="Area seret dan lepas berkas Excel">
                    <input name="file" type="file" id="file-input" accept=".xlsx,.xls" required style="display: none;">
                    <div class="guru-upload-zone__content" id="upload-zone-content">
                        <span class="material-symbols-outlined guru-upload-zone__icon" aria-hidden="true">upload</span>
                        <p><strong>Click to upload</strong> or drag and drop file here</p>
                        <span>Maximum file size 10MB and must be .xlsx or .xls format</span>
                    </div>
                    <div class="guru-upload-zone__file-name" id="file-name-display" hidden>
                        <span class="material-symbols-outlined" aria-hidden="true">description</span>
                        <span id="file-name-text">filename.xlsx</span>
                        <button type="button" class="guru-upload-zone__clear" id="file-clear-btn" aria-label="Hapus file">&times;</button>
                    </div>
                </div>
                <p class="guru-unit-form__error" data-step-import-error hidden></p>
                <div class="guru-modal__actions" style="margin-top: 24px; display: flex; justify-content: space-between; align-items: center; width: 100%;">
                    <a href="{{ route('guru.steps.import-template') }}" class="guru-unit-button" style="background-color: var(--background); border: 1px solid var(--border); color: var(--text-main); display: inline-flex; align-items: center; justify-content: center; gap: 4px; text-decoration: none;">
                        <span class="material-symbols-outlined" aria-hidden="true">download</span>Download Template Excel
                    </a>
                    <div style="display: flex; gap: 8px;">
                        <button type="button" class="guru-unit-button" data-close-guru-modal>Batal</button>
                        <button type="submit" class="guru-unit-button guru-unit-button--primary" data-step-import-submit>Unggah & Import</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
