<?php

namespace App\Services;

use App\Models\Node;
use Illuminate\Support\Collection;

class LessonContentBuilder
{
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
            ->map(function ($step): array {
                if ($step->type === 'character') {
                    return [
                        'type' => 'new-letter',
                        'letter' => $step->character_symbol,
                        'reading' => $step->character_pronunciation,
                    ];
                }

                if ($step->type === 'quiz') {
                    $options = collect($step->quiz_options)->map(function ($option, $index) use ($step): array {
                        return [
                            'id' => $index + 1,
                            'text' => $option,
                            'sub' => '',
                            'image' => '',
                            'correct' => $index === $step->quiz_correct_index,
                        ];
                    })->all();

                    return [
                        'type' => 'select-image',
                        'question' => $step->quiz_query,
                        'instruction' => $step->quiz_instruction,
                        'answer' => $step->quiz_options[$step->quiz_correct_index] ?? '',
                        'options' => $options,
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
