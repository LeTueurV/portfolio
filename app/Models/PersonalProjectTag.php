<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PersonalProjectTag extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'personal_project_id',
        'tag',
    ];

    public function personalProject(): BelongsTo
    {
        return $this->belongsTo(PersonalProject::class);
    }
}
