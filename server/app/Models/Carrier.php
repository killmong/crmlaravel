<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Carrier extends Model
{
    protected $fillable = [
        'rmis_id',
        'mc_number',
        'dot_number',
        'tax_id',
        'company_name',
        'contact_name',
        'title',
        'address_1',
        'address_2',
        'city',
        'state',
        'zip',
        'phone',
        'fax',
        'email',
        'is_certified',
        'status',
        'last_synced_at',
    ];

    protected $casts = [
        'is_certified'   => 'boolean',
        'last_synced_at' => 'datetime',
    ];

    public function syncLogs()
    {
        return $this->hasMany(CarrierSyncLog::class, 'rmis_id', 'rmis_id');
    }

    // Scope: only active carriers
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    // Scope: only certified carriers
    public function scopeCertified($query)
    {
        return $query->where('is_certified', true);
    }
}
