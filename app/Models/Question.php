<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Question extends Model
{
    protected $fillable = ['disease_id', 'text', 'gender', 'criticality_level_id', 'icon', 'reverse_meaning'];

    public function disease(): BelongsTo
    {
        return $this->belongsTo(Disease::class);
    }

    public function criticalityLevel(): BelongsTo
    {
        return $this->belongsTo(CriticalityLevel::class);
    }
}
