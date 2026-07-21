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

        // Fetch all levels: Huruf first, then N5→N1
        $levels = \App\Models\Level::query()->get()->sortBy(function ($lvl) {
            if ($lvl->code === 'huruf') return 0;
            $n = (int) ltrim($lvl->code, 'N');
            return $n > 0 ? (6 - $n) : 99;
        })->values();

        // Get selected level ID from query param, fallback to N5 or first level
        $selectedLevelId = $request->query('level_id');
        $level = null;

        if ($selectedLevelId) {
            $level = \App\Models\Level::query()->find($selectedLevelId);
        }

        if (!$level) {
            $level = \App\Models\Level::query()->where('code', 'N5')->first() 
                ?? \App\Models\Level::query()->orderBy('code')->first();
        }

        if (!$level) {
            return view('welcome', [
                'activeMenu' => 'LEARN',
                'section' => null,
                'levels' => collect(),
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

        // Load units matching the level, only active ones, with their node steps progress
        $levelUnits = $level->units()
            ->where('is_active', true)
            ->with(['nodes.progress' => fn ($query) => $query
                ->where('userId', $user->id)
                ->where('status', 'completed')
            ])
            ->get();

        $units = $this->buildUnits($levelUnits);
        $currentUnit = $this->findCurrentUnit($units);
        $steps = $currentUnit ? $this->buildSteps($currentUnit) : collect();
        $focusStep = $steps->firstWhere('status', 'active') ?? $steps->firstWhere('status', 'completed');

        return view('welcome', [
            'activeMenu' => 'LEARN',
            'level' => $level,
            'levels' => $levels,
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
            'completed' => 'Mulai ulang',
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
            'level' => $nodeOrder, // node->order matches level route param
        ]);
    }

    public function showLesson(Request $request, $unitOrder, $levelOrder): View
    {
        $user = $request->user();

        // Find the unit by its relative position (order)
        $unit = Unit::query()
            ->orderBy('order')
            ->skip(max(0, $unitOrder - 1))
            ->firstOrFail();

        // Find the node in that unit by order
        $node = $unit->nodes()
            ->orderBy('order')
            ->skip(max(0, $levelOrder - 1))
            ->firstOrFail();

        // Check unlock restriction: all previous units/nodes must be completed
        $allUnits = Unit::query()->orderBy('order')->get();
        $unlocked = true;
        foreach ($allUnits as $u) {
            $nodes = $u->nodes()->orderBy('order')->get();
            foreach ($nodes as $n) {
                if ($n->id === $node->id) {
                    break 2;
                }
                $progress = $n->progress()->where('userId', $user->id)->where('status', 'completed')->exists();
                if (!$progress) {
                    $unlocked = false;
                    break 2;
                }
            }
        }

        if (!$unlocked) {
            abort(403, 'Materi ini masih terkunci. Silakan selesaikan materi sebelumnya terlebih dahulu.');
        }

        // Fetch steps/questions for this node
        $dbSteps = $node->steps()->orderBy('sort_order')->get();
        
        $questions = $dbSteps->map(function ($step) {
            if ($step->type === 'character') {
                return [
                    'type' => 'new-letter',
                    'letter' => $step->character_symbol,
                    'reading' => $step->character_pronunciation,
                ];
            } elseif ($step->type === 'quiz') {
                $options = collect($step->quiz_options)->map(function ($opt, $idx) use ($step) {
                    return [
                        'id' => $idx + 1,
                        'text' => $opt,
                        'sub' => '',
                        'image' => '',
                        'correct' => $idx === $step->quiz_correct_index,
                    ];
                })->toArray();

                return [
                    'type' => 'select-image', // maps to choice options screen
                    'question' => $step->quiz_query,
                    'instruction' => $step->quiz_instruction,
                    'answer' => $step->quiz_options[$step->quiz_correct_index] ?? '',
                    'options' => $options,
                ];
            } else {
                // text type step, fallback or general info
                return [
                    'type' => 'new-letter',
                    'letter' => 'Materi',
                    'reading' => $step->content,
                ];
            }
        });

        // If no node_steps are found, fallback to questions table if any
        if ($questions->isEmpty()) {
            $dbQuestions = $node->questions()->orderBy('order')->get();
            $questions = $dbQuestions->map(function ($q) {
                $opts = is_array($q->options) ? $q->options : [];
                $correctAns = is_array($q->correctAnswer) ? $q->correctAnswer : [];
                
                // Map database questions to the layout structure
                $optionsMapped = collect($opts)->map(function ($opt, $idx) use ($correctAns) {
                    $val = is_array($opt) ? ($opt['text'] ?? $opt) : $opt;
                    $isCorrect = false;
                    if (isset($correctAns['text'])) {
                        $isCorrect = $val === $correctAns['text'];
                    } elseif (is_array($correctAns)) {
                        $isCorrect = in_array($val, $correctAns);
                    } else {
                        $isCorrect = $val === $correctAns;
                    }

                    return [
                        'id' => $idx + 1,
                        'text' => $val,
                        'sub' => is_array($opt) ? ($opt['sub'] ?? '') : '',
                        'image' => is_array($opt) ? ($opt['image'] ?? '') : '',
                        'correct' => $isCorrect,
                    ];
                })->toArray();

                $ansText = '';
                if (isset($correctAns['text'])) {
                    $ansText = $correctAns['text'];
                } elseif (is_array($correctAns)) {
                    $ansText = implode(', ', $correctAns);
                } else {
                    $ansText = (string)$correctAns;
                }

                return [
                    'type' => $q->type === 'drag-drop' ? 'translate-to-ja' : 'select-image',
                    'question' => $q->question,
                    'answer' => $ansText,
                    'options' => $optionsMapped,
                    'correctIds' => [1], // fallback template correct target
                ];
            });
        }

        return view('lesson-ui', [
            'unitOrder' => $unitOrder,
            'levelOrder' => $levelOrder,
            'node' => $node,
            'questions' => $questions,
            'levelId' => $unit->level_id,
        ]);
    }
}
