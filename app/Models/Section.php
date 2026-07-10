<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['id', 'name', 'description'])]
class Section extends Model
{
    public $incrementing = false;

    protected $keyType = 'string';

    public function units(): HasMany
    {
        return $this->hasMany(Unit::class, 'sectionId')->ordered();
    }

    public function stories(): HasMany
    {
        return $this->hasMany(Story::class, 'sectionId')->orderBy('order');
    }
}
