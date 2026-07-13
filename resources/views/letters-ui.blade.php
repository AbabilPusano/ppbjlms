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
                    <h1>Pengenalan Huruf Jepang</h1>
                    <p>
                        Kenali bentuk huruf dan cara bacanya di sini. Silakan pilih set huruf yang ingin kamu pelajari.
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
                        <h2 class="section-heading__title">Referensi Huruf</h2>
                    </div>
                    <p class="section-heading__desc">
                        Klik kartu huruf untuk melihat detail cara baca dan penulisannya.
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
  '万': { type:'kanji', romaji:'man / ban', meaning:'sepuluh ribu', onyomi:'マン (man), バン (ban)', kunyomi:'よろず (yorozu)', stroke_count:3, radical:'一（satu）', jlpt:'N5', examples:[{word:'一万円',romaji:'ichimanen',meaning:'sepuluh ribu yen'},{word:'万年筆',romaji:'mannenhitsu',meaning:'pena'},{word:'万国',romaji:'bankoku',meaning:'semua negara'}] },
  '円': { type:'kanji', romaji:'en / maru', meaning:'lingkaran / yen', onyomi:'エン (en)', kunyomi:'まる-い (maru-i)', stroke_count:4, radical:'冂（kotak atas）', jlpt:'N5', examples:[{word:'百円',romaji:'hyakuen',meaning:'seratus yen'},{word:'円形',romaji:'enkei',meaning:'bentuk lingkaran'},{word:'円満',romaji:'enman',meaning:'harmonis'}] },
  '年': { type:'kanji', romaji:'nen / toshi', meaning:'tahun', onyomi:'ネン (nen)', kunyomi:'とし (toshi)', stroke_count:6, radical:'干（kering）', jlpt:'N5', examples:[{word:'今年',romaji:'kotoshi',meaning:'tahun ini'},{word:'来年',romaji:'rainen',meaning:'tahun depan'},{word:'去年',romaji:'kyonen',meaning:'tahun lalu'}] },
  '日': { type:'kanji', romaji:'nichi / hi / ka', meaning:'hari / matahari / Jepang', onyomi:'ニチ (nichi), ジツ (jitsu)', kunyomi:'ひ (hi), -か (-ka)', stroke_count:4, radical:'日（matahari）', jlpt:'N5', examples:[{word:'今日',romaji:'kyou',meaning:'hari ini'},{word:'日本',romaji:'nihon',meaning:'Jepang'},{word:'毎日',romaji:'mainichi',meaning:'setiap hari'}] },
  '月': { type:'kanji', romaji:'getsu / tsuki', meaning:'bulan / rembulan', onyomi:'ゲツ (getsu), ガツ (gatsu)', kunyomi:'つき (tsuki)', stroke_count:4, radical:'月（bulan）', jlpt:'N5', examples:[{word:'今月',romaji:'kongetsu',meaning:'bulan ini'},{word:'月曜日',romaji:'getsuyoubi',meaning:'hari Senin'},{word:'三日月',romaji:'mikazuki',meaning:'bulan sabit'}] },
  '火': { type:'kanji', romaji:'ka / hi', meaning:'api', onyomi:'カ (ka)', kunyomi:'ひ (hi), ほ (ho)', stroke_count:4, radical:'火（api）', jlpt:'N5', examples:[{word:'火曜日',romaji:'kayoubi',meaning:'hari Selasa'},{word:'花火',romaji:'hanabi',meaning:'kembang api'},{word:'火山',romaji:'kazan',meaning:'gunung berapi'}] },
  '水': { type:'kanji', romaji:'sui / mizu', meaning:'air', onyomi:'スイ (sui)', kunyomi:'みず (mizu)', stroke_count:4, radical:'水（air）', jlpt:'N5', examples:[{word:'水曜日',romaji:'suiyoubi',meaning:'hari Rabu'},{word:'水泳',romaji:'suiei',meaning:'renang'},{word:'水道',romaji:'suidou',meaning:'sistem air / pipa air'}] },
  '木': { type:'kanji', romaji:'moku / ki', meaning:'pohon / kayu', onyomi:'モク (moku), ボク (boku)', kunyomi:'き (ki), こ (ko)', stroke_count:4, radical:'木（pohon）', jlpt:'N5', examples:[{word:'木曜日',romaji:'mokuyoubi',meaning:'hari Kamis'},{word:'木造',romaji:'mokuzou',meaning:'berbahan kayu'},{word:'木材',romaji:'mokuzai',meaning:'kayu / lumber'}] },
  '金': { type:'kanji', romaji:'kin / kane', meaning:'emas / uang / logam', onyomi:'キン (kin), コン (kon)', kunyomi:'かね (kane), かな (kana)', stroke_count:8, radical:'金（emas）', jlpt:'N5', examples:[{word:'金曜日',romaji:'kinyoubi',meaning:'hari Jumat'},{word:'お金',romaji:'okane',meaning:'uang'},{word:'金メダル',romaji:'kin medaru',meaning:'medali emas'}] },
  '土': { type:'kanji', romaji:'do / tsuchi', meaning:'tanah / bumi', onyomi:'ド (do), ト (to)', kunyomi:'つchi (tsuchi)', stroke_count:3, radical:'土（tanah）', jlpt:'N5', examples:[{word:'土曜日',romaji:'doyoubi',meaning:'hari Sabtu'},{word:'土地',romaji:'tochi',meaning:'lahan / tanah'},{word:'土台',romaji:'dodai',meaning:'fondasi'}] },
  '人': { type:'kanji', romaji:'jin / hito', meaning:'orang / manusia', onyomi:'ジン (jin), ニン (nin)', kunyomi:'ひと (hito)', stroke_count:2, radical:'人（manusia）', jlpt:'N5', examples:[{word:'日本人',romaji:'nihonjin',meaning:'orang Jepang'},{word:'一人',romaji:'hitori',meaning:'satu orang'},{word:'人気',romaji:'ninki',meaning:'popularitas'}] },
  '大': { type:'kanji', romaji:'dai / oo', meaning:'besar', onyomi:'ダイ (dai), タイ (tai)', kunyomi:'おお-きい (oo-kii)', stroke_count:3, radical:'大（besar）', jlpt:'N5', examples:[{word:'大きい',romaji:'ookii',meaning:'besar'},{word:'大学',romaji:'daigaku',meaning:'universitas'},{word:'大切',romaji:'taisetsu',meaning:'penting / berharga'}] },
  '小': { type:'kanji', romaji:'shou / ko / o', meaning:'kecil', onyomi:'ショウ (shou)', kunyomi:'ちい-さい (chii-sai), こ (ko), お (o)', stroke_count:3, radical:'小（kecil）', jlpt:'N5', examples:[{word:'小さい',romaji:'chiisai',meaning:'kecil'},{word:'小学校',romaji:'shougakkou',meaning:'sekolah dasar'},{word:'小説',romaji:'shousetsu',meaning:'novel'}] },
  '上': { type:'kanji', romaji:'jou / ue', meaning:'atas', onyomi:'ジョウ (jou), ショウ (shou)', kunyomi:'うえ (ue), のぼ-る (nobo-ru)', stroke_count:3, radical:'一（satu）', jlpt:'N5', examples:[{word:'上手',romaji:'jouzu',meaning:'pandai / mahir'},{word:'上下',romaji:'jouge',meaning:'atas bawah'},{word:'屋上',romaji:'okujou',meaning:'atap gedung'}] },
  '下': { type:'kanji', romaji:'ka / shita', meaning:'bawah', onyomi:'カ (ka), ゲ (ge)', kunyomi:'した (shita), くだ-る (kuda-ru)', stroke_count:3, radical:'一（satu）', jlpt:'N5', examples:[{word:'下手',romaji:'heta',meaning:'tidak pandai'},{word:'地下',romaji:'chika',meaning:'bawah tanah'},{word:'下車',romaji:'gesha',meaning:'turun dari kendaraan'}] },
  '中': { type:'kanji', romaji:'chuu / naka', meaning:'tengah / dalam', onyomi:'チュウ (chuu)', kunyomi:'なか (naka)', stroke_count:4, radical:'｜（garis vertikal）', jlpt:'N5', examples:[{word:'中学校',romaji:'chuugakkou',meaning:'SMP'},{word:'中国',romaji:'chuugoku',meaning:'China'},{word:'一日中',romaji:'ichinichijuu',meaning:'sepanjang hari'}] },
  '山': { type:'kanji', romaji:'san / yama', meaning:'gunung', onyomi:'サン (san)', kunyomi:'やま (yama)', stroke_count:3, radical:'山（gunung）', jlpt:'N5', examples:[{word:'富士山',romaji:'fujisan',meaning:'Gunung Fuji'},{word:'山道',romaji:'yamamichi',meaning:'jalan gunung'},{word:'登山',romaji:'tozan',meaning:'mendaki gunung'}] },
  '川': { type:'kanji', romaji:'sen / kawa', meaning:'sungai', onyomi:'セン (sen)', kunyomi:'かわ (kawa)', stroke_count:3, radical:'川（sungai）', jlpt:'N5', examples:[{word:'川上',romaji:'kawakami',meaning:'hulu sungai'},{word:'小川',romaji:'ogawa',meaning:'sungai kecil'},{word:'川原',romaji:'kawara',meaning:'tepian sungai'}] },
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
