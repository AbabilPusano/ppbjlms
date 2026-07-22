<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['id', 'tipe', 'simbol', 'cara_baca'])]
class Huruf extends Model
{
    protected $table = 'hurufs';

    public $incrementing = false;

    protected $keyType = 'string';
}
