import json

# This script merges base data with Indonesian translations, radicals, and examples
# to generate the PHP array block and JS LETTER_DATA block for N3 Kanji.

groups_data = [
  {
    "title": "Waktu (Time)",
    "letters": [
      ("曜", "you", "hari (dalam seminggu)", "日（matahari）", [("日曜日", "nichiyoubi", "hari Minggu"), ("月曜日", "getsuyoubi", "hari Senin")]),
      ("末", "matsu", "akhir", "木（pohon）", [("週末", "shuumatsu", "akhir pekan"), ("年末", "nenmatsu", "akhir tahun")]),
      ("昨", "saku", "kemarin / lalu", "日（matahari）", [("昨日", "kinou", "kemarin"), ("昨年", "sakunen", "tahun lalu")]),
      ("翌", "yoku", "berikutnya", "羽（bulu/sayap）", [("翌日", "yokujitsu", "hari berikutnya"), ("翌年", "yokunen", "tahun berikutnya")]),
      ("現", "gen", "muncul / nyata / sekarang", "玉（permata）", [("現代", "gendai", "era modern"), ("現金", "genkin", "uang tunai")]),
      ("昔", "mukashi", "kuno / masa lalu", "日（matahari）", [("昔話", "mukashibanashi", "dongeng lama"), ("大昔", "oo-mukashi", "zaman dahulu kala")]),
      ("次", "tsugi", "berikutnya / setelah", "欠（kurang）", [("次回", "jikai", "lain kali"), ("目次", "mokuji", "daftar isi")]),
      ("回", "kai", "kali / berputar", "囗（kotak/keliling）", [("今回", "konkai", "kali ini"), ("一回", "ikkai", "sekali")]),
      ("再", "sai", "lagi / kedua kali", "冂（kotak atas）", [("再来週", "saraishuu", "dua minggu lagi"), ("再生", "saisei", "daur ulang / putar ulang")]),
      ("旧", "kyuu", "lama / bekas", "日（matahari）", [("旧館", "kyuukan", "gedung lama"), ("旧姓", "kyuusei", "nama gadis")]),
      ("秒", "byou", "detik", "禾（padi）", [("毎秒", "maibyou", "setiap detik"), ("数秒", "suubyou", "beberapa detik")])
    ]
  },
  {
    "title": "Orang (People)",
    "letters": [
      ("彼", "kare", "dia (laki-laki) / pacar", "彳（jalan perlahan）", [("彼氏", "kareshia", "pacar laki-laki"), ("彼女", "kanojo", "dia (perempuan) / pacar")]),
      ("君", "kimi", "kamu / penguasa / gelar kehormatan", "口（mulut）", [("君", "kimi", "kamu (informal)"), ("～君", "kun", "gelar panggilan pria muda")]),
      ("仲", "naka", "hubungan / perantara", "人（manusia）", [("仲間", "nakama", "teman/rekan"), ("仲良し", "nakayoshi", "berteman baik")]),
      ("達", "tachi", "mencapai / jamak (mereka)", "辶（berjalan）", [("友達", "tomodachi", "teman"), ("配達", "haitatsu", "pengiriman")]),
      ("供", "tomo", "menawarkan / menemani / jamak", "人（manusia）", [("子供", "kodomo", "anak-anak"), ("提供", "teikyou", "penawaran/penyediaan")]),
      ("娘", "musume", "anak perempuan / gadis", "女（perempuan）", [("娘", "musume", "anak perempuan sendiri"), ("一人娘", "hitorimusume", "anak perempuan tunggal")]),
      ("婦", "fu", "wanita / istri", "女（perempuan）", [("主婦", "shufu", "ibu rumah tangga"), ("婦人", "fujin", "wanita/nyonya")]),
      ("老", "rou", "tua / lanjut usia", "老（tua）", [("老人", "roujin", "orang tua/lansia"), ("老後", "rougo", "hari tua")]),
      ("他", "hoka", "yang lain / selain itu", "人（manusia）", [("他人", "tanin", "orang lain/orang asing"), ("その他", "sonota", "yang lainnya")])
    ]
  },
  {
    "title": "Tubuh (Body)",
    "letters": [
      ("頭", "atama", "kepala", "頁（halaman/kepala）", [("頭痛", "zutsuu", "sakit kepala"), ("頭がいい", "atama ga ii", "pintar/cerdas")]),
      ("顔", "kao", "wajah", "頁（halaman/kepala）", [("笑顔", "egao", "wajah tersenyum"), ("顔色", "kaoiro", "rona wajah / ekspresi")]),
      ("鼻", "hana", "hidung", "鼻（hidung）", [("鼻水", "hanamizu", "ingus"), ("鼻血", "hanaji", "mimisan")]),
      ("首", "kubi", "leher / kepala / pemimpin", "首（leher）", [("手首", "tekubi", "pergelangan tangan"), ("首都", "shuto", "ibu kota")]),
      ("血", "chi", "darah", "血（darah）", [("血液", "ketsueki", "darah (medis)"), ("鼻血", "hanaji", "mimisan")]),
      ("液", "eki", "cairan / kuah", "水（air）", [("液体", "ekitai", "zat cair"), ("血液", "ketsueki", "darah")]),
      ("歯", "ha", "gigi", "歯（gigi）", [("歯医者", "haisha", "dokter gigi"), ("虫歯", "mushiba", "gigi berlubang")]),
      ("型", "kata", "tipe / model / cetakan", "土（tanah）", [("新型", "shingata", "model baru"), ("髪型", "kamigata", "gaya rambut")])
    ]
  },
  {
    "title": "Makanan (Food)",
    "letters": [
      ("氷", "koori", "es", "水（air）", [("氷水", "koorimizu", "air es"), ("かき氷", "kakigouri", "es serut")]),
      ("湯", "yu", "air panas", "水（air）", [("お湯", "oyu", "air panas"), ("銭湯", "sentou", "pemandian umum")]),
      ("卵", "tamago", "telur", "卩（segel/telur）", [("卵黄", "ranou", "kuning telur"), ("ゆで卵", "yudetamago", "telur rebus")]),
      ("豆", "mame", "kacang", "豆（kacang/wadah）", [("豆腐", "toufu", "tahu"), ("納豆", "nattou", "natto (kedelai fermentasi)")]),
      ("粉", "kona", "tepung / bubuk", "米（beras）", [("小麦粉", "komugiko", "tepung terigu"), ("花粉", "kafun", "serbuk sari")]),
      ("乳", "nyuu", "susu / payudara", "乙（kedua/melengkung）", [("牛乳", "gyuunyuu", "susu sapi"), ("乳製品", "nyuuseihin", "produk susu")]),
      ("塩", "shio", "garam", "土（tanah）", [("食塩", "shokuen", "garam dapur"), ("塩分", "enbun", "kadar garam")]),
      ("調", "shiraberu", "menyelidiki / mengatur / nada", "言（kata-kata）", [("調べる", "shiraberu", "menyelidiki"), ("調査", "chousa", "survei/investigasi")]),
      ("和", "wa", "harmoni / kedamaian / gaya Jepang", "口（mulut）", [("平和", "heiwa", "perdamaian"), ("和食", "washoku", "makanan Jepang")])
    ]
  },
  {
    "title": "Kata Sifat 1 (Adjective 1)",
    "letters": [
      ("熱", "netsu", "panas (benda/demam)", "火（api）", [("熱い", "atsui", "panas (benda)"), ("高熱", "kounetsu", "demam tinggi")]),
      ("冷", "tsumetai", "dingin (benda) / mendinginkan", "冫（es）", [("冷たい", "tsumetai", "dingin (benda)"), ("冷蔵庫", "reizouko", "kulkas")]),
      ("暖", "atatakai", "hangat (cuaca)", "日（matahari）", [("暖かい", "atatakai", "hangat (cuaca)"), ("暖房", "danbou", "pemanas ruangan")]),
      ("温", "on", "hangat (benda/air)", "水（air）", [("温かい", "atatakai", "hangat (benda)"), ("温泉", "onsen", "pemandian air panas")]),
      ("良", "ii", "baik / bagus", "艮（berhenti）", [("良い", "yoi/ii", "baik"), ("良心", "ryoushin", "hati nurani")]),
      ("悪", "warui", "buruk / jahat", "心（hati）", [("悪い", "warui", "buruk/salah"), ("悪魔", "akuma", "iblis/setan")]),
      ("厚", "atsui", "tebal / ramah", "厂（tebing）", [("厚い", "atsui", "tebal"), ("濃厚", "noukou", "pekat/kental")])
    ]
  },
  {
    "title": "Kata Sifat 2 (Adjective 2)",
    "letters": [
      ("易", "yasashii", "mudah / gampang / ramah", "日（matahari）", [("易しい", "yasashii", "mudah"), ("容易", "youi", "gampang/mudah")]),
      ("難", "muzukashii", "sulit / sukar / bencana", "隹（burung ekor pendek）", [("難しい", "muzukashii", "sulit"), ("困難", "konnan", "kesulitan")]),
      ("深", "fukai", "dalam / mendalam", "水（air）", [("深い", "fukai", "dalam"), ("深夜", "shinya", "tengah malam")]),
      ("浅", "asai", "dangkal / cetek", "水（air）", [("浅い", "asai", "dangkal"), ("浅草", "asakusa", "Asakusa (tempat di Tokyo)")]),
      ("痛", "itai", "sakit / nyeri / pedih", "疒（penyakit）", [("痛い", "itai", "sakit"), ("頭痛", "zutsuu", "sakit kepala")]),
      ("苦", "kurushii", "pahit / menderita / susah", "艸（rumput）", [("苦い", "nigai", "pahit"), ("苦しい", "kurushii", "menderita/sesak")]),
      ("欲", "hoshii", "ingin / mau / nafsu", "欠（kurang）", [("欲しい", "hoshii", "ingin"), ("欲望", "yokubou", "hasrat/nafsu")])
    ]
  },
  {
    "title": "Kata Kerja 1 (Verbs 1)",
    "letters": [
      ("捨", "suteru", "membuang", "手（tangan）", [("捨てる", "suteru", "membuang"), ("使い捨て", "tsukaisute", "sekali pakai")]),
      ("拾", "hirou", "memungut / mengambil", "手（tangan）", [("拾う", "hirou", "memungut"), ("拾得物", "shuutokubutsu", "barang temuan")]),
      ("貸", "kasu", "meminjamkan", "貝（kerang/uang）", [("貸す", "kasu", "meminjamkan"), ("賃貸", "chintai", "sewa/penyewaan")]),
      ("借", "kariru", "meminjam", "人（manusia）", [("借りる", "kariru", "meminjam"), ("借金", "shakkin", "utang")]),
      ("覚", "oboeru", "mengingat / sadar", "見（melihat）", [("覚える", "oboeru", "mengingat/menghafal"), ("目覚まし", "mezamashi", "alarm bangun tidur")]),
      ("忘", "wasureru", "melupakan", "心（hati）", [("忘れる", "wasureru", "melupakan/tertinggal"), ("忘れ物", "wasuremono", "barang tertinggal")]),
      ("押", "osu", "menekan / mendorong", "手（tangan）", [("押す", "osu", "menekan/mendorong"), ("押し入れ", "oshiire", "lemari dinding")]),
      ("引", "hiku", "menarik / meregangkan", "弓（busur panah）", [("引く", "hiku", "menarik/mengurangi"), ("引き出し", "hikidashi", "laci")])
    ]
  },
  {
    "title": "Kata Kerja 2 (Verbs 2)",
    "letters": [
      ("打", "utsu", "memukul / mengetik", "手（tangan）", [("打つ", "utsu", "memukul"), ("打ち合わせ", "uchiawase", "pertemuan/rapat")]),
      ("投", "nageru", "melempar / menginvestasikan", "手（tangan）", [("投げる", "nageru", "melempar"), ("投手", "toushu", "pitcher (pelempar bola)")]),
      ("信", "shinjiru", "percaya / iman / sinyal", "人（manusia）", [("信じる", "shinjiru", "percaya"), ("信号", "shingou", "lampu lalu lintas")]),
      ("伝", "tsutaeru", "menyampaikan / menularkan", "人（manusia）", [("伝える", "tsutaeru", "menyampaikan"), ("伝説", "densetsu", "legenda")]),
      ("想", "omou", "berpikir / membayangkan / konsep", "心（hati）", [("想像", "souzou", "imajinasi"), ("感想", "kansou", "kesan/opini")]),
      ("続", "tsuzuku", "melanjutkan / terus", "糸（benang）", [("続く", "tsuzuku", "berlanjut"), ("続ける", "tsuzukeru", "melanjutkan")]),
      ("祝", "iwau", "merayakan / menyelamati", "示（menunjukkan/altar）", [("祝う", "iwau", "merayakan"), ("お祝い", "oiwai", "perayaan/hadiah selamat")]),
      ("願", "negau", "memohon / meminta / keinginan", "頁（halaman/kepala）", [("願う", "negau", "memohon/meminta"), ("お願い", "onegai", "permohonan/tolong")]),
      ("酔", "you", "mabuk / pusing perjalanan", "酉（botol anggur）", [("酔う", "you", "mabuk/pusing"), ("二日酔い", "futsukayoi", "hangover (sakit setelah mabuk)")])
    ]
  },
  {
    "title": "Kehidupan Sehari-hari 1 (Daily Life 1)",
    "letters": [
      ("起", "okiru", "bangun / terjadi", "走（berlari）", [("起きる", "okiru", "bangun tidur / terjadi"), ("起こす", "okosu", "membangunkan")]),
      ("寝", "neru", "tidur", "宀（atap）", [("寝る", "neru", "tidur"), ("昼寝", "hirune", "tidur siang")]),
      ("洗", "arau", "mencuci", "水（air）", [("洗う", "arau", "mencuci"), ("洗面所", "senmenjo", "tempat cuci muka")]),
      ("濯", "susugu", "membilas / mencuci pakaian", "水（air）", [("洗濯", "sentaku", "cucian pakaian"), ("洗濯機", "sentakuki", "mesin cuci")]),
      ("干", "hosu", "menjemur / mengeringkan", "干（kering）", [("干す", "hosu", "menjemur/mengeringkan"), ("梅干し", "umeboshi", "asinan plum kering")]),
      ("浴", "abiru", "mandi / menyiram", "水（air）", [("浴びる", "abiru", "mandi (menyiram)"), ("浴室", "yokushitsu", "kamar mandi")])
    ]
  },
  {
    "title": "Kehidupan Sehari-hari 2 (Daily Life 2)",
    "letters": [
      ("活", "katsu", "hidup / aktivitas / energi", "水（air）", [("生活", "seikatsu", "kehidupan sehari-hari"), ("活動", "katsudou", "aktivitas")]),
      ("夢", "yume", "mimpi", "夕（malam/sore）", [("夢", "yume", "mimpi"), ("夢中", "muchuu", "tergila-gila / asyik")]),
      ("貯", "tameru", "menabung / menyimpan", "貝（kerang/uang）", [("貯金", "chokin", "tabungan uang"), ("貯蔵", "chozou", "penyimpanan/penimbunan")]),
      ("列", "retsu", "baris / antrean", "刀（pedang）", [("列車", "ressha", "kereta api"), ("行列", "gyouretsu", "antrean panjang")]),
      ("袋", "fukuro", "kantong / karung / tas", "衣（pakaian）", [("紙袋", "kamibukuro", "kantong kertas"), ("手袋", "tebukuro", "sarung tangan")]),
      ("久", "hisashiburi", "lama (durasi waktu)", "丿（garis melengkung）", [("久しぶり", "hisashiburi", "lama tidak bertemu"), ("永久", "eikyuu", "selamanya/kekal")])
    ]
  },
  {
    "title": "Perasaan 1 (Feeling 1)",
    "letters": [
      ("喜", "yorokobu", "senang / gembira / sukacita", "口（mulut）", [("喜ぶ", "yorokobu", "gembira/senang"), ("喜び", "yorokobi", "kegembiraan")]),
      ("怒", "okoru", "marah / murka", "心（hati）", [("怒る", "okoru", "marah"), ("怒り", "ikari", "kemarahan")]),
      ("笑", "warau", "tertawa / tersenyum", "竹（bambu）", [("笑う", "warau", "tertawa/tersenyum"), ("笑い話", "waraibanashi", "cerita lucu")]),
      ("泣", "naku", "menangis", "水（air）", [("泣く", "naku", "menangis"), ("泣き言", "nakigoto", "keluhan")]),
      ("悲", "kanashii", "sedih / duka", "心（hati）", [("悲しい", "kanashii", "sedih"), ("悲劇", "higeki", "tragedi")]),
      ("涙", "namida", "air mata", "水（air）", [("涙", "namida", "air mata"), ("涙ぐむ", "namidagumu", "mata berkaca-kaca")]),
      ("幸", "shiawase", "bahagia / keberuntungan", "干（kering/hukuman）", [("幸せ", "shiawase", "bahagia"), ("幸運", "kouun", "keberuntungan")])
    ]
  },
  {
    "title": "Perasaan 2 (Feeling 2)",
    "letters": [
      ("感", "kan", "merasakan / perasaan", "心（hati）", [("感じる", "kanjiru", "merasakan"), ("感謝", "kansha", "terima kasih / apresiasi")]),
      ("情", "nasake", "perasaan / simpati / keadaan", "心（hati）", [("感情", "kanjou", "emosi/perasaan"), ("情報", "jouhou", "informasi")]),
      ("悩", "nayamu", "khawatir / cemas / menderita", "心（hati）", [("悩む", "nayamu", "khawatir/bingung"), ("悩み", "nayami", "masalah/kekhawatiran")]),
      ("困", "komaru", "kesusahan / bingung / terjepit", "囗（kotak）", [("困る", "komaru", "susah/bingung"), ("困難", "konnan", "kesulitan")]),
      ("残", "nokoru", "sisa / tertinggal / kejam", "歹（tulang/kematian）", [("残る", "nokoru", "tersisa"), ("残念", "zannen", "sayang sekali / kecewa")]),
      ("念", "nen", "pikiran / ingatan / keinginan", "心（hati）", [("残念", "zannen", "sayang sekali"), ("記念", "kinen", "peringatan/memori")])
    ]
  },
  {
    "title": "Hubungan (Relationship)",
    "letters": [
      ("関", "kakawaru", "gerbang / hubungan / melibatkan", "門（gerbang）", [("関係", "kankei", "hubungan"), ("関心", "kanshin", "minat/ketertarikan")]),
      ("係", "kakari", "petugas / hubungan / mengikat", "人（manusia）", [("係員", "kakariin", "petugas/staf"), ("関係", "kankei", "hubungan")]),
      ("結", "musubu", "mengikat / menghubungkan / menyimpulkan", "糸（benang）", [("結婚", "kekkon", "pernikahan"), ("結果", "kekka", "hasil")]),
      ("婚", "kon", "pernikahan", "女（perempuan）", [("結婚", "kekkon", "pernikahan"), ("婚約", "konyaku", "pertunangan")]),
      ("独", "hitori", "sendiri / lajang / Jerman", "犬（anjing/hewan）", [("独身", "dokushin", "lajang/single"), ("独立", "dokuritsu", "merdeka/mandiri")]),
      ("身", "mi", "tubuh / kedudukan / diri sendiri", "身（tubuh）", [("自身", "jishin", "diri sendiri"), ("出身", "shusshin", "asal daerah/almamater")]),
      ("恋", "koi", "cinta / asmara", "心（hati）", [("恋人", "koibito", "pacar"), ("恋愛", "renai", "asmara/cinta")]),
      ("愛", "ai", "cinta / kasih sayang", "心（hati）", [("愛する", "aisuru", "mencintai"), ("愛情", "aijou", "kasih sayang")]),
      ("永", "nagai", "selamanya / kekal / abadi", "水（air）", [("永遠", "eien", "keabadian"), ("永住", "eijuu", "tinggal menetap/permanen")]),
      ("福", "fuku", "keberuntungan / berkah", "示（menunjukkan/altar）", [("幸福", "koufuku", "kebahagiaan"), ("福祉", "fukushi", "kesejahteraan")])
    ]
  },
  {
    "title": "Rencana 1 (Plan 1)",
    "letters": [
      ("予", "arakajime", "sebelumnya / memesan", "亅（kait）", [("予定", "yotei", "rencana/jadwal"), ("予約", "yoyaku", "reservasi/janji")]),
      ("定", "sadameru", "menetapkan / pasti", "宀（atap）", [("予定", "yotei", "rencana"), ("決定", "kettei", "keputusan")]),
      ("用", "mochiiru", "menggunakan / urusan / keperluan", "用（menggunakan）", [("用意", "youi", "persiapan"), ("利用", "riyou", "penggunaan/pemanfaatan")]),
      ("事", "koto", "hal / urusan / kejadian", "亅（kait）", [("仕事", "shigoto", "pekerjaan"), ("事故事", "jiko", "kecelakaan")]),
      ("約", "yaku", "janji / sekitar / menyingkat", "糸（benang）", [("予約", "yoyaku", "reservasi"), ("約束", "yakusoku", "janji")]),
      ("束", "taba", "ikat / berkas / mengendalikan", "木（pohon）", [("約束", "yakusoku", "janji"), ("花束", "hanataba", "buket bunga")])
    ]
  },
  {
    "title": "Rencana 2 (Plan 2)",
    "letters": [
      ("必", "kanarazu", "pasti / mutlak", "心（hati）", [("必ず", "kanarazu", "pasti/tentu saja"), ("必要", "hitsuyou", "butuh/perlu")]),
      ("要", "iru", "membutuhkan / penting / pokok", "襾（penutup）", [("必要", "hitsuyou", "perlu"), ("重要", "juuyou", "penting")]),
      ("取", "toru", "mengambil / memperoleh", "又（tangan/lagi）", [("取る", "toru", "mengambil"), ("受け取る", "uketoru", "menerima")]),
      ("消", "kesu", "memadamkan / menghapus / lenyap", "水（air）", [("消す", "kesu", "memadamkan/menghapus"), ("消しゴム", "keshigomu", "penghapus")]),
      ("返", "kaesu", "mengembalikan / membalas", "辶（berjalan）", [("返す", "kaesu", "mengembalikan"), ("返事", "henji", "jawaban/balasan")]),
      ("守", "mamoru", "melindungi / mematuhi / menjaga", "宀（atap）", [("守る", "mamoru", "melindungi/mematuhi"), ("留守", "rusu", "tidak di rumah")])
    ]
  },
  {
    "title": "Satuan 1 (Amount 1)",
    "letters": [
      ("杯", "sakazuki", "cangkir / gelas (satuan)", "木（pohon）", [("乾杯", "kanpai", "tos/toast"), ("一杯", "ippai", "segelas/penuh")]),
      ("枚", "mai", "lembar (satuan kertas/baju)", "木（pohon）", [("一枚", "ichimai", "satu lembar"), ("枚数", "maisuu", "jumlah lembar")]),
      ("匹", "hiki", "ekor (satuan hewan kecil)", "匸（kotak tersembunyi）", [("一匹", "ippiki", "seekor"), ("数匹", "suuhiki", "beberapa ekor")]),
      ("冊", "satsu", "jilid / buku (satuan)", "冂（kotak atas/buku）", [("一冊", "issatsu", "satu jilid buku"), ("冊子", "sasshi", "brosur/pamflet")]),
      ("各", "kaku", "setiap / masing-masing", "口（mulut）", [("各自", "kakuji", "masing-masing orang"), ("各駅", "kakueki", "setiap stasiun")]),
      ("量", "ryou", "kuantitas / menimbang / mengukur", "里（kampung）", [("量る", "hakaru", "mengukur"), ("大量", "tairyou", "jumlah banyak/massal")]),
      ("最", "mottomo", "paling / ter-", "曰（berkata）", [("最近", "saikin", "akhir-akhir ini"), ("最初", "saisho", "pertama kali / awal")])
    ]
  },
  {
    "title": "Satuan 2 (Amount 2)",
    "letters": [
      ("単", "tan", "tunggal / sederhana", "十（sepuluh）", [("簡単", "kantan", "mudah/sederhana"), ("単語", "tango", "kosakata")]),
      ("複", "fuku", "ganda / majemuk", "衣（pakaian）", [("複数", "fukusuu", "jumlah ganda"), ("複雑", "fukuzatsu", "rumit/kompleks")]),
      ("全", "subete", "semua / seluruh", "入（masuk）", [("全部", "zenbu", "semua"), ("安全", "anzen", "aman")]),
      ("以", "i", "dengan cara / dari (batas)", "人（manusia）", [("以上", "ijou", "lebih dari/sekian"), ("以下", "ika", "kurang dari/berikut ini")]),
      ("未", "imada", "belum", "木（pohon）", [("未来", "mirai", "masa depan"), ("未満", "miman", "kurang dari (batas bawah)")]),
      ("満", "michiru", "penuh / memuaskan", "水（air）", [("満車", "mansha", "parkir penuh"), ("満足", "manzoku", "puas")]),
      ("無", "mu", "tidak ada / ketiadaan", "火（api）", [("無理", "muri", "mustahil/terlalu memaksakan"), ("無料", "muryou", "gratis")]),
      ("非", "hi", "bukan / kesalahan / negatif", "非（bukan）", [("非常に", "hijouni", "sangat/luar biasa"), ("非常口", "hijouguchi", "pintu darurat")])
    ]
  },
  {
    "title": "Alam (Nature)",
    "letters": [
      ("季", "ki", "musim", "子（anak）", [("季節", "kisetsu", "musim"), ("四季", "shiki", "empat musim")]),
      ("候", "kou", "musim / iklim / mencalonkan", "人（manusia）", [("気候", "kikou", "iklim"), ("候補", "kouho", "kandidat/calon")]),
      ("島", "shima", "pulau", "山（gunung）", [("島国", "shimaguni", "negara kepulauan"), ("半島", "hantou", "semenanjung")]),
      ("陸", "riku", "darat / daratan", "阜（bukit）", [("大陸", "tairiku", "benua"), ("着陸", "chakuriku", "pendaratan (pesawat)")]),
      ("河", "kawa", "sungai besar", "水（air）", [("河川", "kasen", "sungai-sungai"), ("運河", "unga", "kanal/terusan")]),
      ("流", "nagareru", "mengalir / gaya", "水（air）", [("流れる", "nagareru", "mengalir"), ("流行", "ryuukou", "tren/populer")]),
      ("陽", "you", "matahari / positif", "阜（bukit）", [("太陽", "taiyou", "matahari"), ("陽気", "youki", "ceria/periang")]),
      ("岸", "kishi", "pantai / tepi sungai / pantai", "山（gunung）", [("海岸", "kaigan", "pantai/pesisir"), ("川岸", "kawagishi", "tepi sungai")])
    ]
  },
  {
    "title": "Pertanian (Agriculture)",
    "letters": [
      ("農", "nou", "pertanian", "辰（naga）", [("農業", "nougyou", "pertanian"), ("農家", "nouka", "petani/keluarga petani")]),
      ("産", "umu", "menghasilkan / melahirkan", "生（hidup）", [("産業", "sangyou", "industri"), ("お土産", "omiyage", "oleh-oleh")]),
      ("果", "ka", "buah / hasil / mencapai", "木（pohon）", [("果物", "kudamono", "buah-buahan"), ("結果", "kekka", "hasil")]),
      ("実", "mi", "buah / kebenaran / kenyataan", "宀（atap）", [("実は", "jitsu wa", "sebenarnya"), ("実験", "jikken", "eksperimen/percobaan")]),
      ("葉", "ha", "daun", "艸（rumput）", [("葉書", "hagaki", "kartu pos"), ("言葉", "kotoba", "kata/bahasa")]),
      ("菜", "na", "sayuran / lauk", "艸（rumput）", [("野菜", "yasai", "sayuran"), ("菜食", "saishoku", "vegetarian")]),
      ("植", "ueru", "menanam / tanaman", "木（pohon）", [("植える", "ueru", "menanam"), ("植物", "shokubutsu", "tumbuhan/tanaman")]),
      ("根", "ne", "akar / dasar", "木（pohon）", [("根", "ne", "akar"), ("大根", "daikon", "lobak putih")])
    ]
  },
  {
    "title": "Arah (Direction)",
    "letters": [
      ("位", "kurai", "posisi / pangkat / sekitar", "人（manusia）", [("第一位", "dai-ichi-i", "juara pertama"), ("位置", "ichi", "posisi/lokasi")]),
      ("置", "oku", "meletakkan / menempatkan", "网（jaring）", [("置く", "oku", "meletakkan"), ("位置", "ichi", "posisi/lokasi")]),
      ("存", "son", "ada / eksis / sadar", "子（anak）", [("存在", "sonzai", "keberadaan/eksistensi"), ("ご存じ", "gozonji", "tahu (sopan)")]),
      ("在", "zai", "berada / terletak / tinggal", "土（tanah）", [("現在", "genzai", "saat ini/sekarang"), ("在住", "zaijuu", "tinggal menetap")]),
      ("積", "tsumoru", "menumpuk / mengumpulkan / luas", "禾（padi）", [("積もる", "tsumoru", "menumpuk (salju)"), ("積極的", "sekkyokuteki", "aktif/positif")]),
      ("央", "ou", "pusat / tengah", "大（besar）", [("中央", "chuuou", "pusat/tengah"), ("中央口", "chuuou guchi", "pintu keluar tengah")]),
      ("向", "mukau", "menghadap / menuju", "口（mulut）", [("向かう", "mukau", "menghadap/menuju"), ("方向", "houkou", "arah")]),
      ("底", "soko", "dasar / alas", "广（tebing/rumah）", [("海底", "kaitei", "dasar laut"), ("靴底", "kutsuzoko", "sol sepatu")])
    ]
  },
  {
    "title": "Bangunan (Building)",
    "letters": [
      ("建", "tateru", "membangun / mendirikan", "廴（berjalan panjang）", [("建てる", "tateru", "membangun"), ("建物", "tatemono", "bangunan")]),
      ("築", "kizuku", "membangun / konstruksi", "木（pohon）", [("新築", "shinchiku", "bangunan baru"), ("建築", "kenchiku", "arsitektur/konstruksi")]),
      ("構", "kamau", "membangun / struktur / memedulikan", "木（pohon）", [("構う", "kamau", "peduli/menghiraukan"), ("結構", "kekkou", "cukup / baiklah")]),
      ("造", "tsukuru", "membuat / memproduksi", "辶（berjalan）", [("造る", "tsukuru", "membuat (skala besar)"), ("製造", "seizou", "manufaktur/pembuatan")]),
      ("設", "moukeru", "mendirikan / menyusun / fasilitas", "言（kata-kata）", [("設計", "sekkei", "desain/perancangan"), ("建設", "kensetsu", "pembangunan/konstruksi")]),
      ("橋", "hashi", "jembatan", "木（pohon）", [("橋", "hashi", "jembatan"), ("歩道橋", "hodoukyou", "jembatan penyeberangan orang")])
    ]
  },
  {
    "title": "Di Dalam Rumah (Inside The House)",
    "letters": [
      ("窓", "mado", "jendela", "穴（lubang）", [("窓口", "madoguchi", "loket pelayanan"), ("窓", "mado", "jendela")]),
      ("戸", "to", "pintu kayu / rumah", "戸 (pintu)", [("神戸", "koube", "Kobe (nama kota)"), ("網戸", "amido", "pintu kawat kasa")]),
      ("階", "kai", "lantai / tangga / tingkat", "阜（bukit/kiri）", [("二階", "nikai", "lantai dua"), ("階段", "kaidan", "tangga")]),
      ("段", "dan", "tingkat / langkah / tangga", "殳（alat pukul）", [("階段", "kaidan", "tangga"), ("値段", "nedan", "harga")]),
      ("机", "tsukue", "meja", "木（pohon）", [("机", "tsukue", "meja tulis"), ("学習机", "gakushuuzuku", "meja belajar")]),
      ("柱", "hashira", "pilar / tiang", "木（pohon）", [("柱", "hashira", "pilar/tiang"), ("電柱", "denchuu", "tiang listrik")]),
      ("庫", "ko", "gudang / tempat penyimpanan", "广（tebing/rumah）", [("冷蔵庫", "reizouko", "kulkas"), ("車庫", "shako", "garasi mobil")])
    ]
  },
  {
    "title": "Berkendara 1 (Drive 1)",
    "letters": [
      ("角", "kado", "sudut / pojok / tanduk", "角 (tanduk)", [("角", "kado", "sudut/pojokan jalan"), ("角", "tsuno", "tanduk")]),
      ("坂", "saka", "lereng / tanjakan", "土（tanah）", [("坂", "saka", "lereng/tanjakan"), ("急坂", "kyuu-zaka", "tanjakan curam")]),
      ("折", "oru", "melipat / mematahkan / belok", "手（tangan）", [("折る", "oru", "melipat/mematahkan"), ("折り紙", "origami", "seni melipat kertas")]),
      ("曲", "magaru", "membelok / melengkung / lagu", "曰（berkata/kotak）", [("曲がる", "magaru", "belok"), ("曲", "kyoku", "lagu")]),
      ("路", "ro", "jalan / rute", "足（kaki）", [("道路", "douro", "jalan raya"), ("線路", "senro", "rel kereta api")]),
      ("側", "gawa", "sisi / sebelah", "人（manusia）", [("右側", "migigawa", "sisi kanan"), ("内側", "uchigawa", "bagian dalam")])
    ]
  },
  {
    "title": "Berkendara 2 (Drive 2)",
    "letters": [
      ("直", "naosu", "lurus / memperbaiki / langsung", "目（mata）", [("直す", "naosu", "memperbaiki"), ("直接", "chokusetsu", "langsung")]),
      ("線", "sen", "garis / rel / kabel", "糸（benang）", [("線", "sen", "garis"), ("新幹線", "shinkansen", "kereta cepat Jepang")]),
      ("逆", "gyaku", "terbalik / berlawanan / membangkang", "辶（berjalan）", [("逆", "gyaku", "terbalik/sebaliknya"), ("逆立ち", "sakadachi", "berdiri dengan tangan")]),
      ("進", "susumu", "maju / berkembang / meningkatkan", "辶（berjalan）", [("進む", "susumu", "maju/berkembang"), ("進学", "shingaku", "melanjutkan sekolah")]),
      ("禁", "kin", "melarang / larangan", "示（menunjukkan/altar）", [("禁止", "kinshi", "larangan"), ("禁煙", "kinen", "dilarang merokok")]),
      ("許", "yurusu", "mengizinkan / memaafkan", "言（kata-kata）", [("許す", "yurusu", "memaafkan/mengizinkan"), ("許可", "kyoka", "izin/persetujuan")])
    ]
  },
  {
    "title": "Pertemuan Pertama 1 (First Meeting 1)",
    "letters": [
      ("初", "hatsu", "pertama / awal", "刀（pedang/sisi kanan）", [("初めて", "hajimete", "pertama kali"), ("最初", "saisho", "awal/pertama")]),
      ("姓", "sei", "nama keluarga / marga", "女（perempuan）", [("同姓", "dousei", "marga yang sama"), ("旧姓", "kyuusei", "nama gadis")]),
      ("個", "ko", "individu / satuan barang kecil", "人（manusia）", [("個人", "kojin", "individu/pribadi"), ("一個", "ikko", "satu buah (satuan)")]),
      ("性", "sei", "gender / sifat / kualitas", "心（hati）", [("男性", "dansei", "pria"), ("可能性", "kanousei", "kemungkinan")]),
      ("変", "kaeru", "berubah / aneh / tidak biasa", "夕（malam/sore）", [("変わる", "kawaru", "berubah"), ("大変", "taihen", "sangat / berat")]),
      ("付", "tsukeru", "menempel / menyertakan / melampirkan", "人（manusia）", [("付ける", "tsukeru", "menempelkan/menyalakan"), ("片付け", "katazuke", "merapikan")])
    ]
  },
  {
    "title": "Pertemuan Pertama 2 (First Meeting 2)",
    "letters": [
      ("紹", "shou", "memperkenalkan / mewarisi", "糸（benang）", [("紹介", "shoukai", "perkenalan"), ("自己紹介", "jiko shoukai", "perkenalan diri")]),
      ("介", "kai", "perantara / merawat / kerang", "人（manusia/garis）", [("紹介", "shoukai", "perkenalan"), ("介護", "kaigo", "perawatan lansia/pasien")]),
      ("常", "tsune", "biasa / normal / selalu", "巾（handuk）", [("日常", "nichijou", "sehari-hari"), ("非常口", "hijouguchi", "pintu darurat")]),
      ("識", "shiki", "pengetahuan / kesadaran / mengenal", "言（kata-kata）", [("意識", "ishiki", "kesadaran"), ("知識", "chishiki", "pengetahuan")]),
      ("失", "ushinau", "kehilangan / salah / merugi", "大（besar/garis）", [("失う", "ushinau", "kehilangan"), ("失礼", "shitsurei", "tidak sopan / permisi")]),
      ("礼", "rei", "sopan santun / busur / salam", "示（menunjukkan/altar）", [("お礼", "orei", "terima kasih"), ("失礼", "shitsurei", "tidak sopan / permisi")])
    ]
  },
  {
    "title": "Sekolah 1 (School 1)",
    "letters": [
      ("授", "ju", "memberikan / mengajar / menganugerahi", "手（tangan）", [("授業", "jugyou", "pelajaran/kelas"), ("教授", "kyouju", "profesor")]),
      ("業", "gyou", "usaha / industri / pekerjaan / karma", "木（pohon/papan）", [("工業", "kougyou", "industri"), ("卒業", "sotsugyou", "kelulusan")]),
      ("欠", "ketsu", "kurang / absen / cacat", "欠（kurang）", [("欠席", "kesseki", "absen/tidak hadir"), ("欠点", "ketten", "kekurangan/cacat")]),
      ("席", "seki", "kursi / tempat duduk", "广（tebing/rumah）", [("席", "seki", "tempat duduk"), ("出席", "shusseki", "kehadiran")]),
      ("成", "sei", "menjadi / tumbuh / sukses", "戈（tombak）", [("成功", "seikou", "sukses"), ("成人", "seijin", "orang dewasa")]),
      ("績", "seki", "prestasi / hasil / memintal", "糸（benang）", [("成績", "seiseki", "nilai/prestasi akademis"), ("実績", "jisseki", "rekam jejak/pencapaian")])
    ]
  }
]

# Load base data
with open("d:/ppbjlms/ppbjlms/tools/n3_base_data.json", "r", encoding="utf-8") as f:
    base_data = json.load(f)

# 1. Generate PHP Block
php_output = []
for g in groups_data:
    php_letters = []
    for kanji_char, romaji, _, _, _ in g["letters"]:
        php_letters.append(f"['{kanji_char}','{romaji}']")
    php_letters_str = ",".join(php_letters)
    php_output.append(f"                [\n                    'title' => '{g['title']}',\n                    'letters' => [{php_letters_str}]\n                ]")

php_block = "        'KANJI_N3' => [\n            'label' => 'Kanji N3',\n            'helper' => '197 kanji JLPT N3',\n            'groups' => [\n" + ",\n".join(php_output) + "\n            ]\n        ],"

with open("d:/ppbjlms/ppbjlms/tools/n3_php_block.txt", "w", encoding="utf-8") as f:
    f.write(php_block)

# 2. Generate JS Block
js_output = []
for g in groups_data:
    for kanji_char, default_romaji, ind_meaning, radical, examples in g["letters"]:
        base = base_data.get(kanji_char, {})
        
        # Format Onyomi
        on_reads = base.get("on_readings", [])
        onyomi_str = ", ".join(on_reads) if on_reads else "-"
        # Let's map commonly used onyomi romaji representation as well
        # In base data:
        # 曜 -> ヨウ (you)
        # For simplicity, we can do: ヨウ (you) for N3 if we know it or keep as Katakana since JS modal shows the raw data
        # Wait, the existing template is: "イチ (ichi), イツ (itsu)"
        # Let's write a simple helper or just use Katakana representation and append romaji representation.
        # Wait, let's write a python converter to display katakana (romaji)
        
        def to_romaji(kana_list, is_on=True):
            # Simple approximate converter for Japanese Katakana/Hiragana to romaji
            hiragana_map = {
                'あ': 'a', 'い': 'i', 'う': 'u', 'え': 'e', 'お': 'o',
                'か': 'ka', 'き': 'ki', 'く': 'ku', 'け': 'ke', 'こ': 'ko',
                'さ': 'sa', 'し': 'shi', 'す': 'su', 'せ': 'se', 'そ': 'so',
                'た': 'ta', 'ち': 'chi', 'つ': 'tsu', 'て': 'te', 'と': 'to',
                'な': 'na', 'に': 'ni', 'ぬ': 'nu', 'ね': 'ne', 'の': 'no',
                'は': 'ha', 'ひ': 'hi', 'ふ': 'fu', 'へ': 'he', 'ほ': 'ho',
                'ま': 'ma', 'み': 'mi', 'む': 'mu', 'め': 'me', 'も': 'mo',
                'や': 'ya', 'ゆ': 'yu', 'よ': 'yo',
                'ら': 'ra', 'り': 'ri', 'る': 'ru', 'れ': 're', 'ろ': 'ro',
                'わ': 'wa', 'を': 'wo', 'ん': 'n',
                'が': 'ga', 'ぎ': 'gi', 'ぐ': 'gu', 'げ': 'ge', 'ご': 'go',
                'ざ': 'za', 'じ': 'ji', 'ず': 'zu', 'ぜ': 'ze', 'ぞ': 'zo',
                'だ': 'da', 'ぢ': 'ji', 'づ': 'zu', 'で': 'de', 'ど': 'do',
                'ば': 'ba', 'び': 'bi', 'ぶ': 'bu', 'べ': 'be', 'ぼ': 'bo',
                'ぱ': 'pa', 'ぴ': 'pi', 'ぷ': 'pu', 'ぺ': 'pe', 'ぽ': 'po',
                'きゃ': 'kya', 'きゅ': 'kyu', 'きょ': 'kyo',
                'しゃ': 'sha', 'しゅ': 'shu', 'しょ': 'sho',
                'ちゃ': 'cha', 'ちゅ': 'chu', 'ちょ': 'cho',
                'にゃ': 'nya', 'にゅ': 'nyu', 'にょ': 'nyo',
                'ひゃ': 'hya', 'ひゅ': 'hyu', 'ひょ': 'hyo',
                'みゃ': 'mya', 'みゅ': 'myu', 'みょ': 'myo',
                'りゃ': 'rya', 'りゅ': 'ryu', 'りょ': 'ryo',
                'ぎゃ': 'gya', 'ぎゅ': 'gyu', 'ぎょ': 'gyo',
                'じゃ': 'ja', 'じゅ': 'ju', 'じょ': 'jo',
                'びゃ': 'bya', 'びゅ': 'byu', 'びょ': 'byo',
                'ぴゃ': 'pya', 'ぴゅ': 'pyu', 'ぴょ': 'pyo'
            }
            katakana_map = {
                'ア': 'a', 'イ': 'i', 'ウ': 'u', 'エ': 'e', 'オ': 'o',
                'カ': 'ka', 'キ': 'ki', 'ク': 'ku', 'ケ': 'ke', 'コ': 'ko',
                'サ': 'sa', 'シ': 'shi', 'ス': 'su', 'セ': 'se', 'ソ': 'so',
                'タ': 'ta', 'チ': 'chi', 'ツ': 'tsu', 'テ': 'te', 'ト': 'to',
                'ナ': 'na', 'ニ': 'ni', 'ヌ': 'nu', 'ネ': 'ne', 'ノ': 'no',
                'ハ': 'ha', 'ヒ': 'hi', 'フ': 'fu', 'ヘ': 'he', 'ホ': 'ho',
                'マ': 'ma', 'ミ': 'mi', 'ム': 'mu', 'メ': 'me', 'モ': 'mo',
                'ヤ': 'ya', 'ユ': 'yu', 'ヨ': 'yo',
                'ラ': 'ra', 'リ': 'ri', 'ル': 'ru', 'レ': 're', 'ロ': 'ro',
                'ワ': 'wa', 'ヲ': 'wo', 'ン': 'n',
                'ガ': 'ga', 'ギ': 'gi', 'グ': 'gu', 'ゲ': 'ge', 'ゴ': 'go',
                'ザ': 'za', 'ジ': 'ji', 'ズ': 'zu', 'ゼ': 'ze', 'ゾ': 'zo',
                'ダ': 'da', 'ヂ': 'ji', 'ヅ': 'zu', 'デ': 'de', 'ド': 'do',
                'バ': 'ba', 'ビ': 'bi', 'ブ': 'bu', 'ベ': 'be', 'ボ': 'bo',
                'パ': 'pa', 'ピ': 'pi', 'プ': 'pu', 'ペ': 'pe', 'ポ': 'po',
                'キャ': 'kya', 'キュ': 'kyu', 'キョ': 'kyo',
                'シャ': 'sha', 'シュ': 'shu', 'ショ': 'sho',
                'チャ': 'cha', 'チュ': 'chu', 'チョ': 'cho',
                'ニャ': 'nya', 'ニュ': 'nyu', 'ニョ': 'nyo',
                'ヒゃ': 'hya', 'ヒュ': 'hyu', 'ヒョ': 'hyo',
                'ミャ': 'mya', 'ミュ': 'myu', 'ミョ': 'myo',
                'リャ': 'rya', 'リュ': 'ryu', 'リョ': 'ryo',
                'ギャ': 'gya', 'ギュ': 'gyu', 'ギョ': 'gyo',
                'ジャ': 'ja', 'ジュ': 'ju', 'ジョ': 'jo',
                'ビャ': 'bya', 'ビュ': 'byu', 'ビョ': 'byo',
                'ピャ': 'pya', 'ピュ': 'pyu', 'ピョ': 'pyo'
            }
            mapping = katakana_map if is_on else hiragana_map
            
            res = []
            for k in kana_list:
                # remove particles like -tsu or -ru
                clean = k.replace('-', '').replace('.', '')
                r_str = ""
                i = 0
                while i < len(clean):
                    if i + 2 <= len(clean) and clean[i:i+2] in mapping:
                        r_str += mapping[clean[i:i+2]]
                        i += 2
                    elif clean[i] in mapping:
                        r_str += mapping[clean[i]]
                        i += 1
                    elif clean[i] in ['っ', 'ッ']:
                        # sokuon: peek next char
                        i += 1
                        if i < len(clean):
                            next_char = clean[i]
                            next_romaji = mapping.get(next_char, "")
                            if next_romaji:
                                r_str += next_romaji[0]
                    elif clean[i] in ['ー', '〜']:
                        # long vowel
                        i += 1
                    else:
                        r_str += clean[i]
                        i += 1
                res.append(f"{k} ({r_str})")
            return ", ".join(res)

        onyomi_formatted = to_romaji(on_reads, is_on=True) if on_reads else "-"
        kun_reads = base.get("kun_readings", [])
        kunyomi_formatted = to_romaji(kun_reads, is_on=False) if kun_reads else "-"
        
        strokes = base.get("stroke_count", 0)
        
        # Build examples json/js array
        ex_list = []
        for word, w_romaji, w_mean in examples:
            ex_list.append(f"{{word:'{word}',romaji:'{w_romaji}',meaning:'{w_mean}'}}")
        ex_str = f"[{','.join(ex_list)}]"
        
        # JS structure line
        line = f"  '{kanji_char}': {{ type:'kanji', romaji:'{default_romaji}', meaning:'{ind_meaning}', onyomi:'{onyomi_formatted}', kunyomi:'{kunyomi_formatted}', stroke_count:{strokes}, radical:'{radical}', jlpt:'N3', examples:{ex_str} }},"
        js_output.append(line)

js_block = "\n".join(js_output)
with open("d:/ppbjlms/ppbjlms/tools/n3_js_block.txt", "w", encoding="utf-8") as f:
    f.write(js_block)

print("PHP and JS blocks generated successfully!")
