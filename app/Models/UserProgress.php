<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['id', 'userId', 'nodeId', 'status'])]
class UserProgress extends Model
{
    protected $table = 'user_progress';

    public $incrementing = false;

    protected $keyType = 'string';

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'userId');
    }

    public function node(): BelongsTo
    {
        return $this->belongsTo(Node::class, 'nodeId');
    }
}
