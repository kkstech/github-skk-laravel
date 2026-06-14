<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WorkPosition extends Model
{
    protected $fillable = ['subclassification_id', 'kode_jabatan', 'nama'];

    public function subclassification(): BelongsTo
    {
        return $this->belongsTo(Subclassification::class);
    }
}
