<?php

namespace App\Http\Controllers;

use App\Models\Level;
use App\Models\Section;
use App\Models\Unit;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class GuruUnitApiController extends Controller
{
    public function levels(): JsonResponse
    {
        $levels = Level::query()
            ->withCount(['units' => fn ($query) => $query->where('is_active', true)])
            ->orderBy('code')
            ->get()
            ->map(fn (Level $level): array => [
                'id' => $level->id,
                'code' => $level->code,
                'name' => $level->name,
                'units_count' => $level->units_count,
            ]);

        return response()->json(['data' => $levels]);
    }

    public function units(Level $level): JsonResponse
    {
        $units = $level->units()
            ->get()
            ->map(fn (Unit $unit): array => $this->serializeUnit($unit));

        return response()->json([
            'data' => [
                'level' => [
                    'id' => $level->id,
                    'code' => $level->code,
                    'name' => $level->name,
                ],
                'units' => $units,
                'units_count' => $units->count(),
            ],
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $this->validateUnit($request);
        $level = Level::query()->findOrFail($validated['level_id']);
        $section = $this->sectionForLevel($level);
        $sortOrder = $validated['sort_order'] ?? ((int) Unit::query()->where('level_id', $level->id)->max('sort_order')) + 1;

        $unit = Unit::query()->create([
            'id' => (string) Str::ulid(),
            'sectionId' => $section->id,
            'level_id' => $level->id,
            'title' => $validated['title'],
            'description' => $validated['description'],
            'type' => $validated['category_romaji'],
            'category_japanese' => $validated['category_japanese'],
            'category_romaji' => $validated['category_romaji'],
            'order' => $sortOrder,
            'sort_order' => $sortOrder,
            'is_active' => $validated['is_active'] ?? true,
        ]);

        return response()->json(['data' => $this->serializeUnit($unit)], 201);
    }

    public function update(Request $request, Unit $unit): JsonResponse
    {
        $validated = $this->validateUnit($request);
        $level = Level::query()->findOrFail($validated['level_id']);
        $section = $this->sectionForLevel($level);
        $sortOrder = $validated['sort_order'] ?? $unit->sort_order;

        $unit->update([
            'sectionId' => $section->id,
            'level_id' => $level->id,
            'title' => $validated['title'],
            'description' => $validated['description'],
            'type' => $validated['category_romaji'],
            'category_japanese' => $validated['category_japanese'],
            'category_romaji' => $validated['category_romaji'],
            'order' => $sortOrder,
            'sort_order' => $sortOrder,
            'is_active' => $validated['is_active'] ?? true,
        ]);

        return response()->json(['data' => $this->serializeUnit($unit->refresh())]);
    }

    public function destroy(Unit $unit): JsonResponse
    {
        $unit->delete();

        return response()->json(['message' => 'Unit berhasil dihapus.']);
    }

    public function reorder(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'level_id' => ['required', 'integer', Rule::exists('levels', 'id')],
            'unit_ids' => ['required', 'array', 'min:1'],
            'unit_ids.*' => ['required', 'string', Rule::exists('units', 'id')],
        ]);

        DB::transaction(function () use ($validated): void {
            foreach (array_values($validated['unit_ids']) as $index => $unitId) {
                Unit::query()
                    ->where('level_id', $validated['level_id'])
                    ->whereKey($unitId)
                    ->update([
                        'sort_order' => $index,
                        'order' => $index,
                    ]);
            }
        });

        return $this->units(Level::query()->findOrFail($validated['level_id']));
    }

    /**
     * @return array<string, mixed>
     */
    private function validateUnit(Request $request): array
    {
        return $request->validate([
            'level_id' => ['required', 'integer', Rule::exists('levels', 'id')],
            'title' => ['required', 'string', 'max:191'],
            'description' => ['nullable', 'string', 'max:2000'],
            'category_japanese' => ['required', 'string', 'max:191'],
            'category_romaji' => ['required', 'string', 'max:191'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['sometimes', 'boolean'],
        ]);
    }

    private function sectionForLevel(Level $level): Section
    {
        return Section::query()->firstOrCreate(
            ['name' => $level->code],
            [
                'id' => Str::slug($level->code) ?: (string) Str::ulid(),
                'description' => $level->name,
            ],
        );
    }

    /**
     * @return array<string, mixed>
     */
    private function serializeUnit(Unit $unit): array
    {
        return [
            'id' => $unit->id,
            'level_id' => $unit->level_id,
            'title' => $unit->title,
            'description' => $unit->description,
            'category_japanese' => $unit->category_japanese,
            'category_romaji' => $unit->category_romaji,
            'sort_order' => $unit->sort_order,
            'is_active' => $unit->is_active,
            'created_at' => $unit->created_at?->toISOString(),
            'updated_at' => $unit->updated_at?->toISOString(),
        ];
    }
}
