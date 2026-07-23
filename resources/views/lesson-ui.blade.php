@extends('layouts.lms-ui', ['title' => ($isPreview ?? false) ? 'Preview Materi - LMS PPBJ' : 'Lesson N5 - LMS PPBJ'])

@section('body')
@php
    $previewMode = $isPreview ?? false;
    $lessonReturnUrl = $returnUrl ?? url('/?level_id=' . $levelId);
    $lessonReturnLabel = $returnLabel ?? 'Kembali ke dashboard';
    $lessonHeaderContext = $lessonContext ?? "Unit {$unitOrder} · Step {$levelOrder}";
@endphp
<div class="lesson-page{{ $previewMode ? ' lesson-page--preview' : '' }}" data-lesson data-node-id="{{ $node->id }}" @unless($previewMode) data-complete-url="{{ route('api.v1.nodes.complete.web', $node->id) }}" @endunless>
    <script type="application/json" data-lesson-questions>
        {!! json_encode($questions) !!}
    </script>

    <header class="lesson-header">
        <a href="{{ $lessonReturnUrl }}" class="lesson-close" aria-label="{{ $lessonReturnLabel }}">
            <span class="material-symbols-outlined" aria-hidden="true">arrow_back</span>
        </a>

        <div class="lesson-header__copy">
            <span>{{ $node->title }}</span>
            <strong>{{ $lessonHeaderContext }}</strong>
        </div>

        @if($previewMode)
            <span class="lesson-preview-badge">
                <span class="material-symbols-outlined" aria-hidden="true">visibility</span>
                Mode Preview Guru
            </span>
        @endif

        <div class="lesson-progress" aria-label="Progress lesson">
            <span data-lesson-progress style="width: 0%"></span>
        </div>

        <div class="lesson-lives" aria-label="Nyawa tersisa">
            <span class="material-symbols-outlined" aria-hidden="true">favorite</span>
            <strong>5</strong>
        </div>
    </header>

    <main class="lesson-main">
        @if($questions->isEmpty())
            <section class="lesson-empty" data-lesson-empty>
                <div class="lesson-empty__icon" aria-hidden="true">
                    <span class="material-symbols-outlined">draft</span>
                </div>
                <span class="lesson-kicker">Belum siap dipreview</span>
                <h1>Isi unit ini belum memiliki langkah pembelajaran.</h1>
                <p>Tambahkan teks, karakter, atau kuis terlebih dahulu agar hasilnya dapat dilihat seperti tampilan siswa.</p>
                @if($previewMode && isset($manageStepsUrl))
                    <div class="lesson-empty__actions">
                        <a href="{{ $manageStepsUrl }}" class="lesson-score-button">Kelola langkah</a>
                        <a href="{{ $lessonReturnUrl }}" class="lesson-empty__back">Kembali ke daftar isi unit</a>
                    </div>
                @endif
            </section>
        @else
            <section class="lesson-screen lesson-screen--new-letter" data-lesson-screen="new-letter" hidden style="display: flex; justify-content: center; align-items: center; width: 100%;">
                <div class="lesson-intro-panel" style="width: 100%; max-width: 800px; min-height: 360px; padding: 48px; box-sizing: border-box; display: flex; flex-direction: column; align-items: center; justify-content: center; background: var(--card-bg, #fff); border: 2px solid var(--line); border-radius: 20px; box-shadow: 0 8px 30px rgba(0,0,0,0.04); margin: 0 auto; gap: 24px;">
                    <div style="text-align: center;">
                        <span class="lesson-kicker" style="font-size: 14px; text-transform: uppercase; letter-spacing: 1px; color: var(--accent); font-weight: 700;">Huruf Baru</span>
                        <h1 data-lesson-letter style="font-size: 5rem; font-family: var(--font-jp); margin: 12px 0 6px 0; color: var(--ink);"></h1>
                        <p data-lesson-reading style="font-size: 1.5rem; color: var(--muted-soft); margin: 0; font-weight: 500;"></p>
                    </div>
                </div>
            </section>

            <section class="lesson-screen lesson-screen--new-vocabulary" data-lesson-screen="new-vocabulary" hidden style="display: flex; justify-content: center; align-items: center; width: 100%;">
                <div class="lesson-intro-panel" style="width: 100%; max-width: 800px; padding: 48px; box-sizing: border-box; display: flex; flex-direction: column; align-items: center; justify-content: center; background: var(--card-bg, #fff); border: 2px solid var(--line); border-radius: 20px; box-shadow: 0 8px 30px rgba(0,0,0,0.04); margin: 0 auto; gap: 24px;">
                    <div style="text-align: center;">
                        <span class="lesson-kicker" style="font-size: 14px; text-transform: uppercase; letter-spacing: 1px; color: var(--accent); font-weight: 700;">Kosakata Baru</span>
                        <h1 data-vocab-kata style="font-size: 4rem; font-family: var(--font-jp); margin: 12px 0 6px 0; color: var(--ink);"></h1>
                        <p data-vocab-romaji style="font-size: 1.5rem; color: var(--muted-soft); margin: 0; font-weight: 500;"></p>
                    </div>
                    
                    <div style="width: 100%; height: 1px; background: var(--line);"></div>
                    
                    <div style="text-align: center; width: 100%;">
                        <span style="font-size: 12px; color: var(--muted-soft); text-transform: uppercase; font-weight: 600; display: block; margin-bottom: 4px;">Arti / Terjemahan</span>
                        <h2 data-vocab-arti style="font-size: 2rem; margin: 0; color: var(--accent);"></h2>
                    </div>

                    <div data-vocab-meta-section style="width: 100%; display: flex; flex-direction: column; gap: 12px; background: #faf9f6; padding: 16px; border-radius: 12px; border: 1px solid var(--line);">
                        <div data-vocab-meta-kanji-row style="display: flex; justify-content: space-between; font-size: 14px;">
                            <span style="color: var(--muted-soft); font-weight: 600;">Kanji Terkait</span>
                            <span data-vocab-kanji style="font-weight: 700; font-family: var(--font-jp);"></span>
                        </div>
                        <div data-vocab-meta-tipe-row style="display: flex; justify-content: space-between; font-size: 14px;">
                            <span style="color: var(--muted-soft); font-weight: 600;">Tipe Kata</span>
                            <span data-vocab-tipe style="font-weight: 700; color: var(--ink-soft);"></span>
                        </div>
                        <div data-vocab-meta-level-row style="display: flex; justify-content: space-between; font-size: 14px;">
                            <span style="color: var(--muted-soft); font-weight: 600;">Level JLPT</span>
                            <span data-vocab-level style="font-weight: 700; color: var(--accent);"></span>
                        </div>
                        <div data-vocab-meta-desc-row style="display: flex; flex-direction: column; gap: 4px; font-size: 14px; border-top: 1px dashed var(--line); padding-top: 8px;">
                            <span style="color: var(--muted-soft); font-weight: 600;">Penjelasan / Deskripsi</span>
                            <span data-vocab-deskripsi style="color: var(--ink-soft); line-height: 1.4;"></span>
                        </div>
                    </div>
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
                    <a href="{{ $lessonReturnUrl }}" class="lesson-score-button">{{ $lessonReturnLabel }}</a>
                </div>
            </section>
        @endif
    </main>

    @if($questions->isNotEmpty())
        <footer class="lesson-footer" data-lesson-footer>
            <div class="lesson-feedback" data-lesson-feedback>
                <button type="button" class="lesson-skip" data-lesson-skip>Lewati</button>
            </div>
            <button type="button" class="lesson-action" data-lesson-action>Lanjut</button>
        </footer>
    @endif
</div>
@endsection
