<?php

namespace App\Services;

use App\Models\Node;
use Illuminate\Support\Collection;

class LessonContentBuilder
{
    public static function getStudiedKanjis(int|string $userId): array
    {
        $completedNodeIds = \App\Models\UserProgress::where('userId', $userId)
            ->where('status', 'completed')
            ->pluck('nodeId');

        $vocabSymbols = \App\Models\NodeStep::whereIn('node_id', $completedNodeIds)
            ->where('type', 'vocabulary')
            ->pluck('character_symbol')
            ->toArray();

        $kanjisFromVocab = \App\Models\Kanji::whereHas('katasMany', function ($q) use ($vocabSymbols) {
            $q->whereIn('kata', $vocabSymbols);
        })->pluck('kanji')->toArray();

        $kanjisFromCharSteps = \App\Models\NodeStep::whereIn('node_id', $completedNodeIds)
            ->where('type', 'character')
            ->pluck('character_symbol')
            ->toArray();

        return array_unique(array_merge($kanjisFromVocab, $kanjisFromCharSteps));
    }

    /**
     * Build the lesson player payload from the authoring data for a node.
     *
     * @return Collection<int, array<string, mixed>>
     */
    public function build(Node $node): Collection
    {
        $questions = $node->steps()
            ->orderBy('sort_order')
            ->get()
            ->map(function ($step) use ($node): array {
                if ($step->type === 'character') {
                    return [
                        'type' => 'new-letter',
                        'letter' => $step->character_symbol,
                        'reading' => $step->character_pronunciation,
                    ];
                }

                if ($step->type === 'vocabulary') {
                    $kata = \App\Models\Kata::with('kanjis')->where('kata', $step->character_symbol)->first();
                    $levelCode = $node->unit && $node->unit->level ? strtolower($node->unit->level->code) : '';
                    $isHurufLevel = ($levelCode === 'huruf');
                    $displayWord = $isHurufLevel ? ($kata?->kana ?? $step->character_symbol) : $step->character_symbol;

                    $kanjiText = '';
                    $kanjiMeaning = '';
                    if ($kata && $kata->kanjis->isNotEmpty()) {
                        $kanjiText = $kata->kanjis->pluck('kanji')->join(', ');
                        $kanjiMeaning = $kata->kanjis->map(fn($k) => $k->makna)->join(', ');
                    } elseif ($kata && $kata->kanji) {
                        $kanjiText = $kata->kanji->kanji;
                        $kanjiMeaning = $kata->kanji->makna;
                    }

                    return [
                        'type' => 'new-vocabulary',
                        'letter' => $displayWord,
                        'reading' => $step->character_pronunciation,
                        'meaning' => $step->content,
                        'kanji' => $kanjiText,
                        'kanji_meaning' => $kanjiMeaning,
                        'tipe_kata' => $kata?->tipe_kata ?? '',
                        'deskripsi' => $kata?->deskripsi ?? '',
                        'level' => $kata?->level ?? '',
                    ];
                }

                if ($step->type === 'quiz') {
                    $stepToMap = $step;
                    if ($step->character_symbol === 'random') {
                        // 1. Get all prior quiz steps in the current node (with lower sort_order)
                        $priorQuizStepsInNode = \App\Models\NodeStep::where('node_id', $node->id)
                            ->where('type', 'quiz')
                            ->where('character_symbol', '!=', 'random')
                            ->where('sort_order', '<', $step->sort_order)
                            ->get();

                        // 2. Get all quiz steps from nodes prior to this one in the same unit
                        $priorNodeIds = \App\Models\Node::where('unitId', $node->unitId)
                            ->where('order', '<', $node->order)
                            ->pluck('id');

                        $priorQuizStepsInOtherNodes = \App\Models\NodeStep::whereIn('node_id', $priorNodeIds)
                            ->where('type', 'quiz')
                            ->where('character_symbol', '!=', 'random')
                            ->get();

                        // Combine all candidate quiz steps
                        $candidates = $priorQuizStepsInNode->concat($priorQuizStepsInOtherNodes);

                        if ($candidates->isEmpty()) {
                            // Fallback to ANY other quiz steps in this node
                            $candidates = \App\Models\NodeStep::where('node_id', $node->id)
                                ->where('type', 'quiz')
                                ->where('character_symbol', '!=', 'random')
                                ->get();
                            
                            if ($candidates->isEmpty()) {
                                // Fallback to any quiz step in the same unit
                                $unitNodeIds = \App\Models\Node::where('unitId', $node->unitId)->pluck('id');
                                $candidates = \App\Models\NodeStep::whereIn('node_id', $unitNodeIds)
                                    ->where('type', 'quiz')
                                    ->where('character_symbol', '!=', 'random')
                                    ->get();
                            }
                        }

                        if ($candidates->isNotEmpty()) {
                            $stepToMap = $candidates->random();
                        } else {
                            // Mock fallback
                            return [
                                'type' => 'select-image',
                                'question' => 'Contoh Kuis (Kuis acak belum memiliki referensi)',
                                'instruction' => 'PILIH JAWABAN YANG BENAR',
                                'answer' => 'Benar',
                                'options' => [
                                    ['id' => 1, 'text' => 'Benar', 'sub' => '', 'image' => '', 'correct' => true],
                                    ['id' => 2, 'text' => 'Salah', 'sub' => '', 'image' => '', 'correct' => false],
                                    ['id' => 3, 'text' => 'Salah', 'sub' => '', 'image' => '', 'correct' => false],
                                    ['id' => 4, 'text' => 'Salah', 'sub' => '', 'image' => '', 'correct' => false],
                                ],
                            ];
                        }
                    }

                    $options = collect($stepToMap->quiz_options)->map(function ($option, $index) use ($stepToMap): array {
                        return [
                            'id' => $index + 1,
                            'text' => $option,
                            'sub' => '',
                            'image' => '',
                            'correct' => $index === $stepToMap->quiz_correct_index,
                        ];
                    })->all();

                    $quizQuery = $stepToMap->quiz_query;
                    $quizInstruction = $stepToMap->quiz_instruction;

                    $kata = \App\Models\Kata::with('kanjis')->where('kata', $stepToMap->character_symbol)->first();
                    if ($kata) {
                        $user = auth()->user();
                        $studiedKanjis = $user ? self::getStudiedKanjis($user->id) : [];

                        $associatedKanjis = $kata->kanjis->pluck('kanji')->toArray();
                        $allStudied = collect($associatedKanjis)->every(fn($k) => in_array($k, $studiedKanjis));

                        if (!$allStudied) {
                            if ($quizQuery === $kata->kata) {
                                $quizQuery = $kata->kana;
                            }
                        }

                        $options = collect($stepToMap->quiz_options)->map(function ($option, $index) use ($stepToMap, $studiedKanjis): array {
                            $displayText = $option;
                            $optKata = \App\Models\Kata::with('kanjis')->where('kata', $option)->first();
                            if ($optKata) {
                                $optKanjis = $optKata->kanjis->pluck('kanji')->toArray();
                                $optAllStudied = collect($optKanjis)->every(fn($k) => in_array($k, $studiedKanjis));
                                if (!$optAllStudied) {
                                    $displayText = $optKata->kana;
                                }
                            }
                            return [
                                'id' => $index + 1,
                                'text' => $displayText,
                                'sub' => '',
                                'image' => '',
                                'correct' => $index === $stepToMap->quiz_correct_index,
                            ];
                        })->all();
                    }

                    $correctOption = collect($options)->firstWhere('correct', true);
                    $answerText = $correctOption ? $correctOption['text'] : '';
                    $shuffledOptions = collect($options)->shuffle()->values()->all();

                    return [
                        'type' => 'select-image',
                        'question' => $quizQuery,
                        'instruction' => $quizInstruction,
                        'answer' => $answerText,
                        'options' => $shuffledOptions,
                    ];
                }

                return [
                    'type' => 'new-letter',
                    'letter' => 'Materi',
                    'reading' => $step->content,
                ];
            });

        if ($questions->isNotEmpty()) {
            return $questions;
        }

        return $node->questions()
            ->orderBy('order')
            ->get()
            ->map(function ($question): array {
                $options = is_array($question->options) ? $question->options : [];
                $correctAnswer = is_array($question->correctAnswer) ? $question->correctAnswer : [];

                $mappedOptions = collect($options)->map(function ($option, $index) use ($correctAnswer): array {
                    $value = is_array($option) ? ($option['text'] ?? $option) : $option;
                    $isCorrect = false;

                    if (isset($correctAnswer['text'])) {
                        $isCorrect = $value === $correctAnswer['text'];
                    } elseif (is_array($correctAnswer)) {
                        $isCorrect = in_array($value, $correctAnswer);
                    } else {
                        $isCorrect = $value === $correctAnswer;
                    }

                    return [
                        'id' => $index + 1,
                        'text' => $value,
                        'sub' => is_array($option) ? ($option['sub'] ?? '') : '',
                        'image' => is_array($option) ? ($option['image'] ?? '') : '',
                        'correct' => $isCorrect,
                    ];
                })->all();

                if (isset($correctAnswer['text'])) {
                    $answerText = $correctAnswer['text'];
                } elseif (is_array($correctAnswer)) {
                    $answerText = implode(', ', $correctAnswer);
                } else {
                    $answerText = (string) $correctAnswer;
                }

                return [
                    'type' => $question->type === 'drag-drop' ? 'translate-to-ja' : 'select-image',
                    'question' => $question->question,
                    'answer' => $answerText,
                    'options' => $mappedOptions,
                    'correctIds' => [1],
                ];
            });
    }
}
