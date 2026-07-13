<?php

namespace App\Http\Controllers;

use App\Models\Section;
use App\Models\Unit;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class StudentDashboardController extends Controller
{
    public function __invoke(Request $request): View
    {
        $user = $request->user();

        $section = Section::query()
            ->whereHas('units')
            ->orderByDesc('name')
            ->first()
            ?? Section::query()->orderByDesc('name')->first();

        if (! $section) {
            return view('welcome', [
                'activeMenu' => 'LEARN',
                'section' => null,
                'units' => collect(),
                'currentUnit' => null,
                'steps' => collect(),
                'completedSteps' => 0,
                'totalSteps' => 0,
                'continueUrl' => null,
                'continueLabel' => 'Belum ada unit',
                'focusStep' => null,
            ]);
        }

        $section->load([
            'units.nodes.progress' => fn ($query) => $query
                ->where('userId', $user->id)
                ->where('status', 'completed'),
        ]);

        $units = $this->buildUnits($section->units);
        $currentUnit = $this->findCurrentUnit($units);
        $steps = $currentUnit ? $this->buildSteps($currentUnit) : collect();
        $focusStep = $steps->firstWhere('status', 'active') ?? $steps->firstWhere('status', 'completed');

        return view('welcome', [
            'activeMenu' => 'LEARN',
            'section' => $section,
            'units' => $units,
            'currentUnit' => $currentUnit,
            'steps' => $steps,
            'completedSteps' => $units->sum('completed'),
            'totalSteps' => $units->sum('total'),
            'continueUrl' => $focusStep['url'] ?? null,
            'continueLabel' => $currentUnit ? 'Lanjut '.$currentUnit['name'] : 'Belum ada unit',
            'focusStep' => $focusStep,
        ]);
    }

    /**
     * @param  Collection<int, Unit>  $units
     * @return Collection<int, array<string, mixed>>
     */
    private function buildUnits(Collection $units): Collection
    {
        $previousCompleted = true;

        return $units->values()->map(function (Unit $unit, int $index) use (&$previousCompleted): array {
            $nodes = $unit->nodes->values();
            $total = $nodes->count();
            $completed = $nodes->filter(fn ($node) => $node->progress->isNotEmpty())->count();
            $unlocked = $index === 0 || $previousCompleted;
            $progress = $total > 0 ? (int) round(($completed / $total) * 100) : 0;
            $isCompleted = $total > 0 && $completed === $total;
            $nextNode = $nodes->first(fn ($node) => $node->progress->isEmpty()) ?? $nodes->last();

            $status = match (true) {
                ! $unlocked => 'locked',
                $isCompleted => 'completed',
                $completed > 0 => 'in_progress',
                default => 'not_started',
            };

            $unitData = [
                'id' => $unit->id,
                'name' => 'Unit '.($index + 1),
                'section_name' => $unit->section?->name,
                'topic' => $unit->title,
                'description' => $unit->description,
                'type' => $unit->type,
                'status' => $status,
                'statusLabel' => $this->unitStatusLabel($status),
                'progress' => $progress,
                'completed' => $completed,
                'total' => $total,
                'lessonCount' => $nodes->where('type', 'lesson')->count(),
                'quizCount' => $nodes->where('type', 'quiz')->count(),
                'next' => $this->nextUnitLabel($status, $completed, $total),
                'unlocked' => $unlocked,
                'isCompleted' => $isCompleted,
                'url' => $this->lessonUrl($unit, $index, $nextNode?->order ?? 0),
                'model' => $unit,
                'position' => $index + 1,
            ];

            $previousCompleted = $isCompleted;

            return $unitData;
        });
    }

    /**
     * @param  Collection<int, array<string, mixed>>  $units
     * @return array<string, mixed>|null
     */
    private function findCurrentUnit(Collection $units): ?array
    {
        return $units->firstWhere('status', 'in_progress')
            ?? $units->firstWhere('status', 'not_started')
            ?? $units->last(fn (array $unit) => $unit['status'] === 'completed');
    }

    /**
     * @param  array<string, mixed>  $unit
     * @return Collection<int, array<string, mixed>>
     */
    private function buildSteps(array $unit): Collection
    {
        /** @var Unit $unitModel */
        $unitModel = $unit['model'];
        $firstOpenStepFound = false;

        return $unitModel->nodes->values()->map(function ($node, int $index) use ($unit, $unitModel, &$firstOpenStepFound): array {
            $completed = $node->progress->isNotEmpty();
            $status = 'locked';

            if ($unit['unlocked']) {
                if ($completed) {
                    $status = 'completed';
                } elseif (! $firstOpenStepFound) {
                    $status = 'active';
                    $firstOpenStepFound = true;
                }
            }

            return [
                'id' => $node->id,
                'index' => $index + 1,
                'title' => $node->title,
                'subtitle' => $this->stepSubtitle($node->type),
                'status' => $status,
                'label' => $this->stepStatusLabel($status),
                'type' => $this->stepTypeLabel($node->type),
                'url' => $this->lessonUrl($unitModel, ((int) $unit['position']) - 1, $node->order),
            ];
        });
    }

    private function unitStatusLabel(string $status): string
    {
        return match ($status) {
            'completed' => 'Selesai',
            'in_progress' => 'Sedang berjalan',
            'locked' => 'Terkunci',
            default => 'Belum dimulai',
        };
    }

    private function stepStatusLabel(string $status): string
    {
        return match ($status) {
            'completed' => 'Selesai',
            'active' => 'Aktif',
            default => 'Terkunci',
        };
    }

    private function nextUnitLabel(string $status, int $completed, int $total): string
    {
        return match ($status) {
            'completed' => 'Siap diulang',
            'in_progress' => 'Lanjut step '.min($completed + 1, max($total, 1)),
            'locked' => 'Selesaikan unit sebelumnya',
            default => $total > 0 ? 'Mulai step 1' : 'Belum ada step',
        };
    }

    private function stepSubtitle(string $type): string
    {
        return match ($type) {
            'quiz' => 'Quiz singkat',
            'checkpoint' => 'Checkpoint',
            default => 'Materi belajar',
        };
    }

    private function stepTypeLabel(string $type): string
    {
        return match ($type) {
            'quiz' => 'Quiz',
            'checkpoint' => 'Checkpoint',
            default => 'Materi',
        };
    }

    private function lessonUrl(Unit $unit, int $unitIndex, int $nodeOrder): string
    {
        return route('lesson.show', [
            'unit' => $unitIndex + 1,
            'level' => $nodeOrder + 1,
        ]);
    }
}
