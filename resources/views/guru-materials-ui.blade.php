@extends('layouts.lms-ui', ['title' => 'Kelola Materi - LMS PPBJ'])

@section('body')
<script>
    window.currentUnitId = "{{ $unit->id }}";
    window.currentLevelId = "{{ $unit->level_id }}";
</script>
<div class="guru-shell" data-guru-materials>
    <header class="guru-topbar">
        <button type="button" class="guru-hamburger" data-guru-menu aria-label="Toggle menu">
            <span class="material-symbols-outlined" aria-hidden="true">menu</span>
        </button>
        <div>
            <p>Materi Pembelajaran</p>
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
            <a href="{{ url('/') }}"><span class="material-symbols-outlined">arrow_back</span>Kembali ke LMS</a>
        </div>
    </aside>

    <main class="guru-main guru-units-main guru-materials-main">
        <div class="guru-heading guru-units-heading">
            <div>
                <h1>KELOLA ISI UNIT: {{ $unit->title }}</h1>
                <p>Level: {{ $unit->level->name }} | {{ $unit->description }}</p>
            </div>
            <div class="guru-units-toolbar">
                <a href="{{ route('guru.units.index') }}" class="guru-unit-button">
                    <span class="material-symbols-outlined" aria-hidden="true">arrow_back</span>
                    KEMBALI KE UNIT
                </a>
                <button type="button" class="guru-unit-button guru-unit-button--primary" data-open-material-modal>
                    <span class="material-symbols-outlined" aria-hidden="true">add</span>
                    TAMBAH ISI UNIT
                </button>
            </div>
        </div>

        <section class="guru-units-state" data-materials-loading>
            <span class="material-symbols-outlined" aria-hidden="true">progress_activity</span>
            <p>Memuat materi pembelajaran...</p>
        </section>

        <section class="guru-units-state guru-units-state--error" data-materials-error hidden>
            <span class="material-symbols-outlined" aria-hidden="true">error</span>
            <p data-materials-error-message>Gagal memuat materi.</p>
            <button type="button" class="guru-unit-button" data-retry-materials>Coba lagi</button>
        </section>

        <section class="guru-units-state" data-materials-empty hidden>
            <span class="material-symbols-outlined" aria-hidden="true">menu_book</span>
            <p>Belum ada isi unit pada unit ini.</p>
            <button type="button" class="guru-unit-button guru-unit-button--primary" data-open-material-modal>Tambah isi unit pertama</button>
        </section>

        <section class="guru-units-list" data-material-unit-list aria-label="Daftar materi pembelajaran"></section>
    </main>

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
                <input type="hidden" name="level_id" data-material-level value="{{ $unit->level_id }}">
                <input type="hidden" name="unit_id" data-material-unit value="{{ $unit->id }}">
                <input type="hidden" name="type" value="lesson">
                <input type="hidden" name="order" value="">
                <label>
                    <span>Judul</span>
                    <input name="title" type="text" maxlength="191" required placeholder="Contoh: Kolom A">
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

    <div class="guru-modal-overlay" data-material-level-modal hidden>
        <div class="guru-modal guru-modal--small" role="dialog" aria-modal="true" aria-labelledby="material-level-modal-title">
            <div class="guru-modal__head">
                <div>
                    <span>Level</span>
                    <h2 id="material-level-modal-title">Pilih Level</h2>
                </div>
                <button type="button" class="guru-icon-button" data-close-guru-modal aria-label="Tutup modal">
                    <span class="material-symbols-outlined" aria-hidden="true">close</span>
                </button>
            </div>
            <div class="guru-level-options" data-material-level-options></div>
        </div>
    </div>
</div>
@endsection
