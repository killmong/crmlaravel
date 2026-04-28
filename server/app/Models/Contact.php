<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Contact extends Model
{
    use HasFactory, SoftDeletes;

    /*
    |--------------------------------------------------------------------------
    | Mass Assignment  —  mirrors every column in the contacts migration
    |--------------------------------------------------------------------------
    */
    protected $fillable = [
        // Core Info
        'name',
        'email',
        'phone',
        'company',

        // Classification
        'type',         // lead | customer | partner | vendor
        'status',       // active | inactive | archived
        'source',       // website | referral | cold_call | social_media | email_campaign | other
        'priority',     // low | medium | high
        'department',

        // Financials
        'deal_value',
        'currency',

        // Extra
        'notes',
        'address',
        'city',
        'state',
        'country',
        'pincode',

        // Ownership
        'user_id',
        'assigned_to',

        // Origin
        'lead_id',
        'lead_converted_at',
    ];

    /*
    |--------------------------------------------------------------------------
    | Casts
    |--------------------------------------------------------------------------
    */
    protected $casts = [
        'deal_value'        => 'integer',
        'lead_converted_at' => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    /** Owner / creator */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /** Assigned agent */
    public function assignee()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    /** The lead this contact was converted from */
    public function lead()
    {
        return $this->belongsTo(Lead::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------
    */

    public function wasConverted(): bool
    {
        return ! is_null($this->lead_id);
    }

    public function getFullAddressAttribute(): string
    {
        return collect([$this->address, $this->city, $this->state, $this->pincode, $this->country])
            ->filter()
            ->implode(', ');
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

    public function scopeMine($query)
    {
        return $query->where('user_id', auth()->id());
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeConverted($query)
    {
        return $query->whereNotNull('lead_id');
    }
}
