import urllib.request
import json
import urllib.parse
from concurrent.futures import ThreadPoolExecutor, as_completed

kanjis = [
    # Waktu (11)
    '曜', '末', '昨', '翌', '現', '昔', '次', '回', '再', '旧', '秒',
    # Orang (9)
    '彼', '君', '仲', '達', '供', '娘', '婦', '老', '他',
    # Tubuh (8)
    '頭', '顔', '鼻', '首', '血', '液', '歯', '型',
    # Makanan (9)
    '氷', '湯', '卵', '豆', '粉', '乳', '塩', '調', '和',
    # Kata Sifat 1 (7)
    '熱', '冷', '暖', '温', '良', '悪', '厚',
    # Kata Sifat 2 (7)
    '易', '難', '深', '浅', '痛', '苦', '欲',
    # Kata Kerja 1 (8)
    '捨', '拾', '貸', '借', '覚', '忘', '押', '引',
    # Kata Kerja 2 (9)
    '打', '投', '信', '伝', '想', '続', '祝', '願', '酔',
    # Kehidupan Sehari-hari 1 (6)
    '起', '寝', '洗', '濯', '干', '浴',
    # Kehidupan Sehari-hari 2 (6)
    '活', '夢', '貯', '列', '袋', '久',
    # Perasaan 1 (7)
    '喜', '怒', '笑', '泣', '悲', '涙', '幸',
    # Perasaan 2 (6)
    '感', '情', '悩', '困', '残', '念',
    # Hubungan (10)
    '関', '係', '結', '婚', '独', '身', '恋', '愛', '永', '福',
    # Rencana 1 (6)
    '予', '定', '用', '事', '約', '束',
    # Rencana 2 (6)
    '必', '要', '取', '消', '返', '守',
    # Satuan 1 (7)
    '杯', '枚', '匹', '冊', '各', '量', '最',
    # Satuan 2 (8)
    '単', '複', '全', '以', '未', '満', '無', '非',
    # Alam (8)
    '季', '候', '島', '陸', '河', '流', '陽', '岸',
    # Pertanian (8)
    '農', '産', '果', '実', '葉', '菜', '植', '根',
    # Arah (8)
    '位', '置', '存', '在', '積', '央', '向', '底',
    # Bangunan (6)
    '建', '築', '構', '造', '設', '橋',
    # Di Dalam Rumah (7)
    '窓', '戸', '階', '段', '机', '柱', '庫',
    # Berkendara 1 (6)
    '角', '坂', '折', '曲', '路', '側',
    # Berkendara 2 (6)
    '直', '線', '逆', '進', '禁', '許',
    # Pertemuan Pertama 1 (6)
    '初', '姓', '個', '性', '変', '付',
    # Pertemuan Pertama 2 (6)
    '紹', '介', '常', '識', '失', '礼',
    # Sekolah 1 (6)
    '授', '業', '欠', '席', '成', '績'
]

def fetch_kanji(k):
    url = f"https://kanjiapi.dev/v1/kanji/{urllib.parse.quote(k)}"
    try:
        req = urllib.request.Request(url, headers={'User-Agent': 'Mozilla/5.0'})
        with urllib.request.urlopen(req, timeout=10) as response:
            data = json.loads(response.read().decode('utf-8'))
            return k, data
    except Exception as e:
        return k, None

results = {}
with ThreadPoolExecutor(max_workers=15) as executor:
    future_to_kanji = {executor.submit(fetch_kanji, k): k for k in kanjis}
    for future in as_completed(future_to_kanji):
        k = future_to_kanji[future]
        try:
            k, data = future.result()
            if data:
                results[k] = data
        except Exception as exc:
            pass

with open("d:/ppbjlms/ppbjlms/tools/n3_base_data.json", "w", encoding="utf-8") as f:
    json.dump(results, f, ensure_ascii=False, indent=2)
