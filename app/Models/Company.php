<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Company extends Model
{
    protected $fillable = [
        'name',
        'sector',
        'description',
        'location',
        'photo_url',
    ];

    public function stages(): HasMany
    {
        return $this->hasMany(Stage::class);
    }

    public function projects(): HasMany
    {
        return $this->hasMany(Project::class);
    }

    public function realisations(): HasMany
    {
        return $this->hasMany(Realisation::class);
    }
}
