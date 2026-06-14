<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Classification extends Model
{
    protected $fillable = ['nama'];

    public function subclassifications(): HasMany
    {
        return $this->hasMany(Subclassification::class);
    }
}
