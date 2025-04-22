<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Outcome extends Model
{
    protected $fillable = ['disease_id', 'criticality_level_id', 'title', 'description'];

    public function disease(): BelongsTo
    {
        return $this->belongsTo(Disease::class);
    }

    public function criticalityLevel(): BelongsTo
    {
        return $this->belongsTo(CriticalityLevel::class);
    }
}
