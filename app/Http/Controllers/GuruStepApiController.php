<?php

namespace App\Http\Controllers;

use App\Models\Node;
use App\Models\NodeStep;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class GuruStepApiController extends Controller
{
    public function checkCharacter(Request $request): JsonResponse
    {
        $symbol = $request->query('symbol');
        $excludeStepId = $request->query('exclude_step_id');

        if (!$symbol) {
            return response()->json(['exists' => false]);
        }

        $query = NodeStep::query()
            ->where('type', 'character')
            ->whereRaw('BINARY character_symbol = ?', [$symbol]);

        if ($excludeStepId) {
            $query->where('id', '!=', $excludeStepId);
        }

        $existingStep = $query->first();

        if ($existingStep) {
            $node = Node::query()->find($existingStep->node_id);
            return response()->json([
                'exists' => true,
                'node_title' => $node ? $node->title : 'Lain',
            ]);
        }

        return response()->json(['exists' => false]);
    }

    public function availableQuizCharacters(Node $node): JsonResponse
    {
        $nodeIds = Node::where('unitId', $node->unitId)
            ->where('order', '<=', $node->order)
            ->pluck('id');

        $characters = NodeStep::whereIn('node_id', $nodeIds)
            ->where('type', 'character')
            ->whereNotNull('character_symbol')
            ->where('character_symbol', '!=', '')
            ->get(['character_symbol', 'character_pronunciation'])
            ->unique('character_symbol')
            ->values();

        return response()->json([
            'characters' => $characters
        ]);
    }

    public function availableQuizWords(Node $node): JsonResponse
    {
        $nodeIds = Node::where('unitId', $node->unitId)
            ->where('order', '<=', $node->order)
            ->pluck('id');

        $words = NodeStep::whereIn('node_id', $nodeIds)
            ->where('type', 'vocabulary')
            ->whereNotNull('character_symbol')
            ->where('character_symbol', '!=', '')
            ->get()
            ->map(function ($step) {
                $kataRecord = \App\Models\Kata::where('kata', $step->character_symbol)->first();
                return [
                    'character_symbol' => $step->character_symbol,
                    'character_pronunciation' => $step->character_pronunciation,
                    'content' => $step->content,
                    'kana' => $kataRecord ? $kataRecord->kana : $step->character_symbol,
                ];
            })
            ->unique('character_symbol')
            ->values();

        return response()->json([
            'words' => $words
        ]);
    }

    public function index(Node $node): JsonResponse
    {
        $steps = $node->steps()
            ->get()
            ->map(fn(NodeStep $step): array => $this->serializeStep($step));

        return response()->json([
            'data' => [
                'node' => [
                    'id' => $node->id,
                    'title' => $node->title,
                    'type' => $node->type,
                    'unit_id' => $node->unitId,
                    'level_name' => $node->unit && $node->unit->level ? $node->unit->level->name : null,
                    'level_code' => $node->unit && $node->unit->level ? $node->unit->level->code : null,
                    'unit_type' => $node->unit ? $node->unit->type : null,
                ],
                'steps' => $steps,
                'steps_count' => $steps->count(),
            ],
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $this->validateStep($request);
        $node = Node::query()->findOrFail($validated['node_id']);

        if ($validated['type'] === 'character') {
            $char = $validated['character_symbol'] ?? null;
            if ($char) {
                $existingStep = NodeStep::query()
                    ->where('type', 'character')
                    ->whereRaw('BINARY character_symbol = ?', [$char])
                    ->first();
                if ($existingStep) {
                    $existingNode = Node::query()->find($existingStep->node_id);
                    $nodeName = $existingNode ? $existingNode->title : 'lain';
                    return response()->json(['message' => "huruf sudah ada di node {$nodeName}"], 422);
                }
            }
        }

        if ($validated['type'] === 'vocabulary') {
            $kata = $validated['character_symbol'] ?? null;
            if ($kata) {
                $hasKanji = false;
                $kataRecord = \App\Models\Kata::where('kata', $kata)->first();
                if ($kataRecord) {
                    $hasKanji = $kataRecord->kanjis()->exists();
                } else {
                    $hasKanji = (bool) preg_match('/[\x{4e00}-\x{9faf}]/u', $kata);
                }

                $newTampilkanKanji = $request->input('tampilkan_kanji') === '1' || $request->input('tampilkan_kanji') === 'true';

                $existingSteps = NodeStep::query()
                    ->where('type', 'vocabulary')
                    ->whereRaw('BINARY character_symbol = ?', [$kata])
                    ->get();

                foreach ($existingSteps as $existingStep) {
                    $existingTampilkanKanji = $existingStep->quiz_instruction !== '0';
                    $existingNode = Node::query()->find($existingStep->node_id);
                    $nodeName = $existingNode ? $existingNode->title : 'lain';

                    if (!$hasKanji) {
                        return response()->json(['message' => "kata sudah ada di node {$nodeName}"], 422);
                    } else {
                        if ($newTampilkanKanji && $existingTampilkanKanji) {
                            return response()->json(['message' => "kata sudah ada di node {$nodeName}"], 422);
                        } elseif (!$newTampilkanKanji && !$existingTampilkanKanji) {
                            return response()->json(['message' => "kata sudah ada di node {$nodeName} (tanpa kanji)"], 422);
                        }
                    }
                }
            }

            $selectedId = $request->input('vocab_selected_id');
            if ($kata && empty($selectedId)) {
                if (\App\Models\Kata::where('kata', $kata)->exists()) {
                    return response()->json(['message' => 'Kata tersebut sudah ada di database referensi.'], 422);
                }

                $kanjiIds = [];
                if ($request->input('kanji_id')) {
                    $kanjiIds = array_filter(explode(',', $request->input('kanji_id')));
                }
                $firstKanjiId = count($kanjiIds) > 0 ? $kanjiIds[0] : null;

                $newKata = \App\Models\Kata::create([
                    'id' => (string) Str::uuid(),
                    'kata' => $kata,
                    'kana' => $request->input('kana'),
                    'romaji' => $validated['character_pronunciation'] ?? '',
                    'arti' => $validated['content'] ?? '',
                    'kanji_id' => $firstKanjiId,
                    'tipe_kata' => $request->input('tipe_kata'),
                    'deskripsi' => $request->input('deskripsi'),
                    'level' => $request->input('level'),
                ]);

                if (count($kanjiIds) > 0) {
                    $newKata->kanjis()->sync($kanjiIds);
                }
            }
        }

        $type = $validated['type'];
        $charSymbol = $validated['character_symbol'] ?? null;
        $quizQuery = $validated['quiz_query'] ?? null;

        if ($type === 'quiz' && $request->input('quiz_question_type') === 'random') {
            $charSymbol = 'random';
            $quizQuery = 'RANDOM_QUIZ';
        }

        $content = $validated['content'] ?? null;
        if ($type === 'quiz') {
            $content = $request->input('quiz_type') ?? null;
        }

        $quizInstruction = $validated['quiz_instruction'] ?? null;
        if ($type === 'vocabulary') {
            $quizInstruction = $request->input('tampilkan_kanji') === '1' || $request->input('tampilkan_kanji') === 'true' ? '1' : '0';
        }

        $sortOrder = $validated['sort_order'] ?? null;
        if ($sortOrder !== null) {
            NodeStep::query()
                ->where('node_id', $node->id)
                ->where('sort_order', '>=', $sortOrder)
                ->increment('sort_order');
        } else {
            $sortOrder = ((int) NodeStep::query()->where('node_id', $node->id)->max('sort_order')) + 1;
        }

        $charPron = $validated['character_pronunciation'] ?? null;
        if ($type === 'quiz') {
            if ($request->input('quiz_question_type') === 'vocabulary') {
                $charPron = $request->input('quiz_tampilkan_kanji') === '1' || $request->input('quiz_tampilkan_kanji') === 'true' ? '1' : '0';
            } else {
                $charPron = null;
            }
        }

        $step = NodeStep::query()->create([
            'id' => (string) Str::ulid(),
            'node_id' => $node->id,
            'type' => $type,
            'content' => $content,
            'character_symbol' => $charSymbol,
            'character_pronunciation' => $charPron,
            'quiz_instruction' => $quizInstruction,
            'quiz_query' => $quizQuery,
            'quiz_options' => $validated['quiz_options'] ?? null,
            'quiz_correct_index' => $validated['quiz_correct_index'] ?? null,
            'sort_order' => $sortOrder,
        ]);

        return response()->json([
            'message' => 'Langkah pembelajaran berhasil ditambahkan.',
            'data' => $this->serializeStep($step),
        ]);
    }

    public function update(Request $request, Node $node, NodeStep $step): JsonResponse
    {
        $validated = $this->validateStep($request, $step);

        if ($validated['type'] === 'character') {
            $symbol = $validated['character_symbol'] ?? null;
            if ($symbol && $symbol !== $step->character_symbol) {
                $existingNode = Node::query()
                    ->where('id', '!=', $node->id)
                    ->whereHas('steps', function ($query) use ($symbol) {
                        $query->where('type', 'character')->whereRaw('BINARY character_symbol = ?', [$symbol]);
                    })
                    ->first();

                if ($existingNode) {
                    $nodeName = $existingNode ? $existingNode->title : 'lain';
                    return response()->json(['message' => "huruf sudah ada di node {$nodeName}"], 422);
                }
            }
        }

        if ($validated['type'] === 'vocabulary') {
            $kata = $validated['character_symbol'] ?? null;
            if ($kata) {
                $hasKanji = false;
                $kataRecord = \App\Models\Kata::where('kata', $kata)->first();
                if ($kataRecord) {
                    $hasKanji = $kataRecord->kanjis()->exists();
                } else {
                    $hasKanji = (bool) preg_match('/[\x{4e00}-\x{9faf}]/u', $kata);
                }

                $newTampilkanKanji = $request->input('tampilkan_kanji') === '1' || $request->input('tampilkan_kanji') === 'true';

                $existingSteps = NodeStep::query()
                    ->where('type', 'vocabulary')
                    ->whereRaw('BINARY character_symbol = ?', [$kata])
                    ->where('id', '!=', $step->id)
                    ->get();

                foreach ($existingSteps as $existingStep) {
                    $existingTampilkanKanji = $existingStep->quiz_instruction !== '0';
                    $existingNode = Node::query()->find($existingStep->node_id);
                    $nodeName = $existingNode ? $existingNode->title : 'lain';

                    if (!$hasKanji) {
                        return response()->json(['message' => "kata sudah ada di node {$nodeName}"], 422);
                    } else {
                        if ($newTampilkanKanji && $existingTampilkanKanji) {
                            return response()->json(['message' => "kata sudah ada di node {$nodeName}"], 422);
                        } elseif (!$newTampilkanKanji && !$existingTampilkanKanji) {
                            return response()->json(['message' => "kata sudah ada di node {$nodeName} (tanpa kanji)"], 422);
                        }
                    }
                }
            }

            $selectedId = $request->input('vocab_selected_id');
            if ($kata && empty($selectedId)) {
                if ($kata !== $step->character_symbol) {
                    if (\App\Models\Kata::where('kata', $kata)->exists()) {
                        return response()->json(['message' => 'Kata tersebut sudah ada di database referensi.'], 422);
                    }
                }
                $kataRecord = \App\Models\Kata::where('kata', $step->character_symbol)->first();

                $kanjiIds = [];
                if ($request->input('kanji_id')) {
                    $kanjiIds = array_filter(explode(',', $request->input('kanji_id')));
                }
                $firstKanjiId = count($kanjiIds) > 0 ? $kanjiIds[0] : null;

                $kataData = [
                    'kata' => $kata,
                    'kana' => $request->input('kana'),
                    'romaji' => $validated['character_pronunciation'] ?? '',
                    'arti' => $validated['content'] ?? '',
                    'kanji_id' => $firstKanjiId,
                    'tipe_kata' => $request->input('tipe_kata'),
                    'deskripsi' => $request->input('deskripsi'),
                    'level' => $request->input('level'),
                ];
                if ($kataRecord) {
                    $kataRecord->update($kataData);
                } else {
                    $kataRecord = \App\Models\Kata::create(array_merge($kataData, ['id' => (string) Str::uuid()]));
                }
                $kataRecord->kanjis()->sync($kanjiIds);
            }
        }

        $charSymbol = $validated['character_symbol'] ?? null;
        $quizQuery = $validated['quiz_query'] ?? null;
        if ($validated['type'] === 'quiz' && $request->input('quiz_question_type') === 'random') {
            $charSymbol = 'random';
            $quizQuery = 'RANDOM_QUIZ';
        }

        $content = $validated['content'] ?? null;
        if ($validated['type'] === 'quiz') {
            $content = $request->input('quiz_type') ?? null;
        }

        $quizInstruction = $validated['quiz_instruction'] ?? null;
        if ($validated['type'] === 'vocabulary') {
            $quizInstruction = $request->input('tampilkan_kanji') === '1' || $request->input('tampilkan_kanji') === 'true' ? '1' : '0';
        }

        $charPron = $validated['character_pronunciation'] ?? null;
        if ($validated['type'] === 'quiz') {
            if ($request->input('quiz_question_type') === 'vocabulary') {
                $charPron = $request->input('quiz_tampilkan_kanji') === '1' || $request->input('quiz_tampilkan_kanji') === 'true' ? '1' : '0';
            } else {
                $charPron = null;
            }
        }

        $step->update([
            'type' => $validated['type'],
            'content' => $content,
            'character_symbol' => $charSymbol,
            'character_pronunciation' => $charPron,
            'quiz_instruction' => $quizInstruction,
            'quiz_query' => $quizQuery,
            'quiz_options' => $validated['quiz_options'] ?? null,
            'quiz_correct_index' => isset($validated['quiz_correct_index']) ? (int) $validated['quiz_correct_index'] : null,
            'sort_order' => $validated['sort_order'] ?? $step->sort_order,
        ]);

        return response()->json(['data' => $this->serializeStep($step->refresh())]);
    }

    public function destroy(NodeStep $step): JsonResponse
    {
        $step->delete();
        return response()->json(['message' => 'Langkah unit berhasil dihapus.']);
    }

    public function reorder(Request $request): JsonResponse
    {
        $request->validate([
            'steps' => 'required|array',
            'steps.*.id' => 'required|string|exists:node_steps,id',
            'steps.*.sort_order' => 'required|integer',
        ]);

        foreach ($request->input('steps') as $item) {
            NodeStep::query()->where('id', $item['id'])->update([
                'sort_order' => $item['sort_order'],
            ]);
        }

        return response()->json(['message' => 'Urutan langkah berhasil diperbarui.']);
    }

    private function validateStep(Request $request, ?NodeStep $step = null): array
    {
        return $request->validate([
            'node_id' => $step ? 'nullable|string|exists:nodes,id' : 'required|string|exists:nodes,id',
            'type' => 'required|string|in:text,character,quiz,vocabulary',
            'content' => 'nullable|string',
            'character_symbol' => 'nullable|string|max:50',
            'character_pronunciation' => 'nullable|string|max:100',
            'quiz_instruction' => 'nullable|string',
            'quiz_query' => 'nullable|string',
            'quiz_options' => 'nullable|array',
            'quiz_options.*' => 'nullable|string',
            'quiz_correct_index' => 'nullable|integer',
            'sort_order' => 'nullable|integer',
            'kanji_id' => 'nullable|string',
            'vocab_selected_id' => 'nullable|string',
            'kana' => 'nullable|string|max:191',
            'tipe_kata' => 'nullable|string|max:191',
            'deskripsi' => 'nullable|string',
            'level' => 'nullable|string|in:N5,N4,N3,N2,N1',
            'quiz_question_type' => 'nullable|string|in:character,vocabulary,random',
            'tampilkan_kanji' => 'nullable|string',
            'quiz_tampilkan_kanji' => 'nullable|string',
        ]);
    }

    private function serializeStep(NodeStep $step): array
    {
        $serialized = [
            'id' => $step->id,
            'node_id' => $step->node_id,
            'type' => $step->type,
            'type_label' => match ($step->type) {
                'text' => 'Teks Pengantar',
                'character' => 'Huruf Baru',
                'vocabulary' => 'Kata Baru',
                'quiz' => 'Kuis Pilihan Ganda',
                default => 'Unknown'
            },
            'content' => $step->content,
            'character_symbol' => $step->character_symbol,
            'character_pronunciation' => $step->character_pronunciation,
            'quiz_instruction' => $step->quiz_instruction,
            'quiz_query' => $step->quiz_query,
            'quiz_options' => $step->quiz_options,
            'quiz_correct_index' => $step->quiz_correct_index,
            'sort_order' => $step->sort_order,
            'created_at' => $step->created_at?->toISOString(),
            'updated_at' => $step->updated_at?->toISOString(),
        ];

        if ($step->type === 'vocabulary') {
            $kataRecord = \App\Models\Kata::with('kanjis')->where('kata', $step->character_symbol)->first();
            $serialized['kata_id'] = $kataRecord?->id;
            $serialized['kana'] = $kataRecord?->kana;
            $serialized['kanji_id'] = $kataRecord ? $kataRecord->kanjis->pluck('id')->join(',') : '';
            $serialized['kanji_text'] = $kataRecord && $kataRecord->kanjis->isNotEmpty() ? $kataRecord->kanjis->map(fn($k) => "{$k->kanji} ({$k->makna})")->join(', ') : '';
            $serialized['tipe_kata'] = $kataRecord?->tipe_kata;
            $serialized['deskripsi'] = $kataRecord?->deskripsi;
            $serialized['level'] = $kataRecord?->level;
            $serialized['tampilkan_kanji'] = $step->quiz_instruction !== '0';
        }

        if ($step->type === 'quiz') {
            $serialized['quiz_type'] = $step->content;
            $quizTampilkanKanji = $step->character_pronunciation !== '0';
            $serialized['quiz_tampilkan_kanji'] = $quizTampilkanKanji;

            if ($step->character_symbol === 'random') {
                $serialized['quiz_question_type'] = 'random';
                $serialized['quiz_kata_id'] = null;
                $serialized['quiz_kata_meaning'] = null;
                $serialized['quiz_kata_romaji'] = null;
            } else {
                $kataRecord = \App\Models\Kata::where('kata', $step->character_symbol)->first();
                $serialized['quiz_question_type'] = $kataRecord ? 'vocabulary' : 'character';
                $serialized['quiz_kata_id'] = $kataRecord?->id;
                $serialized['quiz_kata_meaning'] = $kataRecord?->arti;
                $serialized['quiz_kata_romaji'] = $kataRecord?->romaji;

                if ($kataRecord && !$quizTampilkanKanji) {
                    $serialized['character_symbol'] = $kataRecord->kana;
                    if ($step->quiz_query === $step->character_symbol) {
                        $serialized['quiz_query'] = $kataRecord->kana;
                    }
                }
            }

            if (!$quizTampilkanKanji && !empty($step->quiz_options)) {
                $convertedOptions = [];
                foreach ($step->quiz_options as $option) {
                    $optKata = \App\Models\Kata::where('kata', $option)->orWhere('arti', $option)->first();
                    if ($optKata) {
                        if ($option === $optKata->kata) {
                            $convertedOptions[] = $optKata->kana;
                        } else {
                            $convertedOptions[] = $option;
                        }
                    } else {
                        $convertedOptions[] = $option;
                    }
                }
                $serialized['quiz_options'] = $convertedOptions;
            }
        }

        return $serialized;
    }

    public function downloadTemplate(): \Symfony\Component\HttpFoundation\Response
    {
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $headers = [
            'Tipe',
            'Konten Teks',
            'Simbol Huruf',
            'Cara Baca',
            'Instruksi Kuis',
            'Pertanyaan',
            'Pilihan 1',
            'Pilihan 2',
            'Pilihan 3',
            'Pilihan 4',
            'Jawaban Benar (0-3)'
        ];

        $data = [
            ['text', 'Ini adalah isi teks pengantar', '', '', '', '', '', '', '', '', ''],
            ['character', '', 'あ', 'a', '', '', '', '', '', '', ''],
            ['vocabulary', 'Makan', '食べる', 'taberu', '', '', '', '', '', '', ''],
            ['quiz', '', '', '', 'PILIH JAWABAN YANG BENAR', 'あ', 'a', 'i', 'u', 'e', '0']
        ];

        // Write headers
        foreach ($headers as $colIdx => $header) {
            $sheet->setCellValue([$colIdx + 1, 1], $header);
        }

        // Write data
        foreach ($data as $rowIdx => $row) {
            foreach ($row as $colIdx => $value) {
                $sheet->setCellValue([$colIdx + 1, $rowIdx + 2], $value);
            }
        }

        // Auto-size columns and wrap text
        foreach (range(1, count($headers)) as $col) {
            $sheet->getColumnDimensionByColumn($col)->setAutoSize(true);
        }
        $sheet->getStyle('A1:Z100')->getAlignment()->setWrapText(true);

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $tempFile = tempnam(sys_get_temp_dir(), 'xlsx');
        $writer->save($tempFile);

        $content = file_get_contents($tempFile);
        unlink($tempFile);

        return response($content, 200, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="template-langkah-pembelajaran.xlsx"'
        ]);
    }

    public function importSteps(Request $request, Node $node): JsonResponse
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls'
        ]);

        $file = $request->file('file');

        try {
            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($file->getRealPath());
            $sheet = $spreadsheet->getActiveSheet();
            $rows = $sheet->toArray();
        } catch (\Exception $e) {
            return response()->json(['message' => 'Gagal membaca file Excel: ' . $e->getMessage()], 422);
        }

        if (count($rows) <= 1) {
            return response()->json(['message' => 'Berkas Excel kosong atau tidak memiliki baris data.'], 422);
        }

        // Remove header row
        array_shift($rows);

        try {
            DB::transaction(function () use ($rows, $node) {
                foreach ($rows as $row) {
                    if (empty($row) || empty($row[0])) {
                        continue;
                    }

                    $type = trim($row[0]);
                    if (!in_array($type, ['text', 'character', 'quiz', 'vocabulary'])) {
                        throw new \InvalidArgumentException('Tipe langkah tidak valid. Harus "text", "character", "quiz", atau "vocabulary".');
                    }

                    $nextOrder = ((int) NodeStep::query()->where('node_id', $node->id)->max('sort_order')) + 1;

                    $stepData = [
                        'id' => (string) Str::ulid(),
                        'node_id' => $node->id,
                        'type' => $type,
                        'sort_order' => $nextOrder,
                    ];

                    if ($type === 'text') {
                        $stepData['content'] = $row[1] ?? '';
                    } elseif ($type === 'character') {
                        $symbol = trim($row[2] ?? '');
                        $reading = trim($row[3] ?? '');
                        if ($symbol) {
                            $existingStep = NodeStep::query()
                                ->where('type', 'character')
                                ->whereRaw('BINARY character_symbol = ?', [$symbol])
                                ->first();
                            if ($existingStep) {
                                $existingNode = Node::query()->find($existingStep->node_id);
                                $nodeName = $existingNode ? $existingNode->title : 'lain';
                                throw new \InvalidArgumentException("huruf sudah ada di node {$nodeName}");
                            }
                            $exists = \App\Models\Huruf::where('simbol', $symbol)->exists();
                            if (!$exists) {
                                $tipe = preg_match('/[\x{30A0}-\x{30FF}]/u', $symbol) ? 'katakana' : 'hiragana';
                                \App\Models\Huruf::create([
                                    'id' => (string) Str::uuid(),
                                    'tipe' => $tipe,
                                    'simbol' => $symbol,
                                    'cara_baca' => $reading,
                                ]);
                            } elseif (empty($reading)) {
                                $reading = \App\Models\Huruf::where('simbol', $symbol)->value('cara_baca') ?? '';
                            }
                        }
                        $stepData['character_symbol'] = $symbol;
                        $stepData['character_pronunciation'] = $reading;
                    } elseif ($type === 'vocabulary') {
                        $kata = trim($row[2] ?? '');
                        $reading = trim($row[3] ?? '');
                        $meaning = trim($row[1] ?? '');
                        if ($kata) {
                            if (!\App\Models\Kata::where('kata', $kata)->exists()) {
                                \App\Models\Kata::create([
                                    'id' => (string) Str::uuid(),
                                    'kata' => $kata,
                                    'romaji' => $reading,
                                    'arti' => $meaning,
                                ]);
                            }
                        }
                        $stepData['character_symbol'] = $kata;
                        $stepData['character_pronunciation'] = $reading;
                        $stepData['content'] = $meaning;
                        $stepData['quiz_instruction'] = '1'; // default tampilkan_kanji = true
                    } elseif ($type === 'quiz') {
                        $stepData['quiz_instruction'] = !empty($row[4]) ? $row[4] : 'PILIH JAWABAN YANG BENAR';
                        $stepData['quiz_query'] = $row[5] ?? '';
                        $stepData['quiz_options'] = [
                            !empty($row[6]) ? $row[6] : '',
                            !empty($row[7]) ? $row[7] : '',
                            !empty($row[8]) ? $row[8] : '',
                            !empty($row[9]) ? $row[9] : '',
                        ];
                        $stepData['quiz_correct_index'] = isset($row[10]) ? (int) $row[10] : 0;
                    }

                    NodeStep::query()->create($stepData);
                }
            });
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }

        return response()->json(['message' => 'Langkah pembelajaran berhasil di-import.']);
    }

    public function checkLock(Request $request): JsonResponse
    {
        $kata = $request->query('kata');
        $excludeStepId = $request->query('exclude_step_id');

        if (empty($kata)) {
            return response()->json([
                'disabled' => false,
                'force_value' => null,
            ]);
        }

        $existingSteps = NodeStep::query()
            ->where('type', 'vocabulary')
            ->whereRaw('BINARY character_symbol = ?', [$kata]);

        if ($excludeStepId) {
            $existingSteps->where('id', '!=', $excludeStepId);
        }

        $steps = $existingSteps->get();

        if ($steps->isEmpty()) {
            return response()->json([
                'disabled' => false,
                'force_value' => null,
            ]);
        }

        $firstStep = $steps->first();
        $existingTampilkanKanji = $firstStep->quiz_instruction !== '0';
        $forceValue = !$existingTampilkanKanji;

        return response()->json([
            'disabled' => true,
            'force_value' => $forceValue,
        ]);
    }
}
