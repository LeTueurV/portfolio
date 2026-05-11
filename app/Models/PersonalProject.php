<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PersonalProject extends Model
{
    protected $fillable = [
        'title',
        'type',
        'description',
        'long_description',
        'year',
        'github_url',
        'demo_url',
        'order',
        'is_featured',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'order' => 'integer',
    ];

    public function tags(): HasMany
    {
        return $this->hasMany(PersonalProjectTag::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(PersonalProjectImage::class)->orderBy('order');
    }

    public function getTagsArrayAttribute(): array
    {
        return $this->tags->pluck('tag')->toArray();
    }
}
