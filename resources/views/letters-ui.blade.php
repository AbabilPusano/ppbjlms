@extends('layouts.lms-ui', ['title' => 'Huruf - LMS PPBJ'])

@section('body')
@php
    $activeMenu = 'LETTERS';
    $sets = [
        'HIRAGANA' => [
            'label' => 'Hiragana',
            'helper' => 'huruf dasar',
            'letters' => [['あ','a'],['い','i'],['う','u'],['え','e'],['お','o'],['か','ka'],['き','ki'],['く','ku'],['け','ke'],['こ','ko'],['さ','sa'],['し','shi'],['す','su'],['せ','se'],['そ','so'],['た','ta'],['ち','chi'],['つ','tsu'],['て','te'],['と','to'],['な','na'],['に','ni'],['ぬ','nu'],['ね','ne'],['の','no'],['は','ha'],['ひ','hi'],['ふ','fu'],['へ','he'],['ほ','ho']],
        ],
        'KATAKANA' => [
            'label' => 'Katakana',
            'helper' => 'kata serapan',
            'letters' => [['ア','a'],['イ','i'],['ウ','u'],['エ','e'],['オ','o'],['カ','ka'],['キ','ki'],['ク','ku'],['ケ','ke'],['コ','ko'],['サ','sa'],['シ','shi'],['ス','su'],['セ','se'],['ソ','so'],['タ','ta'],['チ','chi'],['ツ','tsu'],['テ','te'],['ト','to'],['ナ','na'],['ニ','ni'],['ヌ','nu'],['ネ','ne'],['ノ','no'],['ハ','ha'],['ヒ','hi'],['フ','fu'],['ヘ','he'],['ホ','ho']],
        ],
        'KANJI' => [
            'label' => 'Kanji',
            'helper' => 'makna dasar',
            'letters' => [['一','ichi'],['二','ni'],['三','san'],['四','shi'],['五','go'],['六','roku'],['七','nana'],['八','hachi'],['九','kyuu'],['十','juu'],['百','hyaku'],['千','sen'],['万','man'],['円','en'],['年','nen'],['日','nichi'],['月','getsu'],['火','ka'],['水','sui'],['木','moku'],['金','kin'],['土','do'],['人','hito'],['大','dai'],['小','shou'],['上','ue'],['下','shita'],['中','naka'],['山','yama'],['川','kawa']],
        ],
    ];
@endphp

<div class="app-container">
    @include('partials.student-sidebar')

    <main class="main-content">
        <section class="learning-shell letters-shell">
            <header class="learning-hero letters-hero">
                <div class="learning-hero__copy">
                    <span class="learning-hero__eyebrow">Ruang huruf Jepang</span>
                    <h1>Kenali hurufnya dulu, satu bentuk kecil setiap hari.</h1>
                    <p>
                        Pilih jenis huruf, lihat bentuknya, lalu cocokkan dengan romaji.
                        Halaman ini dibuat ringan supaya kamu bisa mengulang tanpa distraksi.
                    </p>
                </div>
                <div class="learning-hero__meta">
                    <div class="learning-stat-card"><span class="learning-stat-card__label">Set aktif</span><strong>仮</strong></div>
                    <div class="learning-stat-card"><span class="learning-stat-card__label">Huruf tampil</span><strong>30</strong></div>
                    <button type="button" class="learning-continue-btn letters-hero__button" data-ui-only>
                        <span class="material-symbols-outlined" aria-hidden="true">auto_stories</span>
                        Mulai ulang
                    </button>
                </div>
            </header>

            <section class="letters-board">
                <div class="section-heading">
                    <div>
                        <span class="ds-section-eyebrow">Pilih set huruf</span>
                        <h2 class="section-heading__title">Tabel huruf yang rapi untuk latihan baca</h2>
                    </div>
                    <p class="section-heading__desc">
                        Kartu hanya menampilkan huruf dan romaji. Tidak ada progress per kartu,
                        jadi fokusnya tetap ke pengenalan bentuk.
                    </p>
                </div>

                <div class="letters-tabs" role="tablist" aria-label="Pilih jenis huruf">
                    @foreach ($sets as $key => $set)
                        <button type="button" role="tab" class="letters-tab {{ $loop->first ? 'letters-tab--active' : '' }}" data-letter-tab="{{ $key }}">
                            <span class="material-symbols-outlined" aria-hidden="true">translate</span>
                            <span>{{ $set['label'] }}</span>
                            <small>{{ $set['helper'] }}</small>
                        </button>
                    @endforeach
                </div>

                @foreach ($sets as $key => $set)
                    <div class="letters-card-panel" data-letter-panel="{{ $key }}" @if (!$loop->first) hidden @endif>
                        <div class="letters-card-panel__head">
                            <div>
                                <span class="letters-card-panel__eyebrow">{{ $set['helper'] }}</span>
                                <h3>{{ $set['label'] }}</h3>
                            </div>
                            <span class="letters-card-panel__count">{{ count($set['letters']) }} huruf</span>
                        </div>

                        <div class="letters-card-grid" role="tabpanel" aria-label="{{ $set['label'] }}">
                            @foreach ($set['letters'] as $letter)
                                <article class="kana-card">
                                    <span class="kana-card__symbol">{{ $letter[0] }}</span>
                                    <span class="kana-card__romaji">{{ $letter[1] }}</span>
                                </article>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </section>
        </section>
    </main>
</div>
@endsection
