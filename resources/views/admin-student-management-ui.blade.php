@extends('layouts.lms-ui', ['title' => 'Kelola Password Akun - LMS PPBJ'])

@section('body')
@php($currentUser = auth()->user())
@php($isTeacherTab = $activeRole === 'guru')
@php($roleLabel = $isTeacherTab ? 'Guru' : 'Siswa')

<div class="guru-shell">
    <header class="guru-topbar">
        <button type="button" class="guru-hamburger" data-guru-menu aria-label="Toggle menu">
            <span class="material-symbols-outlined" aria-hidden="true">menu</span>
        </button>
        <div><p>Kelola Password Akun</p></div>
        <div class="guru-user ">
            <div><p>{{ $currentUser?->name ?? 'Admin' }}</p><span>Admin</span></div>
            <strong>{{ strtoupper(substr($currentUser?->name ?? 'Admin', 0, 1)) }}</strong>
        </div>
    </header>

    <aside class="guru-sidebar">
        <div class="guru-logo">
            <span class="material-symbols-outlined" aria-hidden="true">school</span>
            <strong>GURU LMS</strong>
        </div>
        <p class="guru-menu-label">Menu Utama</p>
        <nav class="guru-nav" aria-label="Navigasi admin">
            <a href="{{ route('guru.dashboard') }}"><span class="material-symbols-outlined">home</span>Dashboard</a>
            <a href="{{ route('guru.units.index') }}"><span class="material-symbols-outlined">category</span>Unit Pembelajaran</a>
            <a href="{{ route('guru.stories.index') }}"><span class="material-symbols-outlined">menu_book</span>Kelola Cerita</a>
            @include('partials.admin-account-nav')
            <a href="{{ route('guru.penilaian.index') }}" class="{{ request()->routeIs('guru.penilaian.*') ? 'active' : '' }}"><span class="material-symbols-outlined">checklist</span>Penilaian{!! request()->routeIs('guru.penilaian.*') ? '<i></i>' : '' !!}</a>
            <a href="{{ route('guru.referensi.index') }}" class="{{ request()->routeIs('guru.referensi.*') ? 'active' : '' }}"><span class="material-symbols-outlined">database</span>Bank Referensi{!! request()->routeIs('guru.referensi.*') ? '<i></i>' : '' !!}</a>
            <a href="#" data-ui-only><span class="material-symbols-outlined">settings</span>Pengaturan</a>
        </nav>
        <div class="guru-back"><a href="{{ url('/') }}"><span class="material-symbols-outlined">arrow_back</span>Kembali ke LMS</a></div>
    </aside>

    <main class="guru-main guru-admin-main">
        <div class="guru-heading guru-units-heading">
            <div>
                <h1>KELOLA PASSWORD AKUN</h1>
                <p>Reset password siswa atau guru menjadi sama dengan username masing-masing.</p>
            </div>
        </div>

        @if (session('status') === 'account-password-reset')
            <div class="guru-admin-success" role="status">
                Password {{ session('reset_role') === 'guru' ? 'guru' : 'siswa' }}
                <strong>{{ session('reset_username') }}</strong> berhasil direset menjadi sama dengan username.
            </div>
        @endif

        <nav class="guru-account-tabs" aria-label="Pilih jenis akun">
            <a href="{{ route('admin.students.index', ['tab' => 'siswa']) }}" class="{{ $isTeacherTab ? '' : 'active' }}" @if (!$isTeacherTab) aria-current="page" @endif>
                <span class="material-symbols-outlined" aria-hidden="true">school</span>
                Siswa
            </a>
            <a href="{{ route('admin.students.index', ['tab' => 'guru']) }}" class="{{ $isTeacherTab ? 'active' : '' }}" @if ($isTeacherTab) aria-current="page" @endif>
                <span class="material-symbols-outlined" aria-hidden="true">co_present</span>
                Guru
            </a>
        </nav>

        <section class="guru-admin-results guru-admin-results--list" aria-label="Daftar {{ strtolower($roleLabel) }}">
            <div class="guru-admin-results__head">
                <div>
                    <span class="ds-section-eyebrow">Daftar {{ strtolower($roleLabel) }}</span>
                    <h2>{{ $accounts->total() }} akun {{ strtolower($roleLabel) }}</h2>
                </div>
                <span>15 per halaman</span>
            </div>

            <div class="guru-admin-table">
                <table>
                    <thead><tr><th>Nama</th><th>Username</th><th>Email</th><th>Dibuat</th><th class="guru-admin-table__action">Aksi</th></tr></thead>
                    <tbody>
                        @forelse ($accounts as $account)
                            <tr>
                                <td>{{ $account->name }}</td>
                                <td><code>{{ $account->username }}</code></td>
                                <td>{{ $account->email }}</td>
                                <td>{{ $account->created_at?->format('d M Y') }}</td>
                                <td class="guru-admin-table__action">
                                    <form action="{{ route('admin.accounts.reset-password', $account) }}" method="POST" onsubmit="return confirm('Reset password {{ $account->username }} menjadi sama dengan username?')">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="guru-unit-button guru-unit-button--danger guru-admin-reset-button">
                                            <span class="material-symbols-outlined" aria-hidden="true">lock_reset</span>Reset Password
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="guru-admin-table__empty">Belum ada akun {{ strtolower($roleLabel) }}.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($accounts->hasPages())
                <nav class="guru-admin-pagination" aria-label="Navigasi halaman {{ strtolower($roleLabel) }}">
                    @if ($accounts->onFirstPage())
                        <span class="disabled">Sebelumnya</span>
                    @else
                        <a href="{{ $accounts->previousPageUrl() }}" rel="prev">Sebelumnya</a>
                    @endif

                    <span>Halaman {{ $accounts->currentPage() }} dari {{ $accounts->lastPage() }}</span>

                    @if ($accounts->hasMorePages())
                        <a href="{{ $accounts->nextPageUrl() }}" rel="next">Berikutnya</a>
                    @else
                        <span class="disabled">Berikutnya</span>
                    @endif
                </nav>
            @endif
        </section>
    </main>
</div>
@endsection
