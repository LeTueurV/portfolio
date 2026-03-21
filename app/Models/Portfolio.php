<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Portfolio extends Model
{
    protected $fillable = [
        'first_name',
        'last_name',
        'bio',
        'photo_url',
        'email',
        'phone',
        'location',
        'linkedin_url',
        'github_url',
        'year_start',
        'year_end',
        'contact_message',
        'cv_url',
    ];

    public function getFullNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }
}
