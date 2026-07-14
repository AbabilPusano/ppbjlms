<?php

namespace App\Http\Controllers;

use App\Models\Section;
use App\Models\Story;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class GuruStoryController extends Controller
{
    /**
     * Daftar semua cerita yang sudah ada (halaman index guru).
     */
    public function index(): View
    {
        $stories = Story::query()
            ->with('section')
            ->orderBy('order')
            ->get()
            ->groupBy('sectionId');

        $sections = Section::query()->orderByDesc('name')->get();

        return view('guru-stories-index-ui', compact('stories', 'sections'));
    }

    /**
     * Tampilkan form generator cerita AI.
     */
    public function create(): View
    {
        $sections = Section::query()->orderByDesc('name')->get();

        return view('guru-stories-create-ui', compact('sections'));
    }

    /**
     * Panggil Gemini API untuk generate cerita.
     * Dipanggil via AJAX dari halaman create.
     */
    public function generate(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'level'  => ['required', 'string', 'in:N5,N4,N3,N2,N1'],
            'theme'  => ['required', 'string', 'min:3', 'max:200'],
            'pages'  => ['sometimes', 'integer', 'min:2', 'max:10'],
        ]);

        $level     = $validated['level'];
        $theme     = $validated['theme'];
        $pageCount = $validated['pages'] ?? 5;

        $apiKey = config('services.gemini.key');

        if (! $apiKey) {
            return response()->json([
                'success' => false,
                'error'   => 'GEMINI_API_KEY belum dikonfigurasi di file .env.',
            ], 500);
        }

        $prompt = $this->buildPrompt($level, $theme, $pageCount);

        try {
            $response = Http::timeout(60)->post(
                "https://generativelanguage.googleapis.com/v1beta/models/gemini-3.1-flash-lite:generateContent?key={$apiKey}",
                [
                    'contents' => [
                        [
                            'parts' => [
                                ['text' => $prompt],
                            ],
                        ],
                    ],
                    'generationConfig' => [
                        'temperature'     => 0.7,
                        'responseMimeType' => 'application/json',
                    ],
                ]
            );

            if ($response->failed()) {
                Log::error('Gemini API error', [
                    'status' => $response->status(),
                    'body'   => $response->body(),
                ]);

                return response()->json([
                    'success' => false,
                    'error'   => 'Gagal menghubungi Gemini API. Cek API Key dan kuota kamu.',
                ], 502);
            }

            $raw  = $response->json('candidates.0.content.parts.0.text', '{}');
            $data = json_decode($raw, true);

            if (! isset($data['title'], $data['pages'])) {
                return response()->json([
                    'success' => false,
                    'error'   => 'Format jawaban dari AI tidak sesuai. Coba lagi.',
                ], 422);
            }

            return response()->json([
                'success' => true,
                'story'   => $data,
            ]);
        } catch (\Throwable $e) {
            Log::error('Gemini generate exception', ['message' => $e->getMessage()]);

            return response()->json([
                'success' => false,
                'error'   => 'Terjadi kesalahan: '.$e->getMessage(),
            ], 500);
        }
    }

    /**
     * Simpan cerita yang sudah di-review guru ke database.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'section_id' => ['required', 'string', 'exists:sections,id'],
            'title'      => ['required', 'string', 'max:255'],
            'pages'      => ['required', 'array', 'min:1'],
            'pages.*.japanese'    => ['required', 'string'],
            'pages.*.romaji'      => ['required', 'string'],
            'pages.*.translation' => ['required', 'string'],
            'pages.*.words'       => ['sometimes', 'array'],
            'pages.*.words.*.word'    => ['sometimes', 'string'],
            'pages.*.words.*.reading' => ['sometimes', 'string'],
            'pages.*.words.*.romaji'  => ['sometimes', 'string'],
            'pages.*.words.*.meaning' => ['sometimes', 'string'],
        ]);

        // Use raw pages from request to preserve all fields including 'words'
        $pages = collect($request->input('pages', []))->map(function ($page) {
            return [
                'japanese'    => $page['japanese'] ?? '',
                'romaji'      => $page['romaji'] ?? '',
                'translation' => $page['translation'] ?? '',
                'words'       => $page['words'] ?? [],
            ];
        })->all();

        $order = (int) Story::query()->where('sectionId', $validated['section_id'])->max('order') + 1;

        $story = Story::query()->create([
            'id'        => (string) Str::ulid(),
            'sectionId' => $validated['section_id'],
            'title'     => $validated['title'],
            'content'   => $pages,
            'order'     => $order,
        ]);

        return response()->json([
            'success' => true,
            'story'   => [
                'id'    => $story->id,
                'title' => $story->title,
            ],
        ]);
    }

    /**
     * Hapus cerita.
     */
    public function destroy(Story $story): RedirectResponse
    {
        $story->delete();

        return redirect()->route('guru.stories.index')
            ->with('success', "Cerita \"{$story->title}\" berhasil dihapus.");
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Private helpers
    // ─────────────────────────────────────────────────────────────────────────

    private function buildPrompt(string $level, string $theme, int $pageCount): string
    {
        $levelDesc = match ($level) {
            'N5' => 'JLPT N5 (pemula mutlak). WAJIB menggunakan hiragana/katakana sebanyak mungkin. Kanji yang boleh digunakan SANGAT SEDIKIT: hanya 日,本,人,山,川,年,月,大,小,中,上,下. Semua kata lain HARUS ditulis dalam hiragana.',
            'N4' => 'JLPT N4 (dasar). Kanji umum N4 diperbolehkan, sisanya dalam hiragana. Kalimat pendek.',
            'N3' => 'JLPT N3 (menengah). Kanji N3 ke bawah diperbolehkan. Kalimat sedang.',
            'N2' => 'JLPT N2 (mahir). Kanji N2 ke bawah diperbolehkan. Kalimat variatif.',
            'N1' => 'JLPT N1 (ahli). Semua kanji diperbolehkan. Kalimat kompleks dan natural.',
            default => 'JLPT N5',
        };

        $prompt = "Kamu adalah penulis cerita pendek bahasa Jepang untuk aplikasi pembelajaran JLPT interaktif.\n\n";
        $prompt .= "Tugas: Buat cerita pendek dengan tema \"{$theme}\" untuk level {$levelDesc}.\n\n";
        $prompt .= "Cerita harus terdiri dari tepat {$pageCount} halaman/paragraf.\n\n";
        $prompt .= 'Output HARUS berupa JSON valid dengan struktur berikut:\n';
        $prompt .= '{\n';
        $prompt .= '  "title": "judul cerita dalam bahasa Jepang",\n';
        $prompt .= '  "title_romaji": "judul dalam romaji",\n';
        $prompt .= '  "title_translation": "judul dalam bahasa Indonesia",\n';
        $prompt .= '  "pages": [\n';
        $prompt .= '    {\n';
        $prompt .= '      "japanese": "kalimat bahasa Jepang lengkap (1-2 kalimat per halaman)",\n';
        $prompt .= '      "romaji": "cara baca seluruh kalimat dalam romaji Hepburn",\n';
        $prompt .= '      "translation": "terjemahan alami dalam bahasa Indonesia",\n';
        $prompt .= '      "words": [\n';
        $prompt .= '        {\n';
        $prompt .= '          "word": "satu kata atau partikel tunggal dari kalimat",\n';
        $prompt .= '          "reading": "cara baca dalam hiragana",\n';
        $prompt .= '          "romaji": "cara baca dalam romaji",\n';
        $prompt .= '          "meaning": "arti singkat dalam bahasa Indonesia (maks 5 kata)"\n';
        $prompt .= '        }\n';
        $prompt .= '      ]\n';
        $prompt .= '    }\n';
        $prompt .= '  ]\n';
        $prompt .= '}\n\n';
        $prompt .= "Aturan WAJIB untuk array \"words\":\n";
        $prompt .= "- Pisahkan setiap kata dan partikel menjadi item terpisah\n";
        $prompt .= "- Urutan words HARUS mengikuti urutan kata di kalimat\n";
        $prompt .= "- Sertakan partikel (は,が,を,に,で,と,も,の,か,よ,ね) sebagai item tersendiri\n";
        $prompt .= "- Untuk partikel, meaning gunakan: [partikel subjek], [partikel objek], [partikel tempat], dll.\n";
        $prompt .= "- Semua kata HARUS tercakup, tidak ada yang terlewat\n\n";
        $prompt .= "Aturan cerita:\n";
        $prompt .= "- Alur jelas: pembuka, isi, penutup\n";
        $prompt .= "- Romaji sistem Hepburn (standar)\n";
        $prompt .= "- Terjemahan bahasa Indonesia yang alami\n";
        $prompt .= "- HANYA output JSON murni, tanpa markdown, tanpa komentar di luar JSON\n";

        return $prompt;
    }
}
