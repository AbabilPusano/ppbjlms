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
                "question": "Which one of these is sushi?",
                "answer": "sushi",
                "options": [
                    { "id": 1, "text": "mizu", "sub": "みず", "image": "💧", "correct": false },
                    { "id": 2, "text": "sushi", "sub": "すし", "image": "🍣", "correct": true },
                    { "id": 3, "text": "gohan", "sub": "ごはん", "image": "🍚", "correct": false },
                    { "id": 4, "text": "ocha", "sub": "おちゃ", "image": "🍵", "correct": false }
                ]
            },
            {
                "type": "translate-to-ja",
                "question": "Write this in Japanese",
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
                "question": "Which one of these is tea?",
                "answer": "ocha",
                "options": [
                    { "id": 1, "text": "mizu", "sub": "みず", "image": "💧", "correct": false },
                    { "id": 2, "text": "sushi", "sub": "すし", "image": "🍣", "correct": false },
                    { "id": 3, "text": "gohan", "sub": "ごはん", "image": "🍚", "correct": false },
                    { "id": 4, "text": "ocha", "sub": "おちゃ", "image": "🍵", "correct": true }
                ]
            },
            {
                "type": "translate-to-ja",
                "question": "Write this in Japanese",
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
            <span class="material-symbols-outlined" aria-hidden="true">close</span>
        </a>
        <div class="lesson-progress" aria-label="Progress lesson">
            <span data-lesson-progress style="width: 0%"></span>
        </div>
        <div class="lesson-lives" aria-label="Nyawa tersisa">
            <span aria-hidden="true">♥</span>
            <strong>5</strong>
        </div>
    </header>

    <main class="lesson-main">
        <section class="lesson-screen lesson-screen--new-letter" data-lesson-screen="new-letter" hidden>
            <span class="lesson-kicker">New letter</span>
            <div class="lesson-letter-wrap">
                <div class="lesson-letter-card"><span data-lesson-letter>あ</span></div>
                <strong data-lesson-reading>a</strong>
            </div>
        </section>

        <section class="lesson-screen lesson-screen--choice" data-lesson-screen="select-image" hidden>
            <div class="lesson-question-copy">
                <span class="lesson-kicker">New word</span>
                <h1 data-choice-question>Which one of these is sushi?</h1>
            </div>
            <div class="lesson-choice-grid" data-choice-options></div>
        </section>

        <section class="lesson-screen lesson-screen--translate" data-lesson-screen="translate-to-ja" hidden>
            <div class="lesson-question-copy">
                <h1 data-translate-question>Write this in Japanese</h1>
                <div class="lesson-prompt-row">
                    <div class="lesson-character" aria-hidden="true">
                        <svg viewBox="0 0 100 130">
                            <circle cx="50" cy="40" r="30" fill="#fb85bf" />
                            <rect x="20" y="70" width="60" height="50" rx="15" fill="#d45e99" />
                            <circle cx="40" cy="35" r="3" fill="white" />
                            <circle cx="60" cy="35" r="3" fill="white" />
                            <path d="M45 50 Q50 55 55 50" stroke="white" stroke-width="3" fill="none" />
                        </svg>
                    </div>
                    <div class="lesson-speech">
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
                <a href="{{ url('/') }}" class="lesson-score-button">Lanjut</a>
            </div>
        </section>
    </main>

    <footer class="lesson-footer" data-lesson-footer>
        <div class="lesson-feedback" data-lesson-feedback>
            <button type="button" class="lesson-skip" data-lesson-skip>Skip</button>
        </div>
        <button type="button" class="lesson-action" data-lesson-action>Continue</button>
    </footer>
</div>
@endsection
