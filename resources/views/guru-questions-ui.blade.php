@extends('layouts.lms-ui', ['title' => 'Kelola Soal Quiz - LMS PPBJ'])

@section('body')
<div class="guru-shell" data-guru-questions data-material-id="{{ request()->route('material') }}">
    <header class="guru-topbar">
        <button type="button" class="guru-hamburger" data-guru-menu aria-label="Toggle menu">
            <span class="material-symbols-outlined" aria-hidden="true">menu</span>
        </button>
        <div>
            <p>Kelola Soal</p>
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
                <h1 data-question-title>LIST SOAL</h1>
                <p data-question-subtitle>Memuat quiz...</p>
            </div>
            <div class="guru-units-toolbar">
                <button type="button" class="guru-unit-button guru-unit-button--primary" data-open-question-modal>
                    <span class="material-symbols-outlined" aria-hidden="true">add</span>
                    TAMBAH SOAL
                </button>
                <button type="button" class="guru-unit-button" data-export-questions>
                    <span class="material-symbols-outlined" aria-hidden="true">file_download</span>
                    EXPORT XLSX
                </button>
                <button type="button" class="guru-unit-button" data-import-questions>
                    <span class="material-symbols-outlined" aria-hidden="true">file_upload</span>
                    IMPORT XLSX
                </button>
                <button type="button" class="guru-unit-button" data-question-template>
                    <span class="material-symbols-outlined" aria-hidden="true">description</span>
                    TEMPLATE
                </button>
            </div>
        </div>

        <section class="guru-units-state" data-questions-loading>
            <span class="material-symbols-outlined" aria-hidden="true">progress_activity</span>
            <p>Memuat soal quiz...</p>
        </section>

        <section class="guru-units-state guru-units-state--error" data-questions-error hidden>
            <span class="material-symbols-outlined" aria-hidden="true">error</span>
            <p data-questions-error-message>Gagal memuat soal.</p>
            <button type="button" class="guru-unit-button" data-retry-questions>Coba lagi</button>
        </section>

        <section class="guru-units-state" data-questions-empty hidden>
            <span class="material-symbols-outlined" aria-hidden="true">quiz</span>
            <p>Belum ada soal untuk quiz ini.</p>
            <button type="button" class="guru-unit-button guru-unit-button--primary" data-open-question-modal>Tambah soal pertama</button>
        </section>

        <p class="guru-tool-note" data-question-tool-note hidden>Export, import, dan template disiapkan sebagai aksi MVP. Integrasi XLSX penuh bisa disambungkan berikutnya.</p>

        <section class="guru-question-list" data-question-list aria-label="Daftar soal"></section>
    </main>

    <div class="guru-modal-overlay" data-question-modal hidden>
        <div class="guru-modal" role="dialog" aria-modal="true" aria-labelledby="question-modal-title">
            <div class="guru-modal__head">
                <div>
                    <span>Soal Quiz</span>
                    <h2 id="question-modal-title" data-question-modal-title>Tambah Soal</h2>
                </div>
                <button type="button" class="guru-icon-button" data-close-guru-modal aria-label="Tutup modal">
                    <span class="material-symbols-outlined" aria-hidden="true">close</span>
                </button>
            </div>
            <form class="guru-unit-form" data-question-form>
                <input type="hidden" name="id" data-question-id>
                <label>
                    <span>Tipe Soal</span>
                    <select name="type" data-question-type required>
                        <option value="new-letter">Huruf Baru</option>
                        <option value="multiple-choice">Pilihan Ganda</option>
                    </select>
                </label>
                <div data-question-fields="new-letter">
                    <div class="guru-unit-form__grid">
                        <label>
                            <span>Huruf</span>
                            <input name="letter" type="text" maxlength="20">
                        </label>
                        <label>
                            <span>Cara Baca</span>
                            <input name="reading" type="text" maxlength="50">
                        </label>
                    </div>
                </div>
                <div data-question-fields="multiple-choice" hidden>
                    <label>
                        <span>Pertanyaan</span>
                        <textarea name="question_text" rows="3"></textarea>
                    </label>
                    <label>
                        <span>Huruf yang Dibahas</span>
                        <input name="choice_letter" type="text" maxlength="20">
                    </label>
                    <div class="guru-unit-form__grid">
                        <label>
                            <span>Pilihan A</span>
                            <input name="option_a" type="text" maxlength="191">
                        </label>
                        <label>
                            <span>Pilihan B</span>
                            <input name="option_b" type="text" maxlength="191">
                        </label>
                        <label>
                            <span>Pilihan C</span>
                            <input name="option_c" type="text" maxlength="191">
                        </label>
                        <label>
                            <span>Pilihan D</span>
                            <input name="option_d" type="text" maxlength="191">
                        </label>
                    </div>
                    <label>
                        <span>Jawaban Benar</span>
                        <select name="correct_answer">
                            <option value="A">A</option>
                            <option value="B">B</option>
                            <option value="C">C</option>
                            <option value="D">D</option>
                        </select>
                    </label>
                </div>
                <label>
                    <span>Urutan</span>
                    <input name="order" type="number" min="0" step="1">
                </label>
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
