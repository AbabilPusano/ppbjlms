<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['id', 'nodeId', 'type', 'question', 'options', 'correctAnswer', 'relatedCharacters', 'order'])]
class Question extends Model
{
    public $incrementing = false;

    protected $keyType = 'string';

    protected function casts(): array
    {
        return [
            'options' => 'array',
            'correctAnswer' => 'array',
            'relatedCharacters' => 'array',
        ];
    }

    public function node(): BelongsTo
    {
        return $this->belongsTo(Node::class, 'nodeId');
    }
}
