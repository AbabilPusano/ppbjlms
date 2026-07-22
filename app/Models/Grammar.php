<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['id', 'struktur', 'kalimat_id', 'deskripsi', 'level'])]
class Grammar extends Model
{
    protected $table = 'grammars';

    public $incrementing = false;

    protected $keyType = 'string';

    public function kalimat(): BelongsTo
    {
        return $this->belongsTo(Kalimat::class, 'kalimat_id');
    }
}
