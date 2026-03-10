<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RealisationImage extends Model
{
    protected $fillable = [
        'realisation_id',
        'image_url',
        'caption',
        'description',
        'order',
    ];

    public function realisation(): BelongsTo
    {
        return $this->belongsTo(Realisation::class);
    }
}
