<?php

namespace App\Http\Controllers;

use App\Models\Level;
use App\Models\Node;
use App\Models\Question;
use App\Models\Unit;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
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
}
