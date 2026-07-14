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
            'label' => 'Kanji N5',
            'helper' => '113 kanji JLPT N5',
            'letters' => [
                /* 数字 Number (13) */
                ['一','ichi'],['二','ni'],['三','san'],['四','shi/yon'],['五','go'],['六','roku'],['七','nana'],['八','hachi'],['九','kyuu'],['十','juu'],['百','hyaku'],['千','sen'],['円','en'],
                /* カレンダー Calendar (8) */
                ['月','tsuki'],['火','hi'],['水','mizu'],['木','ki'],['金','kane'],['土','tsuchi'],['日','nichi'],['年','nen'],
                /* 人 People (6) */
                ['人','hito'],['子','ko'],['男','otoko'],['女','onna'],['父','chichi'],['母','haha'],
                /* 体 Body (7) */
                ['口','kuchi'],['目','me'],['耳','mimi'],['手','te'],['足','ashi'],['体','karada'],['力','chikara'],
                /* 位置 Position (8) */
                ['上','ue'],['下','shita'],['左','hidari'],['右','migi'],['大','dai'],['小','shou'],['中','naka'],['外','soto'],
                /* 学校 School (7) */
                ['学','manabu'],['校','kou'],['先','saki'],['生','sei'],['本','hon'],['名','mei'],['友','tomo'],
                /* 自然 Nature (8) */
                ['山','yama'],['川','kawa'],['田','ta'],['石','ishi'],['雨','ame'],['夕','yuu'],['岩','iwa'],['音','oto'],
                /* 植物・動物 Plant & Animal (9) */
                ['林','hayashi'],['森','mori'],['花','hana'],['竹','take'],['犬','inu'],['貝','kai'],['牛','ushi'],['魚','sakana'],['鳥','tori'],
                /* 食べ物 Food (5) */
                ['米','kome'],['肉','niku'],['茶','cha'],['好','suki'],['物','mono'],
                /* 時間 Time (7) */
                ['今','ima'],['何','nani'],['分','fun'],['半','han'],['方','kata'],['時','toki'],['間','aida'],
                /* 町 City (8) */
                ['町','machi'],['寺','tera'],['東','higashi'],['西','nishi'],['南','minami'],['北','kita'],['車','kuruma'],['電','den'],
                /* 形容詞 Adjective (11) */
                ['高','taka'],['安','yasu'],['多','oo'],['少','suku'],['新','atara'],['古','furu'],['明','aka'],['暗','kura'],['長','naga'],['元','moto'],['気','ki'],
                /* 動詞 Verb (16) */
                ['見','miru'],['立','tatsu'],['入','hairu'],['出','deru'],['休','yasumu'],['行','iku'],['来','kuru'],['言','iu'],['帰','kaeru'],['書','kaku'],['読','yomu'],['話','hanasu'],['聞','kiku'],['食','taberu'],['飲','nomu'],['買','kau'],
            ],
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
                    <h1>Pengenalan Huruf Jepang</h1>
                    <p>
                        Kenali bentuk huruf dan cara bacanya di sini. Silakan pilih set huruf yang ingin kamu pelajari.
                    </p>
                </div>
                <div class="learning-hero__meta">
                    <div class="learning-stat-card letters-stat-card">
                        <span class="learning-stat-card__label">Set aktif</span>
                        <strong id="letters-active-set" class="letters-stat-text">Hiragana</strong>
                    </div>
                    <div class="learning-stat-card letters-stat-card">
                        <span class="learning-stat-card__label">Huruf tampil</span>
                        <strong id="letters-count">30</strong>
                    </div>
                </div>
            </header>

            <section class="letters-board">
                <div class="section-heading">
                    <div>
                        <span class="ds-section-eyebrow">Pilih set huruf</span>
                        <h2 class="section-heading__title">Referensi Huruf</h2>
                    </div>
                    <p class="section-heading__desc">
                        Klik kartu huruf untuk melihat detail cara baca dan penulisannya.
                    </p>
                </div>

                <div class="letters-tabs" role="tablist" aria-label="Pilih jenis huruf">
                    @foreach ($sets as $key => $set)
                        <button type="button" role="tab" class="letters-tab {{ $loop->first ? 'letters-tab--active' : '' }}" 
                            data-letter-tab="{{ $key }}"
                            data-letter-label="{{ $set['label'] }}"
                            data-letter-count="{{ count($set['letters']) }}">
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
                                <article class="kana-card" data-char="{{ $letter[0] }}" data-type="{{ strtolower($key) }}" role="button" tabindex="0" aria-label="Lihat detail {{ $letter[0] }}">
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

{{-- Kana / Kanji Detail Modal --}}
<div class="kana-modal-overlay" id="kanaModal" role="dialog" aria-modal="true" aria-labelledby="kanaModalTitle">
    <div class="kana-modal">
        <button type="button" class="kana-modal__close" id="kanaModalClose" aria-label="Tutup">×</button>

        <div class="kana-modal__hero">
            <span class="kana-modal__symbol" id="kanaModalSymbol"></span>
            <div class="kana-modal__hero-meta">
                <div style="display: flex; align-items: center; gap: 8px;">
                    <span class="kana-modal__type-badge" id="kanaModalBadge"></span>
                    <button type="button" class="kana-audio-btn" id="kanaAudioBtn" aria-label="Putar Suara" style="display: none;">
                        <span class="material-symbols-outlined" style="font-size: 16px;">volume_up</span>
                    </button>
                </div>
                <div class="kana-modal__romaji" id="kanaModalRomaji"></div>
                <div class="kana-modal__pronunciation" id="kanaModalPronunciation"></div>
            </div>
        </div>

        <div class="kana-modal__body" id="kanaModalBody">
            {{-- Diisi oleh JavaScript --}}
        </div>

        <hr class="kana-modal__divider">

        {{-- Writing Tutorial Section --}}
        <div class="kana-modal__writing">
            <p class="kana-modal__section-label">Cara Menulis (Urutan Goresan)</p>
            <div class="kana-writer-area" id="kanaWriterArea">
                <div class="kana-writer-canvas-wrap">
                    <div id="kanaWriterTarget"></div>
                    <div class="kana-writer-status" id="kanaWriterStatus">
                        <span class="material-symbols-outlined">autorenew</span>
                        <span>Memuat data...</span>
                    </div>
                </div>
                <div class="kana-writer-controls">
                    <button type="button" class="kana-writer-btn" id="kanaWriterPlayBtn">
                        <span class="material-symbols-outlined">play_arrow</span>
                        Animasikan
                    </button>
                    <button type="button" class="kana-writer-btn kana-writer-btn--outline" id="kanaWriterLoopBtn">
                        <span class="material-symbols-outlined">loop</span>
                        Ulangi Terus
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
const LETTER_DATA = {
  // ─── HIRAGANA ────────────────────────────────────────────────
  'あ': { type:'hiragana', romaji:'a', pronunciation:'Dibaca seperti "a" pada kata "apa"', stroke_count:3, examples:[{word:'あお',romaji:'ao',meaning:'biru'},{word:'あさ',romaji:'asa',meaning:'pagi'}] },
  'い': { type:'hiragana', romaji:'i', pronunciation:'Dibaca seperti "i" pada kata "ibu"', stroke_count:2, examples:[{word:'いぬ',romaji:'inu',meaning:'anjing'},{word:'いえ',romaji:'ie',meaning:'rumah'}] },
  'う': { type:'hiragana', romaji:'u', pronunciation:'Dibaca seperti "u" pada kata "ular", bibir tidak terlalu maju', stroke_count:2, examples:[{word:'うみ',romaji:'umi',meaning:'laut'},{word:'うた',romaji:'uta',meaning:'lagu'}] },
  'え': { type:'hiragana', romaji:'e', pronunciation:'Dibaca seperti "e" pada kata "ekor"', stroke_count:2, examples:[{word:'えき',romaji:'eki',meaning:'stasiun'},{word:'えん',romaji:'en',meaning:'yen / taman'}] },
  'お': { type:'hiragana', romaji:'o', pronunciation:'Dibaca seperti "o" pada kata "obat"', stroke_count:3, examples:[{word:'おか',romaji:'oka',meaning:'bukit'},{word:'おとこ',romaji:'otoko',meaning:'pria'}] },
  'か': { type:'hiragana', romaji:'ka', pronunciation:'Dibaca seperti "ka" pada kata "kapal"', stroke_count:3, examples:[{word:'かわ',romaji:'kawa',meaning:'sungai'},{word:'かお',romaji:'kao',meaning:'wajah'}] },
  'き': { type:'hiragana', romaji:'ki', pronunciation:'Dibaca seperti "ki" pada kata "kipas"', stroke_count:4, examples:[{word:'きく',romaji:'kiku',meaning:'mendengar'},{word:'きた',romaji:'kita',meaning:'utara'}] },
  'く': { type:'hiragana', romaji:'ku', pronunciation:'Dibaca seperti "ku" pada kata "kucing"', stroke_count:1, examples:[{word:'くに',romaji:'kuni',meaning:'negara'},{word:'くも',romaji:'kumo',meaning:'awan / laba-laba'}] },
  'け': { type:'hiragana', romaji:'ke', pronunciation:'Dibaca seperti "ke" pada kata "keras"', stroke_count:3, examples:[{word:'けさ',romaji:'kesa',meaning:'tadi pagi'},{word:'けむり',romaji:'kemuri',meaning:'asap'}] },
  'こ': { type:'hiragana', romaji:'ko', pronunciation:'Dibaca seperti "ko" pada kata "kota"', stroke_count:2, examples:[{word:'こえ',romaji:'koe',meaning:'suara'},{word:'ここ',romaji:'koko',meaning:'di sini'}] },
  'さ': { type:'hiragana', romaji:'sa', pronunciation:'Dibaca seperti "sa" pada kata "satu"', stroke_count:3, examples:[{word:'さかな',romaji:'sakana',meaning:'ikan'},{word:'さくら',romaji:'sakura',meaning:'bunga sakura'}] },
  'し': { type:'hiragana', romaji:'shi', pronunciation:'Dibaca seperti "shi" — antara "si" dan "shi" dalam bahasa Inggris', stroke_count:1, examples:[{word:'しろ',romaji:'shiro',meaning:'putih'},{word:'しお',romaji:'shio',meaning:'garam'}] },
  'す': { type:'hiragana', romaji:'su', pronunciation:'Dibaca seperti "su" pada kata "susu"', stroke_count:2, examples:[{word:'すき',romaji:'suki',meaning:'suka'},{word:'すし',romaji:'sushi',meaning:'sushi'}] },
  'せ': { type:'hiragana', romaji:'se', pronunciation:'Dibaca seperti "se" pada kata "sepatu"', stroke_count:3, examples:[{word:'せかい',romaji:'sekai',meaning:'dunia'},{word:'せんせい',romaji:'sensei',meaning:'guru'}] },
  'そ': { type:'hiragana', romaji:'so', pronunciation:'Dibaca seperti "so" pada kata "soal"', stroke_count:2, examples:[{word:'そら',romaji:'sora',meaning:'langit'},{word:'そと',romaji:'soto',meaning:'luar'}] },
  'た': { type:'hiragana', romaji:'ta', pronunciation:'Dibaca seperti "ta" pada kata "tahu"', stroke_count:4, examples:[{word:'たべる',romaji:'taberu',meaning:'makan'},{word:'たかい',romaji:'takai',meaning:'tinggi / mahal'}] },
  'ち': { type:'hiragana', romaji:'chi', pronunciation:'Dibaca seperti "chi" dalam bahasa Inggris (bukan "ci")', stroke_count:2, examples:[{word:'ちず',romaji:'chizu',meaning:'peta'},{word:'ちち',romaji:'chichi',meaning:'ayah'}] },
  'つ': { type:'hiragana', romaji:'tsu', pronunciation:'Dibaca seperti "tsu" — gabungan "t" dan "su"', stroke_count:1, examples:[{word:'つき',romaji:'tsuki',meaning:'bulan'},{word:'つくえ',romaji:'tsukue',meaning:'meja'}] },
  'て': { type:'hiragana', romaji:'te', pronunciation:'Dibaca seperti "te" pada kata "telur"', stroke_count:2, examples:[{word:'てがみ',romaji:'tegami',meaning:'surat'},{word:'てんき',romaji:'tenki',meaning:'cuaca'}] },
  'と': { type:'hiragana', romaji:'to', pronunciation:'Dibaca seperti "to" pada kata "tomat"', stroke_count:2, examples:[{word:'とり',romaji:'tori',meaning:'burung'},{word:'ともだち',romaji:'tomodachi',meaning:'teman'}] },
  'な': { type:'hiragana', romaji:'na', pronunciation:'Dibaca seperti "na" pada kata "nasi"', stroke_count:4, examples:[{word:'なまえ',romaji:'namae',meaning:'nama'},{word:'なつ',romaji:'natsu',meaning:'musim panas'}] },
  'に': { type:'hiragana', romaji:'ni', pronunciation:'Dibaca seperti "ni" pada kata "niat"', stroke_count:3, examples:[{word:'にく',romaji:'niku',meaning:'daging'},{word:'にほん',romaji:'nihon',meaning:'Jepang'}] },
  'ぬ': { type:'hiragana', romaji:'nu', pronunciation:'Dibaca seperti "nu" pada kata "nuri"', stroke_count:2, examples:[{word:'ぬの',romaji:'nuno',meaning:'kain'},{word:'いぬ',romaji:'inu',meaning:'anjing'}] },
  'ね': { type:'hiragana', romaji:'ne', pronunciation:'Dibaca seperti "ne" pada kata "nenek"', stroke_count:2, examples:[{word:'ねこ',romaji:'neko',meaning:'kucing'},{word:'ねる',romaji:'neru',meaning:'tidur'}] },
  'の': { type:'hiragana', romaji:'no', pronunciation:'Dibaca seperti "no" pada kata "nomor"', stroke_count:1, examples:[{word:'のむ',romaji:'nomu',meaning:'minum'},{word:'のり',romaji:'nori',meaning:'rumput laut'}] },
  'は': { type:'hiragana', romaji:'ha', pronunciation:'Dibaca seperti "ha" pada kata "hari"', stroke_count:3, examples:[{word:'はな',romaji:'hana',meaning:'bunga / hidung'},{word:'はし',romaji:'hashi',meaning:'sumpit / jembatan'}] },
  'ひ': { type:'hiragana', romaji:'hi', pronunciation:'Dibaca seperti "hi" pada kata "hijau"', stroke_count:1, examples:[{word:'ひと',romaji:'hito',meaning:'orang'},{word:'ひかり',romaji:'hikari',meaning:'cahaya'}] },
  'ふ': { type:'hiragana', romaji:'fu', pronunciation:'Dibaca antara "fu" dan "hu", angin keluar lewat bibir sedikit terbuka', stroke_count:4, examples:[{word:'ふゆ',romaji:'fuyu',meaning:'musim dingin'},{word:'ふね',romaji:'fune',meaning:'kapal'}] },
  'へ': { type:'hiragana', romaji:'he', pronunciation:'Dibaca seperti "he" pada kata "hemat"', stroke_count:1, examples:[{word:'へや',romaji:'heya',meaning:'kamar'},{word:'へた',romaji:'heta',meaning:'tidak pandai'}] },
  'ほ': { type:'hiragana', romaji:'ho', pronunciation:'Dibaca seperti "ho" pada kata "hobi"', stroke_count:4, examples:[{word:'ほん',romaji:'hon',meaning:'buku'},{word:'ほし',romaji:'hoshi',meaning:'bintang'}] },

  // ─── KATAKANA ────────────────────────────────────────────────
  'ア': { type:'katakana', romaji:'a', pronunciation:'Dibaca seperti "a" pada kata "apa"', stroke_count:2, examples:[{word:'アイス',romaji:'aisu',meaning:'es krim'},{word:'アニメ',romaji:'anime',meaning:'anime'}] },
  'イ': { type:'katakana', romaji:'i', pronunciation:'Dibaca seperti "i" pada kata "ibu"', stroke_count:2, examples:[{word:'イタリア',romaji:'itaria',meaning:'Italia'},{word:'イメージ',romaji:'imeeji',meaning:'image / kesan'}] },
  'ウ': { type:'katakana', romaji:'u', pronunciation:'Dibaca seperti "u" pada kata "ular"', stroke_count:3, examples:[{word:'ウール',romaji:'uuru',meaning:'wol'},{word:'ウイルス',romaji:'uirusu',meaning:'virus'}] },
  'エ': { type:'katakana', romaji:'e', pronunciation:'Dibaca seperti "e" pada kata "ekor"', stroke_count:3, examples:[{word:'エアコン',romaji:'eakon',meaning:'AC'},{word:'エネルギー',romaji:'enerugii',meaning:'energi'}] },
  'オ': { type:'katakana', romaji:'o', pronunciation:'Dibaca seperti "o" pada kata "obat"', stroke_count:3, examples:[{word:'オレンジ',romaji:'orenji',meaning:'jeruk / oranye'},{word:'オフィス',romaji:'ofisu',meaning:'kantor'}] },
  'カ': { type:'katakana', romaji:'ka', pronunciation:'Dibaca seperti "ka" pada kata "kapal"', stroke_count:2, examples:[{word:'カメラ',romaji:'kamera',meaning:'kamera'},{word:'カレー',romaji:'karee',meaning:'kari'}] },
  'キ': { type:'katakana', romaji:'ki', pronunciation:'Dibaca seperti "ki" pada kata "kipas"', stroke_count:3, examples:[{word:'キッチン',romaji:'kicchin',meaning:'dapur'},{word:'キー',romaji:'kii',meaning:'kunci'}] },
  'ク': { type:'katakana', romaji:'ku', pronunciation:'Dibaca seperti "ku" pada kata "kucing"', stroke_count:2, examples:[{word:'クラス',romaji:'kurasu',meaning:'kelas'},{word:'クッキー',romaji:'kukkii',meaning:'kue kering'}] },
  'ケ': { type:'katakana', romaji:'ke', pronunciation:'Dibaca seperti "ke" pada kata "keras"', stroke_count:3, examples:[{word:'ケーキ',romaji:'keeki',meaning:'kue'},{word:'ケータイ',romaji:'keetai',meaning:'ponsel'}] },
  'コ': { type:'katakana', romaji:'ko', pronunciation:'Dibaca seperti "ko" pada kata "kota"', stroke_count:2, examples:[{word:'コーヒー',romaji:'koohii',meaning:'kopi'},{word:'コンピュータ',romaji:'konpyuuta',meaning:'komputer'}] },
  'サ': { type:'katakana', romaji:'sa', pronunciation:'Dibaca seperti "sa" pada kata "satu"', stroke_count:3, examples:[{word:'サッカー',romaji:'sakkaa',meaning:'sepak bola'},{word:'サラダ',romaji:'sarada',meaning:'salad'}] },
  'シ': { type:'katakana', romaji:'shi', pronunciation:'Dibaca seperti "shi"', stroke_count:3, examples:[{word:'シャツ',romaji:'shatsu',meaning:'kemeja'},{word:'システム',romaji:'shisutemu',meaning:'sistem'}] },
  'ス': { type:'katakana', romaji:'su', pronunciation:'Dibaca seperti "su"', stroke_count:2, examples:[{word:'スポーツ',romaji:'supootsu',meaning:'olahraga'},{word:'スーパー',romaji:'suupaa',meaning:'supermarket'}] },
  'セ': { type:'katakana', romaji:'se', pronunciation:'Dibaca seperti "se"', stroke_count:2, examples:[{word:'セーター',romaji:'seetaa',meaning:'sweater'},{word:'センター',romaji:'sentaa',meaning:'pusat / center'}] },
  'ソ': { type:'katakana', romaji:'so', pronunciation:'Dibaca seperti "so"', stroke_count:2, examples:[{word:'ソファ',romaji:'sofa',meaning:'sofa'},{word:'ソース',romaji:'soosu',meaning:'saus'}] },
  'タ': { type:'katakana', romaji:'ta', pronunciation:'Dibaca seperti "ta"', stroke_count:3, examples:[{word:'タクシー',romaji:'takushii',meaning:'taksi'},{word:'タイム',romaji:'taimu',meaning:'waktu'}] },
  'チ': { type:'katakana', romaji:'chi', pronunciation:'Dibaca seperti "chi"', stroke_count:3, examples:[{word:'チケット',romaji:'chiketto',meaning:'tiket'},{word:'チーズ',romaji:'chiizu',meaning:'keju'}] },
  'ツ': { type:'katakana', romaji:'tsu', pronunciation:'Dibaca seperti "tsu"', stroke_count:3, examples:[{word:'ツアー',romaji:'tsuaa',meaning:'tur'},{word:'ツール',romaji:'tsuuru',meaning:'alat / tool'}] },
  'テ': { type:'katakana', romaji:'te', pronunciation:'Dibaca seperti "te"', stroke_count:3, examples:[{word:'テレビ',romaji:'terebi',meaning:'televisi'},{word:'テスト',romaji:'tesuto',meaning:'tes / ujian'}] },
  'ト': { type:'katakana', romaji:'to', pronunciation:'Dibaca seperti "to"', stroke_count:2, examples:[{word:'トイレ',romaji:'toire',meaning:'toilet'},{word:'トマト',romaji:'tomato',meaning:'tomat'}] },
  'ナ': { type:'katakana', romaji:'na', pronunciation:'Dibaca seperti "na"', stroke_count:2, examples:[{word:'ナイフ',romaji:'naifu',meaning:'pisau'},{word:'ナンバー',romaji:'nanbaa',meaning:'nomor'}] },
  'ニ': { type:'katakana', romaji:'ni', pronunciation:'Dibaca seperti "ni"', stroke_count:2, examples:[{word:'ニュース',romaji:'nyuusu',meaning:'berita'},{word:'ニット',romaji:'nitto',meaning:'rajutan / knit'}] },
  'ヌ': { type:'katakana', romaji:'nu', pronunciation:'Dibaca seperti "nu"', stroke_count:2, examples:[{word:'ヌードル',romaji:'nuudoru',meaning:'mie'},{word:'ヌード',romaji:'nuudo',meaning:'polos / nude'}] },
  'ネ': { type:'katakana', romaji:'ne', pronunciation:'Dibaca seperti "ne"', stroke_count:4, examples:[{word:'ネクタイ',romaji:'nekutai',meaning:'dasi'},{word:'ネット',romaji:'netto',meaning:'internet / net'}] },
  'ノ': { type:'katakana', romaji:'no', pronunciation:'Dibaca seperti "no"', stroke_count:1, examples:[{word:'ノート',romaji:'nooto',meaning:'buku catatan'},{word:'ノック',romaji:'nokku',meaning:'ketukan'}] },
  'ハ': { type:'katakana', romaji:'ha', pronunciation:'Dibaca seperti "ha"', stroke_count:2, examples:[{word:'ハンバーガー',romaji:'hanbaagaa',meaning:'hamburger'},{word:'ハム',romaji:'hamu',meaning:'ham'}] },
  'ヒ': { type:'katakana', romaji:'hi', pronunciation:'Dibaca seperti "hi"', stroke_count:2, examples:[{word:'ヒント',romaji:'hinto',meaning:'petunjuk'},{word:'ヒーロー',romaji:'hiiroo',meaning:'pahlawan'}] },
  'フ': { type:'katakana', romaji:'fu', pronunciation:'Dibaca seperti "fu"', stroke_count:1, examples:[{word:'フランス',romaji:'furansu',meaning:'Prancis'},{word:'フォーク',romaji:'fooku',meaning:'garpu'}] },
  'ヘ': { type:'katakana', romaji:'he', pronunciation:'Dibaca seperti "he"', stroke_count:1, examples:[{word:'ヘリコプター',romaji:'herikoputaa',meaning:'helikopter'},{word:'ヘルプ',romaji:'herupu',meaning:'bantuan'}] },
  'ホ': { type:'katakana', romaji:'ho', pronunciation:'Dibaca seperti "ho"', stroke_count:4, examples:[{word:'ホテル',romaji:'hoteru',meaning:'hotel'},{word:'ホーム',romaji:'hoomu',meaning:'peron / rumah'}] },

  // ─── KANJI ───────────────────────────────────────────────────
  '一': { type:'kanji', romaji:'ichi / hito', meaning:'satu', onyomi:'イチ (ichi), イツ (itsu)', kunyomi:'ひと-つ (hito-tsu)', stroke_count:1, radical:'一（satu）', jlpt:'N5', examples:[{word:'一日',romaji:'ichinichi',meaning:'satu hari'},{word:'一人',romaji:'hitori',meaning:'satu orang'},{word:'一番',romaji:'ichiban',meaning:'paling / nomor satu'}] },
  '二': { type:'kanji', romaji:'ni / futa', meaning:'dua', onyomi:'ニ (ni)', kunyomi:'ふた-つ (futa-tsu)', stroke_count:2, radical:'二（dua）', jlpt:'N5', examples:[{word:'二月',romaji:'nigatsu',meaning:'Februari'},{word:'二人',romaji:'futari',meaning:'dua orang'},{word:'二回',romaji:'nikai',meaning:'dua kali'}] },
  '三': { type:'kanji', romaji:'san / mit', meaning:'tiga', onyomi:'サン (san)', kunyomi:'み-つ (mi-tsu)', stroke_count:3, radical:'一（satu）', jlpt:'N5', examples:[{word:'三月',romaji:'sangatsu',meaning:'Maret'},{word:'三人',romaji:'sannin',meaning:'tiga orang'},{word:'三角',romaji:'sankaku',meaning:'segitiga'}] },
  '四': { type:'kanji', romaji:'shi / yon', meaning:'empat', onyomi:'シ (shi)', kunyomi:'よ-つ (yo-tsu), よん (yon)', stroke_count:5, radical:'囗（kotak）', jlpt:'N5', examples:[{word:'四月',romaji:'shigatsu',meaning:'April'},{word:'四人',romaji:'yonin',meaning:'empat orang'},{word:'四角',romaji:'shikaku',meaning:'persegi'}] },
  '五': { type:'kanji', romaji:'go / itsu', meaning:'lima', onyomi:'ゴ (go)', kunyomi:'いつ-つ (itsu-tsu)', stroke_count:4, radical:'二（dua）', jlpt:'N5', examples:[{word:'五月',romaji:'gogatsu',meaning:'Mei'},{word:'五人',romaji:'gonin',meaning:'lima orang'},{word:'五感',romaji:'gokan',meaning:'lima indera'}] },
  '六': { type:'kanji', romaji:'roku / mut', meaning:'enam', onyomi:'ロク (roku)', kunyomi:'む-つ (mu-tsu)', stroke_count:4, radical:'八（delapan）', jlpt:'N5', examples:[{word:'六月',romaji:'rokugatsu',meaning:'Juni'},{word:'六人',romaji:'rokunin',meaning:'enam orang'},{word:'六角',romaji:'rokkaku',meaning:'segi enam'}] },
  '七': { type:'kanji', romaji:'shichi / nana', meaning:'tujuh', onyomi:'シチ (shichi)', kunyomi:'なな-つ (nana-tsu)', stroke_count:2, radical:'一（satu）', jlpt:'N5', examples:[{word:'七月',romaji:'shichigatsu',meaning:'Juli'},{word:'七人',romaji:'shichinin',meaning:'tujuh orang'},{word:'七夕',romaji:'tanabata',meaning:'festival bintang'}] },
  '八': { type:'kanji', romaji:'hachi / ya', meaning:'delapan', onyomi:'ハチ (hachi)', kunyomi:'や-つ (ya-tsu)', stroke_count:2, radical:'八（delapan）', jlpt:'N5', examples:[{word:'八月',romaji:'hachigatsu',meaning:'Agustus'},{word:'八人',romaji:'hachinin',meaning:'delapan orang'},{word:'八百',romaji:'happyaku',meaning:'delapan ratus'}] },
  '九': { type:'kanji', romaji:'kyuu / ku', meaning:'sembilan', onyomi:'キュウ (kyuu), ク (ku)', kunyomi:'ここの-つ (kokono-tsu)', stroke_count:2, radical:'乙（kedua）', jlpt:'N5', examples:[{word:'九月',romaji:'kugatsu',meaning:'September'},{word:'九人',romaji:'kyuunin',meaning:'sembilan orang'},{word:'九州',romaji:'kyuushuu',meaning:'Kyushu (pulau)'}] },
  '十': { type:'kanji', romaji:'juu / too', meaning:'sepuluh', onyomi:'ジュウ (juu), ジッ (jit)', kunyomi:'とお (too)', stroke_count:2, radical:'十（sepuluh）', jlpt:'N5', examples:[{word:'十月',romaji:'juugatsu',meaning:'Oktober'},{word:'十人',romaji:'juunin',meaning:'sepuluh orang'},{word:'十分',romaji:'juubun',meaning:'cukup / sepuluh menit'}] },
  '百': { type:'kanji', romaji:'hyaku', meaning:'seratus', onyomi:'ヒャク (hyaku)', kunyomi:'もも (momo)', stroke_count:6, radical:'白（putih）', jlpt:'N5', examples:[{word:'百円',romaji:'hyakuen',meaning:'seratus yen'},{word:'数百',romaji:'suuhyaku',meaning:'beberapa ratus'},{word:'百貨店',romaji:'hyakkaten',meaning:'toserba'}] },
  '千': { type:'kanji', romaji:'sen / chi', meaning:'seribu', onyomi:'セン (sen)', kunyomi:'ち (chi)', stroke_count:3, radical:'十（sepuluh）', jlpt:'N5', examples:[{word:'千円',romaji:'senen',meaning:'seribu yen'},{word:'千葉',romaji:'chiba',meaning:'Chiba (kota)'},{word:'三千',romaji:'sanzen',meaning:'tiga ribu'}] },
  '円': { type:'kanji', romaji:'en / maru', meaning:'lingkaran / yen', onyomi:'エン (en)', kunyomi:'まる-い (maru-i)', stroke_count:4, radical:'冂（kotak atas）', jlpt:'N5', examples:[{word:'百円',romaji:'hyakuen',meaning:'seratus yen'},{word:'円形',romaji:'enkei',meaning:'bentuk lingkaran'},{word:'円満',romaji:'enman',meaning:'harmonis'}] },

  // ─── KANJI N5 - TAMBAHAN ────────────────────────────────────

  // カレンダー (Calendar)
  '年': { type:'kanji', romaji:'nen / toshi', meaning:'tahun', onyomi:'ネン (nen)', kunyomi:'とし (toshi)', stroke_count:6, radical:'干（kering）', jlpt:'N5', examples:[{word:'今年',romaji:'kotoshi',meaning:'tahun ini'},{word:'来年',romaji:'rainen',meaning:'tahun depan'},{word:'去年',romaji:'kyonen',meaning:'tahun lalu'}] },
  '日': { type:'kanji', romaji:'nichi / hi', meaning:'hari / matahari', onyomi:'ニチ (nichi), ジツ (jitsu)', kunyomi:'ひ (hi), -か (-ka)', stroke_count:4, radical:'日（matahari）', jlpt:'N5', examples:[{word:'今日',romaji:'kyou',meaning:'hari ini'},{word:'日本',romaji:'nihon',meaning:'Jepang'},{word:'毎日',romaji:'mainichi',meaning:'setiap hari'}] },
  '月': { type:'kanji', romaji:'getsu / tsuki', meaning:'bulan / rembulan', onyomi:'ゲツ (getsu), ガツ (gatsu)', kunyomi:'つき (tsuki)', stroke_count:4, radical:'月（bulan）', jlpt:'N5', examples:[{word:'今月',romaji:'kongetsu',meaning:'bulan ini'},{word:'月曜日',romaji:'getsuyoubi',meaning:'hari Senin'},{word:'三日月',romaji:'mikazuki',meaning:'bulan sabit'}] },
  '火': { type:'kanji', romaji:'ka / hi', meaning:'api', onyomi:'カ (ka)', kunyomi:'ひ (hi), ほ (ho)', stroke_count:4, radical:'火（api）', jlpt:'N5', examples:[{word:'火曜日',romaji:'kayoubi',meaning:'hari Selasa'},{word:'花火',romaji:'hanabi',meaning:'kembang api'},{word:'火山',romaji:'kazan',meaning:'gunung berapi'}] },
  '水': { type:'kanji', romaji:'sui / mizu', meaning:'air', onyomi:'スイ (sui)', kunyomi:'みず (mizu)', stroke_count:4, radical:'水（air）', jlpt:'N5', examples:[{word:'水曜日',romaji:'suiyoubi',meaning:'hari Rabu'},{word:'水泳',romaji:'suiei',meaning:'renang'},{word:'水道',romaji:'suidou',meaning:'saluran air'}] },
  '木': { type:'kanji', romaji:'moku / ki', meaning:'pohon / kayu', onyomi:'モク (moku), ボク (boku)', kunyomi:'き (ki), こ (ko)', stroke_count:4, radical:'木（pohon）', jlpt:'N5', examples:[{word:'木曜日',romaji:'mokuyoubi',meaning:'hari Kamis'},{word:'木造',romaji:'mokuzou',meaning:'berbahan kayu'},{word:'木材',romaji:'mokuzai',meaning:'bahan kayu'}] },
  '金': { type:'kanji', romaji:'kin / kane', meaning:'emas / uang', onyomi:'キン (kin), コン (kon)', kunyomi:'かね (kane)', stroke_count:8, radical:'金（emas）', jlpt:'N5', examples:[{word:'金曜日',romaji:'kinyoubi',meaning:'hari Jumat'},{word:'お金',romaji:'okane',meaning:'uang'},{word:'金メダル',romaji:'kin medaru',meaning:'medali emas'}] },
  '土': { type:'kanji', romaji:'do / tsuchi', meaning:'tanah / bumi', onyomi:'ド (do), ト (to)', kunyomi:'つち (tsuchi)', stroke_count:3, radical:'土（tanah）', jlpt:'N5', examples:[{word:'土曜日',romaji:'doyoubi',meaning:'hari Sabtu'},{word:'土地',romaji:'tochi',meaning:'lahan'},{word:'土台',romaji:'dodai',meaning:'fondasi'}] },

  // 人 (People)
  '人': { type:'kanji', romaji:'jin / hito', meaning:'orang / manusia', onyomi:'ジン (jin), ニン (nin)', kunyomi:'ひと (hito)', stroke_count:2, radical:'人（manusia）', jlpt:'N5', examples:[{word:'日本人',romaji:'nihonjin',meaning:'orang Jepang'},{word:'一人',romaji:'hitori',meaning:'satu orang'},{word:'人気',romaji:'ninki',meaning:'popularitas'}] },
  '子': { type:'kanji', romaji:'shi / ko', meaning:'anak', onyomi:'シ (shi)', kunyomi:'こ (ko)', stroke_count:3, radical:'子（anak）', jlpt:'N5', examples:[{word:'子供',romaji:'kodomo',meaning:'anak-anak'},{word:'女子',romaji:'joshi',meaning:'perempuan'},{word:'子犬',romaji:'koinu',meaning:'anak anjing'}] },
  '男': { type:'kanji', romaji:'dan / otoko', meaning:'laki-laki', onyomi:'ダン (dan), ナン (nan)', kunyomi:'おとこ (otoko)', stroke_count:7, radical:'田（sawah）', jlpt:'N5', examples:[{word:'男の人',romaji:'otoko no hito',meaning:'pria'},{word:'男性',romaji:'dansei',meaning:'laki-laki (formal)'},{word:'長男',romaji:'chounan',meaning:'anak laki-laki sulung'}] },
  '女': { type:'kanji', romaji:'jo / onna', meaning:'perempuan', onyomi:'ジョ (jo), ニョ (nyo)', kunyomi:'おんな (onna)', stroke_count:3, radical:'女（perempuan）', jlpt:'N5', examples:[{word:'女の人',romaji:'onna no hito',meaning:'wanita'},{word:'女性',romaji:'josei',meaning:'perempuan (formal)'},{word:'長女',romaji:'choujo',meaning:'anak perempuan sulung'}] },
  '父': { type:'kanji', romaji:'fu / chichi', meaning:'ayah', onyomi:'フ (fu)', kunyomi:'ちち (chichi)', stroke_count:4, radical:'父（ayah）', jlpt:'N5', examples:[{word:'父親',romaji:'chichioya',meaning:'ayah'},{word:'父母',romaji:'fubo',meaning:'orang tua'},{word:'祖父',romaji:'sofu',meaning:'kakek'}] },
  '母': { type:'kanji', romaji:'bo / haha', meaning:'ibu', onyomi:'ボ (bo)', kunyomi:'はは (haha)', stroke_count:5, radical:'母（ibu）', jlpt:'N5', examples:[{word:'母親',romaji:'hahaoya',meaning:'ibu'},{word:'父母',romaji:'fubo',meaning:'orang tua'},{word:'祖母',romaji:'sobo',meaning:'nenek'}] },

  // 体 (Body)
  '口': { type:'kanji', romaji:'kou / kuchi', meaning:'mulut', onyomi:'コウ (kou)', kunyomi:'くち (kuchi)', stroke_count:3, radical:'口（mulut）', jlpt:'N5', examples:[{word:'出口',romaji:'deguchi',meaning:'pintu keluar'},{word:'入口',romaji:'iriguchi',meaning:'pintu masuk'},{word:'口調',romaji:'kuchou',meaning:'cara berbicara'}] },
  '目': { type:'kanji', romaji:'moku / me', meaning:'mata', onyomi:'モク (moku)', kunyomi:'め (me)', stroke_count:5, radical:'目（mata）', jlpt:'N5', examples:[{word:'目的',romaji:'mokuteki',meaning:'tujuan'},{word:'目玉',romaji:'medama',meaning:'bola mata'},{word:'注目',romaji:'chuumoku',meaning:'perhatian'}] },
  '耳': { type:'kanji', romaji:'ji / mimi', meaning:'telinga', onyomi:'ジ (ji)', kunyomi:'みみ (mimi)', stroke_count:6, radical:'耳（telinga）', jlpt:'N5', examples:[{word:'耳鳴り',romaji:'miminari',meaning:'dengung telinga'},{word:'中耳炎',romaji:'chuujien',meaning:'otitis media'},{word:'耳元',romaji:'mimamori',meaning:'dekat telinga'}] },
  '手': { type:'kanji', romaji:'shu / te', meaning:'tangan', onyomi:'シュ (shu)', kunyomi:'て (te)', stroke_count:4, radical:'手（tangan）', jlpt:'N5', examples:[{word:'上手',romaji:'jouzu',meaning:'mahir'},{word:'下手',romaji:'heta',meaning:'tidak mahir'},{word:'手紙',romaji:'tegami',meaning:'surat'}] },
  '足': { type:'kanji', romaji:'soku / ashi', meaning:'kaki', onyomi:'ソク (soku)', kunyomi:'あし (ashi), た-りる (ta-riru)', stroke_count:7, radical:'足（kaki）', jlpt:'N5', examples:[{word:'足音',romaji:'ashioto',meaning:'suara langkah'},{word:'足元',romaji:'ashimoto',meaning:'kaki / sekitar kaki'},{word:'満足',romaji:'manzoku',meaning:'kepuasan'}] },
  '体': { type:'kanji', romaji:'tai / karada', meaning:'tubuh', onyomi:'タイ (tai), テイ (tei)', kunyomi:'からだ (karada)', stroke_count:7, radical:'人（manusia）', jlpt:'N5', examples:[{word:'体力',romaji:'tairyoku',meaning:'tenaga fisik'},{word:'体重',romaji:'taijuu',meaning:'berat badan'},{word:'体操',romaji:'taisou',meaning:'senam'}] },
  '力': { type:'kanji', romaji:'ryoku / chikara', meaning:'tenaga / kekuatan', onyomi:'リョク (ryoku), リキ (riki)', kunyomi:'ちから (chikara)', stroke_count:2, radical:'力（kekuatan）', jlpt:'N5', examples:[{word:'体力',romaji:'tairyoku',meaning:'tenaga fisik'},{word:'努力',romaji:'doryoku',meaning:'usaha'},{word:'力強い',romaji:'chikarazuyoi',meaning:'kuat'}] },

  // 位置 (Position)
  '上': { type:'kanji', romaji:'jou / ue', meaning:'atas', onyomi:'ジョウ (jou), ショウ (shou)', kunyomi:'うえ (ue), のぼ-る (nobo-ru)', stroke_count:3, radical:'一（satu）', jlpt:'N5', examples:[{word:'上手',romaji:'jouzu',meaning:'pandai / mahir'},{word:'上下',romaji:'jouge',meaning:'atas bawah'},{word:'屋上',romaji:'okujou',meaning:'atap gedung'}] },
  '下': { type:'kanji', romaji:'ka / shita', meaning:'bawah', onyomi:'カ (ka), ゲ (ge)', kunyomi:'した (shita), くだ-る (kuda-ru)', stroke_count:3, radical:'一（satu）', jlpt:'N5', examples:[{word:'下手',romaji:'heta',meaning:'tidak pandai'},{word:'地下',romaji:'chika',meaning:'bawah tanah'},{word:'下車',romaji:'gesha',meaning:'turun dari kendaraan'}] },
  '左': { type:'kanji', romaji:'sa / hidari', meaning:'kiri', onyomi:'サ (sa)', kunyomi:'ひだり (hidari)', stroke_count:5, radical:'工（kerja）', jlpt:'N5', examples:[{word:'左右',romaji:'sayuu',meaning:'kiri kanan'},{word:'左折',romaji:'sasetsu',meaning:'belok kiri'},{word:'左手',romaji:'hidarite',meaning:'tangan kiri'}] },
  '右': { type:'kanji', romaji:'u / migi', meaning:'kanan', onyomi:'ウ (u)', kunyomi:'みぎ (migi)', stroke_count:5, radical:'口（mulut）', jlpt:'N5', examples:[{word:'左右',romaji:'sayuu',meaning:'kiri kanan'},{word:'右折',romaji:'usetsu',meaning:'belok kanan'},{word:'右手',romaji:'migite',meaning:'tangan kanan'}] },
  '大': { type:'kanji', romaji:'dai / oo', meaning:'besar', onyomi:'ダイ (dai), タイ (tai)', kunyomi:'おお-きい (oo-kii)', stroke_count:3, radical:'大（besar）', jlpt:'N5', examples:[{word:'大きい',romaji:'ookii',meaning:'besar'},{word:'大学',romaji:'daigaku',meaning:'universitas'},{word:'大切',romaji:'taisetsu',meaning:'penting / berharga'}] },
  '小': { type:'kanji', romaji:'shou / ko / o', meaning:'kecil', onyomi:'ショウ (shou)', kunyomi:'ちい-さい (chii-sai), こ (ko), お (o)', stroke_count:3, radical:'小（kecil）', jlpt:'N5', examples:[{word:'小さい',romaji:'chiisai',meaning:'kecil'},{word:'小学校',romaji:'shougakkou',meaning:'sekolah dasar'},{word:'小説',romaji:'shousetsu',meaning:'novel'}] },
  '中': { type:'kanji', romaji:'chuu / naka', meaning:'tengah / dalam', onyomi:'チュウ (chuu)', kunyomi:'なか (naka)', stroke_count:4, radical:'｜（garis vertikal）', jlpt:'N5', examples:[{word:'中学校',romaji:'chuugakkou',meaning:'SMP'},{word:'中国',romaji:'chuugoku',meaning:'China'},{word:'一日中',romaji:'ichinichijuu',meaning:'sepanjang hari'}] },
  '外': { type:'kanji', romaji:'gai / soto', meaning:'luar', onyomi:'ガイ (gai), ゲ (ge)', kunyomi:'そと (soto), はず-れる (hazu-reru)', stroke_count:5, radical:'タ（sore）', jlpt:'N5', examples:[{word:'外国',romaji:'gaikoku',meaning:'negeri asing'},{word:'外出',romaji:'gaishutsu',meaning:'keluar / bepergian'},{word:'海外',romaji:'kaigai',meaning:'luar negeri'}] },

  // 学校 (School)
  '学': { type:'kanji', romaji:'gaku / manabu', meaning:'belajar / ilmu', onyomi:'ガク (gaku)', kunyomi:'まな-ぶ (mana-bu)', stroke_count:8, radical:'子（anak）', jlpt:'N5', examples:[{word:'学校',romaji:'gakkou',meaning:'sekolah'},{word:'大学',romaji:'daigaku',meaning:'universitas'},{word:'勉学',romaji:'bengaku',meaning:'belajar'}] },
  '校': { type:'kanji', romaji:'kou', meaning:'sekolah', onyomi:'コウ (kou)', kunyomi:'-', stroke_count:10, radical:'木（pohon）', jlpt:'N5', examples:[{word:'学校',romaji:'gakkou',meaning:'sekolah'},{word:'高校',romaji:'koukou',meaning:'SMA'},{word:'校長',romaji:'kouchou',meaning:'kepala sekolah'}] },
  '先': { type:'kanji', romaji:'sen / saki', meaning:'dulu / ujung', onyomi:'セン (sen)', kunyomi:'さき (saki)', stroke_count:6, radical:'儿（kaki）', jlpt:'N5', examples:[{word:'先生',romaji:'sensei',meaning:'guru'},{word:'先週',romaji:'senshuu',meaning:'minggu lalu'},{word:'先月',romaji:'sengetsu',meaning:'bulan lalu'}] },
  '生': { type:'kanji', romaji:'sei / umareru', meaning:'lahir / hidup', onyomi:'セイ (sei), ショウ (shou)', kunyomi:'い-きる (i-kiru), うま-れる (uma-reru)', stroke_count:5, radical:'生（hidup）', jlpt:'N5', examples:[{word:'先生',romaji:'sensei',meaning:'guru'},{word:'学生',romaji:'gakusei',meaning:'pelajar'},{word:'生活',romaji:'seikatsu',meaning:'kehidupan'}] },
  '本': { type:'kanji', romaji:'hon / moto', meaning:'buku / asal / utama', onyomi:'ホン (hon)', kunyomi:'もと (moto)', stroke_count:5, radical:'木（pohon）', jlpt:'N5', examples:[{word:'日本',romaji:'nihon',meaning:'Jepang'},{word:'本当',romaji:'hontou',meaning:'benar / sungguh'},{word:'絵本',romaji:'ehon',meaning:'buku bergambar'}] },
  '名': { type:'kanji', romaji:'mei / na', meaning:'nama / terkenal', onyomi:'メイ (mei), ミョウ (myou)', kunyomi:'な (na)', stroke_count:6, radical:'タ（sore）', jlpt:'N5', examples:[{word:'名前',romaji:'namae',meaning:'nama'},{word:'名所',romaji:'meisho',meaning:'tempat terkenal'},{word:'名人',romaji:'meijin',meaning:'ahli'}] },
  '友': { type:'kanji', romaji:'yuu / tomo', meaning:'teman', onyomi:'ユウ (yuu)', kunyomi:'とも (tomo)', stroke_count:4, radical:'又（lagi）', jlpt:'N5', examples:[{word:'友達',romaji:'tomodachi',meaning:'teman'},{word:'友人',romaji:'yuujin',meaning:'teman (formal)'},{word:'親友',romaji:'shinyuu',meaning:'sahabat'}] },

  // 自然 (Nature)
  '田': { type:'kanji', romaji:'den / ta', meaning:'sawah', onyomi:'デン (den)', kunyomi:'た (ta)', stroke_count:5, radical:'田（sawah）', jlpt:'N5', examples:[{word:'田んぼ',romaji:'tanbo',meaning:'sawah'},{word:'油田',romaji:'yuden',meaning:'ladang minyak'},{word:'田舎',romaji:'inaka',meaning:'pedesaan'}] },
  '石': { type:'kanji', romaji:'seki / ishi', meaning:'batu', onyomi:'セキ (seki)', kunyomi:'いし (ishi)', stroke_count:5, radical:'石（batu）', jlpt:'N5', examples:[{word:'石炭',romaji:'sekitan',meaning:'batu bara'},{word:'宝石',romaji:'houseki',meaning:'permata'},{word:'石橋',romaji:'ishibashi',meaning:'jembatan batu'}] },
  '雨': { type:'kanji', romaji:'u / ame', meaning:'hujan', onyomi:'ウ (u)', kunyomi:'あめ (ame), あま (ama)', stroke_count:8, radical:'雨（hujan）', jlpt:'N5', examples:[{word:'大雨',romaji:'ooame',meaning:'hujan lebat'},{word:'雨天',romaji:'uten',meaning:'hari hujan'},{word:'梅雨',romaji:'tsuyu',meaning:'musim hujan'}] },
  '夕': { type:'kanji', romaji:'seki / yuu', meaning:'sore', onyomi:'セキ (seki)', kunyomi:'ゆう (yuu)', stroke_count:3, radical:'夕（sore）', jlpt:'N5', examples:[{word:'夕食',romaji:'yuushoku',meaning:'makan malam'},{word:'夕方',romaji:'yuugata',meaning:'sore hari'},{word:'七夕',romaji:'tanabata',meaning:'festival bintang'}] },
  '岩': { type:'kanji', romaji:'gan / iwa', meaning:'batu besar / karang', onyomi:'ガン (gan)', kunyomi:'いわ (iwa)', stroke_count:8, radical:'山（gunung）', jlpt:'N5', examples:[{word:'岩石',romaji:'ganseki',meaning:'batuan'},{word:'火岩',romaji:'kaigan',meaning:'batu api'},{word:'岩場',romaji:'iwaba',meaning:'tempat berbatu'}] },
  '音': { type:'kanji', romaji:'on / oto', meaning:'suara', onyomi:'オン (on)', kunyomi:'おと (oto)', stroke_count:9, radical:'音（suara）', jlpt:'N5', examples:[{word:'音楽',romaji:'ongaku',meaning:'musik'},{word:'発音',romaji:'hatsuon',meaning:'pengucapan'},{word:'音量',romaji:'onryou',meaning:'volume suara'}] },
  '山': { type:'kanji', romaji:'san / yama', meaning:'gunung', onyomi:'サン (san)', kunyomi:'やま (yama)', stroke_count:3, radical:'山（gunung）', jlpt:'N5', examples:[{word:'富士山',romaji:'fujisan',meaning:'Gunung Fuji'},{word:'山道',romaji:'yamamichi',meaning:'jalan gunung'},{word:'登山',romaji:'tozan',meaning:'mendaki gunung'}] },
  '川': { type:'kanji', romaji:'sen / kawa', meaning:'sungai', onyomi:'セン (sen)', kunyomi:'かわ (kawa)', stroke_count:3, radical:'川（sungai）', jlpt:'N5', examples:[{word:'川上',romaji:'kawakami',meaning:'hulu sungai'},{word:'小川',romaji:'ogawa',meaning:'sungai kecil'},{word:'川原',romaji:'kawara',meaning:'tepian sungai'}] },

  // 植物・動物 (Plant & Animal)
  '林': { type:'kanji', romaji:'rin / hayashi', meaning:'hutan kecil', onyomi:'リン (rin)', kunyomi:'はやし (hayashi)', stroke_count:8, radical:'木（pohon）', jlpt:'N5', examples:[{word:'林業',romaji:'ringyou',meaning:'kehutanan'},{word:'森林',romaji:'shinrin',meaning:'hutan'},{word:'林道',romaji:'rindou',meaning:'jalan hutan'}] },
  '森': { type:'kanji', romaji:'shin / mori', meaning:'hutan lebat', onyomi:'シン (shin)', kunyomi:'もり (mori)', stroke_count:12, radical:'木（pohon）', jlpt:'N5', examples:[{word:'森林',romaji:'shinrin',meaning:'hutan'},{word:'森の中',romaji:'mori no naka',meaning:'di dalam hutan'},{word:'熱帯林',romaji:'nettairin',meaning:'hutan tropis'}] },
  '花': { type:'kanji', romaji:'ka / hana', meaning:'bunga', onyomi:'カ (ka)', kunyomi:'はな (hana)', stroke_count:7, radical:'艸（rumput）', jlpt:'N5', examples:[{word:'花見',romaji:'hanami',meaning:'melihat bunga'},{word:'花火',romaji:'hanabi',meaning:'kembang api'},{word:'生花',romaji:'seika',meaning:'bunga segar'}] },
  '竹': { type:'kanji', romaji:'chiku / take', meaning:'bambu', onyomi:'チク (chiku)', kunyomi:'たけ (take)', stroke_count:6, radical:'竹（bambu）', jlpt:'N5', examples:[{word:'竹刀',romaji:'shinai',meaning:'pedang bambu'},{word:'竹林',romaji:'chikurin',meaning:'hutan bambu'},{word:'竹馬',romaji:'takeuma',meaning:'kuda bambu / egrang'}] },
  '犬': { type:'kanji', romaji:'ken / inu', meaning:'anjing', onyomi:'ケン (ken)', kunyomi:'いぬ (inu)', stroke_count:4, radical:'犬（anjing）', jlpt:'N5', examples:[{word:'犬猫',romaji:'inuneko',meaning:'anjing dan kucing'},{word:'秋田犬',romaji:'akita inu',meaning:'anjing Akita'},{word:'子犬',romaji:'koinu',meaning:'anak anjing'}] },
  '貝': { type:'kanji', romaji:'kai', meaning:'kerang', onyomi:'バイ (bai)', kunyomi:'かい (kai)', stroke_count:7, radical:'貝（kerang）', jlpt:'N5', examples:[{word:'貝殻',romaji:'kaigara',meaning:'cangkang kerang'},{word:'二枚貝',romaji:'nimaigai',meaning:'kerang bercangkang dua'},{word:'貝類',romaji:'kairui',meaning:'moluska'}] },
  '牛': { type:'kanji', romaji:'gyuu / ushi', meaning:'sapi', onyomi:'ギュウ (gyuu)', kunyomi:'うし (ushi)', stroke_count:4, radical:'牛（sapi）', jlpt:'N5', examples:[{word:'牛肉',romaji:'gyuuniku',meaning:'daging sapi'},{word:'牛乳',romaji:'gyuunyuu',meaning:'susu sapi'},{word:'水牛',romaji:'suigyuu',meaning:'kerbau'}] },
  '魚': { type:'kanji', romaji:'gyo / sakana', meaning:'ikan', onyomi:'ギョ (gyo)', kunyomi:'さかな (sakana), うお (uo)', stroke_count:11, radical:'魚（ikan）', jlpt:'N5', examples:[{word:'金魚',romaji:'kingyo',meaning:'ikan mas'},{word:'魚市場',romaji:'uoichiba',meaning:'pasar ikan'},{word:'魚介類',romaji:'gyokairui',meaning:'hasil laut'}] },
  '鳥': { type:'kanji', romaji:'chou / tori', meaning:'burung', onyomi:'チョウ (chou)', kunyomi:'とり (tori)', stroke_count:11, radical:'鳥（burung）', jlpt:'N5', examples:[{word:'野鳥',romaji:'yachou',meaning:'burung liar'},{word:'鳥かご',romaji:'torikago',meaning:'sangkar burung'},{word:'渡り鳥',romaji:'wataridori',meaning:'burung migrasi'}] },

  // 食べ物 (Food)
  '米': { type:'kanji', romaji:'bei / kome', meaning:'beras / Amerika', onyomi:'ベイ (bei), マイ (mai)', kunyomi:'こめ (kome)', stroke_count:6, radical:'木（pohon）', jlpt:'N5', examples:[{word:'白米',romaji:'hakumai',meaning:'beras putih'},{word:'米国',romaji:'beikoku',meaning:'Amerika Serikat'},{word:'玄米',romaji:'genmai',meaning:'beras cokelat'}] },
  '肉': { type:'kanji', romaji:'niku', meaning:'daging', onyomi:'ニク (niku)', kunyomi:'-', stroke_count:6, radical:'肉（daging）', jlpt:'N5', examples:[{word:'牛肉',romaji:'gyuuniku',meaning:'daging sapi'},{word:'豚肉',romaji:'butaniku',meaning:'daging babi'},{word:'肉体',romaji:'nikutai',meaning:'tubuh fisik'}] },
  '茶': { type:'kanji', romaji:'cha / sa', meaning:'teh', onyomi:'チャ (cha), サ (sa)', kunyomi:'-', stroke_count:9, radical:'艸（rumput）', jlpt:'N5', examples:[{word:'お茶',romaji:'ocha',meaning:'teh'},{word:'茶道',romaji:'sadou',meaning:'upacara minum teh'},{word:'紅茶',romaji:'koucha',meaning:'teh hitam'}] },
  '好': { type:'kanji', romaji:'kou / suku', meaning:'suka / baik', onyomi:'コウ (kou)', kunyomi:'す-き (su-ki), この-む (kono-mu)', stroke_count:6, radical:'女（perempuan）', jlpt:'N5', examples:[{word:'好き',romaji:'suki',meaning:'suka'},{word:'好物',romaji:'koubutsu',meaning:'makanan favorit'},{word:'好奇心',romaji:'koukishin',meaning:'rasa ingin tahu'}] },
  '物': { type:'kanji', romaji:'butsu / mono', meaning:'benda / hal', onyomi:'ブツ (butsu), モツ (motsu)', kunyomi:'もの (mono)', stroke_count:8, radical:'牛（sapi）', jlpt:'N5', examples:[{word:'食べ物',romaji:'tabemono',meaning:'makanan'},{word:'飲み物',romaji:'nomimono',meaning:'minuman'},{word:'買い物',romaji:'kaimono',meaning:'belanja'}] },

  // 時間 (Time)
  '今': { type:'kanji', romaji:'kon / ima', meaning:'sekarang', onyomi:'コン (kon), キン (kin)', kunyomi:'いま (ima)', stroke_count:4, radical:'人（manusia）', jlpt:'N5', examples:[{word:'今日',romaji:'kyou',meaning:'hari ini'},{word:'今年',romaji:'kotoshi',meaning:'tahun ini'},{word:'今週',romaji:'konshuu',meaning:'minggu ini'}] },
  '何': { type:'kanji', romaji:'ka / nani / nan', meaning:'apa / berapa', onyomi:'カ (ka)', kunyomi:'なに (nani), なん (nan)', stroke_count:7, radical:'人（manusia）', jlpt:'N5', examples:[{word:'何時',romaji:'nanji',meaning:'jam berapa'},{word:'何人',romaji:'nannin',meaning:'berapa orang'},{word:'何歳',romaji:'nansai',meaning:'berapa umur'}] },
  '分': { type:'kanji', romaji:'fun / bun / wakaru', meaning:'menit / mengerti', onyomi:'フン (fun), ブン (bun)', kunyomi:'わ-かる (wa-karu)', stroke_count:4, radical:'刀（pisau）', jlpt:'N5', examples:[{word:'三分',romaji:'sanpun',meaning:'tiga menit'},{word:'分かる',romaji:'wakaru',meaning:'mengerti'},{word:'自分',romaji:'jibun',meaning:'diri sendiri'}] },
  '半': { type:'kanji', romaji:'han', meaning:'setengah', onyomi:'ハン (han)', kunyomi:'なか-ば (naka-ba)', stroke_count:5, radical:'十（sepuluh）', jlpt:'N5', examples:[{word:'半分',romaji:'hanbun',meaning:'setengah'},{word:'一時半',romaji:'ichijihan',meaning:'jam setengah dua'},{word:'半島',romaji:'hantou',meaning:'semenanjung'}] },
  '方': { type:'kanji', romaji:'hou / kata', meaning:'arah / cara', onyomi:'ホウ (hou)', kunyomi:'かた (kata)', stroke_count:4, radical:'方（arah）', jlpt:'N5', examples:[{word:'方法',romaji:'houhou',meaning:'metode'},{word:'読み方',romaji:'yomikata',meaning:'cara membaca'},{word:'あの方',romaji:'ano kata',meaning:'orang itu (sopan)'}] },
  '時': { type:'kanji', romaji:'ji / toki', meaning:'jam / waktu', onyomi:'ジ (ji)', kunyomi:'とき (toki)', stroke_count:10, radical:'日（matahari）', jlpt:'N5', examples:[{word:'何時',romaji:'nanji',meaning:'jam berapa'},{word:'時間',romaji:'jikan',meaning:'waktu / jam'},{word:'時々',romaji:'tokidoki',meaning:'kadang-kadang'}] },
  '間': { type:'kanji', romaji:'kan / aida', meaning:'antara / jarak / selang', onyomi:'カン (kan), ケン (ken)', kunyomi:'あいだ (aida), ま (ma)', stroke_count:12, radical:'門（gerbang）', jlpt:'N5', examples:[{word:'時間',romaji:'jikan',meaning:'waktu / jam'},{word:'人間',romaji:'ningen',meaning:'manusia'},{word:'間違い',romaji:'machigai',meaning:'kesalahan'}] },

  // 町 (City)
  '町': { type:'kanji', romaji:'chou / machi', meaning:'kota kecil / distrik', onyomi:'チョウ (chou)', kunyomi:'まち (machi)', stroke_count:7, radical:'田（sawah）', jlpt:'N5', examples:[{word:'町中',romaji:'machinaka',meaning:'pusat kota'},{word:'下町',romaji:'shitamachi',meaning:'kota bawah'},{word:'街道',romaji:'kaidou',meaning:'jalan raya'}] },
  '寺': { type:'kanji', romaji:'ji / tera', meaning:'kuil', onyomi:'ジ (ji)', kunyomi:'てら (tera)', stroke_count:6, radical:'寸（inchi）', jlpt:'N5', examples:[{word:'お寺',romaji:'otera',meaning:'kuil'},{word:'寺院',romaji:'jiin',meaning:'kuil / biara'},{word:'大寺',romaji:'daiji',meaning:'kuil besar'}] },
  '東': { type:'kanji', romaji:'tou / higashi', meaning:'timur', onyomi:'トウ (tou)', kunyomi:'ひがし (higashi)', stroke_count:8, radical:'木（pohon）', jlpt:'N5', examples:[{word:'東京',romaji:'toukyou',meaning:'Tokyo'},{word:'東西',romaji:'tousei',meaning:'timur barat'},{word:'関東',romaji:'kantou',meaning:'kawasan Kanto'}] },
  '西': { type:'kanji', romaji:'sei / nishi', meaning:'barat', onyomi:'セイ (sei), サイ (sai)', kunyomi:'にし (nishi)', stroke_count:6, radical:'西（barat）', jlpt:'N5', examples:[{word:'西洋',romaji:'seiyou',meaning:'barat / Eropa'},{word:'東西',romaji:'tousei',meaning:'timur barat'},{word:'関西',romaji:'kansai',meaning:'kawasan Kansai'}] },
  '南': { type:'kanji', romaji:'nan / minami', meaning:'selatan', onyomi:'ナン (nan)', kunyomi:'みなみ (minami)', stroke_count:9, radical:'十（sepuluh）', jlpt:'N5', examples:[{word:'南北',romaji:'nanboku',meaning:'utara selatan'},{word:'南国',romaji:'nangoku',meaning:'negara selatan / tropis'},{word:'南極',romaji:'nankyoku',meaning:'Kutub Selatan'}] },
  '北': { type:'kanji', romaji:'hoku / kita', meaning:'utara', onyomi:'ホク (hoku)', kunyomi:'きた (kita)', stroke_count:5, radical:'匕（sendok）', jlpt:'N5', examples:[{word:'北海道',romaji:'hokkaidou',meaning:'Hokkaido'},{word:'南北',romaji:'nanboku',meaning:'utara selatan'},{word:'北極',romaji:'hokkyoku',meaning:'Kutub Utara'}] },
  '車': { type:'kanji', romaji:'sha / kuruma', meaning:'kendaraan / mobil', onyomi:'シャ (sha)', kunyomi:'くるま (kuruma)', stroke_count:7, radical:'車（kendaraan）', jlpt:'N5', examples:[{word:'電車',romaji:'densha',meaning:'kereta listrik'},{word:'自転車',romaji:'jitensha',meaning:'sepeda'},{word:'車内',romaji:'shanai',meaning:'di dalam kendaraan'}] },
  '電': { type:'kanji', romaji:'den', meaning:'listrik / telepon', onyomi:'デン (den)', kunyomi:'-', stroke_count:13, radical:'雨（hujan）', jlpt:'N5', examples:[{word:'電車',romaji:'densha',meaning:'kereta listrik'},{word:'電話',romaji:'denwa',meaning:'telepon'},{word:'電気',romaji:'denki',meaning:'listrik'}] },

  // 形容詞 (Adjective)
  '高': { type:'kanji', romaji:'kou / taka', meaning:'tinggi / mahal', onyomi:'コウ (kou)', kunyomi:'たか-い (taka-i)', stroke_count:10, radical:'高（tinggi）', jlpt:'N5', examples:[{word:'高い',romaji:'takai',meaning:'tinggi / mahal'},{word:'高校',romaji:'koukou',meaning:'SMA'},{word:'最高',romaji:'saikou',meaning:'terbaik / tertinggi'}] },
  '安': { type:'kanji', romaji:'an / yasu', meaning:'murah / aman', onyomi:'アン (an)', kunyomi:'やす-い (yasu-i)', stroke_count:6, radical:'女（perempuan）', jlpt:'N5', examples:[{word:'安い',romaji:'yasui',meaning:'murah'},{word:'安全',romaji:'anzen',meaning:'aman'},{word:'不安',romaji:'fuan',meaning:'cemas'}] },
  '多': { type:'kanji', romaji:'ta / ooi', meaning:'banyak', onyomi:'タ (ta)', kunyomi:'おお-い (oo-i)', stroke_count:6, radical:'夕（sore）', jlpt:'N5', examples:[{word:'多い',romaji:'ooi',meaning:'banyak'},{word:'多数',romaji:'tasuu',meaning:'jumlah besar'},{word:'多分',romaji:'tabun',meaning:'mungkin'}] },
  '少': { type:'kanji', romaji:'shou / suku', meaning:'sedikit', onyomi:'ショウ (shou)', kunyomi:'すく-ない (suku-nai), すこ-し (suko-shi)', stroke_count:4, radical:'小（kecil）', jlpt:'N5', examples:[{word:'少ない',romaji:'sukunai',meaning:'sedikit'},{word:'少し',romaji:'sukoshi',meaning:'sedikit'},{word:'少年',romaji:'shounen',meaning:'remaja laki-laki'}] },
  '新': { type:'kanji', romaji:'shin / atarashii', meaning:'baru', onyomi:'シン (shin)', kunyomi:'あたら-しい (atara-shii), あら-た (ara-ta)', stroke_count:13, radical:'斤（kapak）', jlpt:'N5', examples:[{word:'新しい',romaji:'atarashii',meaning:'baru'},{word:'新幹線',romaji:'shinkansen',meaning:'kereta cepat'},{word:'新聞',romaji:'shinbun',meaning:'koran'}] },
  '古': { type:'kanji', romaji:'ko / furui', meaning:'lama / tua', onyomi:'コ (ko)', kunyomi:'ふる-い (furu-i)', stroke_count:5, radical:'口（mulut）', jlpt:'N5', examples:[{word:'古い',romaji:'furui',meaning:'lama / tua'},{word:'古典',romaji:'koten',meaning:'klasik'},{word:'中古',romaji:'chuuko',meaning:'bekas / second'}] },
  '明': { type:'kanji', romaji:'mei / akarui', meaning:'terang / jelas', onyomi:'メイ (mei), ミョウ (myou)', kunyomi:'あか-るい (aka-rui)', stroke_count:8, radical:'日（matahari）', jlpt:'N5', examples:[{word:'明るい',romaji:'akarui',meaning:'cerah / ceria'},{word:'説明',romaji:'setsumei',meaning:'penjelasan'},{word:'明日',romaji:'ashita',meaning:'besok'}] },
  '暗': { type:'kanji', romaji:'an / kurai', meaning:'gelap', onyomi:'アン (an)', kunyomi:'くら-い (kura-i)', stroke_count:13, radical:'日（matahari）', jlpt:'N5', examples:[{word:'暗い',romaji:'kurai',meaning:'gelap'},{word:'暗闇',romaji:'kurayami',meaning:'kegelapan'},{word:'暗号',romaji:'angou',meaning:'sandi / kode'}] },
  '長': { type:'kanji', romaji:'chou / nagai', meaning:'panjang / kepala', onyomi:'チョウ (chou)', kunyomi:'なが-い (naga-i)', stroke_count:8, radical:'長（panjang）', jlpt:'N5', examples:[{word:'長い',romaji:'nagai',meaning:'panjang'},{word:'校長',romaji:'kouchou',meaning:'kepala sekolah'},{word:'身長',romaji:'shinchou',meaning:'tinggi badan'}] },
  '元': { type:'kanji', romaji:'gen / moto', meaning:'asal / semula', onyomi:'ゲン (gen), ガン (gan)', kunyomi:'もと (moto)', stroke_count:4, radical:'二（dua）', jlpt:'N5', examples:[{word:'元気',romaji:'genki',meaning:'sehat / bersemangat'},{word:'元々',romaji:'motomoto',meaning:'dari awal'},{word:'地元',romaji:'jimoto',meaning:'daerah asal'}] },
  '気': { type:'kanji', romaji:'ki / ke', meaning:'semangat / udara / perasaan', onyomi:'キ (ki), ケ (ke)', kunyomi:'-', stroke_count:6, radical:'气（uap）', jlpt:'N5', examples:[{word:'元気',romaji:'genki',meaning:'sehat / bersemangat'},{word:'天気',romaji:'tenki',meaning:'cuaca'},{word:'気持ち',romaji:'kimochi',meaning:'perasaan'}] },

  // 動詞 (Verb)
  '見': { type:'kanji', romaji:'ken / miru', meaning:'melihat', onyomi:'ケン (ken)', kunyomi:'み-る (mi-ru)', stroke_count:7, radical:'見（melihat）', jlpt:'N5', examples:[{word:'見る',romaji:'miru',meaning:'melihat'},{word:'見学',romaji:'kengaku',meaning:'kunjungan belajar'},{word:'意見',romaji:'iken',meaning:'pendapat'}] },
  '立': { type:'kanji', romaji:'ritsu / tatsu', meaning:'berdiri', onyomi:'リツ (ritsu), リュウ (ryuu)', kunyomi:'た-つ (ta-tsu)', stroke_count:5, radical:'立（berdiri）', jlpt:'N5', examples:[{word:'立つ',romaji:'tatsu',meaning:'berdiri'},{word:'独立',romaji:'dokuritsu',meaning:'mandiri'},{word:'立場',romaji:'tachiba',meaning:'posisi / sudut pandang'}] },
  '入': { type:'kanji', romaji:'nyuu / hairu', meaning:'masuk', onyomi:'ニュウ (nyuu)', kunyomi:'はい-る (hai-ru), い-れる (i-reru)', stroke_count:2, radical:'入（masuk）', jlpt:'N5', examples:[{word:'入る',romaji:'hairu',meaning:'masuk'},{word:'入学',romaji:'nyuugaku',meaning:'masuk sekolah'},{word:'入口',romaji:'iriguchi',meaning:'pintu masuk'}] },
  '出': { type:'kanji', romaji:'shutsu / deru', meaning:'keluar', onyomi:'シュツ (shutsu)', kunyomi:'で-る (de-ru), だ-す (da-su)', stroke_count:5, radical:'凵（kotak bawah）', jlpt:'N5', examples:[{word:'出る',romaji:'deru',meaning:'keluar'},{word:'出発',romaji:'shuppatsu',meaning:'berangkat'},{word:'出口',romaji:'deguchi',meaning:'pintu keluar'}] },
  '休': { type:'kanji', romaji:'kyuu / yasumu', meaning:'istirahat', onyomi:'キュウ (kyuu)', kunyomi:'やす-む (yasu-mu)', stroke_count:6, radical:'人（manusia）', jlpt:'N5', examples:[{word:'休む',romaji:'yasumu',meaning:'beristirahat'},{word:'休日',romaji:'kyuujitsu',meaning:'hari libur'},{word:'夏休み',romaji:'natsuyasumi',meaning:'liburan musim panas'}] },
  '行': { type:'kanji', romaji:'kou / iku', meaning:'pergi', onyomi:'コウ (kou), ギョウ (gyou)', kunyomi:'い-く (i-ku), おこな-う (okona-u)', stroke_count:6, radical:'行（pergi）', jlpt:'N5', examples:[{word:'行く',romaji:'iku',meaning:'pergi'},{word:'旅行',romaji:'ryokou',meaning:'perjalanan'},{word:'行動',romaji:'koudou',meaning:'tindakan'}] },
  '来': { type:'kanji', romaji:'rai / kuru', meaning:'datang', onyomi:'ライ (rai)', kunyomi:'く-る (ku-ru)', stroke_count:7, radical:'木（pohon）', jlpt:'N5', examples:[{word:'来る',romaji:'kuru',meaning:'datang'},{word:'来年',romaji:'rainen',meaning:'tahun depan'},{word:'来週',romaji:'raishuu',meaning:'minggu depan'}] },
  '言': { type:'kanji', romaji:'gen / iu', meaning:'berkata / berbicara', onyomi:'ゲン (gen)', kunyomi:'い-う (i-u)', stroke_count:7, radical:'言（berkata）', jlpt:'N5', examples:[{word:'言う',romaji:'iu',meaning:'berkata'},{word:'言語',romaji:'gengo',meaning:'bahasa'},{word:'宣言',romaji:'sengen',meaning:'deklarasi'}] },
  '帰': { type:'kanji', romaji:'ki / kaeru', meaning:'pulang / kembali', onyomi:'キ (ki)', kunyomi:'かえ-る (kae-ru)', stroke_count:10, radical:'帚（sapu）', jlpt:'N5', examples:[{word:'帰る',romaji:'kaeru',meaning:'pulang'},{word:'帰国',romaji:'kikoku',meaning:'pulang ke negara asal'},{word:'帰宅',romaji:'kitaku',meaning:'pulang ke rumah'}] },
  '書': { type:'kanji', romaji:'sho / kaku', meaning:'menulis', onyomi:'ショ (sho)', kunyomi:'か-く (ka-ku)', stroke_count:10, radical:'曰（berkata）', jlpt:'N5', examples:[{word:'書く',romaji:'kaku',meaning:'menulis'},{word:'教科書',romaji:'kyoukasho',meaning:'buku pelajaran'},{word:'図書館',romaji:'toshokan',meaning:'perpustakaan'}] },
  '読': { type:'kanji', romaji:'doku / yomu', meaning:'membaca', onyomi:'ドク (doku)', kunyomi:'よ-む (yo-mu)', stroke_count:14, radical:'言（berkata）', jlpt:'N5', examples:[{word:'読む',romaji:'yomu',meaning:'membaca'},{word:'読書',romaji:'dokusho',meaning:'membaca buku'},{word:'読者',romaji:'dokusha',meaning:'pembaca'}] },
  '話': { type:'kanji', romaji:'wa / hanasu', meaning:'berbicara / cerita', onyomi:'ワ (wa)', kunyomi:'はな-す (hana-su)', stroke_count:13, radical:'言（berkata）', jlpt:'N5', examples:[{word:'話す',romaji:'hanasu',meaning:'berbicara'},{word:'会話',romaji:'kaiwa',meaning:'percakapan'},{word:'電話',romaji:'denwa',meaning:'telepon'}] },
  '聞': { type:'kanji', romaji:'bun / kiku', meaning:'mendengar / bertanya', onyomi:'ブン (bun)', kunyomi:'き-く (ki-ku), き-こえる (ki-koeru)', stroke_count:14, radical:'耳（telinga）', jlpt:'N5', examples:[{word:'聞く',romaji:'kiku',meaning:'mendengar / bertanya'},{word:'新聞',romaji:'shinbun',meaning:'koran'},{word:'聞こえる',romaji:'kikoeru',meaning:'terdengar'}] },
  '食': { type:'kanji', romaji:'shoku / taberu', meaning:'makan', onyomi:'ショク (shoku)', kunyomi:'た-べる (ta-beru)', stroke_count:9, radical:'食（makan）', jlpt:'N5', examples:[{word:'食べる',romaji:'taberu',meaning:'makan'},{word:'食事',romaji:'shokuji',meaning:'makan / makanan'},{word:'食堂',romaji:'shokudou',meaning:'kantin / ruang makan'}] },
  '飲': { type:'kanji', romaji:'in / nomu', meaning:'minum', onyomi:'イン (in)', kunyomi:'の-む (no-mu)', stroke_count:12, radical:'食（makan）', jlpt:'N5', examples:[{word:'飲む',romaji:'nomu',meaning:'minum'},{word:'飲み物',romaji:'nomimono',meaning:'minuman'},{word:'飲食店',romaji:'inshokuten',meaning:'restoran'}] },
  '買': { type:'kanji', romaji:'bai / kau', meaning:'membeli', onyomi:'バイ (bai)', kunyomi:'か-う (ka-u)', stroke_count:12, radical:'貝（kerang）', jlpt:'N5', examples:[{word:'買う',romaji:'kau',meaning:'membeli'},{word:'買い物',romaji:'kaimono',meaning:'belanja'},{word:'売買',romaji:'baibai',meaning:'jual beli'}] },
};

// ─── MODAL LOGIC ─────────────────────────────────────────────
const overlay    = document.getElementById('kanaModal');
const closeBtn   = document.getElementById('kanaModalClose');
const modalSymbol      = document.getElementById('kanaModalSymbol');
const modalBadge       = document.getElementById('kanaModalBadge');
const modalRomaji      = document.getElementById('kanaModalRomaji');
const modalPronunciation = document.getElementById('kanaModalPronunciation');
const modalBody        = document.getElementById('kanaModalBody');
const writerArea       = document.getElementById('kanaWriterArea');
const writerStatus     = document.getElementById('kanaWriterStatus');
const writerPlayBtn    = document.getElementById('kanaWriterPlayBtn');
const writerLoopBtn    = document.getElementById('kanaWriterLoopBtn');
const audioBtn         = document.getElementById('kanaAudioBtn');

let currentChar   = null;
let currentType   = null;
let currentWriter = null;
let looping       = false;

function getTypeBadgeLabel(type) {
  return { hiragana: 'Hiragana', katakana: 'Katakana', kanji: 'Kanji' }[type] || type;
}

function renderKanaModal(data) {
  return `
    <div>
      <p class="kana-modal__section-label">Informasi Dasar</p>
      <div class="kana-modal__info-grid">
        <div class="kana-modal__info-card">
          <div class="kana-modal__info-card-label">Jumlah Stroke</div>
          <div class="kana-modal__info-card-value">${data.stroke_count} goresan</div>
        </div>
        <div class="kana-modal__info-card">
          <div class="kana-modal__info-card-label">Romaji</div>
          <div class="kana-modal__info-card-value">${data.romaji}</div>
        </div>
      </div>
    </div>
    <hr class="kana-modal__divider">
    <div>
      <p class="kana-modal__section-label">Contoh Kata</p>
      <div class="kana-modal__examples">
        ${data.examples.map(ex => `
          <div class="kana-modal__example">
            <span class="kana-modal__example-jp">${ex.word}</span>
            <div class="kana-modal__example-sub">
              <span class="kana-modal__example-romaji">${ex.romaji}</span>
              <span class="kana-modal__example-meaning">${ex.meaning}</span>
            </div>
          </div>
        `).join('')}
      </div>
    </div>
  `;
}

function renderKanjiModal(data) {
  return `
    <div>
      <p class="kana-modal__section-label">Cara Baca</p>
      <div class="kana-modal__reading-grid">
        <div class="kana-modal__reading-card">
          <div class="kana-modal__reading-card-label">On'yomi (音読み)</div>
          <div class="kana-modal__reading-card-value">${data.onyomi}</div>
        </div>
        <div class="kana-modal__reading-card">
          <div class="kana-modal__reading-card-label">Kun'yomi (訓読み)</div>
          <div class="kana-modal__reading-card-value">${data.kunyomi}</div>
        </div>
      </div>
    </div>
    <hr class="kana-modal__divider">
    <div>
      <p class="kana-modal__section-label">Detail Kanji</p>
      <div class="kana-modal__info-grid">
        <div class="kana-modal__info-card">
          <div class="kana-modal__info-card-label">Jumlah Stroke</div>
          <div class="kana-modal__info-card-value">${data.stroke_count} goresan</div>
        </div>
        <div class="kana-modal__info-card">
          <div class="kana-modal__info-card-label">Radikal</div>
          <div class="kana-modal__info-card-value">${data.radical}</div>
        </div>
        <div class="kana-modal__info-card">
          <div class="kana-modal__info-card-label">JLPT Level</div>
          <div class="kana-modal__info-card-value">${data.jlpt}</div>
        </div>
        <div class="kana-modal__info-card">
          <div class="kana-modal__info-card-label">Arti</div>
          <div class="kana-modal__info-card-value">${data.meaning}</div>
        </div>
      </div>
    </div>
    <hr class="kana-modal__divider">
    <div>
      <p class="kana-modal__section-label">Contoh Kata & Kalimat</p>
      <div class="kana-modal__examples">
        ${data.examples.map(ex => `
          <div class="kana-modal__example">
            <span class="kana-modal__example-jp">${ex.word}</span>
            <div class="kana-modal__example-sub">
              <span class="kana-modal__example-romaji">${ex.romaji}</span>
              <span class="kana-modal__example-meaning">${ex.meaning}</span>
            </div>
          </div>
        `).join('')}
      </div>
    </div>
  `;
}

function openModal(char) {
  const data = LETTER_DATA[char];
  if (!data) return;

  currentChar = char;
  currentType = data.type;

  modalSymbol.textContent        = char;
  modalBadge.textContent         = getTypeBadgeLabel(data.type);
  modalRomaji.textContent        = data.romaji;
  modalPronunciation.textContent = data.pronunciation || '';
  modalBody.innerHTML            = data.type === 'kanji' ? renderKanjiModal(data) : renderKanaModal(data);

  // Show audio button ONLY for Kanji
  if (data.type === 'kanji') {
    audioBtn.style.display = 'inline-flex';
  } else {
    audioBtn.style.display = 'none';
  }

  // Reset writing section
  resetWriterSection();

  // Load and animate stroke order immediately
  initKanaWriter(char);

  overlay.classList.add('is-open');
  document.body.style.overflow = 'hidden';
  closeBtn.focus();
}


function closeModal() {
  overlay.classList.remove('is-open');
  document.body.style.overflow = '';
  resetWriterSection();
}

// ─── LOCAL SVG WRITER LOGIC ──────────────────────────────────
let strokePaths = [];
let strokeTexts = [];
let animationTimeout = null;

function resetWriterSection() {
  looping = false;
  
  cancelAnimation();
  
  const target = document.getElementById('kanaWriterTarget');
  if (target) target.innerHTML = '';
  
  writerLoopBtn.innerHTML = '<span class="material-symbols-outlined">loop</span> Ulangi Terus';
}

function cancelAnimation() {
  if (animationTimeout) {
    clearTimeout(animationTimeout);
    animationTimeout = null;
  }
}

function showWriterStatus(msg, isError = false) {
  writerStatus.classList.remove('is-hidden', 'kana-writer-status--error');
  if (isError) writerStatus.classList.add('kana-writer-status--error');
  writerStatus.innerHTML = `
    <span class="material-symbols-outlined">${isError ? 'error_outline' : 'autorenew'}</span>
    <span>${msg}</span>
  `;
}

function hideWriterStatus() {
  writerStatus.classList.add('is-hidden');
}

function initKanaWriter(char) {
  const target = document.getElementById('kanaWriterTarget');
  if (!target) return;
  target.innerHTML = '';
  showWriterStatus('Memuat...');
  cancelAnimation();

  // Convert character to hex code point
  const code = char.charCodeAt(0);
  const hex = code.toString(16).padStart(5, '0');

  // Fetch local SVG
  fetch(`/kanjivg/${hex}.svg`)
    .then(response => {
      if (!response.ok) throw new Error();
      return response.text();
    })
    .then(svgText => {
      hideWriterStatus();
      target.innerHTML = svgText;
      
      const svg = target.querySelector('svg');
      if (!svg) {
        showWriterStatus('Gagal memuat format gambar.', true);
        return;
      }

      svg.setAttribute('width', '100%');
      svg.setAttribute('height', '100%');
      svg.style.display = 'block';

      // Customize paths and create background outline
      const pathsGroup = svg.querySelector('[id^="kvg:StrokePaths_"]');
      if (pathsGroup) {
        // Clone for background outline
        const outlineGroup = pathsGroup.cloneNode(true);
        outlineGroup.id = 'kvg:StrokeOutline';
        outlineGroup.querySelectorAll('path').forEach(p => {
          p.removeAttribute('id');
          p.style.fill = 'none';
          p.style.stroke = 'rgba(36, 48, 54, 0.08)';
          p.style.strokeWidth = '3.5';
          p.style.strokeLinecap = 'round';
          p.style.strokeLinejoin = 'round';
        });
        pathsGroup.parentNode.insertBefore(outlineGroup, pathsGroup);

        // Configure main stroke paths
        strokePaths = Array.from(pathsGroup.querySelectorAll('path'));
        strokePaths.forEach(p => {
          const length = p.getTotalLength();
          p.style.fill = 'none';
          p.style.stroke = '#243036';
          p.style.strokeWidth = '4.5';
          p.style.strokeLinecap = 'round';
          p.style.strokeLinejoin = 'round';
          p.style.strokeDasharray = length;
          p.style.strokeDashoffset = length;
        });
      } else {
        strokePaths = [];
      }

      // Customize stroke numbers
      const numbersGroup = svg.querySelector('[id^="kvg:StrokeNumbers_"]');
      if (numbersGroup) {
        strokeTexts = Array.from(numbersGroup.querySelectorAll('text'));
        strokeTexts.forEach(t => {
          t.style.fill = 'var(--home-coral)';
          t.style.fontSize = '8px';
          t.style.fontWeight = 'bold';
          t.style.display = 'none';
        });
      } else {
        strokeTexts = [];
      }

      // Start animation
      animateCharacter();
    })
    .catch(() => {
      showWriterStatus('Data stroke order tidak tersedia.', true);
    });
}

function animateCharacter() {
  cancelAnimation();
  if (strokePaths.length === 0) return;

  // Reset all strokes to hidden
  strokePaths.forEach((p, idx) => {
    const length = p.getTotalLength();
    p.style.transition = 'none';
    p.style.strokeDashoffset = length;
    if (strokeTexts[idx]) strokeTexts[idx].style.display = 'none';
  });

  let currentStroke = 0;

  function drawNextStroke() {
    if (currentStroke >= strokePaths.length) {
      if (looping) {
        animationTimeout = setTimeout(() => {
          animateCharacter();
        }, 1200);
      }
      return;
    }

    const p = strokePaths[currentStroke];
    const t = strokeTexts[currentStroke];
    const length = p.getTotalLength();

    // Show stroke number
    if (t) t.style.display = 'block';

    // Transition path offset to 0
    p.getBoundingClientRect(); // force layout reflow
    p.style.transition = 'stroke-dashoffset 0.5s ease-in-out';
    p.style.strokeDashoffset = '0';

    currentStroke++;
    animationTimeout = setTimeout(drawNextStroke, 750);
  }

  animationTimeout = setTimeout(drawNextStroke, 200);
}

// Play once
writerPlayBtn.addEventListener('click', function() {
  looping = false;
  writerLoopBtn.innerHTML = '<span class="material-symbols-outlined">loop</span> Ulangi Terus';
  animateCharacter();
});

// Loop
writerLoopBtn.addEventListener('click', function() {
  if (looping) {
    looping = false;
    writerLoopBtn.innerHTML = '<span class="material-symbols-outlined">loop</span> Ulangi Terus';
    cancelAnimation();
  } else {
    looping = true;
    writerLoopBtn.innerHTML = '<span class="material-symbols-outlined">stop</span> Stop';
    animateCharacter();
  }
});

// Play audio click event
audioBtn.addEventListener('click', function() {
  if ('speechSynthesis' in window) {
    // Cancel any current speaking
    window.speechSynthesis.cancel();
    
    const utterance = new SpeechSynthesisUtterance(currentChar);
    utterance.lang = 'ja-JP';
    utterance.rate = 0.75; // slightly slower for clearer pronunciation
    
    // Choose native Japanese voice if available
    const voices = window.speechSynthesis.getVoices();
    const jaVoice = voices.find(v => v.lang.startsWith('ja'));
    if (jaVoice) utterance.voice = jaVoice;
    
    window.speechSynthesis.speak(utterance);
  }
});

// ─── KLIK KARTU HURUF ────────────────────────────────────────
document.querySelectorAll('.kana-card[data-char]').forEach(card => {
  card.addEventListener('click', () => openModal(card.dataset.char));
  card.addEventListener('keydown', e => {
    if (e.key === 'Enter' || e.key === ' ') {
      e.preventDefault();
      openModal(card.dataset.char);
    }
  });
});

// Tutup modal
closeBtn.addEventListener('click', closeModal);
overlay.addEventListener('click', e => { if (e.target === overlay) closeModal(); });
document.addEventListener('keydown', e => { if (e.key === 'Escape') closeModal(); });
</script>
@endsection
