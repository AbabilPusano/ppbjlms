<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable(['id', 'sectionId', 'level_id', 'title', 'description', 'type', 'category_japanese', 'category_romaji', 'order', 'sort_order', 'is_active'])]
class Unit extends Model
{
    use SoftDeletes;

    public $incrementing = false;

    protected $keyType = 'string';

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function section(): BelongsTo
    {
        return $this->belongsTo(Section::class, 'sectionId');
    }

    public function level(): BelongsTo
    {
        return $this->belongsTo(Level::class);
    }

    public function nodes(): HasMany
    {
        return $this->hasMany(Node::class, 'unitId')->orderBy('order');
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order')->orderBy('order')->orderBy('created_at');
    }
}
