@extends('layouts.lms-ui', ['title' => 'Masuk - LMS PPBJ'])

@section('body')
<main class="login-page">
    <section class="login-intro" aria-label="Sambutan LMS PPBJ">
        <header class="login-brand">
            <span class="login-logo">文</span>
            <div>
                <strong>LMS PPBJ</strong>
                <span>Belajar Jepang pelan-pelan, tapi jalan terus.</span>
            </div>
        </header>

        <div class="login-copy">
            <p class="login-kicker">Teman belajar hari ini</p>
            <h1>Masuk dulu, lanjut belajarnya tanpa buru-buru.</h1>
            <p>
                Satu sesi kecil juga tetap berarti. Ambil napas, buka materi terakhir,
                lalu lanjutkan progresmu dari tempat yang sudah kamu capai.
            </p>
        </div>

        <div class="login-companion">
            <div class="login-mascot">
                <img src="{{ asset('images/oni.webp') }}" alt="Maskot LMS PPBJ">
            </div>
            <div class="login-note">
                <span>Catatan kecil</span>
                <strong>Belajar nggak harus tegang.</strong>
                <p>Kita mulai dari yang bisa dulu, sisanya dikejar bareng pelan-pelan.</p>
            </div>
        </div>

        <div class="login-support" aria-label="Dukungan belajar">
            <div><span class="material-symbols-outlined" aria-hidden="true">auto_stories</span><span>Materi rapi</span></div>
            <div><span class="material-symbols-outlined" aria-hidden="true">task_alt</span><span>Progres kebaca</span></div>
            <div><span class="material-symbols-outlined" aria-hidden="true">favorite</span><span>Ritme lebih santai</span></div>
        </div>
    </section>

    <section class="login-form-area" aria-label="Form masuk">
        <div class="login-form-card">
            <div class="login-form-head">
                <span>Selamat datang kembali</span>
                <h2>Yuk masuk.</h2>
                <p>Akun dan password dibuat dummy untuk tampilan saja.</p>
            </div>

            <form class="login-form" action="#" method="get" data-ui-only>
                {{-- UI only: form ini tidak mengirim request login/backend. --}}
                <div class="login-field">
                    <label for="username">Username</label>
                    <input id="username" name="username" type="text" placeholder="contoh: ppbj01" autocomplete="off">
                </div>

                <div class="login-field">
                    <label for="password">Password</label>
                    <div class="login-password">
                        <input id="password" name="password" type="password" placeholder="masukkan password" autocomplete="off">
                        <button type="button" data-toggle-password aria-label="Tampilkan password">
                            <span class="material-symbols-outlined" aria-hidden="true">visibility</span>
                        </button>
                    </div>
                </div>

                <button type="button" class="login-submit" data-ui-only>
                    <span>Masuk ke LMS</span>
                    <span class="material-symbols-outlined" aria-hidden="true">arrow_forward</span>
                </button>
            </form>

            <p class="login-help">
                Kalau aksesnya belum cocok, hubungi admin kelas supaya dibantu cek akun.
            </p>
        </div>
    </section>
</main>
@endsection
