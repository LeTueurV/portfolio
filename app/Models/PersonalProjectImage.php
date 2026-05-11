<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PersonalProjectImage extends Model
{
    protected $fillable = [
        'personal_project_id',
        'image_url',
        'caption',
        'description',
        'order',
    ];

    public function personalProject(): BelongsTo
    {
        return $this->belongsTo(PersonalProject::class);
    }
}
