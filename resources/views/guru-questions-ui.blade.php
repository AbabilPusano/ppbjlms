@extends('layouts.lms-ui', ['title' => 'Kelola Langkah Pembelajaran - LMS PPBJ'])

@section('body')
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
            <a href="#" data-ui-only><span class="material-symbols-outlined">groups</span>Siswa</a>
            <a href="#" data-ui-only><span class="material-symbols-outlined">fact_check</span>Penilaian</a>
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
            <div class="guru-units-toolbar">
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
</div>
@endsection
