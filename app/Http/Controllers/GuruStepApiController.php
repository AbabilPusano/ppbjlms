<?php

namespace App\Http\Controllers;

use App\Models\Node;
use App\Models\NodeStep;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class GuruStepApiController extends Controller
{
    public function index(Node $node): JsonResponse
    {
        $steps = $node->steps()
            ->get()
            ->map(fn (NodeStep $step): array => $this->serializeStep($step));

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
            'type' => 'required|string|in:text,character,quiz',
            'content' => 'nullable|string',
            'character_symbol' => 'nullable|string|max:50',
            'character_pronunciation' => 'nullable|string|max:100',
            'quiz_instruction' => 'nullable|string',
            'quiz_query' => 'nullable|string',
            'quiz_options' => 'nullable|array',
            'quiz_options.*' => 'nullable|string',
            'quiz_correct_index' => 'nullable|integer',
            'sort_order' => 'nullable|integer',
        ]);
    }

    private function serializeStep(NodeStep $step): array
    {
        return [
            'id' => $step->id,
            'node_id' => $step->node_id,
            'type' => $step->type,
            'type_label' => match($step->type) {
                'text' => 'Teks Pengantar',
                'character' => 'Huruf Baru',
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
    }
}
