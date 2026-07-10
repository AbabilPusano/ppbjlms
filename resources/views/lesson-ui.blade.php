@extends('layouts.lms-ui', ['title' => 'Lesson N5 - LMS PPBJ'])

@section('body')
<div class="lesson-page" data-lesson>
    <script type="application/json" data-lesson-questions>
        [
            {
                "type": "new-letter",
                "letter": "あ",
                "reading": "a"
            },
            {
                "type": "select-image",
                "question": "Pilih kartu yang berarti sushi.",
                "answer": "sushi",
                "options": [
                    { "id": 1, "text": "mizu", "sub": "みず", "image": "水", "correct": false },
                    { "id": 2, "text": "sushi", "sub": "すし", "image": "寿", "correct": true },
                    { "id": 3, "text": "gohan", "sub": "ごはん", "image": "飯", "correct": false },
                    { "id": 4, "text": "ocha", "sub": "おちゃ", "image": "茶", "correct": false }
                ]
            },
            {
                "type": "translate-to-ja",
                "question": "Susun jawaban dalam bahasa Jepang.",
                "prompt": "water",
                "answer": "みず",
                "correctIds": [1],
                "options": [
                    { "id": 1, "text": "みず", "sub": "mizu" },
                    { "id": 2, "text": "すし", "sub": "sushi" },
                    { "id": 3, "text": "ごはん", "sub": "gohan" },
                    { "id": 4, "text": "おちゃ", "sub": "ocha" }
                ]
            },
            {
                "type": "select-image",
                "question": "Pilih kartu yang berarti teh.",
                "answer": "ocha",
                "options": [
                    { "id": 1, "text": "mizu", "sub": "みず", "image": "水", "correct": false },
                    { "id": 2, "text": "sushi", "sub": "すし", "image": "寿", "correct": false },
                    { "id": 3, "text": "gohan", "sub": "ごはん", "image": "飯", "correct": false },
                    { "id": 4, "text": "ocha", "sub": "おちゃ", "image": "茶", "correct": true }
                ]
            },
            {
                "type": "translate-to-ja",
                "question": "Susun jawaban dalam bahasa Jepang.",
                "prompt": "rice",
                "answer": "ごはん",
                "correctIds": [3],
                "options": [
                    { "id": 1, "text": "みず", "sub": "mizu" },
                    { "id": 2, "text": "すし", "sub": "sushi" },
                    { "id": 3, "text": "ごはん", "sub": "gohan" },
                    { "id": 4, "text": "おちゃ", "sub": "ocha" }
                ]
            }
        ]
    </script>

    <header class="lesson-header">
        <a href="{{ url('/') }}" class="lesson-close" aria-label="Kembali ke dashboard">
            <span class="material-symbols-outlined" aria-hidden="true">arrow_back</span>
        </a>

        <div class="lesson-header__copy">
            <span>N5 Hiragana</span>
            <strong>Unit 1 · Step 1</strong>
        </div>

        <div class="lesson-progress" aria-label="Progress lesson">
            <span data-lesson-progress style="width: 0%"></span>
        </div>

        <div class="lesson-lives" aria-label="Nyawa tersisa">
            <span class="material-symbols-outlined" aria-hidden="true">favorite</span>
            <strong>5</strong>
        </div>
    </header>

    <main class="lesson-main">
        <section class="lesson-screen lesson-screen--new-letter" data-lesson-screen="new-letter" hidden>
            <div class="lesson-intro-panel">
                <span class="lesson-kicker">Huruf baru</span>
                <h1>Kenali bentuknya dulu, baru lanjut pelan-pelan.</h1>
                <p>Fokus ke satu huruf. Setelah ini kamu akan latihan memilih dan menyusun jawaban.</p>
            </div>

            <div class="lesson-letter-wrap">
                <div class="lesson-letter-card"><span data-lesson-letter>あ</span></div>
                <div class="lesson-reading-card">
                    <span>Dibaca</span>
                    <strong data-lesson-reading>a</strong>
                </div>
            </div>
        </section>

        <section class="lesson-screen lesson-screen--choice" data-lesson-screen="select-image" hidden>
            <div class="lesson-question-copy">
                <span class="lesson-kicker">Pilih jawaban</span>
                <h1 data-choice-question>Pilih kartu yang berarti sushi.</h1>
                <p>Setiap kartu menampilkan romaji, huruf Jepang, dan penanda visual kecil.</p>
            </div>
            <div class="lesson-choice-grid" data-choice-options></div>
        </section>

        <section class="lesson-screen lesson-screen--translate" data-lesson-screen="translate-to-ja" hidden>
            <div class="lesson-question-copy">
                <span class="lesson-kicker">Susun kata</span>
                <h1 data-translate-question>Susun jawaban dalam bahasa Jepang.</h1>
                <div class="lesson-prompt-row">
                    <div class="lesson-character" aria-hidden="true">
                        <span class="material-symbols-outlined">translate</span>
                    </div>
                    <div class="lesson-speech">
                        <small>Prompt</small>
                        <span data-translate-prompt>water</span>
                    </div>
                </div>
            </div>
            <div class="lesson-word-panel">
                <div class="lesson-answer-bank" data-answer-bank aria-label="Jawaban tersusun"></div>
                <div class="lesson-word-bank" data-word-bank aria-label="Pilihan kata"></div>
            </div>
        </section>

        <section class="lesson-score" data-lesson-score hidden>
            <div class="lesson-score-card">
                <span class="lesson-score-pill">Sesi selesai</span>
                <h1>Latihan selesai</h1>
                <p>Berikut ringkasan jawabanmu. Kamu bisa lanjut ke latihan berikutnya kapan saja.</p>
                <div class="lesson-score-grid">
                    <div>
                        <span>Benar</span>
                        <strong data-score-correct>0</strong>
                        <small data-score-total>dari 5 soal</small>
                    </div>
                    <div>
                        <span>Akurasi</span>
                        <strong data-score-accuracy>0%</strong>
                        <small>hasil sesi ini</small>
                    </div>
                </div>
                <a href="{{ url('/') }}" class="lesson-score-button">Kembali ke dashboard</a>
            </div>
        </section>
    </main>

    <footer class="lesson-footer" data-lesson-footer>
        <div class="lesson-feedback" data-lesson-feedback>
            <button type="button" class="lesson-skip" data-lesson-skip>Lewati</button>
        </div>
        <button type="button" class="lesson-action" data-lesson-action>Lanjut</button>
    </footer>
</div>
@endsection
