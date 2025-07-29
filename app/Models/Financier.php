<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Financier extends Model
{
    protected $fillable = [
        'designation',
        'exN',
        'exN1',
        'var',
        'id_User'
    ];

    /**
     * Get the user that owns the financier record.
     */
    public function utilisateur(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_User', 'id');
    }
}