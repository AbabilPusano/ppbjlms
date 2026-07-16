@extends('layouts.lms-ui', ['title' => 'Kelola Unit - LMS PPBJ'])

@section('body')
<div class="guru-shell" data-guru-units>
    <header class="guru-topbar">
        <button type="button" class="guru-hamburger" data-guru-menu aria-label="Toggle menu">
            <span class="material-symbols-outlined" aria-hidden="true">menu</span>
        </button>
        <div>
            <p>Unit Pembelajaran</p>
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
            <a href="#" data-ui-only><span class="material-symbols-outlined">checklist</span>Penilaian</a>
            <a href="#" data-ui-only><span class="material-symbols-outlined">settings</span>Pengaturan</a>
        </nav>

        <div class="guru-back">
            <a href="{{ url('/') }}"><span class="material-symbols-outlined">arrow_back</span>Kembali ke LMS</a>
        </div>
    </aside>

    <main class="guru-main guru-units-main">
        <div class="guru-heading guru-units-heading">
            <div>
                <h1>KELOLA UNIT PEMBELAJARAN</h1>
                <p>Atur unit, materi pembelajaran, quiz, dan soal dari satu halaman.</p>
            </div>
            <div class="guru-units-toolbar" style="display: flex; gap: 8px; align-items: center;">
                <button type="button" class="guru-unit-button guru-unit-button--danger" data-delete-selected style="display: none; align-items: center; justify-content: center; gap: 4px;">
                    <span class="material-symbols-outlined" aria-hidden="true">delete_sweep</span>
                    HAPUS TERPILIH (<span data-selected-count>0</span>)
                </button>
                <button type="button" class="guru-unit-button guru-unit-button--primary" data-open-unit-modal>
                    <span class="material-symbols-outlined" aria-hidden="true">add</span>
                    TAMBAH UNIT
                </button>
                <div class="guru-level-select-wrapper">
                    <span class="material-symbols-outlined" aria-hidden="true">tune</span>
                    <select class="guru-level-select" data-select-level-inline aria-label="Pilih Level"></select>
                </div>
            </div>
        </div>

        <section class="guru-units-summary" aria-label="Ringkasan unit">
            <div>
                <span>Level aktif</span>
                <strong data-active-level>N5</strong>
            </div>
            <div>
                <span>Jumlah unit</span>
                <strong data-unit-count>0</strong>
            </div>
            <div>
                <span>Isi unit</span>
                <strong data-material-count>0</strong>
            </div>
            <div>
                <span>Quiz</span>
                <strong data-quiz-count>0</strong>
            </div>
        </section>

        <section class="guru-units-state" data-units-loading>
            <span class="material-symbols-outlined" aria-hidden="true">progress_activity</span>
            <p>Memuat unit dari database...</p>
        </section>

        <section class="guru-units-state guru-units-state--error" data-units-error hidden>
            <span class="material-symbols-outlined" aria-hidden="true">error</span>
            <p data-units-error-message>Gagal memuat unit.</p>
            <button type="button" class="guru-unit-button" data-retry-units>Coba lagi</button>
        </section>

        <section class="guru-units-state" data-units-empty hidden>
            <span class="material-symbols-outlined" aria-hidden="true">category</span>
            <p>Belum ada unit untuk level ini.</p>
            <button type="button" class="guru-unit-button guru-unit-button--primary" data-open-unit-modal>Tambah unit pertama</button>
        </section>

        <div class="guru-select-all-container" data-select-all-container style="display: none; align-items: center; justify-content: space-between; gap: 8px; margin-bottom: 12px; background-color: var(--card-bg, #fff); padding: 12px 16px; border-radius: 8px; border: 1px solid var(--border, #eaecf0);">
            <label for="select-all-units" style="cursor: pointer; font-size: 14px; font-weight: 500; color: var(--text-main);">Pilih Semua Unit</label>
            <input type="checkbox" id="select-all-units" style="width: 18px; height: 18px; cursor: pointer; accent-color: var(--accent);">
        </div>

        <section class="guru-unit-list" data-unit-list aria-label="Daftar unit"></section>
    </main>

    <div class="guru-modal-overlay" data-unit-modal hidden>
        <div class="guru-modal" role="dialog" aria-modal="true" aria-labelledby="unit-modal-title">
            <div class="guru-modal__head">
                <div>
                    <span>Unit</span>
                    <h2 id="unit-modal-title" data-unit-modal-title>Tambah Unit</h2>
                </div>
                <button type="button" class="guru-icon-button" data-close-guru-modal aria-label="Tutup modal">
                    <span class="material-symbols-outlined" aria-hidden="true">close</span>
                </button>
            </div>
            <form class="guru-unit-form" data-unit-form>
                <input type="hidden" name="id" data-unit-id>
                <label>
                    <span>Level</span>
                    <select name="level_id" data-unit-level required></select>
                </label>
                <label>
                    <span>Judul</span>
                    <input name="title" type="text" maxlength="191" required>
                </label>
                <label>
                    <span>Deskripsi</span>
                    <textarea name="description" rows="4"></textarea>
                </label>
                <input type="hidden" name="category_japanese" value="学習">
                <input type="hidden" name="category_romaji" value="gakushu">
                <input type="hidden" name="sort_order" value="">
                <input type="hidden" name="is_active" value="1">
                <p class="guru-unit-form__error" data-unit-form-error hidden></p>
                <div class="guru-modal__actions">
                    <button type="button" class="guru-unit-button" data-close-guru-modal>Batal</button>
                    <button type="submit" class="guru-unit-button guru-unit-button--primary" data-unit-submit>Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <div class="guru-modal-overlay" data-material-modal hidden>
        <div class="guru-modal" role="dialog" aria-modal="true" aria-labelledby="material-modal-title">
            <div class="guru-modal__head">
                <div>
                    <span>Isi Unit</span>
                    <h2 id="material-modal-title" data-material-modal-title>Tambah Isi Unit</h2>
                </div>
                <button type="button" class="guru-icon-button" data-close-guru-modal aria-label="Tutup modal">
                    <span class="material-symbols-outlined" aria-hidden="true">close</span>
                </button>
            </div>
            <form class="guru-unit-form" data-material-form>
                <input type="hidden" name="id" data-material-id>
                <input type="hidden" name="level_id" data-material-level>
                <input type="hidden" name="type" value="lesson">
                <input type="hidden" name="order" value="">
                <label>
                    <span>Unit Induk</span>
                    <select name="unit_id" data-material-unit required></select>
                </label>
                <label>
                    <span>Judul</span>
                    <input name="title" type="text" maxlength="191" required>
                </label>
                <p class="guru-unit-form__error" data-material-form-error hidden></p>
                <div class="guru-modal__actions">
                    <button type="button" class="guru-unit-button" data-close-guru-modal>Batal</button>
                    <button type="submit" class="guru-unit-button guru-unit-button--primary" data-material-submit>Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <div class="guru-modal-overlay" data-material-detail-modal hidden>
        <div class="guru-modal guru-modal--small" role="dialog" aria-modal="true" aria-labelledby="material-detail-title">
            <div class="guru-modal__head">
                <div>
                    <span>Detail Materi</span>
                    <h2 id="material-detail-title" data-material-detail-title>Materi Pembelajaran</h2>
                </div>
                <button type="button" class="guru-icon-button" data-close-guru-modal aria-label="Tutup modal">
                    <span class="material-symbols-outlined" aria-hidden="true">close</span>
                </button>
            </div>
            <div class="guru-material-detail">
                <dl>
                    <div>
                        <dt>Tipe</dt>
                        <dd data-material-detail-type>-</dd>
                    </div>
                    <div>
                        <dt>Unit Induk</dt>
                        <dd data-material-detail-unit>-</dd>
                    </div>
                    <div>
                        <dt>Urutan</dt>
                        <dd data-material-detail-order>-</dd>
                    </div>
                </dl>
                <p>Konten detail materi bisa disambungkan ke editor rich text pada tahap berikutnya.</p>
            </div>
        </div>
    </div>

    <div class="guru-modal-overlay" data-level-modal hidden>
        <div class="guru-modal guru-modal--small" role="dialog" aria-modal="true" aria-labelledby="level-modal-title">
            <div class="guru-modal__head">
                <div>
                    <span>Level</span>
                    <h2 id="level-modal-title">Pilih Level</h2>
                </div>
                <button type="button" class="guru-icon-button" data-close-guru-modal aria-label="Tutup modal">
                    <span class="material-symbols-outlined" aria-hidden="true">close</span>
                </button>
            </div>
            <div class="guru-level-options" data-level-options></div>
        </div>
    </div>
</div>
@endsection
