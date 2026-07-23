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



        if ($validated['type'] === 'vocabulary') {
            $kata = $validated['character_symbol'] ?? null;
            if ($kata) {
                if (\App\Models\Kata::where('kata', $kata)->exists()) {
                    return response()->json(['message' => 'Kata tersebut sudah ada di database referensi.'], 422);
                }
                \App\Models\Kata::create([
                    'id' => (string) Str::uuid(),
                    'kata' => $kata,
                    'romaji' => $validated['character_pronunciation'] ?? '',
                    'arti' => $validated['content'] ?? '',
                    'kanji_id' => $request->input('kanji_id'),
                    'tipe_kata' => $request->input('tipe_kata'),
                    'deskripsi' => $request->input('deskripsi'),
                    'level' => $request->input('level'),
                ]);
            }
        }

        $sortOrder = $validated['sort_order'] ?? ((int) NodeStep::query()->where('node_id', $node->id)->max('sort_order')) + 1;

        $step = NodeStep::query()->create([
            'id' => (string) Str::ulid(),
            'node_id' => $node->id,
            'type' => $validated['type'],
            'content' => $validated['content'] ?? null,
            'character_symbol' => $validated['character_symbol'] ?? null,
            'character_pronunciation' => $validated['character_pronunciation'] ?? null,
            'quiz_instruction' => $validated['quiz_instruction'] ?? null,
            'quiz_query' => $validated['quiz_query'] ?? null,
            'quiz_options' => $validated['quiz_options'] ?? null,
            'quiz_correct_index' => isset($validated['quiz_correct_index']) ? (int) $validated['quiz_correct_index'] : null,
            'sort_order' => $sortOrder,
        ]);

        return response()->json(['data' => $this->serializeStep($step)], 201);
    }

    public function update(Request $request, NodeStep $step): JsonResponse
    {
        $validated = $this->validateStep($request, $step);



        if ($validated['type'] === 'vocabulary') {
            $kata = $validated['character_symbol'] ?? null;
            if ($kata) {
                if ($kata !== $step->character_symbol) {
                    if (\App\Models\Kata::where('kata', $kata)->exists()) {
                        return response()->json(['message' => 'Kata tersebut sudah ada di database referensi.'], 422);
                    }
                }
                $kataRecord = \App\Models\Kata::where('kata', $step->character_symbol)->first();
                $kataData = [
                    'kata' => $kata,
                    'romaji' => $validated['character_pronunciation'] ?? '',
                    'arti' => $validated['content'] ?? '',
                    'kanji_id' => $request->input('kanji_id'),
                    'tipe_kata' => $request->input('tipe_kata'),
                    'deskripsi' => $request->input('deskripsi'),
                    'level' => $request->input('level'),
                ];
                if ($kataRecord) {
                    $kataRecord->update($kataData);
                } else {
                    $kataData['id'] = (string) Str::uuid();
                    \App\Models\Kata::create($kataData);
                }
            }
        }

        $step->update([
            'type' => $validated['type'],
            'content' => $validated['content'] ?? null,
            'character_symbol' => $validated['character_symbol'] ?? null,
            'character_pronunciation' => $validated['character_pronunciation'] ?? null,
            'quiz_instruction' => $validated['quiz_instruction'] ?? null,
            'quiz_query' => $validated['quiz_query'] ?? null,
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
            'kanji_id' => 'nullable|string|exists:kanjis,id',
            'tipe_kata' => 'nullable|string|max:191',
            'deskripsi' => 'nullable|string',
            'level' => 'nullable|string|in:N5,N4,N3,N2,N1',
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
            $kataRecord = \App\Models\Kata::where('kata', $step->character_symbol)->first();
            $serialized['kanji_id'] = $kataRecord?->kanji_id;
            $serialized['kanji_text'] = $kataRecord && $kataRecord->kanji ? "{$kataRecord->kanji->kanji} ({$kataRecord->kanji->makna})" : '';
            $serialized['tipe_kata'] = $kataRecord?->tipe_kata;
            $serialized['deskripsi'] = $kataRecord?->deskripsi;
            $serialized['level'] = $kataRecord?->level;
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
                            if (\App\Models\Kata::where('kata', $kata)->exists()) {
                                throw new \InvalidArgumentException("Kata '{$kata}' sudah ada di database referensi.");
                            }
                            \App\Models\Kata::create([
                                'id' => (string) Str::uuid(),
                                'kata' => $kata,
                                'romaji' => $reading,
                                'arti' => $meaning,
                            ]);
                        }
                        $stepData['character_symbol'] = $kata;
                        $stepData['character_pronunciation'] = $reading;
                        $stepData['content'] = $meaning;
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
}
