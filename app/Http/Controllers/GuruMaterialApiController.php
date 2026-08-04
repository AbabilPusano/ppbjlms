<?php

namespace App\Http\Controllers;

use App\Models\Level;
use App\Models\Node;
use App\Models\Question;
use App\Models\Unit;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\NodeStep;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class GuruMaterialApiController extends Controller
{
    public function materials(Level $level): JsonResponse
    {
        $units = $level->units()
            ->with(['nodes' => fn ($query) => $query->withCount('questions')])
            ->get()
            ->map(fn (Unit $unit): array => [
                'id' => $unit->id,
                'level_id' => $unit->level_id,
                'title' => $unit->title,
                'description' => $unit->description,
                'category_japanese' => $unit->category_japanese,
                'category_romaji' => $unit->category_romaji,
                'sort_order' => $unit->sort_order,
                'is_active' => $unit->is_active,
                'materials' => $unit->nodes->map(fn (Node $material): array => $this->serializeMaterial($material))->values(),
            ]);

        return response()->json([
            'data' => [
                'level' => [
                    'id' => $level->id,
                    'code' => $level->code,
                    'name' => $level->name,
                ],
                'units' => $units,
                'units_count' => $units->count(),
                'materials_count' => $units->sum(fn ($unit) => count($unit['materials'])),
            ],
        ]);
    }

    public function storeMaterial(Request $request): JsonResponse
    {
        $validated = $this->validateMaterial($request);
        $unit = Unit::query()->findOrFail($validated['unit_id']);
        abort_if(isset($validated['level_id']) && (int) $unit->level_id !== (int) $validated['level_id'], 422, 'Unit tidak sesuai dengan level yang dipilih.');

        $order = $validated['order'] ?? ((int) Node::query()->where('unitId', $unit->id)->max('order')) + 1;
        $material = Node::query()->create([
            'id' => (string) Str::ulid(),
            'unitId' => $unit->id,
            'title' => $validated['title'],
            'type' => $validated['type'],
            'order' => $order,
        ]);

        return response()->json(['data' => $this->serializeMaterial($material->loadCount('questions'))], 201);
    }

    public function updateMaterial(Request $request, Node $material): JsonResponse
    {
        $validated = $this->validateMaterial($request, $material);
        $unit = Unit::query()->findOrFail($validated['unit_id']);
        abort_if(isset($validated['level_id']) && (int) $unit->level_id !== (int) $validated['level_id'], 422, 'Unit tidak sesuai dengan level yang dipilih.');

        $material->update([
            'unitId' => $unit->id,
            'title' => $validated['title'],
            'type' => $validated['type'],
            'order' => $validated['order'] ?? $material->order,
        ]);

        return response()->json(['data' => $this->serializeMaterial($material->refresh()->loadCount('questions'))]);
    }

    public function destroyMaterial(Node $material): JsonResponse
    {
        $material->delete();

        return response()->json(['message' => 'Isi unit berhasil dihapus.']);
    }

    public function questions(Node $material): JsonResponse
    {
        abort_unless($material->type === 'quiz', 404);

        $material->load(['unit.level', 'questions']);

        return response()->json([
            'data' => [
                'material' => $this->serializeMaterial($material->loadCount('questions')),
                'unit' => [
                    'id' => $material->unit?->id,
                    'title' => $material->unit?->title,
                    'level' => $material->unit?->level?->code,
                    'level_name' => $material->unit?->level?->name,
                ],
                'questions' => $material->questions->map(fn (Question $question): array => $this->serializeQuestion($question))->values(),
            ],
        ]);
    }

    public function storeQuestion(Request $request, Node $material): JsonResponse
    {
        abort_unless($material->type === 'quiz', 404);

        $validated = $this->validateQuestion($request);
        $order = $validated['order'] ?? ((int) Question::query()->where('nodeId', $material->id)->max('order')) + 1;
        $question = Question::query()->create([
            'id' => (string) Str::ulid(),
            'nodeId' => $material->id,
            ...$this->questionPayload($validated, $order),
        ]);

        return response()->json(['data' => $this->serializeQuestion($question)], 201);
    }

    public function updateQuestion(Request $request, Question $question): JsonResponse
    {
        $validated = $this->validateQuestion($request);

        $question->update($this->questionPayload($validated, $validated['order'] ?? $question->order));

        return response()->json(['data' => $this->serializeQuestion($question->refresh())]);
    }

    public function destroyQuestion(Question $question): JsonResponse
    {
        $question->delete();

        return response()->json(['message' => 'Soal berhasil dihapus.']);
    }

    public function questionTemplate(): JsonResponse
    {
        return response()->json([
            'data' => [
                'filename' => 'template-soal-quiz.xlsx',
                'columns' => [
                    'type',
                    'letter',
                    'reading',
                    'question_text',
                    'option_a',
                    'option_b',
                    'option_c',
                    'option_d',
                    'correct_answer',
                ],
            ],
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    private function validateMaterial(Request $request, ?Node $material = null): array
    {
        $rules = [
            'level_id' => ['nullable', 'integer', Rule::exists('levels', 'id')],
            'unit_id' => ['required', 'string', Rule::exists('units', 'id')],
            'title' => ['required', 'string', 'min:3', 'max:191'],
            'type' => ['required', Rule::in(['lesson', 'quiz'])],
            'order' => ['nullable', 'integer', 'min:0'],
        ];

        if ($material && $material->type === 'quiz' && $material->questions()->exists()) {
            $rules['type'][] = Rule::in(['quiz']);
        }

        return $request->validate($rules);
    }

    /**
     * @return array<string, mixed>
     */
    private function validateQuestion(Request $request): array
    {
        $type = (string) $request->input('type');
        $base = [
            'type' => ['required', Rule::in(['new-letter', 'multiple-choice'])],
            'order' => ['nullable', 'integer', 'min:0'],
        ];

        if ($type === 'new-letter') {
            return $request->validate([
                ...$base,
                'letter' => ['required', 'string', 'max:20'],
                'reading' => ['required', 'string', 'max:50'],
            ]);
        }

        return $request->validate([
            ...$base,
            'question_text' => ['required', 'string', 'max:1000'],
            'letter' => ['nullable', 'string', 'max:20'],
            'option_a' => ['required', 'string', 'max:191'],
            'option_b' => ['required', 'string', 'max:191'],
            'option_c' => ['nullable', 'string', 'max:191'],
            'option_d' => ['nullable', 'string', 'max:191'],
            'correct_answer' => ['required', Rule::in(['A', 'B', 'C', 'D'])],
        ]);
    }

    /**
     * @param array<string, mixed> $validated
     * @return array<string, mixed>
     */
    private function questionPayload(array $validated, int $order): array
    {
        if ($validated['type'] === 'new-letter') {
            $letter = (string) $validated['letter'];
            $reading = (string) $validated['reading'];

            return [
                'type' => 'new-letter',
                'question' => "Huruf baru: {$letter}",
                'options' => [],
                'correctAnswer' => ['reading' => $reading],
                'relatedCharacters' => [$letter],
                'order' => $order,
            ];
        }

        $options = collect(['A', 'B', 'C', 'D'])
            ->map(fn (string $key): array => [
                'key' => $key,
                'text' => (string) ($validated['option_'.Str::lower($key)] ?? ''),
            ])
            ->filter(fn (array $option): bool => $option['text'] !== '')
            ->values()
            ->all();

        return [
            'type' => 'multiple-choice',
            'question' => (string) $validated['question_text'],
            'options' => $options,
            'correctAnswer' => ['option' => $validated['correct_answer']],
            'relatedCharacters' => array_values(array_filter([(string) ($validated['letter'] ?? '')])),
            'order' => $order,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function serializeMaterial(Node $material): array
    {
        return [
            'id' => $material->id,
            'unit_id' => $material->unitId,
            'title' => $material->title,
            'type' => $material->type,
            'type_label' => $material->type === 'quiz' ? 'Quiz' : 'Materi Pembelajaran',
            'order' => $material->order,
            'questions_count' => $material->questions_count ?? $material->questions()->count(),
            'created_at' => $material->created_at?->toISOString(),
            'updated_at' => $material->updated_at?->toISOString(),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function serializeQuestion(Question $question): array
    {
        $options = collect($question->options ?? []);
        $correctAnswer = $question->correctAnswer ?? [];

        if ($question->type === 'new-letter') {
            return [
                'id' => $question->id,
                'material_id' => $question->nodeId,
                'type' => $question->type,
                'type_label' => 'Huruf Baru',
                'letter' => $question->relatedCharacters[0] ?? '',
                'reading' => $correctAnswer['reading'] ?? '',
                'question_text' => $question->question,
                'options' => [],
                'correct_answer' => $correctAnswer,
                'order' => $question->order,
            ];
        }

        return [
            'id' => $question->id,
            'material_id' => $question->nodeId,
            'type' => $question->type,
            'type_label' => 'Pilihan Ganda',
            'letter' => $question->relatedCharacters[0] ?? '',
            'question_text' => $question->question,
            'option_a' => $options->firstWhere('key', 'A')['text'] ?? '',
            'option_b' => $options->firstWhere('key', 'B')['text'] ?? '',
            'option_c' => $options->firstWhere('key', 'C')['text'] ?? '',
            'option_d' => $options->firstWhere('key', 'D')['text'] ?? '',
            'correct_answer' => $correctAnswer['option'] ?? '',
            'options' => $question->options ?? [],
            'order' => $question->order,
        ];
    }

    public function reorder(Request $request): JsonResponse
    {
        $request->validate([
            'materials' => 'required|array',
            'materials.*.id' => 'required|string|exists:nodes,id',
            'materials.*.order' => 'required|integer',
        ]);

        foreach ($request->input('materials') as $item) {
            Node::query()->where('id', $item['id'])->update([
                'order' => $item['order'],
            ]);
        }

        return response()->json(['message' => 'Urutan isi unit berhasil diperbarui.']);
    }

    public function downloadTemplate(): \Symfony\Component\HttpFoundation\Response
    {
        $template = [
            "title" => "Contoh Unit Baru",
            "description" => "Deskripsi Unit Baru",
            "category_japanese" => "あいうえお",
            "category_romaji" => "aiueo",
            "materials" => [
                [
                    "title" => "Hanya Node Tanpa Isi",
                    "type" => "lesson",
                    "order" => 1,
                    "steps" => []
                ],
                [
                    "title" => "Node dengan 1 Isi",
                    "type" => "lesson",
                    "order" => 2,
                    "steps" => [
                        [
                            "type" => "text",
                            "content" => "Ini adalah isi teks pengantar."
                        ]
                    ]
                ],
                [
                    "title" => "Node dengan 4 Isi Berbeda",
                    "type" => "lesson",
                    "order" => 3,
                    "steps" => [
                        [
                            "type" => "text",
                            "content" => "Pengantar belajar hiragana あ."
                        ],
                        [
                            "type" => "character",
                            "character_symbol" => "あ",
                            "character_pronunciation" => "a"
                        ],
                        [
                            "type" => "vocabulary",
                            "character_symbol" => "食べる",
                            "character_pronunciation" => "taberu",
                            "content" => "Makan"
                        ],
                        [
                            "type" => "quiz",
                            "quiz_instruction" => "PILIH JAWABAN YANG BENAR",
                            "quiz_query" => "a",
                            "quiz_options" => ["a", "i", "u", "e"],
                            "quiz_correct_index" => 0
                        ]
                    ]
                ]
            ]
        ];

        return response()->json($template, 200, [
            'Content-Disposition' => 'attachment; filename="template-isi-unit.json"'
        ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    }

    public function export(Unit $unit): \Symfony\Component\HttpFoundation\Response
    {
        $unit->load(['nodes' => function ($query) {
            $query->orderBy('order')->with(['steps' => function ($q) {
                $q->orderBy('sort_order');
            }]);
        }]);

        $exportedData = [
            'title' => $unit->title,
            'description' => $unit->description,
            'category_japanese' => $unit->category_japanese,
            'category_romaji' => $unit->category_romaji,
            'materials' => $unit->nodes->map(fn (Node $node): array => [
                'title' => $node->title,
                'type' => $node->type,
                'order' => $node->order,
                'steps' => $node->steps->map(function (NodeStep $step): array {
                    $stepData = [
                        'type' => $step->type,
                        'content' => $step->content,
                        'character_symbol' => $step->character_symbol,
                        'character_pronunciation' => $step->character_pronunciation,
                        'quiz_instruction' => $step->quiz_instruction,
                        'quiz_query' => $step->quiz_query,
                        'quiz_options' => $step->quiz_options,
                        'quiz_correct_index' => $step->quiz_correct_index,
                        'sort_order' => $step->sort_order,
                    ];
                    if ($step->type === 'vocabulary') {
                        $kata = \App\Models\Kata::with('kanjis')->where('kata', $step->character_symbol)->first();
                        if ($kata) {
                            $stepData['kana'] = $kata->kana;
                            $stepData['tipe_kata'] = $kata->tipe_kata;
                            $stepData['deskripsi'] = $kata->deskripsi;
                            $stepData['level'] = $kata->level;
                            $stepData['kanjis'] = $kata->kanjis->pluck('kanji')->toArray();
                        }
                    }
                    return $stepData;
                })->toArray(),
            ])->toArray(),
        ];

        $safeTitle = Str::slug($unit->title);
        $filename = "export-unit-{$safeTitle}.json";

        return response()->json($exportedData, 200, [
            'Content-Disposition' => "attachment; filename=\"{$filename}\""
        ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    }

    public function import(Request $request, Unit $unit): JsonResponse
    {
        $request->validate([
            'file' => 'required|file|mimes:json,txt'
        ]);

        $file = $request->file('file');
        $content = file_get_contents($file->getRealPath());
        $data = json_decode($content, true);

        if (!is_array($data)) {
            return response()->json(['message' => 'Format file JSON tidak valid. Harus berupa array atau object.'], 422);
        }

        try {
            DB::transaction(function () use ($data, $unit) {
                $materialsData = [];
                if (isset($data['materials']) && is_array($data['materials'])) {
                    $materialsData = $data['materials'];
                    $unit->update([
                        'title' => $data['title'] ?? $unit->title,
                        'description' => $data['description'] ?? $unit->description,
                        'category_japanese' => $data['category_japanese'] ?? $unit->category_japanese,
                        'category_romaji' => $data['category_romaji'] ?? $unit->category_romaji,
                    ]);
                } else {
                    $materialsData = $data;
                }

                foreach ($materialsData as $nodeData) {
                    if (empty($nodeData['title'])) {
                        throw new \InvalidArgumentException('Setiap node wajib memiliki judul ("title").');
                    }

                    $nextMaterialOrder = ((int) Node::query()->where('unitId', $unit->id)->max('order')) + 1;

                    $node = Node::query()->create([
                        'id' => (string) Str::ulid(),
                        'unitId' => $unit->id,
                        'title' => $nodeData['title'],
                        'type' => $nodeData['type'] ?? 'lesson',
                        'order' => $nodeData['order'] ?? $nextMaterialOrder,
                    ]);

                    if (isset($nodeData['steps']) && is_array($nodeData['steps'])) {
                        foreach ($nodeData['steps'] as $idx => $stepData) {
                            if (empty($stepData['type']) || !in_array($stepData['type'], ['text', 'character', 'quiz', 'vocabulary'])) {
                                throw new \InvalidArgumentException('Tipe langkah tidak valid. Harus "text", "character", "quiz", atau "vocabulary".');
                            }

                            if ($stepData['type'] === 'character') {
                                $symbol = trim($stepData['character_symbol'] ?? '');
                                $reading = trim($stepData['character_pronunciation'] ?? '');
                                if ($symbol) {
                                    if (!\App\Models\Huruf::where('simbol', $symbol)->exists()) {
                                        $tipe = preg_match('/[\x{30A0}-\x{30FF}]/u', $symbol) ? 'katakana' : 'hiragana';
                                        \App\Models\Huruf::create([
                                            'id' => (string) Str::uuid(),
                                            'tipe' => $tipe,
                                            'simbol' => $symbol,
                                            'cara_baca' => $reading,
                                        ]);
                                    }
                                }
                            }

                            if ($stepData['type'] === 'vocabulary') {
                                 $kata = trim($stepData['character_symbol'] ?? '');
                                 $reading = trim($stepData['character_pronunciation'] ?? '');
                                 $meaning = trim($stepData['content'] ?? '');
                                 if ($kata) {
                                     $kataRecord = \App\Models\Kata::where('kata', $kata)->first();
                                     if (!$kataRecord) {
                                         $kanjiIds = [];
                                         if (!empty($stepData['kanjis']) && is_array($stepData['kanjis'])) {
                                             $kanjiIds = \App\Models\Kanji::whereIn('kanji', $stepData['kanjis'])->pluck('id')->toArray();
                                         }
                                         $firstKanjiId = count($kanjiIds) > 0 ? $kanjiIds[0] : null;

                                         $kataRecord = \App\Models\Kata::create([
                                             'id' => (string) Str::uuid(),
                                             'kata' => $kata,
                                             'kana' => $stepData['kana'] ?? '',
                                             'romaji' => $reading,
                                             'arti' => $meaning,
                                             'kanji_id' => $firstKanjiId,
                                             'tipe_kata' => $stepData['tipe_kata'] ?? null,
                                             'deskripsi' => $stepData['deskripsi'] ?? null,
                                             'level' => $stepData['level'] ?? null,
                                         ]);
                                         if (count($kanjiIds) > 0) {
                                             $kataRecord->kanjis()->sync($kanjiIds);
                                         }
                                     }
                                 }
                             }

                            NodeStep::query()->create([
                                'id' => (string) Str::ulid(),
                                'node_id' => $node->id,
                                'type' => $stepData['type'],
                                'content' => $stepData['content'] ?? null,
                                'character_symbol' => $stepData['character_symbol'] ?? null,
                                'character_pronunciation' => $stepData['character_pronunciation'] ?? null,
                                'quiz_instruction' => $stepData['quiz_instruction'] ?? null,
                                'quiz_query' => $stepData['quiz_query'] ?? null,
                                'quiz_options' => $stepData['quiz_options'] ?? null,
                                'quiz_correct_index' => isset($stepData['quiz_correct_index']) ? (int)$stepData['quiz_correct_index'] : null,
                                'sort_order' => $stepData['sort_order'] ?? ($idx + 1),
                            ]);
                        }
                    }
                }
            });
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }

        return response()->json(['message' => 'Bulk import berhasil diselesaikan.']);
    }
}
