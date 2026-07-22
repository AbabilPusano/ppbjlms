@extends('layouts.lms-ui', ['title' => 'Generate Akun Siswa - LMS PPBJ'])

@section('body')
@php($currentUser = auth()->user())
@php($generatedAccounts = session('generated_accounts', []))
@php($currentRoleLabel = $currentUser?->role === 'admin' ? 'Admin' : 'Guru')

<div class="guru-shell">
    <header class="guru-topbar">
        <button type="button" class="guru-hamburger" data-guru-menu aria-label="Toggle menu">
            <span class="material-symbols-outlined " aria-hidden="true">menu</span>
        </button>
        <div>
            <p>Generate Akun Siswa</p>
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
            <a href="{{ route('guru.penilaian.index') }}" class="{{ request()->routeIs('guru.penilaian.*') ? 'active' : '' }}"><span class="material-symbols-outlined">checklist</span>Penilaian{!! request()->routeIs('guru.penilaian.*') ? '<i></i>' : '' !!}</a>
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
                <h1>GENERATE AKUN SISWA</h1>
                <p>Buat akun siswa baru. Fitur ini tersedia untuk guru dan admin.</p>
            </div>
        </div>

        <section class="guru-admin-grid" aria-label="Generator akun siswa">
            <form class="guru-admin-panel" action="{{ route('admin.student-accounts.store') }}" method="POST">
                @csrf
                <div class="guru-admin-panel__head">
                    <span class="material-symbols-outlined" aria-hidden="true">person_add</span>
                    <div>
                        <h2>Batch akun baru</h2>
                        <p>Username dibuat berurutan dari prefix, role selalu siswa.</p>
                        <p>Password awal sama dengan username dan dapat diganti siswa melalui profil.</p>
                    </div>
                </div>

                @if ($errors->any())
                    <div class="guru-admin-error" role="alert">
                        @foreach ($errors->all() as $error)
                            <span>{{ $error }}</span>
                        @endforeach
                    </div>
                @endif

                <label class="guru-admin-field">
                    <span>Batch siswa</span>
                    <input name="batch" type="text" value="{{ old('batch') }}" placeholder="Contoh: Batch 1" required>
                    <small>Menentukan batch untuk semua akun yang digenerate.</small>
                </label>

                <label class="guru-admin-field">
                    <span>Prefix username</span>
                    <input name="prefix" type="text" value="{{ old('prefix', 'siswa') }}" maxlength="24" pattern="[A-Za-z0-9_]+" required>
                    <small>Contoh: siswa, kelas7, n5batch. Hanya huruf, angka, dan underscore.</small>
                </label>

                <label class="guru-admin-field">
                    <span>Jumlah akun</span>
                    <input name="count" type="number" value="{{ old('count', 10) }}" min="1" max="50" required>
                    <small>Maksimal 50 akun setiap generate.</small>
                </label>

                <button type="submit" class="guru-unit-button guru-unit-button--primary">
                    <span class="material-symbols-outlined" aria-hidden="true">bolt</span>
                    GENERATE AKUN
                </button>
            </form>

            <aside class="guru-admin-panel guru-admin-panel--summary">
                <span class="ds-section-eyebrow">Ringkasan</span>
                <strong>{{ $studentCount }}</strong>
                <p>Akun siswa tersimpan di database.</p>
                <div class="guru-admin-mini-list">
                    @forelse ($recentStudents as $student)
                        <div>
                            <span>{{ $student->username }}</span>
                            <small>{{ $student->created_at?->format('d M Y') }}</small>
                        </div>
                    @empty
                        <p>Belum ada akun siswa.</p>
                    @endforelse
                </div>
            </aside>
        </section>

        @if (session('status') === 'student-accounts-generated' && count($generatedAccounts) > 0)
            <section class="guru-admin-results" aria-label="Akun siswa baru">
                <div class="guru-admin-results__head">
                    <div>
                        <span class="ds-section-eyebrow">Berhasil dibuat</span>
                        <h2>Credential siswa baru</h2>
                        <p>Password awal setiap akun sama dengan username.</p>
                    </div>
                    <span>{{ count($generatedAccounts) }} akun</span>
                </div>

                <div class="guru-admin-table">
                    <table>
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th>Username</th>
                                <th>Password</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($generatedAccounts as $account)
                                <tr>
                                    <td>{{ $account['name'] }}</td>
                                    <td><code>{{ $account['username'] }}</code></td>
                                    <td><code>{{ $account['password'] }}</code></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </section>
        @endif
    </main>
</div>
@endsection
