@php($activeMenu = $activeMenu ?? 'LEARN')

<nav class="left-sidebar home-left-sidebar" aria-label="Navigasi utama">
    <a href="{{ url('/') }}" class="home-nav-brand" aria-label="LMS PPBJ" title="LMS PPBJ">
        <span class="material-symbols-outlined" aria-hidden="true">menu_book</span>
    </a>

    <div class="home-nav-menu">
        <a href="{{ url('/') }}" class="home-nav-item {{ $activeMenu === 'LEARN' ? 'active' : '' }}" aria-label="Belajar" title="Belajar">
            <span class="home-nav-icon"><span class="material-symbols-outlined" aria-hidden="true">school</span></span>
            <span class="home-nav-label">Belajar</span>
        </a>
        <a href="{{ url('/stories') }}" class="home-nav-item {{ $activeMenu === 'STORIES' ? 'active' : '' }}" aria-label="Cerita" title="Cerita">
            <span class="home-nav-icon"><span class="material-symbols-outlined" aria-hidden="true">forum</span></span>
            <span class="home-nav-label">Cerita</span>
        </a>
        <a href="{{ url('/letters') }}" class="home-nav-item {{ $activeMenu === 'LETTERS' ? 'active' : '' }}" aria-label="Huruf" title="Huruf">
            <span class="home-nav-icon"><span class="material-symbols-outlined" aria-hidden="true">translate</span></span>
            <span class="home-nav-label">Huruf</span>
        </a>
        <a href="{{ url('/profile') }}" class="home-nav-item {{ $activeMenu === 'PROFILE' ? 'active' : '' }}" aria-label="Profil" title="Profil">
            <span class="home-nav-icon"><span class="material-symbols-outlined" aria-hidden="true">person</span></span>
            <span class="home-nav-label">Profil</span>
        </a>
    </div>

    @if (in_array(auth()->user()?->role, ['guru', 'admin'], true))
        <div class="home-nav-teacher">
            <a href="{{ route('guru.dashboard') }}" class="home-nav-item {{ $activeMenu === 'GURU' ? 'active' : '' }}" aria-label="Guru" title="Guru">
                <span class="home-nav-icon"><span class="material-symbols-outlined" aria-hidden="true">work</span></span>
                <span class="home-nav-label">{{ auth()->user()?->role === 'admin' ? 'Admin' : 'Guru' }}</span>
            </a>
        </div>
    @endif
</nav>
