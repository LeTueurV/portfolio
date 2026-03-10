<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Realisation extends Model
{
    protected $fillable = [
        'type',
        'company_id',
        'title',
        'description',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function tags(): HasMany
    {
        return $this->hasMany(RealisationTag::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(RealisationImage::class)->orderBy('order');
    }

    public function getTagsArrayAttribute(): array
    {
        return $this->tags->pluck('tag')->toArray();
    }
}
