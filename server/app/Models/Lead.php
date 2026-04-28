<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Lead extends Model
{
    use HasFactory;

    /*
    |--------------------------------------------------------------------------
    | Mass Assignment  —  mirrors every column in the leads migration
    |--------------------------------------------------------------------------
    */
    protected $fillable = [
        // Contact Info
        'name',
        'email',
        'phone',
        'company',

        // Lead Details
        'status',       // new | contacted | qualified | proposal | follow_up | converted | lost
        'source',       // website | referral | cold_call | social_media | email_campaign | other
        'priority',     // low | medium | high
        'value',        // deal value in ₹
        'notes',
        'department',

        // Ownership
        'user_id',      // creator / owner
        'assigned_to',  // re-assigned agent or TL

        // Conversion
        'contact_id',
        'converted_at',
    ];

    /*
    |--------------------------------------------------------------------------
    | Casts
    |--------------------------------------------------------------------------
    */
    protected $casts = [
        'value'        => 'integer',
        'converted_at' => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    /** Lead owner (creator) */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /** Assigned agent / TL (can differ from owner) */
    public function assignee()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    /** Contact record created on conversion */
    public function contact()
    {
        return $this->belongsTo(Contact::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Accessors / Helpers
    |--------------------------------------------------------------------------
    */

    public function isConverted(): bool
    {
        return $this->status === 'converted' && ! is_null($this->contact_id);
    }

    public function getStatusLabelAttribute(): string
    {
        return ucwords(str_replace('_', ' ', $this->status));
    }

    /*
    |--------------------------------------------------------------------------
    | Conversion  —  call this from LeadController@convert
    |--------------------------------------------------------------------------
    */
    public function convertToContact(): Contact
    {
        if ($this->isConverted()) {
            return $this->contact;
        }

        $contact = Contact::create([
            'name'              => $this->name,
            'email'             => $this->email,
            'phone'             => $this->phone,
            'company'           => $this->company,
            'source'            => $this->source,
            'priority'          => $this->priority,
            'deal_value'        => $this->value,
            'notes'             => $this->notes,
            'department'        => $this->department,
            'type'              => 'lead',
            'status'            => 'active',
            'user_id'           => $this->user_id,
            'assigned_to'       => $this->assigned_to,
            'lead_id'           => $this->id,
            'lead_converted_at' => now(),
        ]);

        $this->update([
            'status'       => 'converted',
            'contact_id'   => $contact->id,
            'converted_at' => now(),
        ]);

        return $contact;
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

    /** Leads owned by the current authenticated user */
    public function scopeMine($query)
    {
        return $query->where('user_id', auth()->id());
    }

    /** Leads assigned to the current authenticated user */
    public function scopeAssignedToMe($query)
    {
        return $query->where('assigned_to', auth()->id());
    }

    /** All leads visible to a user based on their team hierarchy */
    public function scopeForTeam($query, User $user)
    {
        return $query->whereIn('user_id', $this->collectTeamIds($user));
    }

    /** Filter by one or more statuses */
    public function scopeWithStatus($query, string|array $status)
    {
        return $query->whereIn('status', (array) $status);
    }

    /** Filter by priority */
    public function scopeWithPriority($query, string $priority)
    {
        return $query->where('priority', $priority);
    }

    /*
    |--------------------------------------------------------------------------
    | Private Helpers
    |--------------------------------------------------------------------------
    */

    private function collectTeamIds(User $user): array
    {
        $ids = [$user->id];

        foreach ($user->subordinates as $subordinate) {
            $ids = array_merge($ids, $this->collectTeamIds($subordinate));
        }

        return $ids;
    }
}
