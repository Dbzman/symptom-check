<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CriticalityLevel extends Model
{
    protected $fillable = ['name', 'color', 'immediate_result', 'sort_order'];

    public function questions(): HasMany
    {
        return $this->hasMany(Question::class);
    }

    public function outcomes(): HasMany
    {
        return $this->hasMany(Outcome::class);
    }
}
