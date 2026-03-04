<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Competence extends Model
{
    protected $fillable = [
        'code',
        'label',
        'bloc',
        'description',
    ];

    public function projects(): BelongsToMany
    {
        return $this->belongsToMany(Project::class, 'competence_project');
    }

    public function stages(): BelongsToMany
    {
        return $this->belongsToMany(Stage::class, 'competence_stage');
    }
}
