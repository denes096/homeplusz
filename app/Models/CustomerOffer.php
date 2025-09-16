<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CustomerOffer extends Model
{
    protected $fillable = [
        'customer_id',
        'customer_search_id',
        'property_ids',
        'email_subject',
        'email_content',
        'sent_at',
    ];

    protected $casts = [
        'property_ids' => 'array',
        'sent_at' => 'datetime',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customers::class);
    }

    public function customerSearch(): BelongsTo
    {
        return $this->belongsTo(CustomerSearch::class);
    }

    public function properties()
    {
        return Property::whereIn('id', $this->property_ids);
    }
}
