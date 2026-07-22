<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['id', 'kalimat', 'romaji', 'arti', 'deskripsi'])]
class Kalimat extends Model
{
    protected $table = 'kalimats';

    public $incrementing = false;

    protected $keyType = 'string';

    public function katas(): BelongsToMany
    {
        return $this->belongsToMany(Kata::class, 'kalimat_kata', 'kalimat_id', 'kata_id');
    }

    public function grammars(): HasMany
    {
        return $this->hasMany(Grammar::class, 'kalimat_id');
    }
}
