<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RealisationTag extends Model
{
    protected $fillable = [
        'realisation_id',
        'tag',
    ];

    public function realisation(): BelongsTo
    {
        return $this->belongsTo(Realisation::class);
    }
}
