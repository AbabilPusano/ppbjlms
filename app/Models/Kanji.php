<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['id', 'kanji', 'onyomi', 'kunyomi', 'makna', 'level'])]
class Kanji extends Model
{
    protected $table = 'kanjis';

    public $incrementing = false;

    protected $keyType = 'string';

    public function katas(): HasMany
    {
        return $this->hasMany(Kata::class, 'kanji_id');
    }

    public function katasMany(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Kata::class, 'kata_kanji', 'kanji_id', 'kata_id')->withTimestamps();
    }
}
