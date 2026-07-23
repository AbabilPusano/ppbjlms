@extends('layouts.lms-ui', ['title' => 'Penilaian Murid - LMS PPBJ'])

@section('body')
@php($currentUser = auth()->user())
@php($currentRoleLabel = $currentUser?->role === 'admin' ? 'Admin' : 'Guru')

<div class="guru-shell">
    <header class="guru-topbar">
        <button type="button" class="guru-hamburger" data-guru-menu aria-label="Toggle menu">
            <span class="material-symbols-outlined" aria-hidden="true">menu</span>
        </button>
        <div>
            <p>Penilaian Murid</p>
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
            <a href="{{ route('guru.penilaian.index') }}" class="active"><span class="material-symbols-outlined">checklist</span>Penilaian<i></i></a>
            <a href="{{ route('guru.referensi.index') }}" class="{{ request()->routeIs('guru.referensi.*') ? 'active' : '' }}"><span class="material-symbols-outlined">database</span>Bank Referensi{!! request()->routeIs('guru.referensi.*') ? '<i></i>' : '' !!}</a>
            <a href="#" data-ui-only><span class="material-symbols-outlined">settings</span>Pengaturan</a>
        </nav>

        <div class="guru-back">
            <a href="{{ url('/') }}"><span class="material-symbols-outlined">arrow_back</span>Kembali ke LMS</a>
        </div>
    </aside>

    <main class="guru-main guru-admin-main">
        <div class="guru-heading guru-units-heading">
            <div>
                <h1>PENILAIAN & PROGRESS MURID</h1>
                <p>Pantau progres terakhir siswa dari setiap batch, edit nama, dan sesuaikan batch mereka.</p>
            </div>
        </div>

        @if (session('status') === 'student-updated')
            <div class="guru-admin-success" role="status" style="margin-bottom: 20px; padding: 12px 16px; border: 1px solid #10b981; background: #ecfdf5; color: #047857; border-radius: 8px; font-weight: 600;">
                {{ session('message') }}
            </div>
        @endif

        <div class="guru-student-filter">
            <div class="guru-student-filter__title">
                <span class="material-symbols-outlined" aria-hidden="true">tune</span>
                <div>
                    <span>Filter Murid</span>
                    <strong>{{ $selectedBatch ?: 'Semua Batch' }}</strong>
                </div>
            </div>

            <form method="GET" action="{{ route('guru.penilaian.index') }}" class="guru-student-filter__form">
                <label class="guru-student-filter__search">
                    <span>Cari</span>
                    <span class="material-symbols-outlined" aria-hidden="true">search</span>
                    <input type="search" name="q" value="{{ $search }}" placeholder="Cari nama atau username">
                </label>

                <label class="guru-student-filter__select">
                    <span>Batch</span>
                    <select name="batch" onchange="this.form.submit()">
                        <option value="">Semua Batch</option>
                        @foreach ($batches as $batch)
                            <option value="{{ $batch }}" {{ $selectedBatch === $batch ? 'selected' : '' }}>{{ $batch }}</option>
                        @endforeach
                    </select>
                    <span class="material-symbols-outlined" aria-hidden="true">expand_more</span>
                </label>

                <button type="submit" class="guru-student-filter__submit">
                    <span class="material-symbols-outlined" aria-hidden="true">search</span>
                    Cari
                </button>

                @if($selectedBatch || $search !== '')
                    <a href="{{ route('guru.penilaian.index') }}" class="guru-student-filter__reset">
                        <span class="material-symbols-outlined" aria-hidden="true">close</span>
                        Reset
                    </a>
                @endif
            </form>
        </div>

        <!-- Students Table -->
        <section class="guru-admin-results guru-admin-results--list" aria-label="Daftar murid">
            <div class="guru-admin-results__head">
                <div>
                    <span class="ds-section-eyebrow">Daftar Murid</span>
                    <h2>{{ $students->total() }} Murid</h2>
                </div>
                <span>Menampilkan 30 murid teratas per halaman</span>
            </div>

            <div class="guru-admin-table">
                <table>
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Username</th>
                            <th>Batch</th>
                            <th>Progress Terakhir</th>
                            <th class="guru-admin-table__action">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($students as $student)
                            <tr>
                                <td style="font-weight: 700;">{{ $student->name }}</td>
                                <td><code>{{ $student->username }}</code></td>
                                <td>
                                    <span style="padding: 4px 8px; border-radius: 4px; background: var(--paper-soft); font-size: 12px; font-weight: bold; color: var(--ink-soft);">
                                        {{ $student->batch ?? '-' }}
                                    </span>
                                </td>
                                <td>
                                    @if ($student->latestProgress && $student->latestProgress->node)
                                        <div style="display: flex; flex-direction: column; gap: 2px;">
                                            <span style="font-size: 13px; font-weight: 600; color: var(--accent);">
                                                {{ $student->latestProgress->node->unit?->title ?? 'Unit tidak diketahui' }}
                                            </span>
                                            <span style="font-size: 11px; color: var(--muted);">
                                                {{ $student->latestProgress->node->title }} ({{ ucfirst($student->latestProgress->node->type) }})
                                            </span>
                                        </div>
                                    @else
                                        <span style="color: var(--muted); font-style: italic; font-size: 12px;">Belum ada progres</span>
                                    @endif
                                </td>
                                <td class="guru-admin-table__action">
                                    <button type="button" 
                                            class="guru-unit-button guru-unit-button--primary"
                                            style="padding: 6px 12px; font-size: 12px; display: inline-flex; align-items: center; gap: 4px;"
                                            onclick="openEditModal('{{ $student->id }}', '{{ addslashes($student->name) }}', '{{ addslashes($student->batch) }}')">
                                        <span class="material-symbols-outlined" style="font-size: 16px;">edit</span> Edit
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="guru-admin-table__empty" style="text-align: center; padding: 32px; color: var(--muted);">
                                    Belum ada murid terdaftar di batch ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($students->hasPages())
                <nav class="guru-admin-pagination" aria-label="Navigasi halaman">
                    @if ($students->onFirstPage())
                        <span class="disabled">Sebelumnya</span>
                    @else
                        <a href="{{ $students->previousPageUrl() }}" rel="prev">Sebelumnya</a>
                    @endif

                    <span>Halaman {{ $students->currentPage() }} dari {{ $students->lastPage() }}</span>

                    @if ($students->hasMorePages())
                        <a href="{{ $students->nextPageUrl() }}" rel="next">Berikutnya</a>
                    @else
                        <span class="disabled">Berikutnya</span>
                    @endif
                </nav>
            @endif
        </section>
    </main>
</div>

<!-- Modal Edit Murid -->
<div id="editModal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center; padding: 16px;">
    <div style="background: #fff; border-radius: 12px; max-width: 480px; width: 100%; padding: 24px; box-shadow: 0 10px 25px rgba(0,0,0,0.15); border: 1px solid var(--line); position: relative;">
        <h3 style="font-size: 20px; font-weight: 800; margin-bottom: 16px; display: flex; align-items: center; gap: 8px;">
            <span class="material-symbols-outlined" style="color: var(--accent);">edit</span> Edit Data Murid
        </h3>
        
        <form id="editForm" method="POST" action="">
            @csrf
            @method('PATCH')
            
            <div style="display: grid; gap: 16px; margin-bottom: 24px;">
                <label class="guru-admin-field" style="margin: 0; display: flex; flex-direction: column; gap: 6px;">
                    <span style="font-weight: 700;">Nama Murid</span>
                    <input type="text" id="editName" name="name" required style="padding: 10px; border: 1px solid var(--line); border-radius: 8px; font-size: 14px;">
                </label>

                <label class="guru-admin-field" style="margin: 0; display: flex; flex-direction: column; gap: 6px;">
                    <span style="font-weight: 700;">Batch</span>
                    <select id="editBatch" name="batch" required style="padding: 10px; border: 1px solid var(--line); border-radius: 8px; font-size: 14px; background: #fff;">
                        @foreach ($batches as $batch)
                            <option value="{{ $batch }}">{{ $batch }}</option>
                        @endforeach
                        <!-- Add an option if student has a batch that is not in distinct batches -->
                        <option id="customBatchOption" value="" style="display: none;"></option>
                    </select>
                </label>
            </div>

            <div style="display: flex; gap: 12px; justify-content: flex-end;">
                <button type="button" onclick="closeEditModal()" class="guru-unit-button" style="background: var(--paper-soft); color: var(--ink); border: 1px solid var(--line);">
                    Batal
                </button>
                <button type="submit" class="guru-unit-button guru-unit-button--primary">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openEditModal(studentId, name, batch) {
    const form = document.getElementById('editForm');
    form.action = `/guru/penilaian/${studentId}`;
    
    document.getElementById('editName').value = name;
    
    const batchSelect = document.getElementById('editBatch');
    const customOption = document.getElementById('customBatchOption');
    
    // Check if batch is already one of the options
    let optionExists = false;
    for (let i = 0; i < batchSelect.options.length; i++) {
        if (batchSelect.options[i].value === batch) {
            optionExists = true;
            break;
        }
    }
    
    if (batch && !optionExists) {
        customOption.value = batch;
        customOption.textContent = batch;
        customOption.style.display = 'block';
    } else {
        customOption.style.display = 'none';
    }
    
    batchSelect.value = batch;
    
    const modal = document.getElementById('editModal');
    modal.style.display = 'flex';
}

function closeEditModal() {
    document.getElementById('editModal').style.display = 'none';
}

// Close modal if user clicks outside of it
window.onclick = function(event) {
    const modal = document.getElementById('editModal');
    if (event.target === modal) {
        closeEditModal();
    }
}
</script>
@endsection
