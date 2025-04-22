<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UserSession extends Model
{
    protected $fillable = ['gender'];

    public function answers(): HasMany
    {
        return $this->hasMany(UserAnswer::class);
    }
}
