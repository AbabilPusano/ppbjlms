@php($accountMenuUser = auth()->user())
@php($canGenerateStudents = in_array($accountMenuUser?->role, ['guru', 'admin'], true))
@php($canManageAllAccounts = $accountMenuUser?->role === 'admin')

@if ($canGenerateStudents)
    <div class="guru-nav-group">
        <div class="guru-nav-group__label {{ request()->routeIs('admin.student-accounts.*', 'admin.teacher-accounts.*', 'admin.students.*') ? 'active' : '' }}">
            <span class="material-symbols-outlined" aria-hidden="true">manage_accounts</span>
            <span>Generate Akun</span>
            <span class="material-symbols-outlined guru-nav-group__chevron" aria-hidden="true">expand_more</span>
        </div>
        <div class="guru-nav-children">
            <a href="{{ route('admin.student-accounts.index') }}" class="{{ request()->routeIs('admin.student-accounts.*') ? 'active' : '' }}">
                <span class="material-symbols-outlined" aria-hidden="true">person_add</span>
                Akun Siswa
            </a>
            @if ($canManageAllAccounts)
                <a href="{{ route('admin.teacher-accounts.index') }}" class="{{ request()->routeIs('admin.teacher-accounts.*') ? 'active' : '' }}">
                    <span class="material-symbols-outlined" aria-hidden="true">person_add_alt</span>
                    Akun Guru
                </a>
                <a href="{{ route('admin.students.index') }}" class="{{ request()->routeIs('admin.students.*') ? 'active' : '' }}">
                    <span class="material-symbols-outlined" aria-hidden="true">lock_reset</span>
                    Reset Password
                </a>
            @endif
        </div>
    </div>
@endif
