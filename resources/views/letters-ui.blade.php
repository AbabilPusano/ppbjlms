@extends('layouts.lms-ui', ['title' => 'Huruf - LMS PPBJ'])

@section('body')
@php
    $activeMenu = 'LETTERS';
    $allSets = [
        'HIRAGANA' => [
            'label' => 'Hiragana',
            'helper' => 'huruf dasar',
            'groups' => [
                [
                    'title' => 'Huruf Dasar (Seion / Gojūon)',
                    'letters' => [
                        ['あ','a'],['い','i'],['う','u'],['え','e'],['お','o'],
                        ['か','ka'],['き','ki'],['く','ku'],['け','ke'],['こ','ko'],
                        ['さ','sa'],['し','shi'],['す','su'],['せ','se'],['そ','so'],
                        ['た','ta'],['ち','chi'],['つ','tsu'],['て','te'],['と','to'],
                        ['な','na'],['に','ni'],['ぬ','nu'],['ね','ne'],['の','no'],
                        ['は','ha'],['ひ','hi'],['ふ','fu'],['へ','he'],['ほ','ho'],
                        ['ま','ma'],['み','mi'],['む','mu'],['め','me'],['も','mo'],
                        ['や','ya'],null,['ゆ','yu'],null,['よ','yo'],
                        ['ら','ra'],['り','ri'],['る','ru'],['れ','re'],['ろ','ro'],
                        ['わ','wa'],null,null,null,['を','wo'],
                        ['ん','n']
                    ]
                ],
                [
                    'title' => 'Suara Turunan (Dakuten & Handakuten)',
                    'letters' => [
                        ['が','ga'],['ぎ','gi'],['ぐ','gu'],['げ','ge'],['ご','go'],
                        ['ざ','za'],['じ','ji'],['ず','zu'],['ぜ','ze'],['ぞ','zo'],
                        ['だ','da'],['ぢ','di'],['づ','du'],['で','de'],['ど','do'],
                        ['ば','ba'],['び','bi'],['ぶ','bu'],['べ','be'],['ぼ','bo'],
                        ['ぱ','pa'],['ぴ','pi'],['ぷ','pu'],['ぺ','pe'],['ぽ','po']
                    ]
                ],
                [
                    'title' => 'Kombinasi (Yōon)',
                    'letters' => [
                        ['きゃ','kya'],['きゅ','kyu'],['きょ','kyo'],
                        ['しゃ','sha'],['しゅ','shu'],['しょ','sho'],
                        ['ちゃ','cha'],['ちゅ','chu'],['ちょ','cho'],
                        ['にゃ','nya'],['にゅ','nyu'],['にょ','nyo'],
                        ['ひゃ','hya'],['ひゅ','hyu'],['ひょ','hyo'],
                        ['みゃ','mya'],['みゅ','myu'],['みょ','myo'],
                        ['りゃ','rya'],['りゅ','ryu'],['りょ','ryo'],
                        ['ぎゃ','gya'],['ぎゅ','gyu'],['ぎょ','gyo'],
                        ['じゃ','ja'],['じゅ','ju'],['じょ','jo'],
                        ['びゃ','bya'],['びゅ','byu'],['びょ','byo'],
                        ['ぴゃ','pya'],['ぴゅ','pyu'],['ぴょ','pyo']
                    ]
                ]
            ]
        ],
        'KATAKANA' => [
            'label' => 'Katakana',
            'helper' => 'kata serapan',
            'groups' => [
                [
                    'title' => 'Huruf Dasar (Seion / Gojūon)',
                    'letters' => [
                        ['ア','a'],['イ','i'],['ウ','u'],['エ','e'],['オ','o'],
                        ['カ','ka'],['キ','ki'],['ク','ku'],['ケ','ke'],['コ','ko'],
                        ['サ','sa'],['シ','shi'],['ス','su'],['セ','se'],['ソ','so'],
                        ['タ','ta'],['チ','chi'],['ツ','tsu'],['テ','te'],['ト','to'],
                        ['ナ','na'],['ニ','ni'],['ヌ','nu'],['ネ','ne'],['ノ','no'],
                        ['ハ','ha'],['ヒ','hi'],['フ','fu'],['ヘ','he'],['ホ','ho'],
                        ['マ','ma'],['ミ','mi'],['ム','mu'],['メ','me'],['モ','mo'],
                        ['ヤ','ya'],null,['ユ','yu'],null,['ヨ','yo'],
                        ['ラ','ra'],['リ','ri'],['ル','ru'],['レ','re'],['ロ','ro'],
                        ['ワ','wa'],null,null,null,['ヲ','wo'],
                        ['ン','n']
                    ]
                ],
                [
                    'title' => 'Suara Turunan (Dakuten & Handakuten)',
                    'letters' => [
                        ['ガ','ga'],['ギ','gi'],['グ','gu'],['ゲ','ge'],['ゴ','go'],
                        ['ザ','za'],['ジ','ji'],['ズ','zu'],['ゼ','ze'],['ゾ','zo'],
                        ['ダ','da'],['ヂ','di'],['ヅ','du'],['デ','de'],['ド','do'],
                        ['バ','ba'],['ビ','bi'],['ブ','bu'],['ベ','be'],['ボ','bo'],
                        ['パ','pa'],['ピ','pi'],['プ','pu'],['ペ','pe'],['ポ','po']
                    ]
                ],
                [
                    'title' => 'Kombinasi (Yōon)',
                    'letters' => [
                        ['キャ','kya'],['キュ','kyu'],['キョ','kyo'],
                        ['シャ','sha'],['シュ','shu'],['ショ','sho'],
                        ['チャ','cha'],['チュ','chu'],['チョ','cho'],
                        ['ニャ','nya'],['ニュ','nyu'],['ニョ','nyo'],
                        ['ヒャ','hya'],['ヒュ','hyu'],['ヒョ','hyo'],
                        ['ミャ','mya'],['ミュ','myu'],['ミョ','myo'],
                        ['リャ','rya'],['リュ','ryu'],['リョ','ryo'],
                        ['ギャ','gya'],['ギュ','gyu'],['ギョ','gyo'],
                        ['ジャ','ja'],['ジュ','ju'],['ジョ','jo'],
                        ['ビャ','bya'],['ビュ','byu'],['ビョ','byo'],
                        ['ピャ','pya'],['ピュ','pyu'],['ピョ','pyo']
                    ]
                ]
            ]
        ],
        'KANJI' => [
            'label' => 'Kanji N5',
            'helper' => '113 kanji JLPT N5',
            'groups' => [
                [
                    'title' => 'Angka & Uang (Numbers & Currency)',
                    'letters' => [['一','ichi'],['二','ni'],['三','san'],['四','shi/yon'],['五','go'],['六','roku'],['七','nana'],['八','hachi'],['九','kyuu'],['十','juu'],['百','hyaku'],['千','sen'],['円','en']]
                ],
                [
                    'title' => 'Waktu & Kalender (Time & Calendar)',
                    'letters' => [['月','tsuki'],['火','hi'],['水','mizu'],['木','ki'],['金','kane'],['土','tsuchi'],['日','nichi'],['年','nen'],['今','ima'],['何','nani'],['分','fun'],['半','han'],['方','kata'],['時','toki'],['間','aida']]
                ],
                [
                    'title' => 'Orang & Keluarga (People & Family)',
                    'letters' => [['人','hito'],['子','ko'],['男','otoko'],['女','onna'],['父','chichi'],['母','haha']]
                ],
                [
                    'title' => 'Anggota Tubuh & Kekuatan (Body & Power)',
                    'letters' => [['口','kuchi'],['目','me'],['耳','mimi'],['手','te'],['足','ashi'],['体','karada'],['力','chikara']]
                ],
                [
                    'title' => 'Arah, Posisi & Ukuran (Direction & Size)',
                    'letters' => [['上','ue'],['下','shita'],['左','hidari'],['右','migi'],['大','dai'],['小','shou'],['中','naka'],['外','soto']]
                ],
                [
                    'title' => 'Sekolah & Kehidupan Kota (School & City)',
                    'letters' => [['学','manabu'],['校','kou'],['先','saki'],['生','sei'],['本','hon'],['名','mei'],['友','tomo'],['町','machi'],['寺','tera'],['車','kuruma'],['電','den']]
                ],
                [
                    'title' => 'Alam, Cuaca & Arah Mata Angin (Nature & Directions)',
                    'letters' => [['山','yama'],['川','kawa'],['田','ta'],['石','ishi'],['雨','ame'],['夕','yuu'],['岩','iwa'],['音','oto'],['東','higashi'],['西','nishi'],['南','minami'],['北','kita']]
                ],
                [
                    'title' => 'Hewan, Tumbuhan & Makanan (Animals, Plants & Food)',
                    'letters' => [['林','hayashi'],['森','mori'],['花','hana'],['竹','take'],['犬','inu'],['貝','kai'],['牛','ushi'],['魚','sakana'],['鳥','tori'],['米','kome'],['肉','niku'],['茶','cha'],['好','suki'],['物','mono']]
                ],
                [
                    'title' => 'Kata Sifat (Adjectives)',
                    'letters' => [['高','taka'],['安','yasu'],['多','oo'],['少','suku'],['新','atara'],['古','furu'],['明','aka'],['暗','kura'],['長','naga'],['元','moto'],['気','ki']]
                ],
                [
                    'title' => 'Kata Kerja (Verbs)',
                    'letters' => [['見','miru'],['立','tatsu'],['入','hairu'],['出','deru'],['休','yasumu'],['行','iku'],['来','kuru'],['言','iu'],['帰','kaeru'],['書','kaku'],['読','yomu'],['話','hanasu'],['聞','kiku'],['食','taberu'],['飲','nomu'],['買','kau']]
                ]
            ]
        ],
        'KANJI_N4' => [
            'label' => 'Kanji N4',
            'helper' => '209 kanji JLPT N4',
            'groups' => [
                [
                    'title' => 'Waktu (Time)',
                    'letters' => [['朝','asa'],['昼','hiru'],['晩','ban'],['夜','yoru'],['前','mae'],['後','ushiro'],['正','tadashii'],['午','go'],['毎','mai'],['週','shuu'],['去','saru']]
                ],
                [
                    'title' => 'Orang (People)',
                    'letters' => [['家','ie'],['族','zoku'],['兄','ani'],['弟','otouto'],['姉','ane'],['妹','imouto'],['夫','otto'],['妻','tsuma'],['主','nushi'],['奥','oku'],['私','watashi'],['王','ou'],['様','sama'],['才','sai']]
                ],
                [
                    'title' => 'Warna & Bentuk (Color & Shape)',
                    'letters' => [['赤','aka'],['青','ao'],['白','shiro'],['黒','kuro'],['色','iro'],['銀','gin'],['黄','ki'],['緑','midori'],['丸','maru'],['玉','tama']]
                ],
                [
                    'title' => 'Musim & Cuaca (Season & Weather)',
                    'letters' => [['春','haru'],['夏','natsu'],['秋','aki'],['冬','fuyu'],['空','sora'],['天','ten'],['風','kaze'],['台','dai'],['雲','kumo'],['雪','yuki'],['晴','hare'],['星','hoshi'],['光','hikari']]
                ],
                [
                    'title' => 'Alam (Nature)',
                    'letters' => [['地','chi'],['谷','tani'],['自','ji'],['然','zen'],['草','kusa'],['原','hara'],['海','umi'],['湖','mizuumi'],['池','ike']]
                ],
                [
                    'title' => 'Desa & Pakaian (Countryside & Clothes)',
                    'letters' => [['里','sato'],['野','no'],['虫','mushi'],['羽','hane'],['馬','uma'],['鳴','naku'],['毛','ke'],['糸','ito'],['衣','koromo'],['服','fuku'],['洋','you']]
                ],
                [
                    'title' => 'Memasak (Cooking)',
                    'letters' => [['料','ryou'],['理','ri'],['飯','meshi'],['麦','mugi'],['油','abura'],['酒','sake'],['味','aji']]
                ],
                [
                    'title' => 'Alamat (Address)',
                    'letters' => [['住','sumu'],['所','tokoro'],['都','miyako'],['道','michi'],['府','fu'],['県','ken'],['京','kyou'],['市','ichi'],['区','ku'],['村','mura'],['番','ban'],['号','gou'],['国','kuni'],['紙','kami']]
                ],
                [
                    'title' => 'Toko (Shop)',
                    'letters' => [['店','mise'],['客','kyaku'],['売','uru'],['品','shina'],['薬','kusuri'],['待','matsu'],['合','au'],['計','hakaru'],['辺','atari']]
                ],
                [
                    'title' => 'Transportasi (Transportation)',
                    'letters' => [['交','majiwaru'],['通','tooru'],['荷','ni'],['送','okuru'],['宅','taku'],['止','tomeru'],['急','isogu'],['特','toku'],['鉄','tetsu'],['船','fune']]
                ],
                [
                    'title' => 'Tempat (Place)',
                    'letters' => [['部','bu'],['屋','ya'],['教','oshieru'],['室','shitsu'],['会','au'],['社','sha'],['駅','eki'],['工','kou'],['場','ba'],['病','byou'],['院','in'],['公','kou'],['園','en'],['図','zu'],['館','kan'],['映','ei'],['画','ga']]
                ],
                [
                    'title' => 'Belajar (Study)',
                    'letters' => [['勉','ben'],['強','tsuyoi'],['宿','yado'],['題','dai'],['質','shitsu'],['問','toi'],['試','tamesu'],['験','ken'],['答','kotae'],['考','kangaeru']]
                ],
                [
                    'title' => 'Mata Pelajaran (Subject)',
                    'letters' => [['字','ji'],['文','bun'],['漢','kan'],['数','kazu'],['英','ei'],['語','go'],['化','bakeru'],['育','sodatsu']]
                ],
                [
                    'title' => 'Universitas (University)',
                    'letters' => [['研','togu'],['究','kyuu'],['医','i'],['科','ka'],['政','sei'],['治','osameru'],['経','kei'],['済','sumu'],['歴','reki'],['史','shi']]
                ],
                [
                    'title' => 'Hobi (Hobby)',
                    'letters' => [['運','hakobu'],['動','ugoku'],['泳','oyogu'],['旅','tabi'],['世','yo'],['界','kai'],['練','ren'],['習','narau'],['写','utsusu'],['真','makoto'],['楽','tanoshii'],['声','koe'],['歌','uta'],['集','atsumeru']]
                ],
                [
                    'title' => 'Kata Kerja (Verbs)',
                    'letters' => [['作','tsukuru'],['使','tsukau'],['思','omou'],['持','motsu'],['当','ataru'],['知','shiru'],['働','hataraku']]
                ],
                [
                    'title' => 'Kata Kerja Berpasangan (Paired Verbs)',
                    'letters' => [['始','hajimaru'],['終','owaru'],['乗','noru'],['降','oriru'],['開','akeru'],['閉','shimeru'],['発','hatsu'],['着','tsuku'],['走','hashiru'],['歩','aruku']]
                ],
                [
                    'title' => 'Kata Sifat 1 (Adjective 1)',
                    'letters' => [['近','chikai'],['遠','tooi'],['重','omoi'],['軽','karui'],['早','hayai'],['速','hayai'],['遅','osoi'],['広','hiroi'],['細','hosoi'],['太','futoi'],['暑','atsui'],['寒','samui']]
                ],
                [
                    'title' => 'Kata Sifat 2 (Adjective 2)',
                    'letters' => [['低','hikui'],['短','mijikai'],['弱','yowai'],['若','wakai'],['静','shizuka'],['有','aru'],['心','kokoro'],['同','onaji'],['便','ben'],['利','ri'],['親','oya'],['切','kiru'],['不','fu']]
                ],
            ]
        ],
    ];

    $isHuruf = ($level && $level->code === 'huruf');
    $sets = [];
    if ($isHuruf) {
        $sets['HIRAGANA'] = $allSets['HIRAGANA'];
        $sets['KATAKANA'] = $allSets['KATAKANA'];
    } else {
        $jlptCode = $level ? $level->code : 'N5';
        if ($jlptCode === 'N5') {
            $sets['KANJI'] = $allSets['KANJI'];
        } elseif ($jlptCode === 'N4') {
            $sets['KANJI'] = $allSets['KANJI_N4'];
        } else {
            $sets['KANJI'] = [
                'label' => 'Kanji ' . $jlptCode,
                'helper' => 'Kanji JLPT ' . $jlptCode,
                'groups' => []
            ];
        }
    }
@endphp

<div class="app-container">
    @include('partials.student-sidebar')

    <main class="main-content">
        <section class="learning-shell letters-shell">
            <header class="learning-hero letters-hero">
                <div class="learning-hero__copy">
                    <span class="learning-hero__eyebrow">
                        @if($level && $level->code === 'huruf')
                            Huruf Jepang — Hiragana &amp; Katakana
                        @elseif($level)
                            Kanji Level {{ $level->code }}
                        @else
                            Ruang huruf Jepang
                        @endif
                    </span>
                    <h1>Pengenalan Huruf Jepang</h1>
                    <p>
                        Kenali bentuk huruf dan cara bacanya di sini. Silakan pilih set huruf yang ingin kamu pelajari.
                    </p>
                </div>
                <div class="learning-hero__meta">
                    @php
                        $firstKey = array_key_first($sets);
                        $initialCount = 0;
                        if ($firstKey !== null) {
                            foreach ($sets[$firstKey]['groups'] as $group) {
                                $initialCount += count(array_filter($group['letters']));
                            }
                        }
                    @endphp
                    <div class="learning-stat-card letters-stat-card">
                        <span class="learning-stat-card__label">Set aktif</span>
                        <strong id="letters-active-set" class="letters-stat-text">
                            {{ $firstKey !== null ? $sets[$firstKey]['label'] : '-' }}
                        </strong>
                    </div>
                    <div class="learning-stat-card letters-stat-card">
                        <span class="learning-stat-card__label">Huruf tampil</span>
                        <strong id="letters-count">{{ $initialCount }}</strong>
                    </div>
                </div>
            </header>

            <section class="letters-board">
                <div class="section-heading"
                    style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; flex-wrap: wrap; gap: 16px;">
                    <div>
                        <span class="ds-section-eyebrow">Pilih set huruf</span>
                        <h2 class="section-heading__title" style="margin: 4px 0 0;">Referensi Huruf</h2>
                        <p class="section-heading__desc" style="margin: 4px 0 0;">
                            Klik kartu huruf untuk melihat detail cara baca dan penulisannya.
                        </p>
                    </div>
                    <div class="level-selector-container" style="display: flex; align-items: center; gap: 8px;">
                        <span style="font-size: 0.9rem; font-weight: 700; color: var(--text-muted);">Level Aktif:</span>
                        <div class="guru-level-select-wrapper"
                            style="position: relative; display: inline-flex; align-items: center; background: var(--card-bg); border: 2px solid var(--border); border-radius: 12px; padding: 4px 12px;">
                            <span class="material-symbols-outlined"
                                style="font-size: 18px; color: var(--text-muted); margin-right: 6px;">tune</span>
                            <select class="guru-level-select"
                                onchange="window.location.href = '?level_id=' + this.value" aria-label="Pilih Level"
                                style="border: none; background: transparent; font-weight: 800; color: var(--text); font-size: 0.95rem; cursor: pointer; outline: none; padding-right: 20px;">
                            @foreach($levels as $lvl)
                                <option value="{{ $lvl->id }}" {{ $level->id === $lvl->id ? 'selected' : '' }}>
                                    @if($lvl->code === 'huruf')
                                        Huruf (Hiragana &amp; Katakana)
                                    @else
                                        {{ $lvl->code }} &mdash; {{ $lvl->name }}
                                    @endif
                                </option>
                            @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="letters-tabs" role="tablist" aria-label="Pilih jenis huruf">
                    @foreach ($sets as $key => $set)
                        @php
                            $lettersCount = 0;
                            foreach ($set['groups'] as $group) {
                                $lettersCount += count(array_filter($group['letters']));
                            }
                        @endphp
                        <button type="button" role="tab" class="letters-tab {{ $loop->first ? 'letters-tab--active' : '' }}" 
                            data-letter-tab="{{ $key }}"
                            data-letter-label="{{ $set['label'] }}"
                            data-letter-count="{{ $lettersCount }}">
                            <span class="material-symbols-outlined" aria-hidden="true">translate</span>
                            <span>{{ $set['label'] }}</span>
                            <small>{{ $set['helper'] }}</small>
                        </button>
                    @endforeach
                </div>

                @foreach ($sets as $key => $set)
                    @php
                        $lettersCount = 0;
                        foreach ($set['groups'] as $group) {
                            $lettersCount += count(array_filter($group['letters']));
                        }
                    @endphp
                    <div class="letters-card-panel" data-letter-panel="{{ $key }}" @if (!$loop->first) hidden @endif>
                        <div class="letters-card-panel__head">
                            <div>
                                <span class="letters-card-panel__eyebrow">{{ $set['helper'] }}</span>
                                <h3>{{ $set['label'] }}</h3>
                            </div>
                            <span class="letters-card-panel__count">{{ $lettersCount }} huruf</span>
                        </div>

                        @if (empty($set['groups']))
                            <div style="padding: 48px 32px; text-align: center; background: var(--card-bg); border: 2px dashed var(--border); border-radius: 20px; margin-top: 24px;">
                                <div style="font-size: 56px; margin-bottom: 16px; line-height: 1;">漢字</div>
                                <h4 style="font-size: 1.15rem; font-weight: 800; margin: 0 0 8px; color: var(--text);">Kanji {{ $level->code }} Segera Hadir</h4>
                                <p style="font-size: 0.9rem; margin: 0 0 20px; color: var(--text-muted); max-width: 320px; margin-inline: auto;">
                                    Daftar kanji JLPT {{ $level->code }} sedang kami siapkan. Sementara itu, pelajari Hiragana &amp; Katakana di level
                                    <strong>Huruf</strong>.
                                </p>
                                <a href="{{ route('letters.index') }}?level_id={{ \App\Models\Level::where('code','huruf')->value('id') }}"
                                   style="display: inline-flex; align-items: center; gap: 8px; background: var(--home-coral, #ff6b6b); color: #fff; padding: 10px 20px; border-radius: 12px; font-weight: 700; font-size: 0.9rem; text-decoration: none;">
                                    <span class="material-symbols-outlined" style="font-size: 18px;">translate</span>
                                    Buka Level Huruf
                                </a>
                            </div>
                        @else
                            @foreach ($set['groups'] as $group)
                                <div class="letters-group-section" style="margin-top: 28px;">
                                    <h4 class="letters-group-title" style="font-size: 14px; font-weight: 700; color: var(--home-ink); margin-bottom: 12px; border-left: 3px solid var(--home-coral); padding-left: 8px; text-transform: uppercase; letter-spacing: 0.05em;">
                                        {{ $group['title'] }}
                                    </h4>
                                    <div class="letters-card-grid" role="tabpanel" aria-label="{{ $group['title'] }}">
                                        @foreach ($group['letters'] as $letter)
                                            @if ($letter)
                                                @php $isYoon = mb_strlen($letter[0]) >= 2; @endphp
                                                <article class="kana-card {{ $isYoon ? 'kana-card--yoon' : '' }}" data-char="{{ $letter[0] }}" data-type="{{ strtolower($key) }}" data-romaji="{{ $letter[1] }}" role="button" tabindex="0" aria-label="Lihat detail {{ $letter[0] }}">
                                                    @if ($isYoon)
                                                        <span class="kana-card__symbol">
                                                            <span class="kana-main">{{ mb_substr($letter[0], 0, 1) }}</span><span class="kana-small">{{ mb_substr($letter[0], 1) }}</span>
                                                        </span>
                                                    @else
                                                        <span class="kana-card__symbol">{{ $letter[0] }}</span>
                                                    @endif
                                                    <span class="kana-card__romaji">{{ $letter[1] }}</span>
                                                </article>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        @endif
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
  'ま': { type:'hiragana', romaji:'ma', pronunciation:'Dibaca seperti "ma" pada kata "mata"', stroke_count:3, examples:[{word:'まつ',romaji:'matsu',meaning:'menunggu / pinus'},{word:'まど',romaji:'mado',meaning:'jendela'}] },
  'み': { type:'hiragana', romaji:'mi', pronunciation:'Dibaca seperti "mi" pada kata "minum"', stroke_count:2, examples:[{word:'みず',romaji:'mizu',meaning:'air'},{word:'みみ',romaji:'mimi',meaning:'telinga'}] },
  'む': { type:'hiragana', romaji:'mu', pronunciation:'Dibaca seperti "mu" pada kata "mulut"', stroke_count:3, examples:[{word:'むし',romaji:'mushi',meaning:'serangga'},{word:'むすめ',romaji:'musume',meaning:'anak perempuan'}] },
  'め': { type:'hiragana', romaji:'me', pronunciation:'Dibaca seperti "me" pada kata "merah"', stroke_count:2, examples:[{word:'め',romaji:'me',meaning:'mata'},{word:'めがね',romaji:'megane',meaning:'kacamata'}] },
  'も': { type:'hiragana', romaji:'mo', pronunciation:'Dibaca seperti "mo" pada kata "mobil"', stroke_count:3, examples:[{word:'もり',romaji:'mori',meaning:'hutan'},{word:'もち',romaji:'mochi',meaning:'kue mochi'}] },
  'や': { type:'hiragana', romaji:'ya', pronunciation:'Dibaca seperti "ya" pada kata "yakin"', stroke_count:3, examples:[{word:'やま',romaji:'yama',meaning:'gunung'},{word:'yasai',romaji:'yasai',meaning:'sayuran'}] },
  'ゆ': { type:'hiragana', romaji:'yu', pronunciation:'Dibaca seperti "yu" pada kata "yuda"', stroke_count:2, examples:[{word:'ゆki',romaji:'yuki',meaning:'salju'},{word:'ゆめ',romaji:'yume',meaning:'mimpi'}] },
  'よ': { type:'hiragana', romaji:'yo', pronunciation:'Dibaca seperti "yo" pada kata "yoyo"', stroke_count:2, examples:[{word:'よる',romaji:'yoru',meaning:'malam'},{word:'よむ',romaji:'yomu',meaning:'membaca'}] },
  'ら': { type:'hiragana', romaji:'ra', pronunciation:'Dibaca seperti "ra" pada kata "rambut"', stroke_count:2, examples:[{word:'らく',romaji:'raku',meaning:'nyaman / mudah'},{word:'らいが',romaji:'raiga',meaning:'taring petir'}] },
  'り': { type:'hiragana', romaji:'ri', pronunciation:'Dibaca seperti "ri" pada kata "ringan"', stroke_count:2, examples:[{word:'りんご',romaji:'ringo',meaning:'apel'},{word:'りす',romaji:'risu',meaning:'tupai'}] },
  'る': { type:'hiragana', romaji:'ru', pronunciation:'Dibaca seperti "ru" pada kata "rusa"', stroke_count:2, examples:[{word:'るす',romaji:'rusu',meaning:'tidak di rumah'},{word:'さる',romaji:'saru',meaning:'monyet'}] },
  'れ': { type:'hiragana', romaji:'re', pronunciation:'Dibaca seperti "re" pada kata "rekam"', stroke_count:2, examples:[{word:'れきし',romaji:'rekishi',meaning:'sejarah'},{word:'れい',romaji:'rei',meaning:'contoh / nol'}] },
  'ろ': { type:'hiragana', romaji:'ro', pronunciation:'Dibaca seperti "ro" pada kata "roti"', stroke_count:2, examples:[{word:'ろく',romaji:'roku',meaning:'enam'},{word:'ろじ',romaji:'roji',meaning:'gang / lorong'}] },
  'わ': { type:'hiragana', romaji:'wa', pronunciation:'Dibaca seperti "wa" pada kata "wajah"', stroke_count:2, examples:[{word:'わたし',romaji:'watashi',meaning:'saya'},{word:'わに',romaji:'wani',meaning:'buaya'}] },
  'を': { type:'hiragana', romaji:'wo', pronunciation:'Dibaca seperti "o", digunakan khusus sebagai partikel objek', stroke_count:3, examples:[{word:'を',romaji:'wo/o',meaning:'partikel objek penanda'}] },
  'ん': { type:'hiragana', romaji:'n', pronunciation:'Dibaca dengung seperti "n", "m", atau "ng"', stroke_count:1, examples:[{word:'にほん',romaji:'nihon',meaning:'Jepang'},{word:'ほん',romaji:'hon',meaning:'buku'}] },

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
  'マ': { type:'katakana', romaji:'ma', pronunciation:'Dibaca seperti "ma"', stroke_count:2, examples:[{word:'マフラー',romaji:'mafuraa',meaning:'syal'},{word:'マッチ',romaji:'matchi',meaning:'korek api'}] },
  'ミ': { type:'katakana', romaji:'mi', pronunciation:'Dibaca seperti "mi"', stroke_count:3, examples:[{word:'ミルク',romaji:'miruku',meaning:'susu'},{word:'ミシン',romaji:'mishin',meaning:'mesin jahit'}] },
  'ム': { type:'katakana', romaji:'mu', pronunciation:'Dibaca seperti "mu"', stroke_count:2, examples:[{word:'ハム',romaji:'hamu',meaning:'daging ham'},{word:'ガム',romaji:'gamu',meaning:'permen karet'}] },
  'メ': { type:'katakana', romaji:'me', pronunciation:'Dibaca seperti "me"', stroke_count:2, examples:[{word:'メール',romaji:'meeru',meaning:'email'},{word:'メロン',romaji:'meron',meaning:'melon'}] },
  'モ': { type:'katakana', romaji:'mo', pronunciation:'Dibaca seperti "mo"', stroke_count:3, examples:[{word:'モデル',romaji:'modelu',meaning:'model'},{word:'モノレール',romaji:'monoreeru',meaning:'monorel'}] },
  'ヤ': { type:'katakana', romaji:'ya', pronunciation:'Dibaca seperti "ya"', stroke_count:2, examples:[{word:'ヤシ',romaji:'yashi',meaning:'kelapa'},{word:'チャート',romaji:'chaato',meaning:'grafik'}] },
  'ユ': { type:'katakana', romaji:'yu', pronunciation:'Dibaca seperti "yu"', stroke_count:2, examples:[{word:'ユニフォーム',romaji:'yunifoomu',meaning:'seragam'},{word:'ユーモア',romaji:'yuumoa',meaning:'humor'}] },
  'ヨ': { type:'katakana', romaji:'yo', pronunciation:'Dibaca seperti "yo"', stroke_count:3, examples:[{word:'ヨット',romaji:'yotto',meaning:'kapal pesiar'},{word:'ヨガ',romaji:'yoga',meaning:'yoga'}] },
  'ラ': { type:'katakana', romaji:'ra', pronunciation:'Dibaca seperti "ra"', stroke_count:2, examples:[{word:'ラジオ',romaji:'rajio',meaning:'radio'},{word:'ラーメン',romaji:'ramen',meaning:'mi ramen'}] },
  'リ': { type:'katakana', romaji:'ri', pronunciation:'Dibaca seperti "ri"', stroke_count:2, examples:[{word:'リボン',romaji:'ribon',meaning:'pita'},{word:'リンゴ',romaji:'ringo',meaning:'apel'}] },
  'ル': { type:'katakana', romaji:'ru', pronunciation:'Dibaca seperti "ru"', stroke_count:2, examples:[{word:'ルール',romaji:'ruuru',meaning:'aturan'},{word:'ルビー',romaji:'rubii',meaning:'batu rubi'}] },
  'レ': { type:'katakana', romaji:'re', pronunciation:'Dibaca seperti "re"', stroke_count:1, examples:[{word:'レポート',romaji:'repooto',meaning:'laporan'},{word:'レモン',romaji:'remon',meaning:'lemon'}] },
  'ロ': { type:'katakana', romaji:'ro', pronunciation:'Dibaca seperti "ro"', stroke_count:3, examples:[{word:'ロボット',romaji:'robotto',meaning:'robot'},{word:'ロケット',romaji:'roketto',meaning:'roket'}] },
  'ワ': { type:'katakana', romaji:'wa', pronunciation:'Dibaca seperti "wa"', stroke_count:2, examples:[{word:'ワイシャツ',romaji:'waishatsu',meaning:'kemeja'},{word:'ワイン',romaji:'wain',meaning:'anggur / wine'}] },
  'ヲ': { type:'katakana', romaji:'wo', pronunciation:'Dibaca seperti "o"', stroke_count:3, examples:[{word:'ヲタ',romaji:'wota',meaning:'otaku (singkatan)'}] },
  'ン': { type:'katakana', romaji:'n', pronunciation:'Dibaca seperti "n"', stroke_count:2, examples:[{word:'パン',romaji:'pan',meaning:'roti'},{word:'マンション',romaji:'manshon',meaning:'apartemen'}] },

  // ─── DAKUTEN & HANDAKUTEN (HIRAGANA) ─────────────────────────
  'が': { type:'hiragana', romaji:'ga', pronunciation:'Dibaca seperti "ga" pada kata "gajah"', stroke_count:5, examples:[{word:'がっこう',romaji:'gakkou',meaning:'sekolah'},{word:'gakusei',romaji:'gakusei',meaning:'siswa'}] },
  'ぎ': { type:'hiragana', romaji:'gi', pronunciation:'Dibaca seperti "gi" pada kata "gigi"', stroke_count:6, examples:[{word:'ぎんこう',romaji:'ginkou',meaning:'bank'},{word:'ぎゅうにゅう',romaji:'gyuunyuu',meaning:'susu sapi'}] },
  'ぐ': { type:'hiragana', romaji:'gu', pronunciation:'Dibaca seperti "gu" pada kata "guru"', stroke_count:3, examples:[{word:'ぐんま',romaji:'gunma',meaning:'Gunma (prefektur)'}] },
  'げ': { type:'hiragana', romaji:'ge', pronunciation:'Dibaca seperti "ge" pada kata "gelas"', stroke_count:5, examples:[{word:'げんき',romaji:'genki',meaning:'sehat / semangat'}] },
  'ご': { type:'hiragana', romaji:'go', pronunciation:'Dibaca seperti "go" pada kata "gol"', stroke_count:4, examples:[{word:'ごはん',romaji:'gohan',meaning:'nasi / makan'}] },
  'ざ': { type:'hiragana', romaji:'za', pronunciation:'Dibaca seperti "za" pada kata "zaman"', stroke_count:5, examples:[{word:'ざっし',romaji:'zasshi',meaning:'majalah'}] },
  'じ': { type:'hiragana', romaji:'ji', pronunciation:'Dibaca seperti "ji" pada kata "jika"', stroke_count:3, examples:[{word:'じしん',romaji:'jishin',meaning:'gempa bumi / percaya diri'},{word:'じてんしゃ',romaji:'jitensha',meaning:'sepeda'}] },
  'ず': { type:'hiragana', romaji:'zu', pronunciation:'Dibaca seperti "zu" pada kata "zulu"', stroke_count:4, examples:[{word:'ちず',romaji:'chizu',meaning:'peta'}] },
  'ぜ': { type:'hiragana', romaji:'ze', pronunciation:'Dibaca seperti "ze" pada kata "zebra"', stroke_count:5, examples:[{word:'ぜんぶ',romaji:'zenbu',meaning:'semua'}] },
  'ぞ': { type:'hiragana', romaji:'zo', pronunciation:'Dibaca seperti "zo" pada kata "zona"', stroke_count:4, examples:[{word:'かぞく',romaji:'kazoku',meaning:'keluarga'}] },
  'だ': { type:'hiragana', romaji:'da', pronunciation:'Dibaca seperti "da" pada kata "dada"', stroke_count:6, examples:[{word:'だいがく',romaji:'daigaku',meaning:'universitas'},{word:'くだもの',romaji:'kudamono',meaning:'buah-buahan'}] },
  'ぢ': { type:'hiragana', romaji:'ji/di', pronunciation:'Dibaca "ji" (sama seperti じ, jarang digunakan)', stroke_count:4, examples:[{word:'はなぢ',romaji:'hanaji',meaning:'mimisan'}] },
  'づ': { type:'hiragana', romaji:'zu/du', pronunciation:'Dibaca "zu" (sama seperti ず, jarang digunakan)', stroke_count:3, examples:[{word:'つづく',romaji:'tsuzuku',meaning:'bersambung'}] },
  'で': { type:'hiragana', romaji:'de', pronunciation:'Dibaca seperti "de" pada kata "desa"', stroke_count:4, examples:[{word:'でんわ',romaji:'denwa',meaning:'telepon'},{word:'でんしゃ',romaji:'densha',meaning:'kereta api'}] },
  'ど': { type:'hiragana', romaji:'do', pronunciation:'Dibaca seperti "do" pada kata "domba"', stroke_count:4, examples:[{word:'doko',romaji:'doko',meaning:'di mana'},{word:'どうぞ',romaji:'douzo',meaning:'silakan'}] },
  'ば': { type:'hiragana', romaji:'ba', pronunciation:'Dibaca seperti "ba" pada kata "batu"', stroke_count:5, examples:[{word:'ばんごう',romaji:'bangou',meaning:'nomor'},{word:'ばしょ',romaji:'basho',meaning:'tempat'}] },
  'び': { type:'hiragana', romaji:'bi', pronunciation:'Dibaca seperti "bi" pada kata "bisa"', stroke_count:3, examples:[{word:'びょういん',romaji:'byouin',meaning:'rumah sakit'},{word:'じびき',romaji:'jibiki',meaning:'kamus'}] },
  'ぶ': { type:'hiragana', romaji:'bu', pronunciation:'Dibaca seperti "bu" pada kata "buku"', stroke_count:6, examples:[{word:'doubutsu',romaji:'doubutsu',meaning:'hewan'},{word:'あぶない',romaji:'abunai',meaning:'bahaya'}] },
  'べ': { type:'hiragana', romaji:'be', pronunciation:'Dibaca seperti "be" pada kata "bebek"', stroke_count:3, examples:[{word:'べんきょう',romaji:'benkyou',meaning:'belajar'}] },
  'ぼ': { type:'hiragana', romaji:'bo', pronunciation:'Dibaca seperti "bo" pada kata "bola"', stroke_count:6, examples:[{word:'ぼうし',romaji:'boushi',meaning:'topi'}] },
  'ぱ': { type:'hiragana', romaji:'pa', pronunciation:'Dibaca seperti "pa" pada kata "padi"', stroke_count:4, examples:[{word:'ぱん',romaji:'pan',meaning:'roti'}] },
  'ぴ': { type:'hiragana', romaji:'pi', pronunciation:'Dibaca seperti "pi" pada kata "pita"', stroke_count:2, examples:[{word:'ぴかぴか',romaji:'pikapika',meaning:'berkelap-kelip'}] },
  'ぷ': { type:'hiragana', romaji:'pu', pronunciation:'Dibaca seperti "pu" pada kata "pulau"', stroke_count:5, examples:[{word:'きっぷ',romaji:'kippu',meaning:'tiket'}] },
  'ぺ': { type:'hiragana', romaji:'pe', pronunciation:'Dibaca seperti "pe" pada kata "peta"', stroke_count:2, examples:[{word:'ぺらぺら',romaji:'perapera',meaning:'lancar berbicara'}] },
  'ぽ': { type:'hiragana', romaji:'po', pronunciation:'Dibaca seperti "po" pada kata "pohon"', stroke_count:5, examples:[{word:'さんぽ',romaji:'sanpo',meaning:'jalan-jalan'}] },

  // ─── DAKUTEN & HANDAKUTEN (KATAKANA) ─────────────────────────
  'ガ': { type:'katakana', romaji:'ga', pronunciation:'Dibaca seperti "ga"', stroke_count:4, examples:[{word:'ガラス',romaji:'garasu',meaning:'kaca'},{word:'ガス',romaji:'gasu',meaning:'gas'}] },
  'ギ': { type:'katakana', romaji:'gi', pronunciation:'Dibaca seperti "gi"', stroke_count:5, examples:[{word:'ギター',romaji:'gitaa',meaning:'gitar'},{word:'ギフト',romaji:'gifuto',meaning:'hadiah / kado'}] },
  'グ': { type:'katakana', romaji:'gu', pronunciation:'Dibaca seperti "gu"', stroke_count:4, examples:[{word:'グループ',romaji:'guruupu',meaning:'kelompok / grup'},{word:'グラフ',romaji:'gurafu',meaning:'grafik'}] },
  'ゲ': { type:'katakana', romaji:'ge', pronunciation:'Dibaca seperti "ge"', stroke_count:5, examples:[{word:'ゲーム',romaji:'geemu',meaning:'permainan / game'},{word:'ゲスト',romaji:'gesuto',meaning:'tamu'}] },
  'ゴ': { type:'katakana', romaji:'go', pronunciation:'Dibaca seperti "go"', stroke_count:4, examples:[{word:'ゴルフ',romaji:'gorufu',meaning:'golf'},{word:'ゴール',romaji:'gooru',meaning:'gawang / gol'}] },
  'ザ': { type:'katakana', romaji:'za', pronunciation:'Dibaca seperti "za"', stroke_count:5, examples:[{word:'デザイン',romaji:'dezain',meaning:'desain'},{word:'ザ・ワールド',romaji:'za waarudo',meaning:'"the world" (contoh pemakaian)'}] },
  'ジ': { type:'katakana', romaji:'ji', pronunciation:'Dibaca seperti "ji"', stroke_count:5, examples:[{word:'ジーンズ',romaji:'jiinzu',meaning:'celana jeans'},{word:'ジュース',romaji:'juusu',meaning:'jus'}] },
  'ズ': { type:'katakana', romaji:'zu', pronunciation:'Dibaca seperti "zu"', stroke_count:4, examples:[{word:'ズボン',romaji:'zubon',meaning:'celana panjang'},{word:'サイズ',romaji:'saizu',meaning:'ukuran'}] },
  'ゼ': { type:'katakana', romaji:'ze', pronunciation:'Dibaca seperti "ze"', stroke_count:4, examples:[{word:'ゼロ',romaji:'zero',meaning:'nol'},{word:'ゼリー',romaji:'zerii',meaning:'jelly / agar-agar'}] },
  'ゾ': { type:'katakana', romaji:'zo', pronunciation:'Dibaca seperti "zo"', stroke_count:4, examples:[{word:'ゾーン',romaji:'zoon',meaning:'zona'},{word:'ゾンビ',romaji:'zombie',meaning:'zombie'}] },
  'ダ': { type:'katakana', romaji:'da', pronunciation:'Dibaca seperti "da"', stroke_count:5, examples:[{word:'ダブル',romaji:'daburu',meaning:'ganda / double'},{word:'ダンス',romaji:'dansu',meaning:'dansa / tari'}] },
  'ヂ': { type:'katakana', romaji:'ji/di', pronunciation:'Dibaca "ji" (jarang digunakan)', stroke_count:5, examples:[{word:'ヂシ',romaji:'ji/di',meaning:'jarang digunakan dalam kata modern'}] },
  'ヅ': { type:'katakana', romaji:'zu/du', pronunciation:'Dibaca "zu" (jarang digunakan)', stroke_count:4, examples:[{word:'カンヅメ',romaji:'kanzume',meaning:'makanan kaleng'}] },
  'デ': { type:'katakana', romaji:'de', pronunciation:'Dibaca seperti "de"', stroke_count:5, examples:[{word:'デパート',romaji:'depaato',meaning:'toserba / mall'},{word:'データ',romaji:'deeta',meaning:'data'}] },
  'ド': { type:'katakana', romaji:'do', pronunciation:'Dibaca seperti "do"', stroke_count:4, examples:[{word:'ドア',romaji:'doa',meaning:'pintu'},{word:'ドラマ',romaji:'dorama',meaning:'drama'}] },
  'バ': { type:'katakana', romaji:'ba', pronunciation:'Dibaca seperti "ba"', stroke_count:4, examples:[{word:'バス',romaji:'basu',meaning:'bus'},{word:'バター',romaji:'bataa',meaning:'mentega'}] },
  'ビ': { type:'katakana', romaji:'bi', pronunciation:'Dibaca seperti "bi"', stroke_count:4, examples:[{word:'ビール',romaji:'biiru',meaning:'bir'},{word:'ビル',romaji:'biru',meaning:'gedung'}] },
  'ブ': { type:'katakana', romaji:'bu', pronunciation:'Dibaca seperti "bu"', stroke_count:4, examples:[{word:'ブログ',romaji:'burogu',meaning:'blog'},{word:'ブランド',romaji:'burando',meaning:'merek / brand'}] },
  'ベ': { type:'katakana', romaji:'be', pronunciation:'Dibaca seperti "be"', stroke_count:3, examples:[{word:'ベルト',romaji:'beruto',meaning:'sabuk / ikat pinggang'},{word:'ベッド',romaji:'beddo',meaning:'tempat tidur'}] },
  'ボ': { type:'katakana', romaji:'bo', pronunciation:'Dibaca seperti "bo"', stroke_count:6, examples:[{word:'ボール',romaji:'booru',meaning:'bola'},{word:'ボタン',romaji:'botan',meaning:'tombol / kancing'}] },
  'パ': { type:'katakana', romaji:'pa', pronunciation:'Dibaca seperti "pa"', stroke_count:4, examples:[{word:'パン',romaji:'pan',meaning:'roti'},{word:'パソコン',romaji:'pasokon',meaning:'komputer / PC'}] },
  'ピ': { type:'katakana', romaji:'pi', pronunciation:'Dibaca seperti "pi"', stroke_count:4, examples:[{word:'ピアノ',romaji:'piano',meaning:'piano'},{word:'ピンク',romaji:'pinku',meaning:'merah muda / pink'}] },
  'プ': { type:'katakana', romaji:'pu', pronunciation:'Dibaca seperti "pu"', stroke_count:3, examples:[{word:'プール',romaji:'puuru',meaning:'kolam renang'},{word:'プレゼント',romaji:'purezento',meaning:'hadiah'}] },
  'ペ': { type:'katakana', romaji:'pe', pronunciation:'Dibaca seperti "pe"', stroke_count:3, examples:[{word:'ペン',romaji:'pen',meaning:'pena'},{word:'ペット',romaji:'petto',meaning:'hewan peliharaan'}] },
  'ポ': { type:'katakana', romaji:'po', pronunciation:'Dibaca seperti "po"', stroke_count:5, examples:[{word:'ポスト',romaji:'posuto',meaning:'kotak pos'},{word:'ポケット',romaji:'poketto',meaning:'saku / kantong'}] },

  // ─── YŌON KOMBINASI (HIRAGANA) ───────────────────────────────
  'きゃ': { type:'hiragana', romaji:'kya', pronunciation:'Kombinasi き + ゃ, dibaca "kya"', stroke_count:0, examples:[{word:'きゃく',romaji:'kyaku',meaning:'tamu / pelanggan'},{word:'きゃんぷ',romaji:'kyanpu',meaning:'berkemah'}] },
  'きゅ': { type:'hiragana', romaji:'kyu', pronunciation:'Kombinasi き + ゅ, dibaca "kyu"', stroke_count:0, examples:[{word:'きゅうり',romaji:'kyuuri',meaning:'mentimun'},{word:'きゅうじつ',romaji:'kyuujitsu',meaning:'hari libur'}] },
  'きょ': { type:'hiragana', romaji:'kyo', pronunciation:'Kombinasi き + ょ, dibaca "kyo"', stroke_count:0, examples:[{word:'きょうしつ',romaji:'kyoushitsu',meaning:'ruang kelas'},{word:'きょうみ',romaji:'kyoumi',meaning:'minat / ketertarikan'}] },
  'しゃ': { type:'hiragana', romaji:'sha', pronunciation:'Kombinasi し + ゃ, dibaca "sha"', stroke_count:0, examples:[{word:'しゃしん',romaji:'shashin',meaning:'foto'},{word:'しゃかい',romaji:'shakai',meaning:'masyarakat / sosial'}] },
  'しゅ': { type:'hiragana', romaji:'shu', pronunciation:'Kombinasi し + ゅ, dibaca "shu"', stroke_count:0, examples:[{word:'しゅくだい',romaji:'shukudai',meaning:'pekerjaan rumah'},{word:'しゅみ',romaji:'shumi',meaning:'hobi'}] },
  'しょ': { type:'hiragana', romaji:'sho', pronunciation:'Kombinasi し + ょ, dibaca "sho"', stroke_count:0, examples:[{word:'しょうがっこう',romaji:'shougakkou',meaning:'sekolah dasar'},{word:'しょくじ',romaji:'shokuji',meaning:'makan / makanan'}] },
  'ちゃ': { type:'hiragana', romaji:'cha', pronunciation:'Kombinasi ち + ゃ, dibaca "cha"', stroke_count:0, examples:[{word:'おちゃ',romaji:'ocha',meaning:'teh'},{word:'ちゃいろ',romaji:'chairo',meaning:'warna coklat'}] },
  'ちゅ': { type:'hiragana', romaji:'chu', pronunciation:'Kombinasi ち + ゅ, dibaca "chu"', stroke_count:0, examples:[{word:'ちゅうごく',romaji:'chuugoku',meaning:'China / Tiongkok'},{word:'ちゅうしょく',romaji:'chuushoku',meaning:'makan siang'}] },
  'ちょ': { type:'hiragana', romaji:'cho', pronunciation:'Kombinasi ち + ょ, dibaca "cho"', stroke_count:0, examples:[{word:'ちょっと',romaji:'chotto',meaning:'sebentar / sedikit'},{word:'ちょうど',romaji:'choudo',meaning:'tepat / pas'}] },
  'にゃ': { type:'hiragana', romaji:'nya', pronunciation:'Kombinasi に + ゃ, dibaca "nya"', stroke_count:0, examples:[{word:'にゃんこ',romaji:'nyanko',meaning:'kucing (bahasa gaul)'},{word:'にゃー',romaji:'nyaa',meaning:'bunyi kucing'}] },
  'にゅ': { type:'hiragana', romaji:'nyu', pronunciation:'Kombinasi に + ゅ, dibaca "nyu"', stroke_count:0, examples:[{word:'にゅうがく',romaji:'nyuugaku',meaning:'masuk sekolah'},{word:'にゅうりょく',romaji:'nyuuryoku',meaning:'input / masukan'}] },
  'にょ': { type:'hiragana', romaji:'nyo', pronunciation:'Kombinasi に + ょ, dibaca "nyo"', stroke_count:0, examples:[{word:'にょうぼう',romaji:'nyoubou',meaning:'istri (bahasa klasik)'}] },
  'ひゃ': { type:'hiragana', romaji:'hya', pronunciation:'Kombinasi ひ + ゃ, dibaca "hya"', stroke_count:0, examples:[{word:'ひゃく',romaji:'hyaku',meaning:'seratus'},{word:'さんびゃく',romaji:'sanbyaku',meaning:'tiga ratus'}] },
  'ひゅ': { type:'hiragana', romaji:'hyu', pronunciation:'Kombinasi ひ + ゅ, dibaca "hyu"', stroke_count:0, examples:[{word:'ひゅーひゅー',romaji:'hyuuhyuu',meaning:'suara angin bertiup'}] },
  'ひょ': { type:'hiragana', romaji:'hyo', pronunciation:'Kombinasi ひ + ょ, dibaca "hyo"', stroke_count:0, examples:[{word:'ひょうじょう',romaji:'hyoujou',meaning:'ekspresi wajah'},{word:'ひょうが',romaji:'hyouga',meaning:'gletser'}] },
  'みゃ': { type:'hiragana', romaji:'mya', pronunciation:'Kombinasi み + ゃ, dibaca "mya"', stroke_count:0, examples:[{word:'みゃく',romaji:'myaku',meaning:'denyut nadi'}] },
  'みゅ': { type:'hiragana', romaji:'myu', pronunciation:'Kombinasi み + ゅ, dibaca "myu"', stroke_count:0, examples:[{word:'みゅーじかる',romaji:'myuujikaru',meaning:'musikal (serapan)'}] },
  'みょ': { type:'hiragana', romaji:'myo', pronunciation:'Kombinasi み + ょ, dibaca "myo"', stroke_count:0, examples:[{word:'みょうじ',romaji:'myouji',meaning:'nama keluarga / marga'}] },
  'りゃ': { type:'hiragana', romaji:'rya', pronunciation:'Kombinasi り + ゃ, dibaca "rya"', stroke_count:0, examples:[{word:'りゃくご',romaji:'ryakugo',meaning:'singkatan'}] },
  'りゅ': { type:'hiragana', romaji:'ryu', pronunciation:'Kombinasi り + ゅ, dibaca "ryu"', stroke_count:0, examples:[{word:'りゅうがく',romaji:'ryuugaku',meaning:'belajar di luar negeri'},{word:'りゅうこう',romaji:'ryuukou',meaning:'tren / mode'}] },
  'りょ': { type:'hiragana', romaji:'ryo', pronunciation:'Kombinasi り + ょ, dibaca "ryo"', stroke_count:0, examples:[{word:'りょこう',romaji:'ryokou',meaning:'perjalanan / wisata'},{word:'りょうり',romaji:'ryouri',meaning:'masakan / memasak'}] },
  'ぎゃ': { type:'hiragana', romaji:'gya', pronunciation:'Kombinasi ぎ + ゃ, dibaca "gya"', stroke_count:0, examples:[{word:'ぎゃく',romaji:'gyaku',meaning:'sebaliknya / terbalik'},{word:'ぎゃくてん',romaji:'gyakuten',meaning:'pembalikan / comeback'}] },
  'ぎゅ': { type:'hiragana', romaji:'gyu', pronunciation:'Kombinasi ぎ + ゅ, dibaca "gyu"', stroke_count:0, examples:[{word:'ぎゅうにゅう',romaji:'gyuunyuu',meaning:'susu sapi'},{word:'ぎゅうどん',romaji:'gyuudon',meaning:'nasi daging sapi'}] },
  'ぎょ': { type:'hiragana', romaji:'gyo', pronunciation:'Kombinasi ぎ + ょ, dibaca "gyo"', stroke_count:0, examples:[{word:'ぎょうざ',romaji:'gyouza',meaning:'pangsit goreng'},{word:'ぎょ',romaji:'gyo',meaning:'ikan (klasik)'}] },
  'じゃ': { type:'hiragana', romaji:'ja', pronunciation:'Kombinasi じ + ゃ, dibaca "ja"', stroke_count:0, examples:[{word:'じゃがいも',romaji:'jagaimo',meaning:'kentang'},{word:'じゃあね',romaji:'jaane',meaning:'sampai jumpa ya'}] },
  'じゅ': { type:'hiragana', romaji:'ju', pronunciation:'Kombinasi じ + ゅ, dibaca "ju"', stroke_count:0, examples:[{word:'じゅうしょ',romaji:'juusho',meaning:'alamat'},{word:'じゅぎょう',romaji:'jugyou',meaning:'pelajaran / kelas'}] },
  'じょ': { type:'hiragana', romaji:'jo', pronunciation:'Kombinasi じ + ょ, dibaca "jo"', stroke_count:0, examples:[{word:'じょせい',romaji:'josei',meaning:'perempuan / wanita'},{word:'じょうず',romaji:'jouzu',meaning:'pandai / terampil'}] },
  'びゃ': { type:'hiragana', romaji:'bya', pronunciation:'Kombinasi び + ゃ, dibaca "bya"', stroke_count:0, examples:[{word:'びゃくや',romaji:'byakuya',meaning:'malam hari yang terang (matahari tengah malam)'}] },
  'びゅ': { type:'hiragana', romaji:'byu', pronunciation:'Kombinasi び + ゅ, dibaca "byu"', stroke_count:0, examples:[{word:'びゅうびゅう',romaji:'byuubyuu',meaning:'suara angin kencang'}] },
  'びょ': { type:'hiragana', romaji:'byo', pronunciation:'Kombinasi び + ょ, dibaca "byo"', stroke_count:0, examples:[{word:'びょういん',romaji:'byouin',meaning:'rumah sakit'},{word:'びょうき',romaji:'byouki',meaning:'sakit / penyakit'}] },
  'ぴゃ': { type:'hiragana', romaji:'pya', pronunciation:'Kombinasi ぴ + ゃ, dibaca "pya"', stroke_count:0, examples:[{word:'ぴゃっぴゃ',romaji:'pyappya',meaning:'onomatope / bunyi ceria'}] },
  'ぴゅ': { type:'hiragana', romaji:'pyu', pronunciation:'Kombinasi ぴ + ゅ, dibaca "pyu"', stroke_count:0, examples:[{word:'ぴゅーぴゅー',romaji:'pyuupyuu',meaning:'suara angin bertiup kencang'}] },
  'ぴょ': { type:'hiragana', romaji:'pyo', pronunciation:'Kombinasi ぴ + ょ, dibaca "pyo"', stroke_count:0, examples:[{word:'ぴょん',romaji:'pyon',meaning:'bunyi lompatan (onomatope)'},{word:'ぴょんぴょん',romaji:'pyonpyon',meaning:'melompat-lompat'}] },

  // ─── YŌON KOMBINASI (KATAKANA) ───────────────────────────────
  'キャ': { type:'katakana', romaji:'kya', pronunciation:'Kombinasi キ + ャ, dibaca "kya"', stroke_count:0, examples:[{word:'キャンプ',romaji:'kyanpu',meaning:'berkemah'},{word:'キャラクター',romaji:'kyarakutaa',meaning:'karakter'}] },
  'キュ': { type:'katakana', romaji:'kyu', pronunciation:'Kombinasi キ + ュ, dibaca "kyu"', stroke_count:0, examples:[{word:'キュウリ',romaji:'kyuuri',meaning:'mentimun'},{word:'キュート',romaji:'kyuuto',meaning:'imut / cute'}] },
  'キョ': { type:'katakana', romaji:'kyo', pronunciation:'Kombinasi キ + ョ, dibaca "kyo"', stroke_count:0, examples:[{word:'キョロキョロ',romaji:'kyorokyoro',meaning:'celingak-celinguk'}] },
  'シャ': { type:'katakana', romaji:'sha', pronunciation:'Kombinasi シ + ャ, dibaca "sha"', stroke_count:0, examples:[{word:'シャワー',romaji:'shawaa',meaning:'shower / mandi'},{word:'シャツ',romaji:'shatsu',meaning:'kemeja / kaos'}] },
  'シュ': { type:'katakana', romaji:'shu', pronunciation:'Kombinasi シ + ュ, dibaca "shu"', stroke_count:0, examples:[{word:'シュークリーム',romaji:'shuukuriimu',meaning:'sus krim'},{word:'シュート',romaji:'shuuto',meaning:'tembakan / shoot'}] },
  'ショ': { type:'katakana', romaji:'sho', pronunciation:'Kombinasi シ + ョ, dibaca "sho"', stroke_count:0, examples:[{word:'ショッピング',romaji:'shoppingu',meaning:'belanja'},{word:'ショー',romaji:'shoo',meaning:'pertunjukan / show'}] },
  'チャ': { type:'katakana', romaji:'cha', pronunciation:'Kombinasi チ + ャ, dibaca "cha"', stroke_count:0, examples:[{word:'チャンス',romaji:'chansu',meaning:'kesempatan / chance'},{word:'チャット',romaji:'chatto',meaning:'obrolan / chat'}] },
  'チュ': { type:'katakana', romaji:'chu', pronunciation:'Kombinasi チ + ュ, dibaca "chu"', stroke_count:0, examples:[{word:'チューリップ',romaji:'chuurippu',meaning:'bunga tulip'},{word:'チューニング',romaji:'chuuningu',meaning:'penyetelan / tuning'}] },
  'チョ': { type:'katakana', romaji:'cho', pronunciation:'Kombinasi チ + ョ, dibaca "cho"', stroke_count:0, examples:[{word:'チョコレート',romaji:'chokoreeto',meaning:'coklat'},{word:'チョイス',romaji:'choisu',meaning:'pilihan / choice'}] },
  'ニャ': { type:'katakana', romaji:'nya', pronunciation:'Kombinasi ニ + ャ, dibaca "nya"', stroke_count:0, examples:[{word:'ニャンコ',romaji:'nyanko',meaning:'kucing (bahasa santai)'}] },
  'ニュ': { type:'katakana', romaji:'nyu', pronunciation:'Kombinasi ニ + ュ, dibaca "nyu"', stroke_count:0, examples:[{word:'ニュース',romaji:'nyuusu',meaning:'berita'},{word:'ニューヨーク',romaji:'nyuuyooku',meaning:'New York'}] },
  'ニョ': { type:'katakana', romaji:'nyo', pronunciation:'Kombinasi ニ + ョ, dibaca "nyo"', stroke_count:0, examples:[{word:'ニョッキ',romaji:'nyokki',meaning:'gnocchi (pasta Italia)'}] },
  'ヒャ': { type:'katakana', romaji:'hya', pronunciation:'Kombinasi ヒ + ャ, dibaca "hya"', stroke_count:0, examples:[{word:'ヒャク',romaji:'hyaku',meaning:'seratus'}] },
  'ヒュ': { type:'katakana', romaji:'hyu', pronunciation:'Kombinasi ヒ + ュ, dibaca "hyu"', stroke_count:0, examples:[{word:'ヒューマン',romaji:'hyuuman',meaning:'manusia / human'}] },
  'ヒョ': { type:'katakana', romaji:'hyo', pronunciation:'Kombinasi ヒ + ョ, dibaca "hyo"', stroke_count:0, examples:[{word:'ヒョウ',romaji:'hyou',meaning:'macan tutul / leopard'}] },
  'ミャ': { type:'katakana', romaji:'mya', pronunciation:'Kombinasi ミ + ャ, dibaca "mya"', stroke_count:0, examples:[{word:'ミャンマー',romaji:'myanmaa',meaning:'Myanmar'}] },
  'ミュ': { type:'katakana', romaji:'myu', pronunciation:'Kombinasi ミ + ュ, dibaca "myu"', stroke_count:0, examples:[{word:'ミュージック',romaji:'myuujikku',meaning:'musik'},{word:'ミュージアム',romaji:'myuujiamu',meaning:'museum'}] },
  'ミョ': { type:'katakana', romaji:'myo', pronunciation:'Kombinasi ミ + ョ, dibaca "myo"', stroke_count:0, examples:[{word:'ミョウガ',romaji:'myouga',meaning:'jahe Jepang'}] },
  'リャ': { type:'katakana', romaji:'rya', pronunciation:'Kombinasi リ + ャ, dibaca "rya"', stroke_count:0, examples:[{word:'リャクゴ',romaji:'ryakugo',meaning:'singkatan'}] },
  'リュ': { type:'katakana', romaji:'ryu', pronunciation:'Kombinasi リ + ュ, dibaca "ryu"', stroke_count:0, examples:[{word:'リュック',romaji:'ryukku',meaning:'ransel / backpack'},{word:'リュクサック',romaji:'ryukusakku',meaning:'ransel'}] },
  'リョ': { type:'katakana', romaji:'ryo', pronunciation:'Kombinasi リ + ョ, dibaca "ryo"', stroke_count:0, examples:[{word:'リョコウ',romaji:'ryokou',meaning:'perjalanan / wisata'}] },
  'ギャ': { type:'katakana', romaji:'gya', pronunciation:'Kombinasi ギ + ャ, dibaca "gya"', stroke_count:0, examples:[{word:'ギャップ',romaji:'gyappu',meaning:'kesenjangan / gap'},{word:'ギャラリー',romaji:'gyararii',meaning:'galeri'}] },
  'ギュ': { type:'katakana', romaji:'gyu', pronunciation:'Kombinasi ギ + ュ, dibaca "gyu"', stroke_count:0, examples:[{word:'ギュウニュウ',romaji:'gyuunyuu',meaning:'susu sapi'}] },
  'ギョ': { type:'katakana', romaji:'gyo', pronunciation:'Kombinasi ギ + ョ, dibaca "gyo"', stroke_count:0, examples:[{word:'ギョウザ',romaji:'gyouza',meaning:'pangsit goreng'}] },
  'ジャ': { type:'katakana', romaji:'ja', pronunciation:'Kombinasi ジ + ャ, dibaca "ja"', stroke_count:0, examples:[{word:'ジャケット',romaji:'jaketto',meaning:'jaket'},{word:'ジャングル',romaji:'janguru',meaning:'hutan lebat / jungle'}] },
  'ジュ': { type:'katakana', romaji:'ju', pronunciation:'Kombinasi ジ + ュ, dibaca "ju"', stroke_count:0, examples:[{word:'ジュース',romaji:'juusu',meaning:'jus'},{word:'ジュエリー',romaji:'juerī',meaning:'perhiasan / jewelry'}] },
  'ジョ': { type:'katakana', romaji:'jo', pronunciation:'Kombinasi ジ + ョ, dibaca "jo"', stroke_count:0, examples:[{word:'ジョギング',romaji:'jogingu',meaning:'joging'},{word:'ジョーク',romaji:'jooku',meaning:'lelucon / joke'}] },
  'ビャ': { type:'katakana', romaji:'bya', pronunciation:'Kombinasi ビ + ャ, dibaca "bya"', stroke_count:0, examples:[{word:'ビャクヤ',romaji:'byakuya',meaning:'putih murni / nama'}] },
  'ビュ': { type:'katakana', romaji:'byu', pronunciation:'Kombinasi ビ + ュ, dibaca "byu"', stroke_count:0, examples:[{word:'ビュッフェ',romaji:'byuffe',meaning:'prasmanan / buffet'}] },
  'ビョ': { type:'katakana', romaji:'byo', pronunciation:'Kombinasi ビ + ョ, dibaca "byo"', stroke_count:0, examples:[{word:'ビョウイン',romaji:'byouin',meaning:'rumah sakit'}] },
  'ピャ': { type:'katakana', romaji:'pya', pronunciation:'Kombinasi ピ + ャ, dibaca "pya"', stroke_count:0, examples:[{word:'ピャノ',romaji:'pya',meaning:'jarang digunakan dalam kata serapan'}] },
  'ピュ': { type:'katakana', romaji:'pyu', pronunciation:'Kombinasi ピ + ュ, dibaca "pyu"', stroke_count:0, examples:[{word:'ピューマ',romaji:'pyuuma',meaning:'puma / singa gunung'}] },
  'ピョ': { type:'katakana', romaji:'pyo', pronunciation:'Kombinasi ピ + ョ, dibaca "pyo"', stroke_count:0, examples:[{word:'ピョンピョン',romaji:'pyonpyon',meaning:'melompat-lompat'}] },

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

  // ── N4 Kanji ──────────────────────────────────────────────────
  // 時間 (Time)
  '朝': { type:'kanji', romaji:'chou / asa', meaning:'pagi', onyomi:'チョウ (chou)', kunyomi:'あさ (asa)', stroke_count:12, radical:'月（bulan）', jlpt:'N4', examples:[{word:'朝ご飯',romaji:'asagohan',meaning:'sarapan'},{word:'今朝',romaji:'kesa',meaning:'pagi ini'},{word:'毎朝',romaji:'maiasa',meaning:'setiap pagi'}] },
  '昼': { type:'kanji', romaji:'chuu / hiru', meaning:'siang / tengah hari', onyomi:'チュウ (chuu)', kunyomi:'ひる (hiru)', stroke_count:9, radical:'日（matahari）', jlpt:'N4', examples:[{word:'昼ご飯',romaji:'hirugohan',meaning:'makan siang'},{word:'昼間',romaji:'hiruma',meaning:'siang hari'},{word:'昼寝',romaji:'hirune',meaning:'tidur siang'}] },
  '晩': { type:'kanji', romaji:'ban', meaning:'malam / petang', onyomi:'バン (ban)', kunyomi:'-', stroke_count:12, radical:'日（matahari）', jlpt:'N4', examples:[{word:'晩ご飯',romaji:'bangohan',meaning:'makan malam'},{word:'今晩',romaji:'konban',meaning:'malam ini'},{word:'毎晩',romaji:'maiban',meaning:'setiap malam'}] },
  '夜': { type:'kanji', romaji:'ya / yoru', meaning:'malam', onyomi:'ヤ (ya)', kunyomi:'よる (yoru)', stroke_count:8, radical:'夕（sore）', jlpt:'N4', examples:[{word:'夜中',romaji:'yonaka',meaning:'tengah malam'},{word:'夜景',romaji:'yakei',meaning:'pemandangan malam'},{word:'今夜',romaji:'konya',meaning:'malam ini'}] },
  '前': { type:'kanji', romaji:'zen / mae', meaning:'depan / sebelum', onyomi:'ゼン (zen)', kunyomi:'まえ (mae)', stroke_count:9, radical:'刀（pisau）', jlpt:'N4', examples:[{word:'前に',romaji:'mae ni',meaning:'sebelum'},{word:'名前',romaji:'namae',meaning:'nama'},{word:'午前',romaji:'gozen',meaning:'pagi / AM'}] },
  '後': { type:'kanji', romaji:'go / kou / ushiro', meaning:'belakang / setelah', onyomi:'ゴ (go), コウ (kou)', kunyomi:'うしろ (ushiro), あと (ato)', stroke_count:9, radical:'彳（langkah）', jlpt:'N4', examples:[{word:'後で',romaji:'ato de',meaning:'nanti / sesudah itu'},{word:'午後',romaji:'gogo',meaning:'sore / PM'},{word:'最後',romaji:'saigo',meaning:'terakhir'}] },
  '正': { type:'kanji', romaji:'sei / shou / tadashii', meaning:'benar / lurus', onyomi:'セイ (sei), ショウ (shou)', kunyomi:'ただ-しい (tada-shii)', stroke_count:5, radical:'止（berhenti）', jlpt:'N4', examples:[{word:'正しい',romaji:'tadashii',meaning:'benar'},{word:'正月',romaji:'shougatsu',meaning:'Tahun Baru'},{word:'正直',romaji:'shoujiki',meaning:'jujur'}] },
  '午': { type:'kanji', romaji:'go', meaning:'tengah hari / kuda', onyomi:'ゴ (go)', kunyomi:'-', stroke_count:4, radical:'十（sepuluh）', jlpt:'N4', examples:[{word:'午前',romaji:'gozen',meaning:'pagi / AM'},{word:'午後',romaji:'gogo',meaning:'sore / PM'},{word:'正午',romaji:'shougo',meaning:'tengah hari'}] },
  '毎': { type:'kanji', romaji:'mai', meaning:'setiap', onyomi:'マイ (mai)', kunyomi:'-', stroke_count:6, radical:'毋（setiap）', jlpt:'N4', examples:[{word:'毎日',romaji:'mainichi',meaning:'setiap hari'},{word:'毎朝',romaji:'maiasa',meaning:'setiap pagi'},{word:'毎週',romaji:'maishuu',meaning:'setiap minggu'}] },
  '週': { type:'kanji', romaji:'shuu', meaning:'minggu', onyomi:'シュウ (shuu)', kunyomi:'-', stroke_count:11, radical:'辶（berjalan）', jlpt:'N4', examples:[{word:'来週',romaji:'raishuu',meaning:'minggu depan'},{word:'今週',romaji:'konshuu',meaning:'minggu ini'},{word:'毎週',romaji:'maishuu',meaning:'setiap minggu'}] },
  '去': { type:'kanji', romaji:'kyo / ko / saru', meaning:'pergi / berlalu', onyomi:'キョ (kyo), コ (ko)', kunyomi:'さ-る (sa-ru)', stroke_count:5, radical:'厶（pribadi）', jlpt:'N4', examples:[{word:'去年',romaji:'kyonen',meaning:'tahun lalu'},{word:'過去',romaji:'kako',meaning:'masa lalu'},{word:'去る',romaji:'saru',meaning:'pergi / berlalu'}] },

  // 人 (People)
  '家': { type:'kanji', romaji:'ka / ie', meaning:'rumah / keluarga', onyomi:'カ (ka)', kunyomi:'いえ (ie)', stroke_count:10, radical:'宀（atap）', jlpt:'N4', examples:[{word:'家族',romaji:'kazoku',meaning:'keluarga'},{word:'家庭',romaji:'katei',meaning:'rumah tangga'},{word:'国家',romaji:'kokka',meaning:'negara'}] },
  '族': { type:'kanji', romaji:'zoku', meaning:'suku / kelompok keluarga', onyomi:'ゾク (zoku)', kunyomi:'-', stroke_count:11, radical:'方（arah）', jlpt:'N4', examples:[{word:'家族',romaji:'kazoku',meaning:'keluarga'},{word:'民族',romaji:'minzoku',meaning:'etnis / suku bangsa'},{word:'水族館',romaji:'suizokukan',meaning:'akuarium'}] },
  '兄': { type:'kanji', romaji:'kei / kyou / ani', meaning:'kakak laki-laki', onyomi:'ケイ (kei), キョウ (kyou)', kunyomi:'あに (ani)', stroke_count:5, radical:'儿（manusia）', jlpt:'N4', examples:[{word:'兄弟',romaji:'kyoudai',meaning:'saudara laki-laki'},{word:'お兄さん',romaji:'oniisan',meaning:'kakak laki-laki (sopan)'},{word:'実兄',romaji:'jikkei',meaning:'kakak kandung'}] },
  '弟': { type:'kanji', romaji:'tei / dai / otouto', meaning:'adik laki-laki', onyomi:'テイ (tei), ダイ (dai)', kunyomi:'おとうと (otouto)', stroke_count:7, radical:'弓（busur）', jlpt:'N4', examples:[{word:'兄弟',romaji:'kyoudai',meaning:'saudara laki-laki'},{word:'弟子',romaji:'deshi',meaning:'murid / penerus'},{word:'お弟子',romaji:'odeshi',meaning:'murid (sopan)'}] },
  '姉': { type:'kanji', romaji:'shi / ane', meaning:'kakak perempuan', onyomi:'シ (shi)', kunyomi:'あね (ane)', stroke_count:8, radical:'女（perempuan）', jlpt:'N4', examples:[{word:'姉妹',romaji:'shimai',meaning:'saudara perempuan'},{word:'お姉さん',romaji:'oneesan',meaning:'kakak perempuan (sopan)'},{word:'義姉',romaji:'gishi',meaning:'kakak ipar perempuan'}] },
  '妹': { type:'kanji', romaji:'mai / imouto', meaning:'adik perempuan', onyomi:'マイ (mai)', kunyomi:'いもうto (imouto)', stroke_count:8, radical:'女（perempuan）', jlpt:'N4', examples:[{word:'姉妹',romaji:'shimai',meaning:'saudara perempuan'},{word:'義妹',romaji:'gimai',meaning:'adik ipar perempuan'},{word:'妹さん',romaji:'imoutosan',meaning:'adik perempuan (sopan)'}] },
  '夫': { type:'kanji', romaji:'fu / otto', meaning:'suami / pria dewasa', onyomi:'フ (fu), フウ (fuu)', kunyomi:'おっと (otto)', stroke_count:4, radical:'大（besar）', jlpt:'N4', examples:[{word:'夫婦',romaji:'fuufu',meaning:'pasangan suami istri'},{word:'夫人',romaji:'fujin',meaning:'nyonya / istri'},{word:'主夫',romaji:'shufu',meaning:'suami rumah tangga'}] },
  '妻': { type:'kanji', romaji:'sai / tsuma', meaning:'istri', onyomi:'サイ (sai)', kunyomi:'つま (tsuma)', stroke_count:8, radical:'女（perempuan）', jlpt:'N4', examples:[{word:'夫妻',romaji:'fusai',meaning:'pasangan suami istri'},{word:'妻子',romaji:'saishi',meaning:'istri dan anak'},{word:'愛妻',romaji:'aisai',meaning:'istri tercinta'}] },
  '主': { type:'kanji', romaji:'shu / omo / nushi', meaning:'utama / tuan / pemimpin', onyomi:'シュ (shu)', kunyomi:'おも (omo), ぬし (nushi), あるじ (aruji)', stroke_count:5, radical:'丶（titik）', jlpt:'N4', examples:[{word:'主人',romaji:'shujin',meaning:'suami / tuan rumah'},{word:'主語',romaji:'shugo',meaning:'subjek (gramatikal)'},{word:'民主主義',romaji:'minshu shugi',meaning:'demokrasi'}] },
  '奥': { type:'kanji', romaji:'ou / oku', meaning:'bagian dalam / istri', onyomi:'オウ (ou)', kunyomi:'おく (oku)', stroke_count:12, radical:'大（besar）', jlpt:'N4', examples:[{word:'奥さん',romaji:'okusan',meaning:'istri (orang lain)'},{word:'奥の手',romaji:'oku no te',meaning:'jurus pamungkas'},{word:'奥地',romaji:'okuchi',meaning:'pedalaman'}] },
  '私': { type:'kanji', romaji:'shi / watashi', meaning:'saya / pribadi', onyomi:'シ (shi)', kunyomi:'わたし (watashi), わたくし (watakushi)', stroke_count:7, radical:'禾（padi）', jlpt:'N4', examples:[{word:'私立',romaji:'shiritsu',meaning:'swasta'},{word:'私鉄',romaji:'shitetsu',meaning:'kereta swasta'},{word:'私語',romaji:'shigo',meaning:'bisik-bisik'}] },
  '王': { type:'kanji', romaji:'ou', meaning:'raja', onyomi:'オウ (ou)', kunyomi:'-', stroke_count:4, radical:'王（raja）', jlpt:'N4', examples:[{word:'王様',romaji:'ousama',meaning:'raja (gelar)'},{word:'王国',romaji:'oukoku',meaning:'kerajaan'},{word:'女王',romaji:'joo',meaning:'ratu'}] },
  '様': { type:'kanji', romaji:'you / sama', meaning:'cara / gaya / tuan/nyonya', onyomi:'ヨウ (you)', kunyomi:'さま (sama)', stroke_count:14, radical:'木（pohon）', jlpt:'N4', examples:[{word:'同様',romaji:'douyou',meaning:'sama / serupa'},{word:'様子',romaji:'yousu',meaning:'keadaan / situasi'},{word:'お客様',romaji:'okyakusama',meaning:'tamu terhormat'}] },
  '才': { type:'kanji', romaji:'sai', meaning:'umur / bakat', onyomi:'サイ (sai)', kunyomi:'-', stroke_count:3, radical:'乃（dari）', jlpt:'N4', examples:[{word:'十才',romaji:'jussai',meaning:'10 tahun'},{word:'天才',romaji:'tensai',meaning:'jenius'},{word:'才能',romaji:'sainou',meaning:'bakat'}] },

  // 色・形 (Color & Shape)
  '赤': { type:'kanji', romaji:'seki / aka', meaning:'merah', onyomi:'セキ (seki)', kunyomi:'あか (aka)', stroke_count:7, radical:'赤（merah）', jlpt:'N4', examples:[{word:'赤い',romaji:'akai',meaning:'merah'},{word:'赤ちゃん',romaji:'akachan',meaning:'bayi'},{word:'赤信号',romaji:'akashingou',meaning:'lampu merah'}] },
  '青': { type:'kanji', romaji:'sei / ao', meaning:'biru / hijau', onyomi:'セイ (sei)', kunyomi:'あお (ao)', stroke_count:8, radical:'青（biru）', jlpt:'N4', examples:[{word:'青い',romaji:'aoi',meaning:'biru'},{word:'青春',romaji:'seishun',meaning:'masa muda'},{word:'青信号',romaji:'aoshingou',meaning:'lampu hijau'}] },
  '白': { type:'kanji', romaji:'haku / shiro', meaning:'putih', onyomi:'ハク (haku)', kunyomi:'しろ (shiro)', stroke_count:5, radical:'白（putih）', jlpt:'N4', examples:[{word:'白い',romaji:'shiroi',meaning:'putih'},{word:'白黒',romaji:'shirokuro',meaning:'hitam putih'},{word:'告白',romaji:'kokuhaku',meaning:'pengakuan / confess'}] },
  '黒': { type:'kanji', romaji:'koku / kuro', meaning:'hitam', onyomi:'コク (koku)', kunyomi:'くろ (kuro)', stroke_count:11, radical:'黑（hitam）', jlpt:'N4', examples:[{word:'黒い',romaji:'kuroi',meaning:'hitam'},{word:'黒板',romaji:'kokuban',meaning:'papan tulis'},{word:'暗黒',romaji:'ankoku',meaning:'kegelapan total'}] },
  '色': { type:'kanji', romaji:'shoku / iro', meaning:'warna', onyomi:'ショク (shoku)', kunyomi:'いろ (iro)', stroke_count:6, radical:'色（warna）', jlpt:'N4', examples:[{word:'色々',romaji:'iroiro',meaning:'bermacam-macam'},{word:'同色',romaji:'doushoku',meaning:'warna sama'},{word:'特色',romaji:'tokushoku',meaning:'ciri khas'}] },
  '銀': { type:'kanji', romaji:'gin', meaning:'perak', onyomi:'ギン (gin)', kunyomi:'-', stroke_count:14, radical:'金（emas）', jlpt:'N4', examples:[{word:'銀行',romaji:'ginkou',meaning:'bank'},{word:'銀色',romaji:'gin iro',meaning:'warna perak'},{word:'銀メダル',romaji:'gin medaru',meaning:'medali perak'}] },
  '黄': { type:'kanji', romaji:'ou / ki', meaning:'kuning', onyomi:'オウ (ou)', kunyomi:'き (ki)', stroke_count:11, radical:'黃（kuning）', jlpt:'N4', examples:[{word:'黄色',romaji:'kiiro',meaning:'kuning'},{word:'黄金',romaji:'ougon',meaning:'emas'},{word:'黄身',romaji:'kimi',meaning:'kuning telur'}] },
  '緑': { type:'kanji', romaji:'ryoku / midori', meaning:'hijau', onyomi:'リョク (ryoku)', kunyomi:'みどり (midori)', stroke_count:14, radical:'糸（benang）', jlpt:'N4', examples:[{word:'緑色',romaji:'midori iro',meaning:'hijau'},{word:'新緑',romaji:'shinryoku',meaning:'daun muda yang hijau'},{word:'緑茶',romaji:'ryokucha',meaning:'teh hijau'}] },
  '丸': { type:'kanji', romaji:'gan / maru', meaning:'bulat / lingkaran', onyomi:'ガン (gan)', kunyomi:'まる (maru)', stroke_count:3, radical:'、（titik）', jlpt:'N4', examples:[{word:'丸い',romaji:'marui',meaning:'bulat'},{word:'丸太',romaji:'maruta',meaning:'kayu gelondongan'},{word:'丸暗記',romaji:'maruanki',meaning:'hafalan total'}] },
  '玉': { type:'kanji', romaji:'gyoku / tama', meaning:'bola / permata', onyomi:'ギョク (gyoku)', kunyomi:'たま (tama)', stroke_count:5, radical:'玉（permata）', jlpt:'N4', examples:[{word:'お年玉',romaji:'otoshidama',meaning:'uang saku Tahun Baru'},{word:'目玉',romaji:'medama',meaning:'bola mata'},{word:'水玉',romaji:'mizutama',meaning:'motif titik-titik'}] },

  // 季節・天気 (Season & Weather)
  '春': { type:'kanji', romaji:'shun / haru', meaning:'musim semi', onyomi:'シュン (shun)', kunyomi:'はる (haru)', stroke_count:9, radical:'日（matahari）', jlpt:'N4', examples:[{word:'春休み',romaji:'haruyasumi',meaning:'liburan musim semi'},{word:'青春',romaji:'seishun',meaning:'masa muda'},{word:'春分',romaji:'shunbun',meaning:'ekuinoks musim semi'}] },
  '夏': { type:'kanji', romaji:'ka / ge / natsu', meaning:'musim panas', onyomi:'カ (ka), ゲ (ge)', kunyomi:'なつ (natsu)', stroke_count:10, radical:'大（besar）', jlpt:'N4', examples:[{word:'夏休み',romaji:'natsuyasumi',meaning:'liburan musim panas'},{word:'夏祭り',romaji:'natsumatsuri',meaning:'festival musim panas'},{word:'真夏',romaji:'manatsu',meaning:'pertengahan musim panas'}] },
  '秋': { type:'kanji', romaji:'shuu / aki', meaning:'musim gugur', onyomi:'シュウ (shuu)', kunyomi:'あki (aki)', stroke_count:9, radical:'禾（padi）', jlpt:'N4', examples:[{word:'秋分',romaji:'shuubun',meaning:'ekuinoks musim gugur'},{word:'秋田',romaji:'akita',meaning:'Akita'},{word:'食欲の秋',romaji:'shokuyoku no aki',meaning:'musim gugur nafsu makan'}] },
  '冬': { type:'kanji', romaji:'tou / fuyu', meaning:'musim dingin', onyomi:'トウ (tou)', kunyomi:'ふゆ (fuyu)', stroke_count:5, radical:'冫（es）', jlpt:'N4', examples:[{word:'冬休み',romaji:'fuyuyasumi',meaning:'liburan musim dingin'},{word:'冬眠',romaji:'toumin',meaning:'hibernasi'},{word:'真冬',romaji:'mafuyu',meaning:'pertengahan musim dingin'}] },
  '空': { type:'kanji', romaji:'kuu / sora', meaning:'langit / kosong', onyomi:'クウ (kuu)', kunyomi:'そら (sora), あ-く (a-ku)', stroke_count:8, radical:'穴（lubang）', jlpt:'N4', examples:[{word:'空港',romaji:'kuukou',meaning:'bandara'},{word:'青空',romaji:'aozora',meaning:'langit biru'},{word:'空気',romaji:'kuuki',meaning:'udara'}] },
  '天': { type:'kanji', romaji:'ten', meaning:'langit / surga', onyomi:'テン (ten)', kunyomi:'あま (ama)', stroke_count:4, radical:'大（besar）', jlpt:'N4', examples:[{word:'天気',romaji:'tenki',meaning:'cuaca'},{word:'天国',romaji:'tengoku',meaning:'surga'},{word:'天才',romaji:'tensai',meaning:'jenius'}] },
  '風': { type:'kanji', romaji:'fuu / kaze', meaning:'angin / gaya', onyomi:'フウ (fuu)', kunyomi:'かぜ (kaze)', stroke_count:9, radical:'風（angin）', jlpt:'N4', examples:[{word:'台風',romaji:'taifuu',meaning:'topan'},{word:'風景',romaji:'fuukei',meaning:'pemandangan'},{word:'冷風',romaji:'reifuu',meaning:'angin dingin'}] },
  '台': { type:'kanji', romaji:'tai / dai', meaning:'panggung / alat bantu hitung', onyomi:'タイ (tai), ダイ (dai)', kunyomi:'-', stroke_count:5, radical:'口（mulut）', jlpt:'N4', examples:[{word:'台風',romaji:'taifuu',meaning:'topan'},{word:'舞台',romaji:'butai',meaning:'panggung'},{word:'台所',romaji:'daidokoro',meaning:'dapur'}] },
  '雲': { type:'kanji', romaji:'un / kumo', meaning:'awan', onyomi:'ウン (un)', kunyomi:'くも (kumo)', stroke_count:12, radical:'雨（hujan）', jlpt:'N4', examples:[{word:'雲海',romaji:'unkai',meaning:'lautan awan'},{word:'黒雲',romaji:'kurokumo',meaning:'awan gelap'},{word:'入道雲',romaji:'nyuudougumo',meaning:'awan kumulus'}] },
  '雪': { type:'kanji', romaji:'setsu / yuki', meaning:'salju', onyomi:'セツ (setsu)', kunyomi:'ゆき (yuki)', stroke_count:11, radical:'雨（hujan）', jlpt:'N4', examples:[{word:'雪国',romaji:'yukiguni',meaning:'negeri salju'},{word:'大雪',romaji:'ooyuki',meaning:'salju lebat'},{word:'初雪',romaji:'hatsuyuki',meaning:'salju pertama'}] },
  '晴': { type:'kanji', romaji:'sei / hare', meaning:'cerah', onyomi:'セイ (sei)', kunyomi:'は-れる (ha-reru)', stroke_count:12, radical:'日（matahari）', jlpt:'N4', examples:[{word:'晴れ',romaji:'hare',meaning:'cerah'},{word:'晴天',romaji:'seiten',meaning:'langit cerah'},{word:'快晴',romaji:'kaisei',meaning:'sangat cerah'}] },
  '星': { type:'kanji', romaji:'sei / hoshi', meaning:'bintang', onyomi:'セイ (sei)', kunyomi:'ほし (hoshi)', stroke_count:9, radical:'日（matahari）', jlpt:'N4', examples:[{word:'星空',romaji:'hoshizora',meaning:'langit berbintang'},{word:'流れ星',romaji:'nagareboshi',meaning:'bintang jatuh'},{word:'土星',romaji:'dosei',meaning:'Saturnus'}] },
  '光': { type:'kanji', romaji:'kou / hikari', meaning:'cahaya / terang', onyomi:'コウ (kou)', kunyomi:'ひかり (hikari), ひか-る (hika-ru)', stroke_count:6, radical:'官（pejabat）', jlpt:'N4', examples:[{word:'観光',romaji:'kankou',meaning:'pariwisata'},{word:'光線',romaji:'kousen',meaning:'sinar'},{word:'日光',romaji:'nikkou',meaning:'cahaya matahari'}] },

  // 自然 (Nature)
  '地': { type:'kanji', romaji:'chi / ji', meaning:'tanah / tempat', onyomi:'チ (chi), ジ (ji)', kunyomi:'-', stroke_count:6, radical:'土（tanah）', jlpt:'N4', examples:[{word:'地下',romaji:'chika',meaning:'bawah tanah'},{word:'地図',romaji:'chizu',meaning:'peta'},{word:'土地',romaji:'tochi',meaning:'tanah / lahan'}] },
  '谷': { type:'kanji', romaji:'koku / tani', meaning:'lembah', onyomi:'コク (koku)', kunyomi:'たに (tani)', stroke_count:7, radical:'谷（lembah）', jlpt:'N4', examples:[{word:'渋谷',romaji:'shibuya',meaning:'Shibuya'},{word:'谷間',romaji:'tanima',meaning:'celah lembah'},{word:'谷底',romaji:'tanisoko',meaning:'dasar lembah'}] },
  '自': { type:'kanji', romaji:'ji / shi', meaning:'diri sendiri', onyomi:'ジ (ji), シ (shi)', kunyomi:'mizukara', stroke_count:6, radical:'自（diri）', jlpt:'N4', examples:[{word:'自分',romaji:'jibun',meaning:'diri sendiri'},{word:'自転車',romaji:'jitensha',meaning:'sepeda'},{word:'自由',romaji:'jiyuu',meaning:'kebebasan'}] },
  '然': { type:'kanji', romaji:'zen / nen', meaning:'alam', onyomi:'ゼン (zen), ネン (nen)', kunyomi:'-', stroke_count:12, radical:'灬（api）', jlpt:'N4', examples:[{word:'自然',romaji:'shizen',meaning:'alam'},{word:'当然',romaji:'touzen',meaning:'tentu saja'},{word:'突然',romaji:'totsuzen',meaning:'tiba-tiba'}] },
  '草': { type:'kanji', romaji:'sou / kusa', meaning:'rumput', onyomi:'ソウ (sou)', kunyomi:'くさ (kusa)', stroke_count:9, radical:'艹（tanaman）', jlpt:'N4', examples:[{word:'雑草',romaji:'zassou',meaning:'gulma'},{word:'草原',romaji:'sougen',meaning:'padang rumput'},{word:'草花',romaji:'kusabana',meaning:'bunga liar'}] },
  '原': { type:'kanji', romaji:'gen / hara', meaning:'padang / sumber', onyomi:'ゲン (gen)', kunyomi:'はら (hara)', stroke_count:10, radical:'厂（tebing）', jlpt:'N4', examples:[{word:'原因',romaji:'genin',meaning:'penyebab'},{word:'原料',romaji:'genryou',meaning:'bahan baku'},{word:'草原',romaji:'sougen',meaning:'padang rumput'}] },
  '海': { type:'kanji', romaji:'kai / umi', meaning:'laut / samudra', onyomi:'カイ (kai)', kunyomi:'うみ (umi)', stroke_count:9, radical:'水（air）', jlpt:'N4', examples:[{word:'海外',romaji:'kaigai',meaning:'luar negeri'},{word:'海岸',romaji:'kaigan',meaning:'pantai'},{word:'日本海',romaji:'nihonkai',meaning:'Laut Jepang'}] },
  '湖': { type:'kanji', romaji:'ko / mizuumi', meaning:'danau', onyomi:'コ (ko)', kunyomi:'みずうみ (mizuumi)', stroke_count:12, radical:'水（air）', jlpt:'N4', examples:[{word:'琵琶湖',romaji:'biwako',meaning:'Danau Biwa'},{word:'湖畔',romaji:'kohan',meaning:'tepi danau'},{word:'湖水',romaji:'kosui',meaning:'air danau'}] },
  '池': { type:'kanji', romaji:'chi / ike', meaning:'kolam / telaga', onyomi:'チ (chi)', kunyomi:'いけ (ike)', stroke_count:6, radical:'水（air）', jlpt:'N4', examples:[{word:'電池',romaji:'denchi',meaning:'baterai'},{word:'池袋',romaji:'ikebukuro',meaning:'Ikebukuro'},{word:'溜め池',romaji:'tameike',meaning:'kolam penampungan'}] },

  // 田舎・服 (Countryside & Clothes)
  '里': { type:'kanji', romaji:'ri / sato', meaning:'desa', onyomi:'リ (ri)', kunyomi:'さと (sato)', stroke_count:7, radical:'田（sawah）', jlpt:'N4', examples:[{word:'郷里',romaji:'kyouri',meaning:'kampung halaman'},{word:'千里',romaji:'senri',meaning:'jarak jauh'},{word:'里帰り',romaji:'satokaeri',meaning:'pulang kampung'}] },
  '野': { type:'kanji', romaji:'ya / no', meaning:'lapangan', onyomi:'ヤ (ya)', kunyomi:'の (no)', stroke_count:11, radical:'里（desa）', jlpt:'N4', examples:[{word:'野球',romaji:'yakyuu',meaning:'bisbol'},{word:'野菜',romaji:'yasai',meaning:'sayuran'},{word:'分野',romaji:'bunya',meaning:'bidang'}] },
  '虫': { type:'kanji', romaji:'chuu / mushi', meaning:'serangga', onyomi:'チュウ (chuu)', kunyomi:'むし (mushi)', stroke_count:6, radical:'虫（serangga）', jlpt:'N4', examples:[{word:'昆虫',romaji:'konchuu',meaning:'serangga'},{word:'虫歯',romaji:'mushiba',meaning:'gigi berlubang'},{word:'害虫',romaji:'gaichuu',meaning:'hama'}] },
  '羽': { type:'kanji', romaji:'u / wa / hane', meaning:'bulu / sayap', onyomi:'ウ (u)', kunyomi:'はね (hane)', stroke_count:6, radical:'羽（bulu）', jlpt:'N4', examples:[{word:'羽根',romaji:'hane',meaning:'bulu / sayap'},{word:'一羽',romaji:'ichiwa',meaning:'seekor burung'},{word:'羽毛',romaji:'umou',meaning:'bulu halus'}] },
  '馬': { type:'kanji', romaji:'ba / uma', meaning:'kuda', onyomi:'バ (ba)', kunyomi:'うま (uma)', stroke_count:10, radical:'馬（kuda）', jlpt:'N4', examples:[{word:'馬車',romaji:'basha',meaning:'kereta kuda'},{word:'乗馬',romaji:'jouba',meaning:'berkuda'},{word:'競馬',romaji:'keiba',meaning:'pacuan kuda'}] },
  '鳴': { type:'kanji', romaji:'mei / naku', meaning:'bersuara / berbunyi', onyomi:'メイ (mei)', kunyomi:'な-く (na-ku), na-r (na-ru)', stroke_count:14, radical:'鳥（burung）', jlpt:'N4', examples:[{word:'鳴く',romaji:'naku',meaning:'bersuara (hewan)'},{word:'共鳴',romaji:'kyoumei',meaning:'resonansi'},{word:'悲鳴',romaji:'himei',meaning:'teriakan'}] },
  '毛': { type:'kanji', romaji:'mou / ke', meaning:'rambut / bulu', onyomi:'モウ (mou)', kunyomi:'け (ke)', stroke_count:4, radical:'毛（rambut）', jlpt:'N4', examples:[{word:'毛布',romaji:'moufu',meaning:'selimut'},{word:'羽毛',romaji:'umou',meaning:'bulu halus'},{word:'眉毛',romaji:'mayuge',meaning:'alis'}] },
  '糸': { type:'kanji', romaji:'shi / ito', meaning:'benang', onyomi:'シ (shi)', kunyomi:'いto (ito)', stroke_count:6, radical:'糸（benang）', jlpt:'N4', examples:[{word:'糸口',romaji:'itoguchi',meaning:'petunjuk awal'},{word:'毛糸',romaji:'keito',meaning:'benang wol'},{word:'絹糸',romaji:'kinuito',meaning:'benang sutra'}] },
  '衣': { type:'kanji', romaji:'i / koromo', meaning:'pakaian', onyomi:'イ (i)', kunyomi:'ころも (koromo)', stroke_count:6, radical:'衣（pakaian）', jlpt:'N4', examples:[{word:'衣服',romaji:'ifuku',meaning:'pakaian'},{word:'衣食住',romaji:'ishokujuu',meaning:'sandang pangan papan'},{word:'更衣室',romaji:'kouishitsu',meaning:'ruang ganti'}] },
  '服': { type:'kanji', romaji:'fuku', meaning:'pakaian', onyomi:'フク (fuku)', kunyomi:'-', stroke_count:8, radical:'月（bulan）', jlpt:'N4', examples:[{word:'洋服',romaji:'youfuku',meaning:'pakaian barat'},{word:'制服',romaji:'seifuku',meaning:'seragam'},{word:'服装',romaji:'fukusou',meaning:'penampilan'}] },
  '洋': { type:'kanji', romaji:'you', meaning:'barat', onyomi:'ヨウ (you)', kunyomi:'-', stroke_count:9, radical:'水（air）', jlpt:'N4', examples:[{word:'洋服',romaji:'youfuku',meaning:'pakaian barat'},{word:'洋食',romaji:'youshoku',meaning:'masakan barat'},{word:'太平洋',romaji:'taiheiyou',meaning:'Samudra Pasifik'}] },

  // 料理 (Cooking)
  '料': { type:'kanji', romaji:'ryou', meaning:'bahan / tarif', onyomi:'リョウ (ryou)', kunyomi:'-', stroke_count:10, radical:'米（beras）', jlpt:'N4', examples:[{word:'料理',romaji:'ryouri',meaning:'masakan'},{word:'料金',romaji:'ryoukin',meaning:'tarif / biaya'},{word:'材料',romaji:'zairyou',meaning:'bahan'}] },
  '理': { type:'kanji', romaji:'ri', meaning:'alasan / logika', onyomi:'リ (ri)', kunyomi:'-', stroke_count:11, radical:'玉（permata）', jlpt:'N4', examples:[{word:'料理',romaji:'ryouri',meaning:'masakan'},{word:'理由',romaji:'riyuu',meaning:'alasan'},{word:'無理',romaji:'muri',meaning:'tidak mungkin'}] },
  '飯': { type:'kanji', romaji:'han / meshi', meaning:'nasi', onyomi:'ハン (han)', kunyomi:'めし (meshi)', stroke_count:12, radical:'食（makan）', jlpt:'N4', examples:[{word:'ご飯',romaji:'gohan',meaning:'nasi'},{word:'夕飯',romaji:'yuuhan',meaning:'makan malam'},{word:'炊飯器',romaji:'suihanki',meaning:'rice cooker'}] },
  '麦': { type:'kanji', romaji:'baku / mugi', meaning:'gandum', onyomi:'バク (baku)', kunyomi:'むぎ (mugi)', stroke_count:7, radical:'麦（gandum）', jlpt:'N4', examples:[{word:'麦茶',romaji:'mugicha',meaning:'teh barley'},{word:'小麦',romaji:'komugi',meaning:'gandum'},{word:'麦畑',romaji:'mugibatake',meaning:'ladang gandum'}] },
  '油': { type:'kanji', romaji:'yu / abura', meaning:'minyak', onyomi:'ユ (yu)', kunyomi:'あぶら (abura)', stroke_count:8, radical:'水（air）', jlpt:'N4', examples:[{word:'石油',romaji:'sekiyu',meaning:'minyak bumi'},{word:'油断',romaji:'yudan',meaning:'lengah'},{word:'炒め油',romaji:'itame abura',meaning:'minyak goreng'}] },
  '酒': { type:'kanji', romaji:'shu / sake', meaning:'alkohol / sake', onyomi:'シュ (shu)', kunyomi:'さけ (sake)', stroke_count:10, radical:'水（air）', jlpt:'N4', examples:[{word:'日本酒',romaji:'nihonshu',meaning:'sake Jepang'},{word:'酒場',romaji:'sakaba',meaning:'bar'},{word:'禁酒',romaji:'kinshu',meaning:'pantang alkohol'}] },
  '味': { type:'kanji', romaji:'mi / aji', meaning:'rasa', onyomi:'ミ (mi)', kunyomi:'あじ (aji)', stroke_count:8, radical:'口（mulut）', jlpt:'N4', examples:[{word:'味噌',romaji:'miso',meaning:'miso'},{word:'意味',romaji:'imi',meaning:'arti'},{word:'趣味',romaji:'shumi',meaning:'hobi'}] },

  // 住所 (Address)
  '住': { type:'kanji', romaji:'juu / sumu', meaning:'tinggal', onyomi:'ジュウ (juu)', kunyomi:'す-む (su-mu)', stroke_count:7, radical:'人（manusia）', jlpt:'N4', examples:[{word:'住所',romaji:'juusho',meaning:'alamat'},{word:'住む',romaji:'sumu',meaning:'tinggal'},{word:'居住',romaji:'kyojuu',meaning:'tempat tinggal'}] },
  '所': { type:'kanji', romaji:'sho / tokoro', meaning:'tempat', onyomi:'ショ (sho)', kunyomi:'ところ (tokoro)', stroke_count:8, radical:'户（pintu）', jlpt:'N4', examples:[{word:'住所',romaji:'juusho',meaning:'alamat'},{word:'場所',romaji:'basho',meaning:'tempat'},{word:'台所',romaji:'daidokoro',meaning:'dapur'}] },
  '都': { type:'kanji', romaji:'to / tsu / miyako', meaning:'ibu kota', onyomi:'ト (to), ツ (tsu)', kunyomi:'みやこ (miyako)', stroke_count:11, radical:'阝（kota）', jlpt:'N4', examples:[{word:'東京都',romaji:'toukyouto',meaning:'Tokyo'},{word:'都会',romaji:'tokai',meaning:'kota besar'},{word:'都市',romaji:'toshi',meaning:'kota'}] },
  '道': { type:'kanji', romaji:'dou / michi', meaning:'jalan', onyomi:'ドウ (dou)', kunyomi:'みち (michi)', stroke_count:12, radical:'辶（berjalan）', jlpt:'N4', examples:[{word:'道路',romaji:'douro',meaning:'jalan raya'},{word:'北海道',romaji:'hokkaidou',meaning:'Hokkaido'},{word:'柔道',romaji:'juudou',meaning:'judo'}] },
  '府': { type:'kanji', romaji:'fu', meaning:'prefektur', onyomi:'フ (fu)', kunyomi:'-', stroke_count:8, radical:'广（atap lebar）', jlpt:'N4', examples:[{word:'大阪府',romaji:'osakahu',meaning:'Prefektur Osaka'},{word:'政府',romaji:'seifu',meaning:'pemerintah'},{word:'首府',romaji:'shufu',meaning:'ibu kota'}] },
  '県': { type:'kanji', romaji:'ken', meaning:'prefektur', onyomi:'ケン (ken)', kunyomi:'-', stroke_count:9, radical:'目（mata）', jlpt:'N4', examples:[{word:'神奈川県',romaji:'kanagawaken',meaning:'Prefektur Kanagawa'},{word:'県立',romaji:'kenritsu',meaning:'prefektural'},{word:'他県',romaji:'taken',meaning:'prefektur lain'}] },
  '京': { type:'kanji', romaji:'kyou / kei / miyako', meaning:'ibu kota', onyomi:'キョウ (kyou), ケイ (kei)', kunyomi:'みやこ (miyako)', stroke_count:8, radical:'亠（tutup）', jlpt:'N4', examples:[{word:'東京',romaji:'toukyou',meaning:'Tokyo'},{word:'京都',romaji:'kyouto',meaning:'Kyoto'},{word:'北京',romaji:'pekin',meaning:'Beijing'}] },
  '市': { type:'kanji', romaji:'shi / ichi', meaning:'kota / pasar', onyomi:'シ (shi)', kunyomi:'いち (ichi)', stroke_count:5, radical:'巾（kain）', jlpt:'N4', examples:[{word:'市場',romaji:'shijou',meaning:'pasar'},{word:'都市',romaji:'toshi',meaning:'kota'},{word:'市民',romaji:'shimin',meaning:'warga kota'}] },
  '区': { type:'kanji', romaji:'ku', meaning:'distrik', onyomi:'ク (ku)', kunyomi:'-', stroke_count:4, radical:'匚（kotak）', jlpt:'N4', examples:[{word:'区別',romaji:'kubetsu',meaning:'perbedaan'},{word:'区役所',romaji:'kuyakusho',meaning:'kantor distrik'},{word:'選挙区',romaji:'senkyoku',meaning:'daerah pemilihan'}] },
  '村': { type:'kanji', romaji:'son / mura', meaning:'desa', onyomi:'ソン (son)', kunyomi:'むら (mura)', stroke_count:7, radical:'木（pohon）', jlpt:'N4', examples:[{word:'農村',romaji:'nouson',meaning:'desa pertanian'},{word:'村長',romaji:'sonchou',meaning:'kepala desa'},{word:'山村',romaji:'senson',meaning:'desa pegunungan'}] },
  '番': { type:'kanji', romaji:'ban', meaning:'nomor', onyomi:'バン (ban)', kunyomi:'-', stroke_count:12, radical:'田（sawah）', jlpt:'N4', examples:[{word:'番号',romaji:'bangou',meaning:'nomor'},{word:'番組',romaji:'bangumi',meaning:'program TV'},{word:'交番',romaji:'kouban',meaning:'pos polisi'}] },
  '号': { type:'kanji', romaji:'gou', meaning:'nomor', onyomi:'ゴウ (gou)', kunyomi:'-', stroke_count:5, radical:'口（mulut）', jlpt:'N4', examples:[{word:'番号',romaji:'bangou',meaning:'nomor'},{word:'信号',romaji:'shingou',meaning:'lampu lalu lintas'},{word:'号泣',romaji:'goukyuu',meaning:'menangis'}] },
  '国': { type:'kanji', romaji:'koku / kuni', meaning:'negara', onyomi:'コク (koku)', kunyomi:'くに (kuni)', stroke_count:8, radical:'囗（kotak）', jlpt:'N4', examples:[{word:'外国',romaji:'gaikoku',meaning:'negara asing'},{word:'国際',romaji:'kokusai',meaning:'internasional'},{word:'国民',romaji:'kokumin',meaning:'warga negara'}] },
  '紙': { type:'kanji', romaji:'shi / kami', meaning:'kertas', onyomi:'シ (shi)', kunyomi:'かみ (kami)', stroke_count:10, radical:'糸（benang）', jlpt:'N4', examples:[{word:'手紙',romaji:'tegami',meaning:'surat'},{word:'新聞紙',romaji:'shimbunshi',meaning:'kertas koran'},{word:'折り紙',romaji:'origami',meaning:'origami'}] },

  // 店 (Shop)
  '店': { type:'kanji', romaji:'ten / mise', meaning:'toko', onyomi:'テン (ten)', kunyomi:'みせ (mise)', stroke_count:8, radical:'广（atap lebar）', jlpt:'N4', examples:[{word:'店員',romaji:'tenin',meaning:'karyawan toko'},{word:'書店',romaji:'shoten',meaning:'toko buku'},{word:'閉店',romaji:'heiten',meaning:'tutup toko'}] },
  '客': { type:'kanji', romaji:'kyaku / kaku', meaning:'tamu', onyomi:'キャク (kyaku)', kunyomi:'-', stroke_count:9, radical:'宀（atap）', jlpt:'N4', examples:[{word:'お客様',romaji:'okyakusama',meaning:'tamu terhormat'},{word:'乗客',romaji:'joukyaku',meaning:'penumpang'},{word:'観客',romaji:'kankyaku',meaning:'penonton'}] },
  '売': { type:'kanji', romaji:'bai / uru', meaning:'menjual', onyomi:'バイ (bai)', kunyomi:'う-る (u-ru)', stroke_count:7, radical:'士（samurai）', jlpt:'N4', examples:[{word:'売る',romaji:'uru',meaning:'menjual'},{word:'販売',romaji:'hanbai',meaning:'penjualan'},{word:'売り場',romaji:'uriba',meaning:'area penjualan'}] },
  '品': { type:'kanji', romaji:'hin / shina', meaning:'barang', onyomi:'ヒン (hin)', kunyomi:'しな (shina)', stroke_count:9, radical:'口（mulut）', jlpt:'N4', examples:[{word:'品物',romaji:'shinamono',meaning:'barang'},{word:'食品',romaji:'shokuhin',meaning:'makanan'},{word:'作品',romaji:'sakuhin',meaning:'karya'}] },
  '薬': { type:'kanji', romaji:'yaku / kusuri', meaning:'obat', onyomi:'ヤク (yaku)', kunyomi:'くすり (kusuri)', stroke_count:16, radical:'艹（tanaman）', jlpt:'N4', examples:[{word:'薬局',romaji:'yakkyoku',meaning:'apotek'},{word:'医薬品',romaji:'iyakuhin',meaning:'produk farmasi'},{word:'漢方薬',romaji:'kanpouyaku',meaning:'obat herbal'}] },
  '待': { type:'kanji', romaji:'tai / matsu', meaning:'menunggu', onyomi:'タイ (tai)', kunyomi:'ま-つ (ma-tsu)', stroke_count:9, radical:'慢（langkah）', jlpt:'N4', examples:[{word:'待つ',romaji:'matsu',meaning:'menunggu'},{word:'期待',romaji:'kitai',meaning:'harapan'},{word:'待合室',romaji:'machiaishitsu',meaning:'ruang tunggu'}] },
  '合': { type:'kanji', romaji:'gou / au', meaning:'sesuai / cocok', onyomi:'ゴウ (gou)', kunyomi:'あ-う (a-u)', stroke_count:6, radical:'人（manusia）', jlpt:'N4', examples:[{word:'合う',romaji:'au',meaning:'cocok'},{word:'場合',romaji:'baai',meaning:'situasi'},{word:'試合',romaji:'shiai',meaning:'pertandingan'}] },
  '計': { type:'kanji', romaji:'kei / hakaru', meaning:'mengukur', onyomi:'ケイ (kei)', kunyomi:'はか-る (haka-ru)', stroke_count:9, radical:'言（berkata）', jlpt:'N4', examples:[{word:'時計',romaji:'tokei',meaning:'jam'},{word:'計画',romaji:'keikaku',meaning:'rencana'},{word:'合計',romaji:'goukei',meaning:'total'}] },
  '辺': { type:'kanji', romaji:'hen / atari', meaning:'sekitar', onyomi:'ヘン (hen)', kunyomi:'atari (atari)', stroke_count:5, radical:'辶（berjalan）', jlpt:'N4', examples:[{word:'この辺',romaji:'kono atari',meaning:'sekitar sini'},{word:'辺り',romaji:'atari',meaning:'sekitar'},{word:'周辺',romaji:'shuuhen',meaning:'sekeliling'}] },

  // 交通 (Transportation)
  '交': { type:'kanji', romaji:'kou / majiwaru', meaning:'bersilang', onyomi:'コウ (kou)', kunyomi:'ま-じわる (ma-jiwaru)', stroke_count:6, radical:'亠（tutup）', jlpt:'N4', examples:[{word:'交通',romaji:'koutsuu',meaning:'lalu lintas'},{word:'交換',romaji:'koukan',meaning:'pertukaran'},{word:'交差点',romaji:'kousaten',meaning:'persimpangan'}] },
  '通': { type:'kanji', romaji:'tsuu / tooru', meaning:'lewat', onyomi:'ツウ (tsuu)', kunyomi:'とお-る (too-ru), かよ-う (kayo-u)', stroke_count:10, radical:'辶（berjalan）', jlpt:'N4', examples:[{word:'交通',romaji:'koutsuu',meaning:'lalu lintas'},{word:'通学',romaji:'tsuugaku',meaning:'pergi sekolah'},{word:'普通',romaji:'futsuu',meaning:'biasa'}] },
  '荷': { type:'kanji', romaji:'ka / ni', meaning:'muatan / beban', onyomi:'カ (ka)', kunyomi:'に (ni)', stroke_count:10, radical:'艹（tanaman）', jlpt:'N4', examples:[{word:'荷物',romaji:'nimotsu',meaning:'barang'},{word:'入荷',romaji:'nyuuka',meaning:'masuk barang'},{word:'出荷',romaji:'shukka',meaning:'pengiriman'}] },
  '送': { type:'kanji', romaji:'sou / okuru', meaning:'mengirim', onyomi:'ソウ (sou)', kunyomi:'おく-る (oku-ru)', stroke_count:9, radical:'辶（berjalan）', jlpt:'N4', examples:[{word:'送る',romaji:'okuru',meaning:'mengirim'},{word:'放送',romaji:'housou',meaning:'siaran'},{word:'運送',romaji:'unsou',meaning:'pengangkutan'}] },
  '宅': { type:'kanji', romaji:'taku', meaning:'rumah', onyomi:'タク (taku)', kunyomi:'-', stroke_count:6, radical:'宀（atap）', jlpt:'N4', examples:[{word:'お宅',romaji:'otaku',meaning:'rumah Anda'},{word:'宅配',romaji:'takuhai',meaning:'antar barang'},{word:'帰宅',romaji:'kitaku',meaning:'pulang ke rumah'}] },
  '止': { type:'kanji', romaji:'shi / tomeru', meaning:'berhenti', onyomi:'シ (shi)', kunyomi:'と-まる (to-maru), と-める (to-meru)', stroke_count:4, radical:'止（berhenti）', jlpt:'N4', examples:[{word:'止まる',romaji:'tomaru',meaning:'berhenti'},{word:'禁止',romaji:'kinshi',meaning:'dilarang'},{word:'中止',romaji:'chuushi',meaning:'batal'}] },
  '急': { type:'kanji', romaji:'kyuu / isogu', meaning:'cepat / darurat', onyomi:'キュウ (kyuu)', kunyomi:'いそ-ぐ (iso-gu)', stroke_count:9, radical:'心（hati）', jlpt:'N4', examples:[{word:'急ぐ',romaji:'isogu',meaning:'terburu-buru'},{word:'急行',romaji:'kyuukou',meaning:'kereta ekspres'},{word:'緊急',romaji:'kinkyuu',meaning:'darurat'}] },
  '特': { type:'kanji', romaji:'toku', meaning:'khusus', onyomi:'トク (toku)', kunyomi:'-', stroke_count:10, radical:'牛（sapi）', jlpt:'N4', examples:[{word:'特別',romaji:'tokubetsu',meaning:'istimewa'},{word:'特急',romaji:'tokkyuu',meaning:'super ekspres'},{word:'特徴',romaji:'tokutyou',meaning:'ciri khas'}] },
  '鉄': { type:'kanji', romaji:'tetsu', meaning:'besi', onyomi:'テツ (tetsu)', kunyomi:'-', stroke_count:13, radical:'金（emas）', jlpt:'N4', examples:[{word:'電鉄',romaji:'dentetsu',meaning:'kereta listrik'},{word:'地下鉄',romaji:'chikatetsu',meaning:'kereta bawah tanah'},{word:'鉄道',romaji:'tetsudou',meaning:'jalur kereta'}] },
  '船': { type:'kanji', romaji:'sen / fune', meaning:'kapal', onyomi:'セン (sen)', kunyomi:'ふね (fune)', stroke_count:11, radical:'舟（perahu）', jlpt:'N4', examples:[{word:'船着き場',romaji:'funatsukiba',meaning:'dermaga'},{word:'飛行船',romaji:'hikoussen',meaning:'balon udara'},{word:'船旅',romaji:'funatabi',meaning:'perjalanan kapal'}] },

  // 場所 (Place)
  '部': { type:'kanji', romaji:'bu / be', meaning:'bagian / klub', onyomi:'ブ (bu)', kunyomi:'-', stroke_count:11, radical:'阝（kota）', jlpt:'N4', examples:[{word:'部屋',romaji:'heya',meaning:'kamar'},{word:'部長',romaji:'buchou',meaning:'kepala bagian'},{word:'全部',romaji:'zenbu',meaning:'semua'}] },
  '屋': { type:'kanji', romaji:'oku / ya', meaning:'rumah / toko', onyomi:'オク (oku)', kunyomi:'や (ya)', stroke_count:9, radical:'尸（mayat）', jlpt:'N4', examples:[{word:'部屋',romaji:'heya',meaning:'kamar'},{word:'本屋',romaji:'honya',meaning:'toko buku'},{word:'屋根',romaji:'yane',meaning:'atap'}] },
  '教': { type:'kanji', romaji:'kyou / oshieru', meaning:'mengajar / agama', onyomi:'キョウ (kyou)', kunyomi:'おし-える (oshi-eru)', stroke_count:11, radical:'攴（pukul ringan）', jlpt:'N4', examples:[{word:'教室',romaji:'kyoushitsu',meaning:'kelas'},{word:'教育',romaji:'kyouiku',meaning:'pendidikan'},{word:'教科書',romaji:'kyoukasho',meaning:'buku pelajaran'}] },
  '室': { type:'kanji', romaji:'shitsu / muro', meaning:'ruangan', onyomi:'シツ (shitsu)', kunyomi:'むろ (muro)', stroke_count:9, radical:'宀（atap）', jlpt:'N4', examples:[{word:'教室',romaji:'kyoushitsu',meaning:'ruang kelas'},{word:'研究室',romaji:'kenkyuushitsu',meaning:'ruang riset'},{word:'洋室',romaji:'youshitsu',meaning:'ruangan Barat'}] },
  '会': { type:'kanji', romaji:'kai / au', meaning:'pertemuan / bertemu', onyomi:'カイ (kai)', kunyomi:'あ-う (a-u)', stroke_count:6, radical:'人（manusia）', jlpt:'N4', examples:[{word:'会社',romaji:'kaisha',meaning:'perusahaan'},{word:'会話',romaji:'kaiwa',meaning:'percakapan'},{word:'社会',romaji:'shakai',meaning:'masyarakat'}] },
  '社': { type:'kanji', romaji:'sha / yashiro', meaning:'perusahaan / kuil', onyomi:'シャ (sha)', kunyomi:'やしろ (yashiro)', stroke_count:7, radical:'示（menunjukkan）', jlpt:'N4', examples:[{word:'会社',romaji:'kaisha',meaning:'perusahaan'},{word:'社会',romaji:'shakai',meaning:'masyarakat'},{word:'神社',romaji:'jinja',meaning:'kuil Shinto'}] },
  '駅': { type:'kanji', romaji:'eki', meaning:'stasiun', onyomi:'エキ (eki)', kunyomi:'-', stroke_count:14, radical:'馬（kuda）', jlpt:'N4', examples:[{word:'駅員',romaji:'ekiin',meaning:'petugas stasiun'},{word:'終点駅',romaji:'shuuteneki',meaning:'stasiun akhir'},{word:'無人駅',romaji:'mujineki',meaning:'stasiun sepi'}] },
  '工': { type:'kanji', romaji:'kou / ku', meaning:'pekerjaan / rekayasa', onyomi:'コウ (kou)', kunyomi:'-', stroke_count:3, radical:'工（pekerjaan）', jlpt:'N4', examples:[{word:'工場',romaji:'koujou',meaning:'pabrik'},{word:'工事',romaji:'kouji',meaning:'konstruksi'},{word:'人工',romaji:'jinkou',meaning:'buatan'}] },
  '場': { type:'kanji', romaji:'jou / ba', meaning:'tempat', onyomi:'ジョウ (jou)', kunyomi:'ば (ba)', stroke_count:12, radical:'土（tanah）', jlpt:'N4', examples:[{word:'工場',romaji:'koujou',meaning:'pabrik'},{word:'場所',romaji:'basho',meaning:'tempat'},{word:'立場',romaji:'tachiba',meaning:'posisi'}] },
  '病': { type:'kanji', romaji:'byou / yamai', meaning:'sakit', onyomi:'ビョウ (byou)', kunyomi:'ya-mu (ya-mu)', stroke_count:10, radical:'疒（penyakit）', jlpt:'N4', examples:[{word:'病院',romaji:'byouin',meaning:'rumah sakit'},{word:'病気',romaji:'byouki',meaning:'sakit'},{word:'大病',romaji:'taibyou',meaning:'sakit parah'}] },
  '院': { type:'kanji', romaji:'in', meaning:'lembaga', onyomi:'イン (in)', kunyomi:'-', stroke_count:10, radical:'阝（kota）', jlpt:'N4', examples:[{word:'病院',romaji:'byouin',meaning:'rumah sakit'},{word:'大学院',romaji:'daigakuin',meaning:'pascasarjana'},{word:'美容院',romaji:'biyouin',meaning:'salon'}] },
  '公': { type:'kanji', romaji:'kou', meaning:'publik', onyomi:'コウ (kou)', kunyomi:'おおやけ (ooyake)', stroke_count:4, radical:'八（delapan）', jlpt:'N4', examples:[{word:'公園',romaji:'kouen',meaning:'taman umum'},{word:'公務員',romaji:'koumuin',meaning:'PNS'},{word:'公立',romaji:'kouritsu',meaning:'negeri'}] },
  '園': { type:'kanji', romaji:'en / sono', meaning:'taman', onyomi:'エン (en)', kunyomi:'sono (sono)', stroke_count:13, radical:'囗（kotak）', jlpt:'N4', examples:[{word:'公園',romaji:'kouen',meaning:'taman umum'},{word:'動物園',romaji:'doubutsuen',meaning:'kebun binatang'},{word:'幼稚園',romaji:'youchien',meaning:'TK'}] },
  '図': { type:'kanji', romaji:'zu / to', meaning:'gambar / peta', onyomi:'ズ (zu), ト (to)', kunyomi:'はか-る (haka-ru)', stroke_count:7, radical:'囗（kotak）', jlpt:'N4', examples:[{word:'図書館',romaji:'toshokan',meaning:'perpustakaan'},{word:'地図',romaji:'chizu',meaning:'peta'},{word:'図形',romaji:'zukei',meaning:'bentuk'}] },
  '館': { type:'kanji', romaji:'kan', meaning:'gedung besar', onyomi:'カン (kan)', kunyomi:'-', stroke_count:16, radical:'食（makan）', jlpt:'N4', examples:[{word:'図書館',romaji:'toshokan',meaning:'perpustakaan'},{word:'博物館',romaji:'hakubutsukan',meaning:'museum'},{word:'旅館',romaji:'ryokan',meaning:'penginapan'}] },
  '映': { type:'kanji', romaji:'ei / utsuru', meaning:'memantulkan / film', onyomi:'エイ (ei)', kunyomi:'うつ-る (utsu-ru)', stroke_count:9, radical:'日（matahari）', jlpt:'N4', examples:[{word:'映画',romaji:'eiga',meaning:'film'},{word:'上映',romaji:'jouei',meaning:'penayangan film'},{word:'反映',romaji:'han-ei',meaning:'cerminan'}] },
  '画': { type:'kanji', romaji:'ga / kaku', meaning:'gambar / film', onyomi:'ガ (ga)', kunyomi:'-', stroke_count:8, radical:'田（sawah）', jlpt:'N4', examples:[{word:'映画',romaji:'eiga',meaning:'film'},{word:'漫画',romaji:'manga',meaning:'komik'},{word:'計画',romaji:'keikaku',meaning:'rencana'}] },

  // 勉強 (Study)
  '勉': { type:'kanji', romaji:'ben', meaning:'berusaha keras', onyomi:'ベン (ben)', kunyomi:'-', stroke_count:10, radical:'力（kekuatan）', jlpt:'N4', examples:[{word:'勉強',romaji:'benkyou',meaning:'belajar'},{word:'勉強家',romaji:'benkyouka',meaning:'orang rajin'}] },
  '強': { type:'kanji', romaji:'kyou / tsuyoi', meaning:'kuat', onyomi:'キョウ (kyou)', kunyomi:'つよ-い (tsuyo-i)', stroke_count:11, radical:'弓（busur）', jlpt:'N4', examples:[{word:'強い',romaji:'tsuyoi',meaning:'kuat'},{word:'勉強',romaji:'benkyou',meaning:'belajar'},{word:'強化',romaji:'kyouka',meaning:'penguatan'}] },
  '宿': { type:'kanji', romaji:'shuku / yado', meaning:'penginapan / pekerjaan rumah', onyomi:'シュク (shuku)', kunyomi:'yado (yado)', stroke_count:11, radical:'宀（atap）', jlpt:'N4', examples:[{word:'宿題',romaji:'shukudai',meaning:'PR'},{word:'宿泊',romaji:'shukuhaku',meaning:'menginap'},{word:'旅宿',romaji:'ryoshuku',meaning:'penginapan'}] },
  '題': { type:'kanji', romaji:'dai', meaning:'topik / soal', onyomi:'ダイ (dai)', kunyomi:'-', stroke_count:18, radical:'頁（halaman）', jlpt:'N4', examples:[{word:'問題',romaji:'mondai',meaning:'soal'},{word:'宿題',romaji:'shukudai',meaning:'PR'},{word:'課題',romaji:'kadai',meaning:'tugas'}] },
  '質': { type:'kanji', romaji:'shitsu / shichi', meaning:'kualitas / pertanyaan', onyomi:'シツ (shitsu)', kunyomi:'-', stroke_count:15, radical:'貝（kerang）', jlpt:'N4', examples:[{word:'質問',romaji:'shitsumon',meaning:'pertanyaan'},{word:'品質',romaji:'hinshitsu',meaning:'mutu'},{word:'素質',romaji:'soshitsu',meaning:'bakat'}] },
  '問': { type:'kanji', romaji:'mon / tou', meaning:'pertanyaan', onyomi:'モン (mon)', kunyomi:'と-う (to-u)', stroke_count:11, radical:'口（mulut）', jlpt:'N4', examples:[{word:'質問',romaji:'shitsumon',meaning:'pertanyaan'},{word:'問題',romaji:'mondai',meaning:'masalah'},{word:'訪問',romaji:'houmon',meaning:'kunjungan'}] },
  '試': { type:'kanji', romaji:'shi / tamesu', meaning:'mencoba', onyomi:'シ (shi)', kunyomi:'ため-す (tame-su)', stroke_count:13, radical:'言（berkata）', jlpt:'N4', examples:[{word:'試験',romaji:'shiken',meaning:'ujian'},{word:'試合',romaji:'shiai',meaning:'pertandingan'},{word:'試着',romaji:'shichaku',meaning:'mencoba pakaian'}] },
  '験': { type:'kanji', romaji:'ken / gen', meaning:'ujian / pengalaman', onyomi:'ケン (ken)', kunyomi:'-', stroke_count:18, radical:'馬（kuda）', jlpt:'N4', examples:[{word:'試験',romaji:'shiken',meaning:'ujian'},{word:'経験',romaji:'keiken',meaning:'pengalaman'},{word:'実験',romaji:'jikken',meaning:'eksperimen'}] },
  '答': { type:'kanji', romaji:'tou / kotaeru', meaning:'menjawab', onyomi:'トウ (tou)', kunyomi:'こた-える (kota-eru)', stroke_count:12, radical:'竹（bambu）', jlpt:'N4', examples:[{word:'答える',romaji:'kotaeru',meaning:'menjawab'},{word:'回答',romaji:'kaitou',meaning:'jawaban'},{word:'正答',romaji:'seitou',meaning:'jawaban benar'}] },
  '考': { type:'kanji', romaji:'kou / kangaeru', meaning:'berpikir', onyomi:'コウ (kou)', kunyomi:'かんが-える (kanga-eru)', stroke_count:6, radical:'老（tua）', jlpt:'N4', examples:[{word:'考える',romaji:'kangaeru',meaning:'berpikir'},{word:'参考',romaji:'sankou',meaning:'referensi'},{word:'思考',romaji:'shikou',meaning:'pemikiran'}] },

  // 科目 (Subject)
  '字': { type:'kanji', romaji:'ji / aza', meaning:'huruf', onyomi:'ジ (ji)', kunyomi:'あざ (aza)', stroke_count:6, radical:'宀（atap）', jlpt:'N4', examples:[{word:'漢字',romaji:'kanji',meaning:'kanji'},{word:'文字',romaji:'moji',meaning:'huruf'},{word:'数字',romaji:'suuji',meaning:'angka'}] },
  '文': { type:'kanji', romaji:'bun / mon', meaning:'tulisan / kalimat', onyomi:'ブン (bun)', kunyomi:'ふみ (fumi)', stroke_count:4, radical:'文（tulisan）', jlpt:'N4', examples:[{word:'文化',romaji:'bunka',meaning:'budaya'},{word:'作文',romaji:'sakubun',meaning:'karangan'},{word:'文法',romaji:'bunpou',meaning:'tata bahasa'}] },
  '漢': { type:'kanji', romaji:'kan', meaning:'Tiongkok / kanji', onyomi:'カン (kan)', kunyomi:'-', stroke_count:13, radical:'水（air）', jlpt:'N4', examples:[{word:'漢字',romaji:'kanji',meaning:'kanji'},{word:'漢語',romaji:'kango',meaning:'kata Tiongkok'},{word:'漢方',romaji:'kanpou',meaning:'herbal'}] },
  '数': { type:'kanji', romaji:'suu / kazu', meaning:'angka', onyomi:'スウ (suu)', kunyomi:'kazu (kazu)', stroke_count:13, radical:'攴（pukul ringan）', jlpt:'N4', examples:[{word:'数字',romaji:'suuji',meaning:'angka'},{word:'数学',romaji:'suugaku',meaning:'matematika'},{word:'複数',romaji:'fukusuu',meaning:'jamak'}] },
  '英': { type:'kanji', romaji:'ei', meaning:'Inggris', onyomi:'エイ (ei)', kunyomi:'-', stroke_count:8, radical:'艹（tanaman）', jlpt:'N4', examples:[{word:'英語',romaji:'eigo',meaning:'bahasa Inggris'},{word:'英国',romaji:'eikoku',meaning:'Inggris'},{word:'英才',romaji:'eisai',meaning:'berbakat'}] },
  '語': { type:'kanji', romaji:'go / kataru', meaning:'bahasa', onyomi:'ゴ (go)', kunyomi:'かた-る (kata-ru)', stroke_count:14, radical:'言（berkata）', jlpt:'N4', examples:[{word:'日本語',romaji:'nihongo',meaning:'bahasa Jepang'},{word:'語学',romaji:'gogaku',meaning:'studi bahasa'},{word:'物語',romaji:'monogatari',meaning:'cerita'}] },
  '化': { type:'kanji', romaji:'ka / ke', meaning:'berubah', onyomi:'カ (ka)', kunyomi:'ば-ける (ba-keru)', stroke_count:4, radical:'人（manusia）', jlpt:'N4', examples:[{word:'文化',romaji:'bunka',meaning:'budaya'},{word:'変化',romaji:'henka',meaning:'perubahan'},{word:'化学',romaji:'kagaku',meaning:'kimia'}] },
  '育': { type:'kanji', romaji:'iku / sodatsu', meaning:'tumbuh', onyomi:'イク (iku)', kunyomi:'そだ-つ (soda-tsu)', stroke_count:8, radical:'月（bulan）', jlpt:'N4', examples:[{word:'教育',romaji:'kyouiku',meaning:'pendidikan'},{word:'育てる',romaji:'sodateru',meaning:'membesarkan'},{word:'体育',romaji:'taiiku',meaning:'jasmani'}] },

  // 大学 (University)
  '研': { type:'kanji', romaji:'ken', meaning:'riset', onyomi:'ケン (ken)', kunyomi:'-', stroke_count:9, radical:'石（batu）', jlpt:'N4', examples:[{word:'研究',romaji:'kenkyuu',meaning:'penelitian'},{word:'研修',romaji:'kenshuu',meaning:'pelatihan'},{word:'研究室',romaji:'kenkyuushitsu',meaning:'ruang riset'}] },
  '究': { type:'kanji', romaji:'kyuu', meaning:'menyelidiki', onyomi:'キュウ (kyuu)', kunyomi:'-', stroke_count:7, radical:'穴（lubang）', jlpt:'N4', examples:[{word:'研究',romaji:'kenkyuu',meaning:'penelitian'},{word:'追究',romaji:'tsuikyuu',meaning:'menyelidiki'},{word:'究極',romaji:'kyuukyoku',meaning:'puncak'}] },
  '医': { type:'kanji', romaji:'i', meaning:'dokter', onyomi:'イ (i)', kunyomi:'-', stroke_count:7, radical:'匸（kotak）', jlpt:'N4', examples:[{word:'医者',romaji:'isha',meaning:'dokter'},{word:'医学',romaji:'igaku',meaning:'kedokteran'},{word:'医療',romaji:'iryou',meaning:'medis'}] },
  '科': { type:'kanji', romaji:'ka', meaning:'bidang ilmu', onyomi:'カ (ka)', kunyomi:'-', stroke_count:9, radical:'禾（padi）', jlpt:'N4', examples:[{word:'科学',romaji:'kagaku',meaning:'sains'},{word:'科目',romaji:'kamoku',meaning:'mata pelajaran'},{word:'外科',romaji:'geka',meaning:'bedah'}] },
  '政': { type:'kanji', romaji:'sei', meaning:'politik', onyomi:'セイ (sei)', kunyomi:'-', stroke_count:9, radical:'攴（pukul ringan）', jlpt:'N4', examples:[{word:'政治',romaji:'seiji',meaning:'politik'},{word:'政府',romaji:'seifu',meaning:'pemerintah'},{word:'財政',romaji:'zaisei',meaning:'keuangan'}] },
  '治': { type:'kanji', romaji:'ji / chi', meaning:'menyembuhkan / memerintah', onyomi:'ジ (ji)', kunyomi:'おさ-める (osa-meru)', stroke_count:8, radical:'水（air）', jlpt:'N4', examples:[{word:'政治',romaji:'seiji',meaning:'politik'},{word:'治す',romaji:'naosu',meaning:'menyembuhkan'},{word:'統治',romaji:'touchi',meaning:'pemerintahan'}] },
  '経': { type:'kanji', romaji:'kei', meaning:'melewati / mengelola', onyomi:'ケイ (kei)', kunyomi:'へ-る (he-ru)', stroke_count:11, radical:'糸（benang）', jlpt:'N4', examples:[{word:'経済',romaji:'keizai',meaning:'ekonomi'},{word:'経験',romaji:'keiken',meaning:'pengalaman'},{word:'神経',romaji:'shinkei',meaning:'saraf'}] },
  '済': { type:'kanji', romaji:'sai', meaning:'selesai', onyomi:'サイ (sai)', kunyomi:'す-む (su-mu)', stroke_count:11, radical:'水（air）', jlpt:'N4', examples:[{word:'経済',romaji:'keizai',meaning:'ekonomi'},{word:'済む',romaji:'sumu',meaning:'selesai'},{word:'救済',romaji:'kyuusai',meaning:'penyelamatan'}] },
  '歴': { type:'kanji', romaji:'reki', meaning:'sejarah', onyomi:'レキ (reki)', kunyomi:'-', stroke_count:14, radical:'止（berhenti）', jlpt:'N4', examples:[{word:'歴史',romaji:'rekishi',meaning:'sejarah'},{word:'経歴',romaji:'keireki',meaning:'karier'},{word:'学歴',romaji:'gakureki',meaning:'pendidikan'}] },
  '史': { type:'kanji', romaji:'shi', meaning:'sejarah', onyomi:'シ (shi)', kunyomi:'-', stroke_count:5, radical:'口（mulut）', jlpt:'N4', examples:[{word:'歴史',romaji:'rekishi',meaning:'sejarah'},{word:'史上',romaji:'shijou',meaning:'dalam sejarah'},{word:'世界史',romaji:'sekaishi',meaning:'sejarah dunia'}] },

  // 趣味 (Hobby)
  '運': { type:'kanji', romaji:'un / hakobu', meaning:'membawa / nasib', onyomi:'ウン (un)', kunyomi:'はこ-ぶ (hako-bu)', stroke_count:12, radical:'辶（berjalan）', jlpt:'N4', examples:[{word:'運動',romaji:'undou',meaning:'olahraga'},{word:'運転',romaji:'unten',meaning:'mengemudi'},{word:'幸運',romaji:'kouun',meaning:'beruntung'}] },
  '動': { type:'kanji', romaji:'dou / ugoku', meaning:'bergerak', onyomi:'ドウ (dou)', kunyomi:'うご-く (ugo-ku)', stroke_count:11, radical:'力（kekuatan）', jlpt:'N4', examples:[{word:'運動',romaji:'undou',meaning:'olahraga'},{word:'動物',romaji:'doubutsu',meaning:'hewan'},{word:'自動',romaji:'jidou',meaning:'otomatis'}] },
  '泳': { type:'kanji', romaji:'ei / oyogu', meaning:'berenang', onyomi:'エイ (ei)', kunyomi:'およ-ぐ (oyo-gu)', stroke_count:8, radical:'水（air）', jlpt:'N4', examples:[{word:'水泳',romaji:'suiei',meaning:'renang'},{word:'泳ぐ',romaji:'oyogu',meaning:'berenang'},{word:'平泳ぎ',romaji:'hiraoyogi',meaning:'gaya dada'}] },
  '旅': { type:'kanji', romaji:'ryo / tabi', meaning:'perjalanan', onyomi:'リョ (ryo)', kunyomi:'たび (tabi)', stroke_count:10, radical:'方（arah）', jlpt:'N4', examples:[{word:'旅行',romaji:'ryokou',meaning:'wisata'},{word:'旅館',romaji:'ryokan',meaning:'penginapan'},{word:'一人旅',romaji:'hitoritabi',meaning:'travel sendiri'}] },
  '世': { type:'kanji', romaji:'sei / yo', meaning:'dunia / zaman', onyomi:'セイ (sei)', kunyomi:'よ (yo)', stroke_count:5, radical:'一（satu）', jlpt:'N4', examples:[{word:'世界',romaji:'sekai',meaning:'dunia'},{word:'世話',romaji:'sewa',meaning:'merawat'},{word:'中世',romaji:'chuusei',meaning:'abad pertengahan'}] },
  '界': { type:'kanji', romaji:'kai', meaning:'batas / dunia', onyomi:'カイ (kai)', kunyomi:'-', stroke_count:9, radical:'田（sawah）', jlpt:'N4', examples:[{word:'世界',romaji:'sekai',meaning:'dunia'},{word:'限界',romaji:'genkai',meaning:'batas'},{word:'業界',romaji:'gyoukai',meaning:'industri'}] },
  '練': { type:'kanji', romaji:'ren', meaning:'berlatih', onyomi:'レン (ren)', kunyomi:'-', stroke_count:14, radical:'糸（benang）', jlpt:'N4', examples:[{word:'練習',romaji:'renshuu',meaning:'latihan'},{word:'訓練',romaji:'kunren',meaning:'pelatihan'},{word:'洗練',romaji:'senren',meaning:'halus'}] },
  '習': { type:'kanji', romaji:'shuu / narau', meaning:'belajar', onyomi:'シュウ (shuu)', kunyomi:'なら-う (nara-u)', stroke_count:11, radical:'羽（bulu）', jlpt:'N4', examples:[{word:'練習',romaji:'renshuu',meaning:'latihan'},{word:'習う',romaji:'narau',meaning:'belajar'},{word:'習慣',romaji:'shuukan',meaning:'kebiasaan'}] },
  '写': { type:'kanji', romaji:'sha / utsusu', meaning:'menyalin / memotret', onyomi:'シャ (sha)', kunyomi:'うつ-す (utsu-su)', stroke_count:5, radical:'冖（penutup）', jlpt:'N4', examples:[{word:'写真',romaji:'shashin',meaning:'foto'},{word:'写す',romaji:'utsusu',meaning:'memotret'},{word:'写本',romaji:'shahon',meaning:'salinan'}] },
  '真': { type:'kanji', romaji:'shin / ma', meaning:'sejati / nyata', onyomi:'シン (shin)', kunyomi:'ま (ma)', stroke_count:10, radical:'目（mata）', jlpt:'N4', examples:[{word:'写真',romaji:'shashin',meaning:'foto'},{word:'真面目',romaji:'majime',meaning:'serius'},{word:'真実',romaji:'shinjitsu',meaning:'kebenaran'}] },
  '楽': { type:'kanji', romaji:'gaku / raku / tanoshii', meaning:'musik / senang', onyomi:'ガク (gaku)', kunyomi:'たの-しい (tano-shii)', stroke_count:13, radical:'木（pohon）', jlpt:'N4', examples:[{word:'音楽',romaji:'ongaku',meaning:'musik'},{word:'楽しい',romaji:'tanoshii',meaning:'senang'},{word:'楽器',romaji:'gakki',meaning:'alat musik'}] },
  '声': { type:'kanji', romaji:'sei / koe', meaning:'suara', onyomi:'セイ (sei)', kunyomi:'こえ (koe)', stroke_count:7, radical:'士（samurai）', jlpt:'N4', examples:[{word:'声優',romaji:'seiyuu',meaning:'seiyuu'},{word:'歌声',romaji:'utagoe',meaning:'suara nyanyi'},{word:'大声',romaji:'ooogoe',meaning:'teriakan'}] },
  '歌': { type:'kanji', romaji:'ka / uta', meaning:'lagu', onyomi:'カ (ka)', kunyomi:'うた (uta)', stroke_count:14, radical:'欠（kurang）', jlpt:'N4', examples:[{word:'歌手',romaji:'kashu',meaning:'penyanyi'},{word:'国歌',romaji:'kokka',meaning:'lagu kebangsaan'},{word:'歌う',romaji:'utau',meaning:'bernyanyi'}] },
  '集': { type:'kanji', romaji:'shuu / atsumeru', meaning:'mengumpulkan', onyomi:'シュウ (shuu)', kunyomi:'あつ-める (atsu-meru)', stroke_count:12, radical:'隹（burung）', jlpt:'N4', examples:[{word:'集める',romaji:'atsumeru',meaning:'kumpul'},{word:'集合',romaji:'shuugou',meaning:'berkumpul'},{word:'特集',romaji:'tokushuu',meaning:'khusus'}] },

  // 動詞 (Verbs)
  '作': { type:'kanji', romaji:'saku / tsukuru', meaning:'membuat', onyomi:'サク (saku)', kunyomi:'つく-る (tsuku-ru)', stroke_count:7, radical:'人（manusia）', jlpt:'N4', examples:[{word:'作る',romaji:'tsukuru',meaning:'membuat'},{word:'作品',romaji:'sakuhin',meaning:'karya'},{word:'操作',romaji:'sousa',meaning:'operasi'}] },
  '使': { type:'kanji', romaji:'shi / tsukau', meaning:'menggunakan', onyomi:'シ (shi)', kunyomi:'つか-う (tsuka-u)', stroke_count:8, radical:'人（manusia）', jlpt:'N4', examples:[{word:'使う',romaji:'tsukau',meaning:'pakai'},{word:'大使',romaji:'taishi',meaning:'dubes'},{word:'使用',romaji:'shiyou',meaning:'guna'}] },
  '思': { type:'kanji', romaji:'shi / omou', meaning:'berpikir', onyomi:'シ (shi)', kunyomi:'おmo-u (omo-u)', stroke_count:9, radical:'心（hati）', jlpt:'N4', examples:[{word:'思う',romaji:'omou',meaning:'pikir'},{word:'意思',romaji:'ishi',meaning:'niat'},{word:'思い出',romaji:'omoide',meaning:'memori'}] },
  '持': { type:'kanji', romaji:'ji / motsu', meaning:'memegang', onyomi:'ジ (ji)', kunyomi:'も-つ (mo-tsu)', stroke_count:9, radical:'手（tangan）', jlpt:'N4', examples:[{word:'持つ',romaji:'motsu',meaning:'bawa'},{word:'気持ち',romaji:'kimochi',meaning:'perasaan'},{word:'所持',romaji:'shoji',meaning:'bawa'}] },
  '当': { type:'kanji', romaji:'tou / ataru', meaning:'kena / giliran', onyomi:'トウ (tou)', kunyomi:'あ-たる (a-taru)', stroke_count:6, radical:'彐（sisir）', jlpt:'N4', examples:[{word:'当たる',romaji:'ataru',meaning:'kena'},{word:'担当',romaji:'tantou',meaning:'tanggung jawab'},{word:'本当',romaji:'hontou',meaning:'benar'}] },
  '知': { type:'kanji', romaji:'chi / shiru', meaning:'tahu', onyomi:'チ (chi)', kunyomi:'し-る (shi-ru)', stroke_count:8, radical:'矢（panah）', jlpt:'N4', examples:[{word:'知る',romaji:'shiru',meaning:'tahu'},{word:'知識',romaji:'chishiki',meaning:'pengetahuan'},{word:'通知',romaji:'tsuuchi',meaning:'kabar'}] },
  '働': { type:'kanji', romaji:'dou / hataraku', meaning:'bekerja', onyomi:'ドウ (dou)', kunyomi:'はたら-く (hatara-ku)', stroke_count:13, radical:'人（manusia）', jlpt:'N4', examples:[{word:'働く',romaji:'hataraku',meaning:'kerja'},{word:'労働',romaji:'roudou',meaning:'buruh'},{word:'共働き',romaji:'tomobataraki',meaning:'kerja bersama'}] },

  // 対になる動詞 (Paired Verbs)
  '始': { type:'kanji', romaji:'shi / hajimeru', meaning:'mulai', onyomi:'シ (shi)', kunyomi:'はじ-める (hajimeru)', stroke_count:8, radical:'女（perempuan）', jlpt:'N4', examples:[{word:'始める',romaji:'hajimeru',meaning:'memulai'},{word:'開始',romaji:'kaishi',meaning:'mulai'},{word:'始まり',romaji:'hajimari',meaning:'awal'}] },
  '終': { type:'kanji', romaji:'shuu / owaru', meaning:'selesai', onyomi:'シュウ (shuu)', kunyomi:'お-わる (owaru)', stroke_count:11, radical:'糸（benang）', jlpt:'N4', examples:[{word:'終わる',romaji:'owaru',meaning:'berakhir'},{word:'終了',romaji:'shuuryou',meaning:'selesai'},{word:'最終',romaji:'saishuu',meaning:'terakhir'}] },
  '乗': { type:'kanji', romaji:'jou / noru', meaning:'naik', onyomi:'ジョウ (jou)', kunyomi:'の-る (noru)', stroke_count:9, radical:'丿（garis miring）', jlpt:'N4', examples:[{word:'乗る',romaji:'noru',meaning:'naik'},{word:'乗客',romaji:'joukyaku',meaning:'penumpang'},{word:'乗り物',romaji:'norimono',meaning:'kendaraan'}] },
  '降': { type:'kanji', romaji:'kou / oriru', meaning:'turun', onyomi:'コウ (kou)', kunyomi:'お-riru (oriru)', stroke_count:10, radical:'阝（kota）', jlpt:'N4', examples:[{word:'降りる',romaji:'oriru',meaning:'turun'},{word:'降る',romaji:'furu',meaning:'hujan'},{word:'以降',romaji:'ikou',meaning:'sejak'}] },
  '開': { type:'kanji', romaji:'kai / akeru', meaning:'membuka', onyomi:'カイ (kai)', kunyomi:'あ-ける (akeru)', stroke_count:12, radical:'門（gerbang）', jlpt:'N4', examples:[{word:'開ける',romaji:'akeru',meaning:'buka'},{word:'開始',romaji:'kaishi',meaning:'mulai'},{word:'公開',romaji:'koukai',meaning:'publik'}] },
  '閉': { type:'kanji', romaji:'hei / shimeru', meaning:'menutup', onyomi:'ヘイ (hei)', kunyomi:'し-める (shimeru)', stroke_count:11, radical:'門（gerbang）', jlpt:'N4', examples:[{word:'閉める',romaji:'shimeru',meaning:'tutup'},{word:'閉店',romaji:'heiten',meaning:'tutup toko'},{word:'閉鎖',romaji:'heisa',meaning:'tutup'}] },
  '発': { type:'kanji', romaji:'hatsu', meaning:'berangkat', onyomi:'ハツ (hatsu)', kunyomi:'-', stroke_count:9, radical:'癶（berjalan）', jlpt:'N4', examples:[{word:'出発',romaji:'shuppatsu',meaning:'berangkat'},{word:'発表',romaji:'happyou',meaning:'presentasi'},{word:'発見',romaji:'hakken',meaning:'penemuan'}] },
  '着': { type:'kanji', romaji:'chaku / tsuku', meaning:'tiba / pakai', onyomi:'チャク (chaku)', kunyomi:'き-る (kiru)', stroke_count:12, radical:'羊（domba）', jlpt:'N4', examples:[{word:'着く',romaji:'tsuku',meaning:'tiba'},{word:'着る',romaji:'kiru',meaning:'pakai'},{word:'到着',romaji:'touchaku',meaning:'sampai'}] },
  '走': { type:'kanji', romaji:'sou / hashiru', meaning:'berlari', onyomi:'ソウ (sou)', kunyomi:'はし-る (hashiru)', stroke_count:7, radical:'走（berlari）', jlpt:'N4', examples:[{word:'走る',romaji:'hashiru',meaning:'lari'},{word:'走行',romaji:'soukou',meaning:'melaju'},{word:'脱走',romaji:'dassou',meaning:'kabur'}] },
  '歩': { type:'kanji', romaji:'ho / aruku', meaning:'berjalan', onyomi:'ホ (ho)', kunyomi:'ある-く (aruku)', stroke_count:8, radical:'止（berhenti）', jlpt:'N4', examples:[{word:'歩く',romaji:'aruku',meaning:'jalan'},{word:'散歩',romaji:'sanpo',meaning:'jalan-jalan'},{word:'徒歩',romaji:'toho',meaning:'jalan kaki'}] },

  // 形容詞① (Adjective 1)
  '近': { type:'kanji', romaji:'kin / chikai', meaning:'dekat', onyomi:'キン (kin)', kunyomi:'ちか-い (chikai)', stroke_count:7, radical:'辶（berjalan）', jlpt:'N4', examples:[{word:'近い',romaji:'chikai',meaning:'dekat'},{word:'最近',romaji:'saikin',meaning:'baru ini'},{word:'近所',romaji:'kinjo',meaning:'tetangga'}] },
  '遠': { type:'kanji', romaji:'en / tooi', meaning:'jauh', onyomi:'エン (en)', kunyomi:'とお-い (tooi)', stroke_count:13, radical:'辶（berjalan）', jlpt:'N4', examples:[{word:'遠い',romaji:'tooi',meaning:'jauh'},{word:'遠足',romaji:'ensoku',meaning:'piknik'},{word:'永遠',romaji:'eien',meaning:'abadi'}] },
  '重': { type:'kanji', romaji:'juu / omoi', meaning:'berat', onyomi:'ジュウ (juu)', kunyomi:'おも-い (omoi)', stroke_count:9, radical:'里（desa）', jlpt:'N4', examples:[{word:'重い',romaji:'omoi',meaning:'berat'},{word:'重要',romaji:'juuyou',meaning:'penting'},{word:'体重',romaji:'taijuu',meaning:'berat badan'}] },
  '軽': { type:'kanji', romaji:'kei / karui', meaning:'ringan', onyomi:'ケイ (kei)', kunyomi:'かる-い (karui)', stroke_count:12, radical:'車（kendaraan）', jlpt:'N4', examples:[{word:'軽い',romaji:'karui',meaning:'ringan'},{word:'軽食',romaji:'keishoku',meaning:'camilan'},{word:'軽率',romaji:'keisotsu',meaning:'ceroboh'}] },
  '早': { type:'kanji', romaji:'sou / hayai', meaning:'pagi / cepat', onyomi:'ソウ (sou)', kunyomi:'はや-い (hayai)', stroke_count:6, radical:'日（matahari）', jlpt:'N4', examples:[{word:'早い',romaji:'hayai',meaning:'pagi'},{word:'早起き',romaji:'hayaoki',meaning:'bangun pagi'},{word:'早速',romaji:'sassoku',meaning:'segera'}] },
  '速': { type:'kanji', romaji:'soku / hayai', meaning:'cepat', onyomi:'ソク (soku)', kunyomi:'はや-い (hayai)', stroke_count:10, radical:'辶（berjalan）', jlpt:'N4', examples:[{word:'速い',romaji:'hayai',meaning:'cepat'},{word:'速度',romaji:'sokudo',meaning:'kecepatan'},{word:'高速',romaji:'kousoku',meaning:'cepat'}] },
  '遅': { type:'kanji', romaji:'chi / osoi', meaning:'lambat / telat', onyomi:'チ (chi)', kunyomi:'おそ-い (osoi)', stroke_count:12, radical:'辶（berjalan）', jlpt:'N4', examples:[{word:'遅い',romaji:'osoi',meaning:'lambat'},{word:'遅刻',romaji:'chikoku',meaning:'telat'},{word:'遅れる',romaji:'okureru',meaning:'terlambat'}] },
  '広': { type:'kanji', romaji:'kou / hiroi', meaning:'luas', onyomi:'コウ (kou)', kunyomi:'ひろ-い (hiroi)', stroke_count:5, radical:'广（atap lebar）', jlpt:'N4', examples:[{word:'広い',romaji:'hiroi',meaning:'luas'},{word:'広告',romaji:'koukoku',meaning:'iklan'},{word:'広場',romaji:'hiroba',meaning:'lapangan'}] },
  '細': { type:'kanji', romaji:'sai / hosoi', meaning:'tipis / halus', onyomi:'サイ (sai)', kunyomi:'ほそ-い (hosoi)', stroke_count:11, radical:'糸（benang）', jlpt:'N4', examples:[{word:'細い',romaji:'hosoi',meaning:'kurus'},{word:'詳細',romaji:'shousai',meaning:'detail'},{word:'細かい',romaji:'komakai',meaning:'rinci'}] },
  '太': { type:'kanji', romaji:'tai / futoi', meaning:'tebal / gemuk', onyomi:'タイ (tai)', kunyomi:'ふto-i (futoi)', stroke_count:4, radical:'大（besar）', jlpt:'N4', examples:[{word:'太い',romaji:'futoi',meaning:'gemuk'},{word:'太陽',romaji:'taiyou',meaning:'matahari'},{word:'太平洋',romaji:'taiheiyou',meaning:'Pasifik'}] },
  '暑': { type:'kanji', romaji:'sho / atsui', meaning:'panas (cuaca)', onyomi:'ショ (sho)', kunyomi:'あつ-い (atsui)', stroke_count:12, radical:'日（matahari）', jlpt:'N4', examples:[{word:'暑い',romaji:'atsui',meaning:'panas'},{word:'暑さ',romaji:'atsusa',meaning:'panas'},{word:'残暑',romaji:'zansho',meaning:'sisa panas'}] },
  '寒': { type:'kanji', romaji:'kan / samui', meaning:'dingin', onyomi:'カン (kan)', kunyomi:'さmu-i (samui)', stroke_count:12, radical:'宀（atap）', jlpt:'N4', examples:[{word:'寒い',romaji:'samui',meaning:'dingin'},{word:'寒波',romaji:'kanpa',meaning:'gelombang dingin'},{word:'厳寒',romaji:'genkan',meaning:'sangat dingin'}] },

  // 形容詞② (Adjective 2)
  '低': { type:'kanji', romaji:'tei / hikui', meaning:'rendah', onyomi:'テイ (tei)', kunyomi:'ひく-い (hikui)', stroke_count:7, radical:'人（manusia）', jlpt:'N4', examples:[{word:'低い',romaji:'hikui',meaning:'rendah'},{word:'最低',romaji:'saitei',meaning:'minimal'},{word:'低下',romaji:'teika',meaning:'turun'}] },
  '短': { type:'kanji', romaji:'tan / mijikai', meaning:'pendek', onyomi:'タン (tan)', kunyomi:'みじか-い (mijikai)', stroke_count:12, radical:'矢（panah）', jlpt:'N4', examples:[{word:'短い',romaji:'mijikai',meaning:'pendek'},{word:'短所',romaji:'tansho',meaning:'kelemahan'},{word:'短期',romaji:'tanki',meaning:'jangka pendek'}] },
  '弱': { type:'kanji', romaji:'jaku / yowai', meaning:'lemah', onyomi:'ジャク (jaku)', kunyomi:'よわ-い (yowai)', stroke_count:10, radical:'弓（busur）', jlpt:'N4', examples:[{word:'弱い',romaji:'yowai',meaning:'lemah'},{word:'弱点',romaji:'jakuten',meaning:'kelemahkan'},{word:'強弱',romaji:'kyoujaku',meaning:'kuat lemah'}] },
  '若': { type:'kanji', romaji:'jaku / wakai', meaning:'muda', onyomi:'ジャク (jaku)', kunyomi:'わか-い (wakai)', stroke_count:8, radical:'艹（tanaman）', jlpt:'N4', examples:[{word:'若い',romaji:'wakai',meaning:'muda'},{word:'若者',romaji:'wakamono',meaning:'anak muda'},{word:'若手',romaji:'wakate',meaning:'bakat muda'}] },
  '静': { type:'kanji', romaji:'sei / shizuka', meaning:'tenang', onyomi:'セイ (sei)', kunyomi:'しず-か (shizuka)', stroke_count:14, radical:'青（biru）', jlpt:'N4', examples:[{word:'静かな',romaji:'shizuka',meaning:'tenang'},{word:'安静',romaji:'ansei',meaning:'istirahat'},{word:'静止',romaji:'seishi',meaning:'berhenti'}] },
  '有': { type:'kanji', romaji:'yuu / aru', meaning:'ada / memiliki', onyomi:'ユウ (yuu)', kunyomi:'あ-る (aru)', stroke_count:6, radical:'月（bulan）', jlpt:'N4', examples:[{word:'有名',romaji:'yuumei',meaning:'terkenal'},{word:'有利',romaji:'yuuri',meaning:'untung'},{word:'有効',romaji:'yuukou',meaning:'efektif'}] },
  '心': { type:'kanji', romaji:'shin / kokoro', meaning:'hati', onyomi:'シン (shin)', kunyomi:'こころ (kokoro)', stroke_count:4, radical:'心（hati）', jlpt:'N4', examples:[{word:'心配',romaji:'shinpai',meaning:'khawatir'},{word:'中心',romaji:'chuushin',meaning:'pusat'},{word:'安心',romaji:'anshin',meaning:'tenang'}] },
  '同': { type:'kanji', romaji:'dou / onaji', meaning:'sama', onyomi:'ドウ (dou)', kunyomi:'おな-じ (onaji)', stroke_count:6, radical:'囗（kotak）', jlpt:'N4', examples:[{word:'同じ',romaji:'onaji',meaning:'sama'},{word:'同時',romaji:'douji',meaning:'bersamaan'},{word:'共同',romaji:'kyoudou',meaning:'bersama'}] },
  '便': { type:'kanji', romaji:'ben / bin', meaning:'nyaman / praktis', onyomi:'ベン (ben)', kunyomi:'たよ-り (tayori)', stroke_count:9, radical:'人（manusia）', jlpt:'N4', examples:[{word:'便利',romaji:'benri',meaning:'praktis'},{word:'不便',romaji:'fuben',meaning:'tidak nyaman'},{word:'便所',romaji:'benjo',meaning:'toilet'}] },
  '利': { type:'kanji', romaji:'ri', meaning:'keuntungan / berguna', onyomi:'リ (ri)', kunyomi:'き-く (kiku)', stroke_count:7, radical:'刀（pisau）', jlpt:'N4', examples:[{word:'便利',romaji:'benri',meaning:'nyaman'},{word:'利用',romaji:'riyou',meaning:'memanfaatkan'},{word:'有利',romaji:'yuuri',meaning:'untung'}] },
  '親': { type:'kanji', romaji:'shin / oya', meaning:'orang tua / akrab', onyomi:'シン (shin)', kunyomi:'おや (oya)', stroke_count:16, radical:'立（berdiri）', jlpt:'N4', examples:[{word:'親切',romaji:'shinsetsu',meaning:'ramah'},{word:'両親',romaji:'ryoushin',meaning:'orang tua'},{word:'親友',romaji:'shinyuu',meaning:'sahabat'}] },
  '切': { type:'kanji', romaji:'setsu / kiru', meaning:'memotong / sangat', onyomi:'セツ (setsu)', kunyomi:'き-る (kiru)', stroke_count:4, radical:'刀（pisau）', jlpt:'N4', examples:[{word:'切る',romaji:'kiru',meaning:'potong'},{word:'親切',romaji:'shinsetsu',meaning:'ramah'},{word:'大切',romaji:'taisetsu',meaning:'penting'}] },
  '不': { type:'kanji', romaji:'fu / bu', meaning:'tidak / bukan', onyomi:'フ (fu)', kunyomi:'-', stroke_count:4, radical:'一（satu）', jlpt:'N4', examples:[{word:'不便',romaji:'fuben',meaning:'tidak nyaman'},{word:'不安',romaji:'fuan',meaning:'gelisah'},{word:'不可能',romaji:'fukanou',meaning:'tidak mungkin'}] },
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
  const examplesHTML = data.examples && data.examples.length > 0 
    ? data.examples.map(ex => `
      <div class="kana-modal__example">
        <span class="kana-modal__example-jp">${ex.word}</span>
        <div class="kana-modal__example-sub">
          <span class="kana-modal__example-romaji">${ex.romaji}</span>
          <span class="kana-modal__example-meaning">${ex.meaning}</span>
        </div>
      </div>
    `).join('')
    : `<div style="color: var(--home-muted); font-size: 14px; font-style: italic; padding: 4px 0;">Contoh kata belum tersedia untuk huruf ini.</div>`;

  return `
    <div>
      <p class="kana-modal__section-label">Informasi Dasar</p>
      <div class="kana-modal__info-grid">
        <div class="kana-modal__info-card">
          <div class="kana-modal__info-card-label">Jumlah Stroke</div>
          <div class="kana-modal__info-card-value" id="kanaModalStrokeCountValue">${data.stroke_count > 0 ? data.stroke_count + ' goresan' : 'Dihitung dinamis dari stroke order'}</div>
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
        ${examplesHTML}
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
  let data = LETTER_DATA[char];
  if (!data) {
    // Dynamic fallback from DOM
    const card = document.querySelector(`.kana-card[data-char="${char}"]`);
    if (card) {
      const type = card.dataset.type;
      const romaji = card.dataset.romaji;
      data = {
        type: type,
        romaji: romaji,
        pronunciation: `Dibaca seperti "${romaji}"`,
        stroke_count: 0,
        examples: []
      };
    }
  }
  if (!data) return;

  // Pre-calculate stroke count for Yoon combination characters to avoid layout shift/flicker
  if (char.length >= 2 && data.stroke_count === 0) {
    const smallToRegular = {
      'ゃ': 'や', 'ゅ': 'ゆ', 'ょ': 'よ',
      'ャ': 'ヤ', 'ュ': 'ユ', 'ョ': 'ヨ'
    };
    const c1 = char[0];
    const c2 = char[1];
    const c1Stroke = LETTER_DATA[c1]?.stroke_count || 0;
    const c2Stroke = LETTER_DATA[smallToRegular[c2]]?.stroke_count || 0;
    data.stroke_count = c1Stroke + c2Stroke;
  }

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
  // Clear modal contents to prevent flickering old data when modal reopens
  modalSymbol.textContent = '';
  modalBadge.textContent = '';
  modalRomaji.textContent = '';
  modalPronunciation.textContent = '';
  modalBody.innerHTML = '';
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

// Helper: fetch SVG text for a single character (local then GitHub fallback)
function fetchCharSVG(c) {
  const code = c.charCodeAt(0);
  const hex = code.toString(16).padStart(5, '0');
  return fetch(`/kanjivg/${hex}.svg?v=2`)
    .then(res => { if (!res.ok) throw new Error(); return res.text(); })
    .catch(() => {
      const url = `https://raw.githubusercontent.com/KanjiVG/kanjivg/master/kanji/${hex}.svg`;
      return fetch(url).then(res => { if (!res.ok) throw new Error(); return res.text(); });
    });
}

// Helper: parse SVG text, apply transform, collect stroke paths & texts into arrays
function parseSVGStrokes(svgText, allPaths, allTexts, transform) {
  const parser = new DOMParser();
  const doc = parser.parseFromString(svgText, 'image/svg+xml');
  const svg = doc.querySelector('svg');
  if (!svg) return null;

  const pathsGroup = svg.querySelector('[id^="kvg:StrokePaths_"]');
  const numbersGroup = svg.querySelector('[id^="kvg:StrokeNumbers_"]');

  if (pathsGroup) {
    // Background outline clone
    const outline = pathsGroup.cloneNode(true);
    outline.removeAttribute('id');
    outline.querySelectorAll('path').forEach(p => {
      p.removeAttribute('id');
      p.style.fill = 'none';
      p.style.stroke = 'rgba(36,48,54,0.08)';
      p.style.strokeWidth = '3.5';
      p.style.strokeLinecap = 'round';
      p.style.strokeLinejoin = 'round';
    });
    if (transform) outline.setAttribute('transform', transform);
    svg.insertBefore(outline, pathsGroup);

    if (transform) pathsGroup.setAttribute('transform', transform);

    Array.from(pathsGroup.querySelectorAll('path')).forEach(p => {
      p.style.fill = 'none';
      p.style.stroke = '#243036';
      p.style.strokeWidth = '4.5';
      p.style.strokeLinecap = 'round';
      p.style.strokeLinejoin = 'round';
      // strokeDasharray will be set after appending to DOM (needs layout)
      allPaths.push(p);
    });
  }

  if (numbersGroup) {
    if (transform) numbersGroup.setAttribute('transform', transform);
    Array.from(numbersGroup.querySelectorAll('text')).forEach(t => {
      t.style.fill = 'var(--home-coral)';
      t.style.fontSize = '8px';
      t.style.fontWeight = 'bold';
      t.style.display = 'none';
      allTexts.push(t);
    });
  }

  return svg;
}

function initKanaWriter(char) {
  const target = document.getElementById('kanaWriterTarget');
  if (!target) return;
  target.innerHTML = '';
  showWriterStatus('Memuat...');
  cancelAnimation();
  strokePaths = [];
  strokeTexts = [];

  if (char.length >= 2) {
    // ── Yōon: two characters side-by-side ──
    const c1 = char[0]; // main character (big)
    const c2 = char[1]; // small ya/yu/yo

    Promise.allSettled([fetchCharSVG(c1), fetchCharSVG(c2)])
      .then(results => {
        hideWriterStatus();

        // Build a composite SVG by embedding each character's inner SVG groups
        // using nested <svg> elements – this is the most reliable cross-browser approach.
        // Each character gets its own <svg> viewport inside the outer container.

        function extractGroups(svgText) {
          // Returns { pathsGroupHTML, numbersGroupHTML } or null
          if (!svgText) return null;
          const parser = new DOMParser();
          const doc = parser.parseFromString(svgText, 'image/svg+xml');
          const srcSvg = doc.querySelector('svg');
          if (!srcSvg) return null;
          const pg = srcSvg.querySelector('[id^="kvg:StrokePaths_"]');
          const ng = srcSvg.querySelector('[id^="kvg:StrokeNumbers_"]');
          return {
            pathsGroup: pg ? pg.cloneNode(true) : null,
            numbersGroup: ng ? ng.cloneNode(true) : null
          };
        }

        const r1 = results[0].status === 'fulfilled' ? extractGroups(results[0].value) : null;
        const r2 = results[1].status === 'fulfilled' ? extractGroups(results[1].value) : null;

        // Outer wrapper SVG  – viewBox spans two character slots
        // Slot 1 (big char):  x=0,   y=0,   w=120, h=109  (native KanjiVG 109×109, slightly cropped)
        // Slot 2 (small char): x=118, y=38,  w=74,  h=71   (≈ 65% size, bottom-right)
        const outerSVG = document.createElementNS('http://www.w3.org/2000/svg', 'svg');
        outerSVG.setAttribute('viewBox', '0 0 192 109');
        outerSVG.setAttribute('width', '100%');
        outerSVG.setAttribute('height', '100%');
        outerSVG.style.display = 'block';

        function addChar(groups, svgX, svgY, svgW, svgH, fontSize, fallbackChar, isSmall) {
          // Each character lives in its own nested <svg> with its own viewBox
          const nested = document.createElementNS('http://www.w3.org/2000/svg', 'svg');
          nested.setAttribute('x', svgX);
          nested.setAttribute('y', svgY);
          nested.setAttribute('width', svgW);
          nested.setAttribute('height', svgH);
          nested.setAttribute('viewBox', '0 0 109 109');
          nested.setAttribute('overflow', 'visible');

          if (groups && groups.pathsGroup) {
            // Ghost outline clone
            const outline = groups.pathsGroup.cloneNode(true);
            outline.removeAttribute('id');
            outline.querySelectorAll('path').forEach(p => {
              p.removeAttribute('id');
              p.style.cssText = 'fill:none;stroke:rgba(36,48,54,0.10);stroke-width:3;stroke-linecap:round;stroke-linejoin:round;';
            });
            nested.appendChild(outline);

            // Animated copy
            const animated = groups.pathsGroup.cloneNode(true);
            animated.querySelectorAll('path').forEach(p => {
              p.style.cssText = 'fill:none;stroke:#243036;stroke-width:4;stroke-linecap:round;stroke-linejoin:round;';
            });
            nested.appendChild(animated);
          } else {
            // Fallback: text glyph
            const t = document.createElementNS('http://www.w3.org/2000/svg', 'text');
            t.textContent = fallbackChar;
            t.setAttribute('x', '54'); t.setAttribute('y', '78');
            t.setAttribute('text-anchor', 'middle');
            t.style.cssText = `font-size:${fontSize}px;fill:#243036;font-family:serif;`;
            nested.appendChild(t);
          }

          if (groups && groups.numbersGroup) {
            const nums = groups.numbersGroup.cloneNode(true);
            nums.querySelectorAll('text').forEach(t => {
              t.style.fill = 'var(--home-coral)';
              t.style.fontSize = isSmall ? '14px' : '8px';
              t.style.fontWeight = 'bold';
              t.style.display = 'none';
            });
            nested.appendChild(nums);
          }

          outerSVG.appendChild(nested);
        }

        // Big character: fills left portion
        addChar(r1, 0, 0, 118, 109, 80, c1, false);
        // Small character: smaller, bottom-right
        addChar(r2, 116, 36, 76, 73, 48, c2, true);

        target.appendChild(outerSVG);

        // Collect animated paths AFTER appended to live DOM
        // (getTotalLength requires layout – nested SVGs guarantee this)
        strokePaths = Array.from(outerSVG.querySelectorAll('g[id^="kvg:StrokePaths_"] path'));
        strokeTexts  = Array.from(outerSVG.querySelectorAll('g[id^="kvg:StrokeNumbers_"] text'));

        strokePaths.forEach(p => {
          const length = p.getTotalLength();
          p.style.strokeDasharray = length;
          p.style.strokeDashoffset = length;
        });

        const strokeCountEl = document.getElementById('kanaModalStrokeCountValue');
        if (strokeCountEl) strokeCountEl.textContent = `${strokePaths.length} goresan`;

        animateCharacter();
      })
      .catch(() => showWriterStatus('Data stroke order tidak tersedia.', true));


  } else {

    // ── Single character ──
    fetchCharSVG(char)
      .then(svgText => {
        hideWriterStatus();
        // Extract ONLY the <svg>...</svg> block to avoid any stray characters from
        // XML declarations (<?xml?>), DOCTYPE internal subsets (]>), comments, etc.
        // KanjiVG files may contain DOCTYPE with internal subset like [...]\n]>
        // which the HTML parser cannot handle cleanly — safest fix is to ignore everything outside <svg>.
        const svgMatch = svgText.match(/<svg[\s\S]*<\/svg>/i);
        const cleanSvg = svgMatch ? svgMatch[0] : svgText;
        target.innerHTML = cleanSvg;


        const svg = target.querySelector('svg');
        if (!svg) { showWriterStatus('Gagal memuat format gambar.', true); return; }

        svg.setAttribute('width', '100%');
        svg.setAttribute('height', '100%');
        svg.style.display = 'block';

        const pathsGroup = svg.querySelector('[id^="kvg:StrokePaths_"]');
        if (pathsGroup) {
          const outlineGroup = pathsGroup.cloneNode(true);
          outlineGroup.id = 'kvg:StrokeOutline';
          outlineGroup.querySelectorAll('path').forEach(p => {
            p.removeAttribute('id');
            p.style.fill = 'none';
            p.style.stroke = 'rgba(36,48,54,0.08)';
            p.style.strokeWidth = '3.5';
            p.style.strokeLinecap = 'round';
            p.style.strokeLinejoin = 'round';
          });
          pathsGroup.parentNode.insertBefore(outlineGroup, pathsGroup);

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
        } else { strokePaths = []; }

        const numbersGroup = svg.querySelector('[id^="kvg:StrokeNumbers_"]');
        if (numbersGroup) {
          strokeTexts = Array.from(numbersGroup.querySelectorAll('text'));
          strokeTexts.forEach(t => {
            t.style.fill = 'var(--home-coral)';
            t.style.fontSize = '8px';
            t.style.fontWeight = 'bold';
            t.style.display = 'none';
          });
        } else { strokeTexts = []; }

        const strokeCountEl = document.getElementById('kanaModalStrokeCountValue');
        if (strokeCountEl && strokePaths.length > 0) {
          strokeCountEl.textContent = `${strokePaths.length} goresan`;
        }

        animateCharacter();
      })
      .catch(() => showWriterStatus('Data stroke order tidak tersedia.', true));
  }
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
