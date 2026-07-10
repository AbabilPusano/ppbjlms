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
            <a href="{{ route('guru.materials.index') }}"><span class="material-symbols-outlined">account_tree</span>Materi</a>
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
                <h1>KELOLA UNIT</h1>
                <p>Atur unit pembelajaran berdasarkan level aktif dan urutan tampilnya.</p>
            </div>
            <div class="guru-units-toolbar">
                <button type="button" class="guru-unit-button guru-unit-button--primary" data-open-unit-modal>
                    <span class="material-symbols-outlined" aria-hidden="true">add</span>
                    TAMBAH UNIT
                </button>
                <button type="button" class="guru-unit-button" data-open-level-modal>
                    <span class="material-symbols-outlined" aria-hidden="true">tune</span>
                    PILIH LEVEL
                </button>
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
                <div class="guru-unit-form__grid">
                    <label>
                        <span>Kategori Jepang</span>
                        <input name="category_japanese" type="text" maxlength="191" required>
                    </label>
                    <label>
                        <span>Romaji</span>
                        <input name="category_romaji" type="text" maxlength="191" required>
                    </label>
                </div>
                <div class="guru-unit-form__grid">
                    <label>
                        <span>Urutan</span>
                        <input name="sort_order" type="number" min="0" step="1">
                    </label>
                    <label class="guru-unit-toggle">
                        <input name="is_active" type="checkbox" checked>
                        <span>Aktif</span>
                    </label>
                </div>
                <p class="guru-unit-form__error" data-unit-form-error hidden></p>
                <div class="guru-modal__actions">
                    <button type="button" class="guru-unit-button" data-close-guru-modal>Batal</button>
                    <button type="submit" class="guru-unit-button guru-unit-button--primary" data-unit-submit>Simpan</button>
                </div>
            </form>
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
