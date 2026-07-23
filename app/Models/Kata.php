<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

#[Fillable(['id', 'kata', 'kana', 'romaji', 'arti', 'kanji_id', 'deskripsi', 'tipe_kata', 'level'])]
class Kata extends Model
{
    protected $table = 'katas';

    public $incrementing = false;

    protected $keyType = 'string';

    public function kanji(): BelongsTo
    {
        return $this->belongsTo(Kanji::class, 'kanji_id');
    }

    public function kalimats(): BelongsToMany
    {
        return $this->belongsToMany(Kalimat::class, 'kalimat_kata', 'kata_id', 'kalimat_id');
    }
}
