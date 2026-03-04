<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Stage extends Model
{
    protected $fillable = [
        'company_id',
        'start_date',
        'end_date',
        'duration',
        'role',
        'description',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function competences(): BelongsToMany
    {
        return $this->belongsToMany(Competence::class, 'competence_stage');
    }

    public function getPeriodAttribute(): string
    {
        $start = $this->start_date->translatedFormat('F');
        $end = $this->end_date->translatedFormat('F Y');
        return "{$start} — {$end}";
    }
}
