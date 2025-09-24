<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PartnerContact extends Model
{
    protected $fillable = [
        'partner_id',
        'name',
        'relationship',
        'phone',
        'email',
        'notes',
        'is_primary',
    ];

    protected $casts = [
        'is_primary' => 'boolean',
    ];

    /**
     * Get the partner that owns the contact
     */
    public function partner(): BelongsTo
    {
        return $this->belongsTo(Partners::class, 'partner_id');
    }
}
