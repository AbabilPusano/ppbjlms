<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NodeStep extends Model
{
    protected $table = 'node_steps';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id',
        'node_id',
        'type',
        'content',
        'character_symbol',
        'character_pronunciation',
        'quiz_instruction',
        'quiz_query',
        'quiz_options',
        'quiz_correct_index',
        'sort_order',
    ];

    protected $casts = [
        'quiz_options' => 'array',
        'quiz_correct_index' => 'integer',
        'sort_order' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function node(): BelongsTo
    {
        return $this->belongsTo(Node::class, 'node_id');
    }
}
