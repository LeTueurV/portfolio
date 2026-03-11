<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Formation extends Model
{
    protected $fillable = [
        'title',
        'school',
        'location',
        'degree_type',
        'start_date',
        'end_date',
        'is_current',
        'description',
        'logo_url',
        'diploma_url',
        'order',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_current' => 'boolean',
    ];

    /**
     * Récupère la période formatée (ex: "2023 - 2025" ou "2024 - En cours")
     */
    public function getPeriodAttribute(): string
    {
        $start = $this->start_date->format('Y');
        
        if ($this->is_current || !$this->end_date) {
            return "{$start} — En cours";
        }
        
        $end = $this->end_date->format('Y');
        
        if ($start === $end) {
            return $start;
        }
        
        return "{$start} — {$end}";
    }

    /**
     * Scope pour ordonner par date (plus récent en premier)
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('order')->orderBy('start_date', 'desc');
    }
}
