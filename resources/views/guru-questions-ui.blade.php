@extends('layouts.lms-ui', ['title' => 'Kelola Langkah Pembelajaran - LMS PPBJ'])

@section('body')
<style>
.guru-unit-button:disabled {
    opacity: 0.55 !important;
    background-color: #98a2b3 !important;
    border-color: #98a2b3 !important;
    color: #ffffff !important;
    cursor: not-allowed !important;
}
.guru-search-select-wrapper {
    position: relative;
    width: 100%;
}
.guru-search-select-control {
    position: relative;
    display: flex;
    align-items: center;
    border: 1px solid #d6d0c5;
    border-radius: 6px;
    background: #fff;
    padding-right: 8px;
}
.guru-search-select-search {
    width: 100%;
    border: none !important;
    outline: none !important;
    padding: 8px 12px;
    border-radius: 6px;
    font-size: 14px;
}
.guru-search-select-arrow {
    color: #667085;
    cursor: pointer;
    user-select: none;
}
.guru-search-select-dropdown {
    position: absolute;
    top: 100%;
    left: 0;
    width: 100%;
    max-height: 220px;
    background: #fff;
    border: 1px solid #d6d0c5;
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    z-index: 1000;
    margin-top: 4px;
    display: flex;
    flex-direction: column;
}
.guru-search-select-options {
    overflow-y: auto;
    max-height: 180px;
    display: flex;
    flex-direction: column;
}
.guru-search-select-option {
    padding: 10px 12px;
    font-size: 14px;
    cursor: pointer;
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-bottom: 1px solid #f2f4f7;
}
.guru-search-select-option:hover,
.guru-search-select-option.is-highlighted {
    background: #f0edf9 !important;
}
.guru-search-select-option.is-selected {
    background: #e1dbf7 !important;
    color: var(--accent);
    font-weight: 600;
}
.guru-search-select-loading {
    padding: 10px;
    text-align: center;
    font-size: 12px;
    color: #667085;
    background: #f9fafb;
}
.guru-question-card {
    position: relative;
}
.guru-step-inline-add {
    position: absolute;
    right: 16px;
    bottom: -16px;
    display: none;
    background: var(--accent, #6366f1);
    color: white !important;
    border: none;
    border-radius: 50%;
    width: 28px;
    height: 28px;
    cursor: pointer;
    align-items: center;
    justify-content: center;
    box-shadow: 0 3px 6px rgba(0,0,0,0.16);
    z-index: 10;
    transition: all 0.2s ease;
}
.guru-step-inline-add:hover {
    transform: scale(1.15);
    background: #4f46e5;
}
.guru-question-card:hover .guru-step-inline-add {
    display: inline-flex;
}
.guru-material-type--character {
    background: rgba(245, 158, 11, 0.1) !important;
    color: #d97706 !important;
}
.guru-material-type--vocabulary {
    background: rgba(16, 185, 129, 0.1) !important;
    color: #059669 !important;
}
.guru-material-type--text {
    background: rgba(107, 114, 128, 0.1) !important;
    color: #4b5563 !important;
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
            <a href="{{ route('guru.stories.index') }}"><span class="material-symbols-outlined">menu_book</span>Kelola Cerita</a>
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
                <div data-question-stats style="font-size: 13px; color: #667085; margin-top: 6px; display: flex; gap: 16px;">
                    <span>Total Huruf Baru: <strong data-stat-char>0</strong></span>
                    <span>Total Kata Baru: <strong data-stat-vocab>0</strong></span>
                    <span>Total Kuis: <strong data-stat-quiz>0</strong></span>
                </div>
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

        <div class="guru-student-filter guru-step-filter" data-step-filter hidden>
            <div class="guru-student-filter__title">
                <span class="material-symbols-outlined" aria-hidden="true">filter_list</span>
                <div>
                    <span>Filter Jenis Langkah</span>
                    <strong data-step-filter-label>Semua Jenis</strong>
                </div>
            </div>

            <div class="guru-student-filter__form">
                <label class="guru-student-filter__select">
                    <span>Jenis Langkah</span>
                    <select data-step-type-filter aria-label="Filter berdasarkan jenis langkah">
                        <option value="">Semua Jenis</option>
                        <option value="text">Teks Pengantar</option>
                        <option value="character">Huruf Baru</option>
                        <option value="vocabulary">Kata Baru</option>
                        <option value="quiz">Pertanyaan</option>
                    </select>
                    <span class="material-symbols-outlined" aria-hidden="true">expand_more</span>
                </label>

                <button type="button" class="guru-student-filter__reset guru-step-filter__reset" data-reset-step-filter hidden>
                    <span class="material-symbols-outlined" aria-hidden="true">close</span>
                    Reset
                </button>
            </div>
        </div>

        <section class="guru-units-state guru-step-filter-empty" data-questions-filter-empty hidden>
            <span class="material-symbols-outlined" aria-hidden="true">filter_alt_off</span>
            <p>Tidak ada langkah dengan jenis yang dipilih.</p>
            <button type="button" class="guru-unit-button" data-reset-step-filter>Tampilkan Semua Jenis</button>
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
                        <option value="quiz">Pertanyaan</option>
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
                <div data-question-fields="character" hidden style="display: flex; flex-direction: column; gap: 16px;">
                    <div class="guru-unit-form__grid">
                        <label>
                            <span>Pilih Huruf Referensi</span>
                            <div class="guru-search-select-wrapper" id="step-character-select" data-character-select>
                                <div class="guru-search-select-control">
                                    <input type="text" class="guru-search-select-search" placeholder="Cari Huruf (Simbol / Romaji)..." autocomplete="off">
                                    <span class="material-symbols-outlined guru-search-select-arrow">arrow_drop_down</span>
                                </div>
                                <div class="guru-search-select-dropdown" style="display: none;">
                                    <div class="guru-search-select-options"></div>
                                    <div class="guru-search-select-loading" style="display: none;">Memuat lebih banyak...</div>
                                </div>
                                <input type="hidden" name="character_symbol" class="guru-search-select-value">
                            </div>
                        </label>
                        <label>
                            <span>Cara Baca (Romaji/Arti)</span>
                            <input name="character_pronunciation" type="text" readonly style="background: #f5f5f5; cursor: not-allowed; border: 1px solid var(--border); padding: 8px; border-radius: 6px;" placeholder="Pilih huruf terlebih dahulu">
                        </label>
                    </div>
                </div>

                <!-- VOCABULARY STEP FIELDS -->
                <div data-question-fields="vocabulary" hidden style="display: flex; flex-direction: column; gap: 16px;">
                    <!-- Tampilkan Kanji Checkbox -->
                    <div id="vocab-tampilkan-kanji-container" style="display: none; align-items: center; gap: 8px; margin-bottom: 4px;">
                        <input type="checkbox" name="tampilkan_kanji" id="vocab-tampilkan-kanji" value="1" checked style="width: 16px; height: 16px; cursor: pointer; accent-color: var(--accent);">
                        <label for="vocab-tampilkan-kanji" style="font-size: 14px; font-weight: 500; color: var(--text-main); cursor: pointer;">Tampilkan Kanji</label>
                    </div>
                    <!-- Search Section -->
                    <div id="vocab-search-section" style="display: flex; flex-direction: column; gap: 12px;">
                        <div class="guru-unit-form__grid">
                            <label>
                                <span>Pilih Kosakata Referensi</span>
                                <div class="guru-search-select-wrapper" id="step-kata-select" data-kata-select>
                                    <div class="guru-search-select-control">
                                        <input type="text" class="guru-search-select-search" placeholder="Cari Kosakata (Jepang / Romaji / Arti)..." autocomplete="off">
                                        <span class="material-symbols-outlined guru-search-select-arrow">arrow_drop_down</span>
                                    </div>
                                    <div class="guru-search-select-dropdown" style="display: none;">
                                        <div class="guru-search-select-options"></div>
                                        <div class="guru-search-select-loading" style="display: none;">Memuat lebih banyak...</div>
                                    </div>
                                    <input type="hidden" name="vocab_selected_id" class="guru-search-select-value">
                                </div>
                            </label>
                            <label>
                                <span>Detail Kosakata (Romaji / Arti)</span>
                                <input id="vocab-search-detail" type="text" readonly style="background: #f5f5f5; cursor: not-allowed; border: 1px solid var(--border); padding: 8px; border-radius: 6px;" placeholder="Pilih kosakata terlebih dahulu">
                            </label>
                        </div>
                        <div style="display: flex; justify-content: flex-end;">
                            <button type="button" id="vocab-btn-create-new" class="guru-unit-button" style="font-size: 12px; padding: 4px 12px; background: var(--paper-soft);">
                                <span class="material-symbols-outlined" style="font-size: 16px; vertical-align: middle; margin-right: 4px;">add</span>Buat Kata Baru
                            </button>
                        </div>
                    </div>

                    <!-- Manual Section (Initially Hidden) -->
                    <div id="vocab-manual-section" style="display: none; flex-direction: column; gap: 16px;">
                        <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #e6e1d6; padding-bottom: 8px;">
                            <span style="font-weight: 600; color: var(--ink);">Definisi Kosakata Baru</span>
                            <button type="button" id="vocab-btn-back-to-search" class="guru-unit-button" style="font-size: 12px; padding: 4px 12px; background: transparent; border: none; color: var(--accent);">
                                <span class="material-symbols-outlined" style="font-size: 16px; vertical-align: middle; margin-right: 4px;">search</span>Cari Kosakata Lain
                            </button>
                        </div>
                        <div class="guru-unit-form__grid">
                            <label>
                                <span>Kosakata (Jepang)</span>
                                <input name="vocab_kata" type="text" placeholder="Contoh: 食べる">
                            </label>
                            <label>
                                <span>Kana (Hiragana / Katakana)</span>
                                <input name="vocab_kana" type="text" placeholder="Contoh: たべる">
                            </label>
                        </div>
                        <div class="guru-unit-form__grid">
                            <label>
                                <span>Cara Baca (Romaji)</span>
                                <input name="vocab_romaji" type="text" placeholder="Contoh: taberu">
                            </label>
                            <label>
                                <span>Arti / Terjemahan</span>
                                <input name="vocab_arti" type="text" placeholder="Contoh: Makan">
                            </label>
                        </div>
                        <div class="guru-unit-form__grid">
                            <label>
                                <span>Hubungkan ke Kanji (Opsional)</span>
                                <div class="guru-search-select-wrapper" id="step-kanji-select" data-kanji-select>
                                    <div class="guru-search-select-control">
                                        <input type="text" class="guru-search-select-search" placeholder="Cari Kanji (Karakter / Makna)..." autocomplete="off">
                                        <span class="material-symbols-outlined guru-search-select-arrow">arrow_drop_down</span>
                                    </div>
                                    <div class="guru-search-select-dropdown" style="display: none;">
                                        <div class="guru-search-select-options"></div>
                                        <div class="guru-search-select-loading" style="display: none;">Memuat lebih banyak...</div>
                                    </div>
                                    <input type="hidden" name="vocab_kanji_id" class="guru-search-select-value">
                                </div>
                                <div id="step-kanji-warning" class="kanji-warning-container" style="color: #ef4444; font-size: 12px; margin-top: 6px; display: flex; flex-direction: column; gap: 4px;"></div>
                            </label>
                            <label>
                                <span>Tipe Kata (Opsional)</span>
                                <input name="vocab_tipe_kata" type="text" placeholder="Contoh: Kata Kerja">
                            </label>
                        </div>
                        <div class="guru-unit-form__grid">
                            <label>
                                <span>Level (Opsional)</span>
                                <select name="vocab_level" style="background: #fff; border: 1px solid #d6d0c5; padding: 8px; border-radius: 6px; width: 100%; box-sizing: border-box;">
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
                </div>

                <!-- QUIZ TYPE SELECT -->
                <div data-question-fields="quiz" hidden style="display: flex; flex-direction: column; gap: 16px;">
                    <label>
                        <span>Jenis Pertanyaan</span>
                        <select name="quiz_question_type" data-quiz-question-type style="background: #fff; border: 1px solid #d6d0c5; padding: 8px; border-radius: 6px; width: 100%; box-sizing: border-box;">
                            <option value="character" selected>Huruf</option>
                            <option value="vocabulary">Kata</option>
                            <option value="grammar" disabled>Grammar (Belum Tersedia)</option>
                            <option value="sentence" disabled>Kalimat (Belum Tersedia)</option>
                            <option value="random">Random (Acak)</option>
                        </select>
                    </label>
 
                    <div data-quiz-question-fields="character" style="display: flex; flex-direction: column; gap: 16px;">
                        <label>
                            <span>Pilih Huruf Referensi (Kuis)</span>
                            <div class="guru-search-select-wrapper" id="quiz-character-select" data-character-select>
                                <div class="guru-search-select-control">
                                    <input type="text" class="guru-search-select-search" placeholder="Cari Huruf (Simbol / Romaji)..." autocomplete="off">
                                    <span class="material-symbols-outlined guru-search-select-arrow">arrow_drop_down</span>
                                </div>
                                <div class="guru-search-select-dropdown" style="display: none;">
                                    <div class="guru-search-select-options"></div>
                                    <div class="guru-search-select-loading" style="display: none;">Memuat lebih banyak...</div>
                                </div>
                                <input type="hidden" name="quiz_character_symbol" class="guru-search-select-value">
                                <input type="hidden" name="quiz_character_pronunciation" class="guru-search-select-pronunciation" id="quiz-character-pronunciation">
                            </div>
                        </label>
                    </div>

                    <div data-quiz-question-fields="vocabulary" style="display: none; flex-direction: column; gap: 16px;">
                        <label>
                            <span>Pilih Kata Referensi (Kuis)</span>
                            <div class="guru-search-select-wrapper" id="quiz-kata-select" data-kata-select>
                                <div class="guru-search-select-control">
                                    <input type="text" class="guru-search-select-search" placeholder="Cari Kosakata (Jepang / Romaji / Arti)..." autocomplete="off">
                                    <span class="material-symbols-outlined guru-search-select-arrow">arrow_drop_down</span>
                                </div>
                                <div class="guru-search-select-dropdown" style="display: none;">
                                    <div class="guru-search-select-options"></div>
                                    <div class="guru-search-select-loading" style="display: none;">Memuat lebih banyak...</div>
                                </div>
                                <input type="hidden" name="quiz_kata_id" class="guru-search-select-value">
                                <input type="hidden" name="quiz_kata_symbol" id="quiz-kata-symbol">
                                <input type="hidden" name="quiz_kata_pronunciation" id="quiz-kata-pronunciation">
                                <input type="hidden" name="quiz_kata_meaning" id="quiz-kata-meaning">
                            </div>
                        </label>
                        <!-- Checkbox Tunjukkan Kanji Kuis -->
                        <div id="quiz-tampilkan-kanji-container" style="display: none; align-items: center; gap: 8px; margin-top: 4px;">
                            <input type="checkbox" name="quiz_tampilkan_kanji" id="quiz-tampilkan-kanji" value="1" checked style="width: 16px; height: 16px; cursor: pointer; accent-color: var(--accent);">
                            <label for="quiz-tampilkan-kanji" style="font-size: 14px; font-weight: 500; color: var(--text-main); cursor: pointer;">Tunjukkan kanji jika ada</label>
                        </div>
                    </div>

                    <div id="quiz-type-container" style="display: none; flex-direction: column; gap: 16px;">
                        <label>
                            <span>Tipe Kuis</span>
                            <select name="quiz_type" data-quiz-type style="background: #fff; border: 1px solid #d6d0c5; padding: 8px; border-radius: 6px; width: 100%; box-sizing: border-box;">
                                <option value="">-- Pilih Tipe Kuis --</option>
                                <option value="romaji_to_kana">Romaji -> Kana</option>
                                <option value="kana_to_romaji">Kana -> Romaji</option>
                            </select>
                        </label>
                    </div>

                    <!-- MULTIPLE CHOICE FIELDS -->
                    <div data-quiz-fields="multiple_choice" style="margin-top: 16px; display: flex; flex-direction: column; gap: 16px;">
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
                            <select name="quiz_correct_index" style="background: #fff; border: 1px solid var(--border); padding: 8px; border-radius: 6px;">
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
