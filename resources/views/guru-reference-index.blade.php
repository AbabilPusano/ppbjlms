@extends('layouts.lms-ui', ['title' => 'Bank Referensi - LMS PPBJ'])

@section('body')
@php($currentUser = auth()->user())
@php($currentRoleLabel = $currentUser?->role === 'admin' ? 'Admin' : 'Guru')

<div class="guru-shell">
    <header class="guru-topbar">
        <button type="button" class="guru-hamburger" data-guru-menu aria-label="Toggle menu">
            <span class="material-symbols-outlined" aria-hidden="true">menu</span>
        </button>
        <div>
            <p>Bank Referensi</p>
        </div>
        <div class="guru-user">
            <div>
                <p>{{ $currentUser?->name ?? $currentRoleLabel }}</p>
                <span>{{ $currentRoleLabel }}</span>
            </div>
            <strong>{{ strtoupper(substr($currentUser?->name ?? $currentRoleLabel, 0, 1)) }}</strong>
        </div>
    </header>

    <aside class="guru-sidebar">
        <div class="guru-logo">
            <span class="material-symbols-outlined" aria-hidden="true">school</span>
            <strong>GURU LMS</strong>
        </div>

        <p class="guru-menu-label">Menu Utama</p>
        <nav class="guru-nav" aria-label="Navigasi {{ strtolower($currentRoleLabel) }}">
            <a href="{{ route('guru.dashboard') }}"><span class="material-symbols-outlined">home</span>Dashboard</a>
            <a href="{{ route('guru.units.index') }}"><span class="material-symbols-outlined">category</span>Unit Pembelajaran</a>
            @include('partials.admin-account-nav')
            <a href="{{ route('guru.penilaian.index') }}"><span class="material-symbols-outlined">checklist</span>Penilaian</a>
            <a href="{{ route('guru.referensi.index') }}" class="active"><span class="material-symbols-outlined">database</span>Bank Referensi<i></i></a>
            <a href="#" data-ui-only><span class="material-symbols-outlined">settings</span>Pengaturan</a>
        </nav>

        <div class="guru-back">
            <a href="{{ url('/') }}"><span class="material-symbols-outlined">arrow_back</span>Kembali ke LMS</a>
        </div>
    </aside>

    <main class="guru-main guru-admin-main">
        <div class="guru-heading guru-units-heading">
            <div>
                <h1>BANK REFERENSI MATERI</h1>
                <p>Kelola data dasar bahasa Jepang seperti Huruf, Kanji, Kata, Kalimat, dan Grammar.</p>
            </div>
        </div>

        @if (session('status') === 'success')
            <div class="guru-admin-success" role="status" style="margin-bottom: 20px; padding: 12px 16px; border: 1px solid #10b981; background: #ecfdf5; color: #047857; border-radius: 8px; font-weight: 600;">
                {{ session('message') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="guru-admin-error" role="alert" style="margin-bottom: 20px; padding: 12px 16px; border: 1px solid #ef4444; background: #fef2f2; color: #b91c1c; border-radius: 8px; font-weight: 600;">
                <ul style="margin: 0; padding-left: 20px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Tab Navigation -->
        <nav class="guru-account-tabs" aria-label="Pilih tabel referensi" style="margin-bottom: 20px;">
            <a href="{{ route('guru.referensi.index', ['tab' => 'huruf']) }}" class="{{ $tab === 'huruf' ? 'active' : '' }}">
                <span class="material-symbols-outlined" aria-hidden="true font-size: 20px;">translate</span>
                Huruf
            </a>
            <a href="{{ route('guru.referensi.index', ['tab' => 'kanji']) }}" class="{{ $tab === 'kanji' ? 'active' : '' }}">
                <span class="material-symbols-outlined" aria-hidden="true font-size: 20px;">draw</span>
                Kanji
            </a>
            <a href="{{ route('guru.referensi.index', ['tab' => 'kata']) }}" class="{{ $tab === 'kata' ? 'active' : '' }}">
                <span class="material-symbols-outlined" aria-hidden="true font-size: 20px;">dictionary</span>
                Kata
            </a>
            <a href="{{ route('guru.referensi.index', ['tab' => 'kalimat']) }}" class="{{ $tab === 'kalimat' ? 'active' : '' }}">
                <span class="material-symbols-outlined" aria-hidden="true font-size: 20px;">forum</span>
                Kalimat
            </a>
            <a href="{{ route('guru.referensi.index', ['tab' => 'grammar']) }}" class="{{ $tab === 'grammar' ? 'active' : '' }}">
                <span class="material-symbols-outlined" aria-hidden="true font-size: 20px;">menu_book</span>
                Grammar
            </a>
        </nav>

        <!-- Main Listing Section -->
        <section class="guru-admin-results guru-admin-results--list" aria-label="Daftar Referensi">
            <div class="guru-admin-results__head" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
                <div>
                    <span class="ds-section-eyebrow">Data Referensi</span>
                    <h2 style="text-transform: capitalize;">Tabel {{ $tab }}</h2>
                </div>
                <button type="button" class="guru-unit-button guru-unit-button--primary" onclick="openAddModal()">
                    <span class="material-symbols-outlined">add</span> Tambah {{ ucfirst($tab) }}
                </button>
            </div>

            <!-- Tab content tables -->
            <div class="guru-admin-table">
                @if ($tab === 'huruf')
                    <table>
                        <thead>
                            <tr>
                                <th>Simbol</th>
                                <th>Tipe</th>
                                <th>Cara Baca</th>
                                <th class="guru-admin-table__action">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($hurufs as $h)
                                <tr>
                                    <td style="font-size: 20px; font-weight: 800; font-family: var(--font-jp);">{{ $h->simbol }}</td>
                                    <td><span style="text-transform: capitalize; padding: 4px 8px; background: var(--paper-soft); border-radius: 4px; font-size: 12px; font-weight: bold;">{{ $h->tipe }}</span></td>
                                    <td><code>{{ $h->cara_baca }}</code></td>
                                    <td class="guru-admin-table__action">
                                        <div style="display: flex; gap: 8px; justify-content: flex-end;">
                                            <button class="guru-unit-button" style="padding: 6px 12px; font-size: 12px; border: 1px solid var(--line);" onclick="openEditHurufModal('{{ $h->id }}', '{{ $h->tipe }}', '{{ addslashes($h->simbol) }}', '{{ addslashes($h->cara_baca) }}')">Edit</button>
                                            <form action="{{ route('guru.referensi.huruf.destroy', $h) }}" method="POST" onsubmit="return confirm('Hapus huruf ini?')">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="guru-unit-button guru-unit-button--danger" style="padding: 6px 12px; font-size: 12px;">Hapus</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="guru-admin-table__empty">Data kosong.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                    <div style="margin-top: 16px;">{{ $hurufs->links() }}</div>

                @elseif ($tab === 'kanji')
                    <table>
                        <thead>
                            <tr>
                                <th>Kanji</th>
                                <th>Onyomi</th>
                                <th>Kunyomi</th>
                                <th>Makna</th>
                                <th>Level</th>
                                <th class="guru-admin-table__action">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($kanjis as $k)
                                <tr>
                                    <td style="font-size: 22px; font-weight: 800; font-family: var(--font-jp);">{{ $k->kanji }}</td>
                                    <td>{{ $k->onyomi ?? '-' }}</td>
                                    <td>{{ $k->kunyomi ?? '-' }}</td>
                                    <td style="font-weight: 600;">{{ $k->makna }}</td>
                                    <td><span style="font-weight: bold; color: var(--accent);">{{ $k->level ?? '-' }}</span></td>
                                    <td class="guru-admin-table__action">
                                        <div style="display: flex; gap: 8px; justify-content: flex-end;">
                                            <button class="guru-unit-button" style="padding: 6px 12px; font-size: 12px; border: 1px solid var(--line);" onclick="openEditKanjiModal('{{ $k->id }}', '{{ addslashes($k->kanji) }}', '{{ addslashes($k->onyomi) }}', '{{ addslashes($k->kunyomi) }}', '{{ addslashes($k->makna) }}', '{{ $k->level }}')">Edit</button>
                                            <form action="{{ route('guru.referensi.kanji.destroy', $k) }}" method="POST" onsubmit="return confirm('Hapus kanji ini?')">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="guru-unit-button guru-unit-button--danger" style="padding: 6px 12px; font-size: 12px;">Hapus</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="6" class="guru-admin-table__empty">Data kosong.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                    <div style="margin-top: 16px;">{{ $kanjis->links() }}</div>

                @elseif ($tab === 'kata')
                    <table>
                        <thead>
                            <tr>
                                <th>Kata</th>
                                <th>Romaji</th>
                                <th>Arti</th>
                                <th>Kanji Hubung</th>
                                <th>Tipe</th>
                                <th>Level</th>
                                <th class="guru-admin-table__action">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($katas as $kt)
                                <tr>
                                    <td style="font-size: 18px; font-weight: 800; font-family: var(--font-jp);">{{ $kt->kata }}</td>
                                    <td><code>{{ $kt->romaji }}</code></td>
                                    <td style="font-weight: 600;">{{ $kt->arti }}</td>
                                    <td>
                                        @if ($kt->kanji)
                                            <span style="font-size: 16px; font-weight: bold; font-family: var(--font-jp); background: #fef2f2; border: 1px solid #fecaca; padding: 2px 6px; border-radius: 4px; color: #b91c1c;">
                                                {{ $kt->kanji->kanji }} ({{ $kt->kanji->makna }})
                                            </span>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td><span style="padding: 4px 8px; background: var(--paper-soft); border-radius: 4px; font-size: 12px;">{{ $kt->tipe_kata ?? '-' }}</span></td>
                                    <td><span style="font-weight: bold; color: var(--accent);">{{ $kt->level ?? '-' }}</span></td>
                                    <td class="guru-admin-table__action">
                                        <div style="display: flex; gap: 8px; justify-content: flex-end;">
                                            <button class="guru-unit-button" style="padding: 6px 12px; font-size: 12px; border: 1px solid var(--line);" onclick="openEditKataModal('{{ $kt->id }}', '{{ addslashes($kt->kata) }}', '{{ addslashes($kt->romaji) }}', '{{ addslashes($kt->arti) }}', '{{ $kt->kanji_id }}', '{{ addslashes($kt->tipe_kata) }}', '{{ addslashes($kt->deskripsi) }}', '{{ $kt->level }}')">Edit</button>
                                            <form action="{{ route('guru.referensi.kata.destroy', $kt) }}" method="POST" onsubmit="return confirm('Hapus kata ini?')">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="guru-unit-button guru-unit-button--danger" style="padding: 6px 12px; font-size: 12px;">Hapus</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="7" class="guru-admin-table__empty">Data kosong.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                    <div style="margin-top: 16px;">{{ $katas->links() }}</div>

                @elseif ($tab === 'kalimat')
                    <table>
                        <thead>
                            <tr>
                                <th>Kalimat</th>
                                <th>Romaji</th>
                                <th>Arti</th>
                                <th>Kosakata Terkait</th>
                                <th class="guru-admin-table__action">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($kalimats as $kl)
                                <tr>
                                    <td style="font-size: 18px; font-weight: 800; font-family: var(--font-jp);">{{ $kl->kalimat }}</td>
                                    <td><code>{{ $kl->romaji }}</code></td>
                                    <td style="font-weight: 600;">{{ $kl->arti }}</td>
                                    <td>
                                        <div style="display: flex; flex-wrap: wrap; gap: 4px;">
                                            @forelse($kl->katas as $word)
                                                <span style="font-size: 11px; font-weight: bold; background: #e0f2fe; color: #0369a1; padding: 2px 6px; border-radius: 4px; border: 1px solid #bae6fd;">
                                                    {{ $word->kata }}
                                                </span>
                                            @empty
                                                -
                                            @endforelse
                                        </div>
                                    </td>
                                    <td class="guru-admin-table__action">
                                        <div style="display: flex; gap: 8px; justify-content: flex-end;">
                                            <button class="guru-unit-button" style="padding: 6px 12px; font-size: 12px; border: 1px solid var(--line);" onclick="openEditKalimatModal('{{ $kl->id }}', '{{ addslashes($kl->kalimat) }}', '{{ addslashes($kl->romaji) }}', '{{ addslashes($kl->arti) }}', {{ json_encode($kl->katas->pluck('id')) }}, '{{ addslashes($kl->deskripsi) }}')">Edit</button>
                                            <form action="{{ route('guru.referensi.kalimat.destroy', $kl) }}" method="POST" onsubmit="return confirm('Hapus kalimat ini?')">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="guru-unit-button guru-unit-button--danger" style="padding: 6px 12px; font-size: 12px;">Hapus</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="guru-admin-table__empty">Data kosong.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                    <div style="margin-top: 16px;">{{ $kalimats->links() }}</div>

                @elseif ($tab === 'grammar')
                    <table>
                        <thead>
                            <tr>
                                <th>Struktur Grammar</th>
                                <th>Contoh Kalimat</th>
                                <th>Deskripsi</th>
                                <th>Level</th>
                                <th class="guru-admin-table__action">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($grammars as $g)
                                <tr>
                                    <td style="font-size: 16px; font-weight: 800; color: var(--accent);">{{ $g->struktur }}</td>
                                    <td>
                                        @if ($g->kalimat)
                                            <div style="font-weight: 600; font-family: var(--font-jp);">{{ $g->kalimat->kalimat }}</div>
                                            <div style="font-size: 12px; color: var(--muted);">{{ $g->kalimat->arti }}</div>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td><p style="margin: 0; font-size: 13px; max-width: 320px; text-overflow: ellipsis; overflow: hidden; white-space: nowrap;">{{ $g->deskripsi ?? '-' }}</p></td>
                                    <td><span style="font-weight: bold; color: var(--accent);">{{ $g->level ?? '-' }}</span></td>
                                    <td class="guru-admin-table__action">
                                        <div style="display: flex; gap: 8px; justify-content: flex-end;">
                                            <button class="guru-unit-button" style="padding: 6px 12px; font-size: 12px; border: 1px solid var(--line);" onclick="openEditGrammarModal('{{ $g->id }}', '{{ addslashes($g->struktur) }}', '{{ $g->kalimat_id }}', '{{ addslashes($g->deskripsi) }}', '{{ $g->level }}')">Edit</button>
                                            <form action="{{ route('guru.referensi.grammar.destroy', $g) }}" method="POST" onsubmit="return confirm('Hapus grammar ini?')">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="guru-unit-button guru-unit-button--danger" style="padding: 6px 12px; font-size: 12px;">Hapus</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="guru-admin-table__empty">Data kosong.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                    <div style="margin-top: 16px;">{{ $grammars->links() }}</div>
                @endif
            </div>
        </section>
    </main>
</div>

<!-- ================= MODALS FOR CRUD ================= -->

<!-- Main Modal Frame -->
<div id="crudModal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center; padding: 16px; overflow-y: auto;">
    <div style="background: #fff; border-radius: 12px; max-width: 520px; width: 100%; padding: 24px; box-shadow: 0 10px 25px rgba(0,0,0,0.15); border: 1px solid var(--line); position: relative; margin: auto;">
        <h3 id="modalTitle" style="font-size: 20px; font-weight: 800; margin-bottom: 20px; display: flex; align-items: center; gap: 8px; color: var(--ink);">
            Tambah Data
        </h3>
        
        <form id="modalForm" method="POST" action="">
            @csrf
            <div id="methodPlaceholder"></div>

            <!-- Huruf Fields -->
            <div id="fields_huruf" class="modal-fields-group" style="display: none; flex-direction: column; gap: 16px;">
                <label class="guru-admin-field" style="margin: 0; display: flex; flex-direction: column; gap: 6px;">
                    <span style="font-weight: 700;">Tipe Huruf</span>
                    <select name="tipe" style="padding: 10px; border: 1px solid var(--line); border-radius: 8px; font-size: 14px; background: #fff;">
                        <option value="hiragana">Hiragana</option>
                        <option value="katakana">Katakana</option>
                    </select>
                </label>
                <label class="guru-admin-field" style="margin: 0; display: flex; flex-direction: column; gap: 6px;">
                    <span style="font-weight: 700;">Simbol / Huruf</span>
                    <input type="text" name="simbol" style="padding: 10px; border: 1px solid var(--line); border-radius: 8px; font-size: 14px; font-family: var(--font-jp);">
                </label>
                <label class="guru-admin-field" style="margin: 0; display: flex; flex-direction: column; gap: 6px;">
                    <span style="font-weight: 700;">Cara Baca (Romaji)</span>
                    <input type="text" name="cara_baca" style="padding: 10px; border: 1px solid var(--line); border-radius: 8px; font-size: 14px;">
                </label>
            </div>

            <!-- Kanji Fields -->
            <div id="fields_kanji" class="modal-fields-group" style="display: none; flex-direction: column; gap: 16px;">
                <label class="guru-admin-field" style="margin: 0; display: flex; flex-direction: column; gap: 6px;">
                    <span style="font-weight: 700;">Karakter Kanji</span>
                    <input type="text" name="kanji" style="padding: 10px; border: 1px solid var(--line); border-radius: 8px; font-size: 14px; font-family: var(--font-jp);">
                </label>
                <label class="guru-admin-field" style="margin: 0; display: flex; flex-direction: column; gap: 6px;">
                    <span style="font-weight: 700;">Onyomi (Cara baca Cina - Opsional)</span>
                    <input type="text" name="onyomi" placeholder="Contoh: ニコ" style="padding: 10px; border: 1px solid var(--line); border-radius: 8px; font-size: 14px;">
                </label>
                <label class="guru-admin-field" style="margin: 0; display: flex; flex-direction: column; gap: 6px;">
                    <span style="font-weight: 700;">Kunyomi (Cara baca Jepang - Opsional)</span>
                    <input type="text" name="kunyomi" placeholder="Contoh: ひ" style="padding: 10px; border: 1px solid var(--line); border-radius: 8px; font-size: 14px;">
                </label>
                <label class="guru-admin-field" style="margin: 0; display: flex; flex-direction: column; gap: 6px;">
                    <span style="font-weight: 700;">Makna / Arti</span>
                    <input type="text" name="makna" placeholder="Contoh: Matahari" style="padding: 10px; border: 1px solid var(--line); border-radius: 8px; font-size: 14px;">
                </label>
                <label class="guru-admin-field" style="margin: 0; display: flex; flex-direction: column; gap: 6px;">
                    <span style="font-weight: 700;">Level (Opsional)</span>
                    <select name="level" style="padding: 10px; border: 1px solid var(--line); border-radius: 8px; font-size: 14px; background: #fff;">
                        <option value="">-- Tanpa Level --</option>
                        <option value="N5">N5</option>
                        <option value="N4">N4</option>
                        <option value="N3">N3</option>
                        <option value="N2">N2</option>
                        <option value="N1">N1</option>
                    </select>
                </label>
            </div>

            <!-- Kata Fields -->
            <div id="fields_kata" class="modal-fields-group" style="display: none; flex-direction: column; gap: 16px;">
                <label class="guru-admin-field" style="margin: 0; display: flex; flex-direction: column; gap: 6px;">
                    <span style="font-weight: 700;">Kosakata (Jepang)</span>
                    <input type="text" name="kata" placeholder="Contoh: 食べる" style="padding: 10px; border: 1px solid var(--line); border-radius: 8px; font-size: 14px; font-family: var(--font-jp);">
                </label>
                <label class="guru-admin-field" style="margin: 0; display: flex; flex-direction: column; gap: 6px;">
                    <span style="font-weight: 700;">Cara Baca (Romaji)</span>
                    <input type="text" name="romaji" placeholder="Contoh: taberu" style="padding: 10px; border: 1px solid var(--line); border-radius: 8px; font-size: 14px;">
                </label>
                <label class="guru-admin-field" style="margin: 0; display: flex; flex-direction: column; gap: 6px;">
                    <span style="font-weight: 700;">Arti / Terjemahan</span>
                    <input type="text" name="arti" placeholder="Contoh: Makan" style="padding: 10px; border: 1px solid var(--line); border-radius: 8px; font-size: 14px;">
                </label>
                <label class="guru-admin-field" style="margin: 0; display: flex; flex-direction: column; gap: 6px;">
                    <span style="font-weight: 700;">Hubungkan ke Kanji (Opsional)</span>
                    <select name="kanji_id" style="padding: 10px; border: 1px solid var(--line); border-radius: 8px; font-size: 14px; background: #fff;">
                        <option value="">-- Tidak menghubungkan --</option>
                        @foreach ($allKanjis as $kan)
                            <option value="{{ $kan->id }}">{{ $kan->kanji }} ({{ $kan->makna }})</option>
                        @endforeach
                    </select>
                </label>
                <label class="guru-admin-field" style="margin: 0; display: flex; flex-direction: column; gap: 6px;">
                    <span style="font-weight: 700;">Tipe Kata (Opsional)</span>
                    <input type="text" name="tipe_kata" placeholder="Contoh: Kata Kerja, Kata Benda" style="padding: 10px; border: 1px solid var(--line); border-radius: 8px; font-size: 14px;">
                </label>
                <label class="guru-admin-field" style="margin: 0; display: flex; flex-direction: column; gap: 6px;">
                    <span style="font-weight: 700;">Deskripsi Tambahan (Opsional)</span>
                    <textarea name="deskripsi" placeholder="Penjelasan tambahan..." style="padding: 10px; border: 1px solid var(--line); border-radius: 8px; font-size: 14px; min-height: 80px; font-family: inherit; resize: vertical;"></textarea>
                </label>
                <label class="guru-admin-field" style="margin: 0; display: flex; flex-direction: column; gap: 6px;">
                    <span style="font-weight: 700;">Level (Opsional)</span>
                    <select name="level" style="padding: 10px; border: 1px solid var(--line); border-radius: 8px; font-size: 14px; background: #fff;">
                        <option value="">-- Tanpa Level --</option>
                        <option value="N5">N5</option>
                        <option value="N4">N4</option>
                        <option value="N3">N3</option>
                        <option value="N2">N2</option>
                        <option value="N1">N1</option>
                    </select>
                </label>
            </div>

            <!-- Kalimat Fields -->
            <div id="fields_kalimat" class="modal-fields-group" style="display: none; flex-direction: column; gap: 16px;">
                <label class="guru-admin-field" style="margin: 0; display: flex; flex-direction: column; gap: 6px;">
                    <span style="font-weight: 700;">Kalimat (Jepang)</span>
                    <input type="text" name="kalimat" placeholder="Contoh: 私は寿司を食べます。" style="padding: 10px; border: 1px solid var(--line); border-radius: 8px; font-size: 14px; font-family: var(--font-jp);">
                </label>
                <label class="guru-admin-field" style="margin: 0; display: flex; flex-direction: column; gap: 6px;">
                    <span style="font-weight: 700;">Romaji</span>
                    <input type="text" name="romaji" placeholder="Contoh: Watashi wa sushi o tabemasu." style="padding: 10px; border: 1px solid var(--line); border-radius: 8px; font-size: 14px;">
                </label>
                <label class="guru-admin-field" style="margin: 0; display: flex; flex-direction: column; gap: 6px;">
                    <span style="font-weight: 700;">Arti / Terjemahan</span>
                    <input type="text" name="arti" placeholder="Contoh: Saya makan sushi." style="padding: 10px; border: 1px solid var(--line); border-radius: 8px; font-size: 14px;">
                </label>
                <label class="guru-admin-field" style="margin: 0; display: flex; flex-direction: column; gap: 6px;">
                    <span style="font-weight: 700;">Kosakata Terkait (Dapat memilih lebih dari satu)</span>
                    <div style="max-height: 120px; overflow-y: auto; border: 1px solid var(--line); border-radius: 8px; padding: 10px; background: #faf9f6; display: flex; flex-direction: column; gap: 6px;">
                        @foreach($allKatas as $word)
                            <label style="display: flex; align-items: center; gap: 8px; font-size: 13px; font-weight: 600;">
                                <input type="checkbox" name="kata_ids[]" value="{{ $word->id }}" style="accent-color: var(--accent);">
                                <span style="font-family: var(--font-jp);">{{ $word->kata }}</span> ({{ $word->arti }})
                            </label>
                        @endforeach
                    </div>
                </label>
                <label class="guru-admin-field" style="margin: 0; display: flex; flex-direction: column; gap: 6px;">
                    <span style="font-weight: 700;">Deskripsi (Opsional)</span>
                    <textarea name="deskripsi" placeholder="Penjelasan tambahan..." style="padding: 10px; border: 1px solid var(--line); border-radius: 8px; font-size: 14px; min-height: 80px; font-family: inherit; resize: vertical;"></textarea>
                </label>
            </div>

            <!-- Grammar Fields -->
            <div id="fields_grammar" class="modal-fields-group" style="display: none; flex-direction: column; gap: 16px;">
                <label class="guru-admin-field" style="margin: 0; display: flex; flex-direction: column; gap: 6px;">
                    <span style="font-weight: 700;">Struktur Grammar</span>
                    <input type="text" name="struktur" placeholder="Contoh: ~てから" style="padding: 10px; border: 1px solid var(--line); border-radius: 8px; font-size: 14px; font-weight: bold; color: var(--accent);">
                </label>
                <label class="guru-admin-field" style="margin: 0; display: flex; flex-direction: column; gap: 6px;">
                    <span style="font-weight: 700;">Contoh Kalimat (Hubungkan - Opsional)</span>
                    <select name="kalimat_id" style="padding: 10px; border: 1px solid var(--line); border-radius: 8px; font-size: 14px; background: #fff;">
                        <option value="">-- Tidak ada contoh kalimat --</option>
                        @foreach ($allKalimats as $kal)
                            <option value="{{ $kal->id }}">{{ $kal->kalimat }}</option>
                        @endforeach
                    </select>
                </label>
                <label class="guru-admin-field" style="margin: 0; display: flex; flex-direction: column; gap: 6px;">
                    <span style="font-weight: 700;">Deskripsi (Opsional)</span>
                    <textarea name="deskripsi" placeholder="Penjelasan pola tata bahasa ini..." style="padding: 10px; border: 1px solid var(--line); border-radius: 8px; font-size: 14px; min-height: 100px; font-family: inherit; resize: vertical;"></textarea>
                </label>
                <label class="guru-admin-field" style="margin: 0; display: flex; flex-direction: column; gap: 6px;">
                    <span style="font-weight: 700;">Level (Opsional)</span>
                    <select name="level" style="padding: 10px; border: 1px solid var(--line); border-radius: 8px; font-size: 14px; background: #fff;">
                        <option value="">-- Tanpa Level --</option>
                        <option value="N5">N5</option>
                        <option value="N4">N4</option>
                        <option value="N3">N3</option>
                        <option value="N2">N2</option>
                        <option value="N1">N1</option>
                    </select>
                </label>
            </div>

            <div style="display: flex; gap: 12px; justify-content: flex-end; margin-top: 24px;">
                <button type="button" onclick="closeCrudModal()" class="guru-unit-button" style="background: var(--paper-soft); color: var(--ink); border: 1px solid var(--line);">
                    Batal
                </button>
                <button type="submit" class="guru-unit-button guru-unit-button--primary">
                    Simpan Data
                </button>
            </div>
        </form>
    </div>
</div>

<script>
const currentTab = "{{ $tab }}";

function openAddModal() {
    const modal = document.getElementById('crudModal');
    const form = document.getElementById('modalForm');
    const methodPlaceholder = document.getElementById('methodPlaceholder');
    
    // Set Action Route
    form.action = `/guru/referensi/${currentTab}`;
    methodPlaceholder.innerHTML = '';
    
    // Set Title
    document.getElementById('modalTitle').textContent = `Tambah Data ${currentTab.toUpperCase()}`;
    
    // Reset Form Fields
    form.reset();
    
    // Hide all fields first, then show the current tab fields
    document.querySelectorAll('.modal-fields-group').forEach(el => el.style.display = 'none');
    document.getElementById(`fields_${currentTab}`).style.display = 'flex';
    
    modal.style.display = 'flex';
}

function openEditHurufModal(id, tipe, simbol, cara_baca) {
    const modal = document.getElementById('crudModal');
    const form = document.getElementById('modalForm');
    const methodPlaceholder = document.getElementById('methodPlaceholder');
    
    form.action = `/guru/referensi/huruf/${id}`;
    methodPlaceholder.innerHTML = '<input type="hidden" name="_method" value="PATCH">';
    
    document.getElementById('modalTitle').textContent = 'Edit Huruf';
    
    // Populate Fields
    document.querySelectorAll('.modal-fields-group').forEach(el => el.style.display = 'none');
    const fields = document.getElementById('fields_huruf');
    fields.style.display = 'flex';
    
    fields.querySelector('[name="tipe"]').value = tipe;
    fields.querySelector('[name="simbol"]').value = simbol;
    fields.querySelector('[name="cara_baca"]').value = cara_baca;
    
    modal.style.display = 'flex';
}

function openEditKanjiModal(id, kanji, onyomi, kunyomi, makna, level) {
    const modal = document.getElementById('crudModal');
    const form = document.getElementById('modalForm');
    const methodPlaceholder = document.getElementById('methodPlaceholder');
    
    form.action = `/guru/referensi/kanji/${id}`;
    methodPlaceholder.innerHTML = '<input type="hidden" name="_method" value="PATCH">';
    
    document.getElementById('modalTitle').textContent = 'Edit Kanji';
    
    // Populate Fields
    document.querySelectorAll('.modal-fields-group').forEach(el => el.style.display = 'none');
    const fields = document.getElementById('fields_kanji');
    fields.style.display = 'flex';
    
    fields.querySelector('[name="kanji"]').value = kanji;
    fields.querySelector('[name="onyomi"]').value = onyomi;
    fields.querySelector('[name="kunyomi"]').value = kunyomi;
    fields.querySelector('[name="makna"]').value = makna;
    fields.querySelector('[name="level"]').value = level || '';
    
    modal.style.display = 'flex';
}

function openEditKataModal(id, kata, romaji, arti, kanji_id, tipe_kata, deskripsi, level) {
    const modal = document.getElementById('crudModal');
    const form = document.getElementById('modalForm');
    const methodPlaceholder = document.getElementById('methodPlaceholder');
    
    form.action = `/guru/referensi/kata/${id}`;
    methodPlaceholder.innerHTML = '<input type="hidden" name="_method" value="PATCH">';
    
    document.getElementById('modalTitle').textContent = 'Edit Kata (Vocabulary)';
    
    // Populate Fields
    document.querySelectorAll('.modal-fields-group').forEach(el => el.style.display = 'none');
    const fields = document.getElementById('fields_kata');
    fields.style.display = 'flex';
    
    fields.querySelector('[name="kata"]').value = kata;
    fields.querySelector('[name="romaji"]').value = romaji;
    fields.querySelector('[name="arti"]').value = arti;
    fields.querySelector('[name="kanji_id"]').value = kanji_id || '';
    fields.querySelector('[name="tipe_kata"]').value = tipe_kata || '';
    fields.querySelector('[name="deskripsi"]').value = deskripsi || '';
    fields.querySelector('[name="level"]').value = level || '';
    
    modal.style.display = 'flex';
}

function openEditKalimatModal(id, kalimat, romaji, arti, kata_ids, deskripsi) {
    const modal = document.getElementById('crudModal');
    const form = document.getElementById('modalForm');
    const methodPlaceholder = document.getElementById('methodPlaceholder');
    
    form.action = `/guru/referensi/kalimat/${id}`;
    methodPlaceholder.innerHTML = '<input type="hidden" name="_method" value="PATCH">';
    
    document.getElementById('modalTitle').textContent = 'Edit Kalimat (Sentence)';
    
    // Populate Fields
    document.querySelectorAll('.modal-fields-group').forEach(el => el.style.display = 'none');
    const fields = document.getElementById('fields_kalimat');
    fields.style.display = 'flex';
    
    fields.querySelector('[name="kalimat"]').value = kalimat;
    fields.querySelector('[name="romaji"]').value = romaji;
    fields.querySelector('[name="arti"]').value = arti;
    fields.querySelector('[name="deskripsi"]').value = deskripsi || '';
    
    // Check checkboxes
    const checkboxes = fields.querySelectorAll('[name="kata_ids[]"]');
    checkboxes.forEach(cb => {
        cb.checked = kata_ids.includes(cb.value);
    });
    
    modal.style.display = 'flex';
}

function openEditGrammarModal(id, struktur, kalimat_id, deskripsi, level) {
    const modal = document.getElementById('crudModal');
    const form = document.getElementById('modalForm');
    const methodPlaceholder = document.getElementById('methodPlaceholder');
    
    form.action = `/guru/referensi/grammar/${id}`;
    methodPlaceholder.innerHTML = '<input type="hidden" name="_method" value="PATCH">';
    
    document.getElementById('modalTitle').textContent = 'Edit Grammar';
    
    // Populate Fields
    document.querySelectorAll('.modal-fields-group').forEach(el => el.style.display = 'none');
    const fields = document.getElementById('fields_grammar');
    fields.style.display = 'flex';
    
    fields.querySelector('[name="struktur"]').value = struktur;
    fields.querySelector('[name="kalimat_id"]').value = kalimat_id || '';
    fields.querySelector('[name="deskripsi"]').value = deskripsi || '';
    fields.querySelector('[name="level"]').value = level || '';
    
    modal.style.display = 'flex';
}

function closeCrudModal() {
    document.getElementById('crudModal').style.display = 'none';
}

window.onclick = function(event) {
    const modal = document.getElementById('crudModal');
    if (event.target === modal) {
        closeCrudModal();
    }
}
</script>
@endsection
