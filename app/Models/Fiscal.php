<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Fiscal extends Model
{
    protected $fillable = [
        'mois',
        'tvaN',
        'tvaN1',
        'tvaVAR',
        'irN',
        'irN1',
        'irVAR',
        'isN',
        'isN1',
        'isVAR',
        'id_User'
    ];

    /**
     * Get the user that owns the fiscal record.
     */
    public function utilisateur(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_User', 'id');
    }
}
