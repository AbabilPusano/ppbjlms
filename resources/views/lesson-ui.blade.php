@extends('layouts.lms-ui', ['title' => 'Lesson N5 - LMS PPBJ'])

@section('body')
<div class="lesson-page" data-lesson data-node-id="{{ $node->id }}" data-complete-url="{{ route('api.v1.nodes.complete.web', $node->id) }}">
    <script type="application/json" data-lesson-questions>
        {!! json_encode($questions) !!}
    </script>

    <header class="lesson-header">
        <a href="{{ url('/?level_id=' . $levelId) }}" class="lesson-close" aria-label="Kembali ke dashboard">
            <span class="material-symbols-outlined" aria-hidden="true">arrow_back</span>
        </a>

        <div class="lesson-header__copy">
            <span>{{ $node->title }}</span>
            <strong>Unit {{ $unitOrder }} · Step {{ $levelOrder }}</strong>
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
        <section class="lesson-screen lesson-screen--new-letter" data-lesson-screen="new-letter" hidden style="display: flex; justify-content: center; align-items: center; width: 100%;">
            <div class="lesson-intro-panel" style="width: 100%; max-width: 800px; min-height: 360px; padding: 48px; box-sizing: border-box; display: flex; flex-direction: column; justify-content: center; background: var(--card-bg, #fff); border: 2px solid var(--line); border-radius: 20px; box-shadow: 0 8px 30px rgba(0,0,0,0.04); margin: 0 auto;">
                <p data-lesson-reading style="font-size: 1.5rem; line-height: 1.7; color: var(--ink-soft); white-space: pre-wrap; margin: 0; text-align: center;"></p>
            </div>
        </section>

        <section class="lesson-screen lesson-screen--choice" data-lesson-screen="select-image" hidden>
            <div class="lesson-question-copy">
                <span class="lesson-kicker">Pilih jawaban</span>
                <h1 data-choice-question style="font-size: 3rem; line-height: 1.1; margin-bottom: 8px;">Pilih kartu yang berarti sushi.</h1>
                <p data-choice-instruction style="font-size: 1rem; color: var(--muted-soft); margin: 0;">PILIH JAWABAN YANG BENAR</p>
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
                <a href="{{ url('/?level_id=' . $levelId) }}" class="lesson-score-button">Kembali ke dashboard</a>
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
