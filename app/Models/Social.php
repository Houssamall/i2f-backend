<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Social extends Model
{
    protected $fillable = [
        'mois',
        'masseN',
        'masseN1',
        'masseVAR',
        'cnssN',
        'cnssN1',
        'cnssVAR',
        'id_User'
    ];

    /**
     * Get the user that owns the social record.
     */
    public function utilisateur(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_User', 'id'); 
    }
}