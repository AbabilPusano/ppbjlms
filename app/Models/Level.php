<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\HasManyThrough;

#[Fillable(['code', 'name'])]
class Level extends Model
{
    public function units(): HasMany
    {
        return $this->hasMany(Unit::class)->ordered();
    }

    public function materials(): HasManyThrough
    {
        return $this->hasManyThrough(Node::class, Unit::class, 'level_id', 'unitId', 'id', 'id');
    }
}
