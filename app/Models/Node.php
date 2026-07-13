<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['id', 'unitId', 'title', 'type', 'order'])]
class Node extends Model
{
    public $incrementing = false;

    protected $keyType = 'string';

    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class, 'unitId');
    }

    public function questions(): HasMany
    {
        return $this->hasMany(Question::class, 'nodeId')->orderBy('order');
    }

    public function steps(): HasMany
    {
        return $this->hasMany(NodeStep::class, 'node_id')->orderBy('sort_order')->orderBy('created_at');
    }

    public function progress(): HasMany
    {
        return $this->hasMany(UserProgress::class, 'nodeId');
    }
}
